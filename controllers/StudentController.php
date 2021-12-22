<?php

namespace Controllers;

use Framework\Http\Http;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Cipher\Encrypt;
use Framework\Mail\Mail;
use App\Services\Auth;
use App\Services\Validate;
use App\Services\Sanitize;
use App\Services\Upload;
use App\Services\User;
use Models\AssignmentAnswerModel;
use Models\AuthModel;
use Models\TutorModel;
use Models\StudentModel;
use Models\StudentCategoryModel;
use Models\StudentCourseModel;
use Models\StudentLessonModel;
use Models\StudentAssignmentModel;
use Models\CourseModel;
use Models\LessonModel;
use Models\CategoryModel;
use Models\CourseAssignmentModel;
use Models\StudentCommentsModel;
use Models\ForumsModel;
use Models\NotificationsModel;

class StudentController
{

   public function register(Request $req, Response $res)
   {
      // create a resource
      $firstname = trim($req->body()->firstname);
      $lastname = trim($req->body()->lastname);
      $email = trim($req->body()->email);
      $telephone = trim($req->body()->telephone);
      $password = trim($req->body()->password);
      $cpassword = trim($req->body()->cpassword);
      $refBy = trim($req->body()->referral_code ?? '');

      $data = [];

      $v = new Validate();

      // validate
      $v->letters("firstname", $firstname, "Invalid Firstname")->max(20);
      $v->letters("lastname", $lastname, "Invalid Lastname")->max(20);
      $v->email("email", $email, "Invalid Email")->min(1)->max(100);
      $v->telephone("telephone", $telephone, "Invalid Telephone")->max(20);

      $v->ucletters("password", $password, "Password field must contain lowercase and uppercase and numbers")->lcletters("password", $password, "Password field must contain lowercase and uppercase and numbers")->alphanumeric("password", $password, "Password field must contain lowercase and uppercase and numbers")->min(8);
      $errors = $v->errors();

      // check cpassword
      if ($cpassword !== $password) {
         $errors['cpassword'] = "Password and Confirm Password must be the same!";
      }

      $amdl = new AuthModel();
      $mdl = new StudentModel();

      // verify referral code if submitted
      if ($refBy !== '' && !$mdl->doesRefCodeExist($refBy)) {
         $errors['referral_code'] = "Referral Code does not exist.";
      }

      if ($errors) {
         $res->error('Registeration failed', $errors);
      }

      // No errors, sanitize fields
      $s = new Sanitize();
      $firstname = $s->string($firstname);
      $lastname = $s->string($lastname);
      $email = $s->email($email);
      $telephone = $s->string($telephone);

      if ($amdl->emailExists($email) == true) {
         $res->error('Email already exists. Try another email!');
      }

      $role = "student";
      $token = Encrypt::token(6);
      $add = $amdl->addAuthDetails($email, Encrypt::hashPassword($password), $role, $token);
      if ($add == false) {
         $res->error('Account was not created. Try again!');
      }

      // generate unique referral code
      $refCode = Encrypt::hash(7);
      while ($mdl->doesRefCodeExist($refCode)) {
         $refCode = Encrypt::hash(7);
      }

      if ($mdl->addStudent($firstname, $lastname, $email, $telephone, STORAGE_PATH . 'avatars/default.png', $refCode, $refBy) == true) {

         // Notify admin

         (new NotificationsModel())->addNotification("admin", "A new student has joined Spicy Guitar Academy. The name is $firstname $lastname ($email).", '/admin/students');

         // Notify Student
         (new NotificationsModel())->addNotification($email, "Hi, $firstname. Welcome to Spicy Guitar Academy. Spicy Guitar Academy is aimed at guiding beginners to fulfill their dreams of becoming professional guitar players. Please verify your account");

         (new NotificationsModel())->addNotification($email, "Hi, $firstname. Invite your friends to Spicy Guitar Academy with your referral code $refCode and Win Spicy Units everytime they subscribe or buy a Featured Course. You can use these Spicy Units to buy Featured Courses you find interesting.");

         $msg = <<<HTML
         <div>
            <h3>Hi, $firstname</h3>
            <p>Welcome to Spicy Guitar Academy,</p>
            <p>Spicy Guitar Academy is aimed at guiding beginners to fulfill their dreams of becoming professional guitar players.</p>
                
            <p>We have the best qualified tutors who are dedicated to help you develop from start to finish to make your dreams come true.
            </p>

            <p>Use this token <b>$token</b> to verify your account.</p>

            <p>You can also invite your friends to Spicy Guitar Academy with your referral code <b>$refCode</b> and WIN Spicy Units everytime they subscribe or buy a Featured Course. You can use these Spicy Units to buy Featured Courses you find interesting.</p>

         </div>
HTML;
         Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", $email, "Welcome to Spicy Guitar Academy.", 'info@spicyguitaracademy.com:Spicy Guitar Academy');

         // Notify Student who referred
         if ($refBy !== '') {
            $refStudent = $mdl->getStudentByRefCode($refBy);

            (new NotificationsModel())->addNotification($refStudent['email'], "Hi, {$refStudent['firstname']}. Thank you for referring $firstname {$lastname[0]}. Hence, you will be rewarded with Spicy Units anytime $firstname {$lastname[0]}. Subscribes or buys a Course. You can use spicy units to either pay for subscription or buy courses.", "/invite_friend");

            $msg = <<<HTML
         <div>
            <h3>Hi, {$refStudent['firstname']}</h3>
            <p>Thank you for referring $firstname {$lastname[0]}.</p>
            <p>Hence, you will be rewarded with Spicy Units anytime $firstname {$lastname[0]}. Subscribes or buy a Course.</p>
                
            <p>You can use spicy units to either pay for subscription or buy courses.</p>
         </div>
HTML;
            Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", $refStudent['email'], "Thank You For Referring.", 'info@spicyguitaracademy.com:Spicy Guitar Academy');
         }

         $res->success('Account was created.');
      } else {
         $res->error('Account was not created. Try again!');
      }
   }

   public function requestReferralCode(Request $req, Response $res)
   {
      $mdl = new StudentModel();

      // generate unique referral code
      $refCode = Encrypt::hash(7);
      while ($mdl->doesRefCodeExist($refCode)) {
         $refCode = Encrypt::hash(7);
      }

      $mdl->updateRefCode(User::$email, $refCode);

      $res->success('Referral code', [
         'referral_code' => $refCode
      ]);
   }

   public function chooseCategory(Request $req, Response $res)
   {

      $email = User::$email;
      $category = $req->body()->category ?? null;

      $v = new Validate();
      $v->numbers('Category', $category, 'Invalid Category')->minvalue(1)->maxvalue(4);

      if (!$v->errors()) {

         $s = new Sanitize();
         $email = $s->email($email);
         $category = $s->numbers($category);

         // add student category
         $mdl = new StudentCategoryModel();
         $mdl->addStudentCategory($email, $category);

         $res->success('Student Category is selected');
      } else {
         $res->error('Student category not selected', $v->errors());
      }
   }

   public function getProfile(Request $req, Response $res)
   {
      $email = User::$email;

      $mdl = new StudentModel();
      $profile = $mdl->getStudent($email)[0];

      // get login data
      $mdl = new AuthModel();
      $details = $mdl->getLoginDetails($email);
      $status = $details[0]['status'];
      $profile['status'] = $status;

      // check status
      if ($status == 'blocked') {
         $res->error('Your Account has been Blocked. Contact the Administrator');
      }

      $res->success("Student Profile", $profile);
   }

   public function rechooseCategory(Request $req, Response $res)
   {

      $email = User::$email;
      $category = $req->body()->category ?? null;

      $v = new Validate();
      $v->numbers('Category', $category, 'Invalid Category')->minvalue(1)->maxvalue(4);

      if (!$v->errors()) {

         $s = new Sanitize();
         $email = $s->email($email);
         $category = $s->numbers($category);

         // add student category
         $mdl = new StudentCategoryModel();

         $mdl->addStudentCategory($email, $category, 0);

         $categoryLabel = (new CategoryModel())->getCategoryById($category)[0]['category'];

         (new NotificationsModel())->addNotification("admin", "A Spicy Guitar Academy student ($email) wants to reselect another category ($categoryLabel).", "/admin/student/details?student=$email&page=category");

         (new NotificationsModel())->addNotification($email, "Your request to change category is being reviewed. This might take awhile.", "");

         $msg = <<<HTML
         <div>
            <p>A Spicy Guitar Academy student ($email) wants to reselect another category ($categoryLabel)</p>
         </div>
HTML;
         // Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", 'info@spicyguitaracademy.com', "Re-select Category.", $email);

         $res->success('Your request to change category is being reviewed. This might take awhile.');
      } else {
         $res->error('Student category not selected', $v->errors());
      }
   }

   public function activateNormalCourse(Request $req, Response $res)
   {
      $email = User::$email;
      $courseId = $req->body()->course ?? null;

      if (is_null($courseId)) {
         $res->error('Invalid course');
      }

      // get previously accessed course
      $courseMdl = new CourseModel();
      $studentCourseMdl = new StudentCourseModel();
      $category = $courseMdl->getCourse($courseId)[0]['category'] ?? 0;
      $previousCourseId = $courseMdl->getPreviousCourse($courseId, $category)[0]['id'] ?? null;

      if (!is_null($previousCourseId)) {
         // make sure all lessons in the previous course have been taken
         $lessonMdl = new LessonModel();
         $lessonsFromPreviousCourse = $lessonMdl->getLessonsByCourse($previousCourseId);

         $studentLessonMdl = new StudentLessonModel();
         foreach ($lessonsFromPreviousCourse as $lesson) {
            $lessonId = $lesson['id'];
            if ($studentLessonMdl->where("lesson_id = '$lessonId' AND medium = 'NORMAL' AND student_id = '$email'")->exist() == false) {
               $res->error('Complete lessons for the previous course');
            }
         }

         // make sure assignment for the course have been answered
         $sAMdl = new StudentAssignmentModel();
         $isPending = $sAMdl->courseAssignmentIsPending($previousCourseId, $email);
         $isAnswered = $sAMdl->courseAssignmentIsAnswered($previousCourseId, $email);
         $isReviewed = $sAMdl->courseAssignmentIsReviewed($previousCourseId, $email);

         $sCMdl = new StudentCourseModel();
         $assignment = $sCMdl->getAssignmentRating($previousCourseId, $email)[0] ?? null;
         $rating = $assignment['assignment_rating'] ?? 0;

         // if the assignment have been taken and reviewed but didn't pass the minimum score
         if (!is_null($assignment) && $isReviewed == true && (intval($rating) > 0 && intval($rating) < 3)) {
            $res->error('Your score is below average, please carefully study the course and attempt the assignment again.');
         }

         // if the assignment have not been taken
         if (!is_null($assignment) && $isPending == true) {
            $res->error('Complete assignments for the previous course');
         }

         // if the assignment have been taken but is still awaiting review
         if (!is_null($assignment) && $isAnswered == true) {
            $res->error('The tutors are still reviewing your answer. This might take a while.');
         }

         if ($studentCourseMdl->where("medium = 'NORMAL' AND student_id = '$email' AND course_id = '$courseId'")->exist() == false) {
            $this->makeCourseActive($courseId, $email);
            $res->success('Course activated');
         } else {
            $res->error('Course already activated');
         }
      } else {
         // activate the course if it doesn't exist before
         if ($studentCourseMdl->where("medium = 'NORMAL' AND student_id = '$email' AND course_id = '$courseId'")->exist() == false) {
            $this->makeCourseActive($courseId, $email);
            $res->success('Course activated');
         } else {
            // the course is already activated
            $res->error('Course already activated');
         }
      }
   }

   public function completeCategory(Request $req, Response $res)
   {
      $email = User::$email;
      $courseId = $req->body()->course ?? null;

      // make sure assignment for the course have been answered
      $sAMdl = new StudentAssignmentModel();
      $isPending = $sAMdl->courseAssignmentIsPending($courseId, $email);
      $isAnswered = $sAMdl->courseAssignmentIsAnswered($courseId, $email);
      $isReviewed = $sAMdl->courseAssignmentIsReviewed($courseId, $email);

      $sCMdl = new StudentCourseModel();
      $assignment = $sCMdl->getAssignmentRating($courseId, $email)[0] ?? null;
      $rating = $assignment['assignment_rating'] ?? 0;

      // if the assignment have been taken and reviewed but didn't pass the minimum score
      if (!is_null($assignment) && $isReviewed == true && (intval($rating) > 0 && intval($rating) < 3)) {
         $res->error('Your score is below average, please carefully study the course and attempt the assignment again.');
      }

      // if the assignment have not been taken
      if (!is_null($assignment) && $isPending == true) {
         $res->error('Complete assignments for the course');
      }

      // if the assignment have been taken but is still awaiting review
      if (!is_null($assignment) && $isAnswered == true) {
         $res->error('The tutors are still reviewing your answer. This might take a while.');
      }

      $res->success('Category completed');
   }

   private function makeCourseActive($courseId, $email)
   {
      // to make course active, add the course to the student course tbl
      // so that when the student takes the lessons, they are added to the student lesson tbl

      $courseMdl = new CourseModel();
      $category = $courseMdl->getCourse($courseId)[0]['category'] ?? 0;
      $studentCourseMdl = new StudentCourseModel();
      $studentCourseMdl->addCourseForStudent($category, $courseId, $email, "NORMAL");

      // if there is an assignment for this course, add it to the studentassignment tbl
      $assignmentMdl = new CourseAssignmentModel();
      $assignmentNumbers = $assignmentMdl->getAssignmentNumbers($courseId);
      if ($assignmentNumbers != []) {
         $studentAssignmentMdl = new StudentAssignmentModel();
         foreach ($assignmentNumbers as $assignmentNumber) {
            $studentAssignmentMdl->addAssignmentForStudent(
               $email,
               $courseId,
               $assignmentNumber['assignment_number']
            );
         }
      }
   }

   public function getMyCourseAssignment(Request $req, Response $res)
   {
      $email = User::$email;
      $course = $req->params()->course ?? null;

      if (is_null($course)) {
         $res->error('Invalid course');
      }

      $s = new Sanitize();
      $course = $s->numbers($course);

      $sAMdl = new StudentAssignmentModel();
      $assignments = $sAMdl->getAssignmentsForStudent($email, $course);

      if (count($assignments) == 0) {
         $res->error('No assignment');
      } else {

         $cAMdl = new CourseAssignmentModel();
         $count = 0;
         foreach ($assignments as $assignment) {

            // get asignment questions
            $assignments[$count]['questions'] =
               $cAMdl->getAssignmentQuestions($course, $assignment['assignment_number']);

            $qCount = 0;
            foreach ($assignments[$count]['questions'] as $question) {
               $assignments[$count]['questions'][$qCount]['content']
                  = utf8_decode($question['content']);
               $qCount++;
            }

            $count++;
         }

         $sCMdl = new StudentCourseModel();
         $rating = $sCMdl->getAssignmentRating($course, $email)[0]['assignment_rating'] ?? 0;

         $res->success('Course assignment', [
            "rating" => $rating,
            "assignments" => $assignments
         ]);
      }
   }

   public function activateNormalLesson(Request $req, Response $res)
   {
      $email = User::$email;
      $lessonId = $req->body()->lesson ?? null;

      $lessonMdl = new LessonModel();
      $studentLessonMdl = new StudentLessonModel();
      $course = $lessonMdl->getLesson($lessonId)[0]['course'] ?? 0;
      if ($studentLessonMdl->where("medium = 'NORMAL' AND student_id = '$email' AND course_id = '$course' AND lesson_id = '$lessonId'")->exist() == false) {
         $studentLessonMdl->addLessonForStudent($lessonId, $email, "NORMAL", $course);
         $res->success('Lesson activated');
      } else {
         $res->error('Lesson already activated');
      }
   }

   public function activateFeaturedLesson(Request $req, Response $res)
   {
      $email = User::$email;
      $lessonId = $req->body()->lesson ?? null;

      $lessonMdl = new LessonModel();
      $studentLessonMdl = new StudentLessonModel();
      $course = $lessonMdl->getLesson($lessonId)[0]['course'] ?? 0;
      if ($studentLessonMdl->where("medium = 'FEATURED' AND student_id = '$email' AND course_id = '$course' AND lesson_id = '$lessonId'")->exist() == false) {
         $studentLessonMdl->addLessonForStudent($lessonId, $email, "FEATURED", $course);
         $res->success('Lesson activated');
      } else {
         $res->error('Lesson already activated');
      }
   }

   public function allCourses(Request $req, Response $res)
   {
      // temporary
      $email = User::$email;
      $mdl = new CourseModel();

      $beginners = $mdl->getCoursesByCategory(1);
      $amateur = $mdl->getCoursesByCategory(2);
      $intermediate = $mdl->getCoursesByCategory(3);
      $advanced = $mdl->getCoursesByCategory(4);


      // get total accessible lessons and total accessed lessons
      $studentLessonMdl = new StudentLessonModel();
      $lessonMdl = new LessonModel();
      $nbeginners = [];
      $namateur = [];
      $nintermediate = [];
      $nadvanced = [];

      foreach ($beginners as $course) {
         $id = $course['id'];
         $course['done'] = "" . $studentLessonMdl->countNormalLessonsTakenByStudent($email, $id) . "";
         $course['total'] = "" . count($lessonMdl->getLessonsByCourse($id)) . "" ?? "0";
         $nbeginners[] = $course;
      }

      foreach ($amateur as $course) {
         $id = $course['id'];
         $course['done'] = "" . $studentLessonMdl->countNormalLessonsTakenByStudent($email, $id) . "";
         $course['total'] = "" . count($lessonMdl->getLessonsByCourse($id)) . "" ?? "0";
         $namateur[] = $course;
      }

      foreach ($intermediate as $course) {
         $id = $course['id'];
         $course['done'] = "" . $studentLessonMdl->countNormalLessonsTakenByStudent($email, $id) . "";
         $course['total'] = "" . count($lessonMdl->getLessonsByCourse($id)) . "" ?? "0";
         $nintermediate[] = $course;
      }

      foreach ($advanced as $course) {
         $id = $course['id'];
         $course['done'] = "" . $studentLessonMdl->countNormalLessonsTakenByStudent($email, $id) . "";
         $course['total'] = "" . count($lessonMdl->getLessonsByCourse($id)) . "" ?? "0";
         $nadvanced[] = $course;
      }

      $res->success('All courses', [
         "beginners" => $nbeginners,
         "amateurs" => $namateur,
         "intermediates" => $nintermediate,
         "advanceds" => $nadvanced
      ]);
   }

   public function studyingCourses(Request $req, Response $res)
   {
      // temporary
      $email = User::$email;

      // get the active category
      $mdl = new StudentCategoryModel();
      $categoryId = $mdl->getActiveCategory($email)[0]['category_id'] ?? null;

      if ($categoryId == null) {
         $res->error('No active category');
      }

      $courseMdl = new CourseModel();
      $lessonMdl = new LessonModel();
      $studentCourseMdl = new StudentCourseModel();
      $studentLessonMdl = new StudentLessonModel();

      $courses = $courseMdl->getCoursesByCategory($categoryId);
      $ncourse = [];
      foreach ($courses as $course) {
         $id = $course['id'];
         $course['status'] = $studentCourseMdl->where("course_id = '$id' AND student_id = '$email' AND medium = 'NORMAL'")->exist();
         $course['done'] = "" . $studentLessonMdl->countNormalLessonsTakenByStudent($email, $id) . "";
         $course['total'] = "" . count($lessonMdl->getLessonsByCourse($id)) . "" ?? "0";
         //  $course['total'] = $stats[1];
         $ncourse[] = $course;
      }

      $res->success('Studying courses', $ncourse);
   }

   public function allFeaturedCourses(Request $req, Response $res)
   {
      $email = User::$email;

      $courseMdl = new CourseModel();
      $studentCourseMdl = new StudentCourseModel();

      $courses = $courseMdl->getFeaturedCourses();

      if (count($courses) > 0) {
         $ncourses = [];
         foreach ($courses as $course) {
            $id = $course['id'];
            $courseDetails = $courseMdl->getFeaturedCourseLessons($id)[0];
            $lessons = $courseDetails['featured_lessons'];
            $lessonCount = $lessons == "" ? 0 : count(explode(" ", $lessons));
            $course['status'] = $studentCourseMdl->where("course_id = '$id' AND student_id = '$email' AND medium = 'FEATURED'")->exist();
            $course['total'] = "$lessonCount";
            $ncourses[] = $course;
         }

         $res->success('Featured courses', $ncourses);
      } else {
         $res->error('No featured courses');
      }
   }

   public function boughtFeaturedCourses(Request $req, Response $res)
   {
      $email = User::$email;

      $courseMdl = new CourseModel();
      $lessonMdl = new LessonModel();
      $studentCourseMdl = new StudentCourseModel();
      $studentLessonMdl = new StudentLessonModel();
      $courses = $studentCourseMdl->custom("SELECT course_tbl.* FROM course_tbl, student_course_tbl WHERE student_course_tbl.medium = 'FEATURED' AND student_course_tbl.student_id = '$email' AND student_course_tbl.course_id = course_tbl.id", true);

      if (count($courses) > 0) {
         $ncourses = [];
         foreach ($courses as $course) {
            $id = $course['id'];
            $courseDetails = $courseMdl->getFeaturedCourseLessons($id)[0];
            $lessons = $courseDetails['featured_lessons'];
            $lessonCount = $lessons == "" ? 0 : count(explode(" ", $lessons));
            $course['status'] = true;
            $course['done'] = "" . count($studentLessonMdl->where("student_id = '$email' AND medium = 'FEATURED' AND course_id = '$id'")->read("*")) . "";
            $course['total'] = "$lessonCount";
            $ncourses[] = $course;
         }
         $res->success('Featured courses', $ncourses);
      } else {
         $res->error('No featured courses');
      }
   }

   public function stats(Request $req, Response $res)
   {
      // temporary
      $email = User::$email;

      // get the active category
      $mdl = new StudentCategoryModel();
      $categoryId = $mdl->getActiveCategory($email)[0]['category_id'] ?? null;

      if (!is_null($categoryId)) {

         // takenCourses: count all courses in the student course tbl with the current category id
         // allCourses: count all courses in the course tbl with the current category id
         // takenLessons: count all lessons in the student lesson tbl with their course id as one of the takenCourses
         // allLessons: count all lessons in the lesson tbl with their course id as one of the allCourses

         $studentCourseMdl = new StudentCourseModel();
         $courseMdl = new CourseModel();
         $studentLessonMdl = new StudentLessonModel();
         $lessonMdl = new LessonModel();

         $countCoursesDone = count($studentCourseMdl->where("student_id = '$email' AND category_id = '$categoryId' AND medium = 'NORMAL'")->read("*") ?? []);

         $countCoursesTotal = count($courseMdl->where("category = '$categoryId' AND active = 1")->read("*") ?? []);

         $countLessonsDone = count($studentLessonMdl->custom("SELECT * FROM student_lesson_tbl WHERE course_id IN (SELECT course_id FROM student_course_tbl WHERE student_id = '$email' AND category_id = '$categoryId') AND medium = 'NORMAL' AND student_id = '$email'", true) ?? []);

         $countLessonsTotal = count($lessonMdl->custom("SELECT * FROM lesson_tbl WHERE course IN (SELECT id FROM course_tbl WHERE category = '$categoryId' AND active = 1) AND active = 1", true) ?? []);

         $res->success('Student statistics', [
            "takenCourses" => $countCoursesDone,
            "allCourses" => $countCoursesTotal,
            "takenLessons" => $countLessonsDone,
            "allLessons" => $countLessonsTotal,
            "category" => intval($categoryId)
         ]);
      } else {
         $res->error('No Courses or Lessons taken');
      }
   }

   public function getStudentLesson(Request $req, Response $res)
   {
      // temporary
      $email = User::$email;
      $lessonId = $req->params()->lesson ?? null;

      if (!is_null($lessonId)) {

         $s = new Sanitize();
         $lessonId = $s->numbers($lessonId);

         // get the lesson
         $mdl = new LessonModel();
         $lesson = $mdl->getLesson($lessonId)[0];

         // update the lesson status
         $mdl = new StudentLessonModel();
         $mdl->updateLessonStatus($lessonId, $email, 1);

         $res->success('success', $lesson);
      } else {
         $res->error('No lesson');
      }
   }

   // public function nextLesson(Request $req, Response $res)
   // {
   //    // temporary
   //    $email = User::$email;
   //    $currentlesson = $req->params()->lesson ?? null;
   //    $currentcourse = $req->query()->course ?? null;

   //    if (!is_null($currentlesson) && !is_null($currentcourse)) {

   //       $s = new Sanitize();
   //       $currentlesson = $s->numbers($currentlesson);
   //       $currentcourse = $s->numbers($currentcourse);

   //       // get the next lesson id
   //       $mdl = new StudentLessonModel();
   //       $lessonId = $mdl->getNextLesson($email, $currentcourse, $currentlesson)[0]['lesson_id'] ?? null;

   //       // die(!is_null($lessonId));

   //       if (!is_null($lessonId)) {
   //          $mdl = new LessonModel();
   //          $lesson = $mdl->getLesson($lessonId)[0];

   //          // update the next lesson status
   //          $mdl = new StudentLessonModel();
   //          $mdl->updateLessonStatus($lessonId, $email, 1);

   //          $res->success('success', $lesson);
   //       } else {
   //          // last lesson in the course

   //          // check for assignment
   //          $mdl = new StudentAssignmentModel();
   //          // *** revisit this logic
   //          $assignmentId = null; // $mdl->getAvailableAssignment($email, $currentcourse)[0]['assignment_id'] ?? null;

   //          if (!is_null($assignmentId)) {
   //             // get the assignment

   //             $mdl = new AssignmentModel();
   //             $assignment = $mdl->getAssignment($currentcourse)[0];

   //             $res->send(
   //                $res->json(["assignment" => $assignment])
   //             );
   //          } else {
   //             $res->error('No more lessons for this course');
   //          }
   //       }
   //    } else {
   //       $res->error('No course');
   //    }
   // }

   // public function previousLesson(Request $req, Response $res)
   // {
   //    // temporary
   //    $email = User::$email;
   //    $currentlesson = $req->params()->lesson ?? null;
   //    $currentcourse = $req->query()->course ?? null;

   //    if (!is_null($currentlesson) && !is_null($currentcourse)) {

   //       $s = new Sanitize();
   //       $currentlesson = $s->numbers($currentlesson);
   //       $currentcourse = $s->numbers($currentcourse);

   //       // get the previous lesson id
   //       $mdl = new StudentLessonModel();
   //       $lessonId = $mdl->getPreviousLesson($email, $currentcourse, $currentlesson)[0]['lesson_id'] ?? null;

   //       // die(!is_null($lessonId));

   //       if (!is_null($lessonId)) {
   //          $mdl = new LessonModel();
   //          $lesson = $mdl->getLesson($lessonId)[0];

   //          // update the next lesson status
   //          $mdl = new StudentLessonModel();
   //          $mdl->updateLessonStatus($lessonId, $email, 1);

   //          $res->success('success', $lesson);
   //       } else {
   //          // last lesson in the course

   //          $res->error('No more previous lessons for this course');
   //       }
   //    } else {
   //       $res->error('No course');
   //    }
   // }

   public function answerAssignment(Request $req, Response $res)
   {
      $student = User::$email;
      $courseId = $req->body()->courseId ?? null;
      $assignmentNumber = $req->body()->assignmentNumber ?? null;
      $type = $req->body()->type ?? null;

      if (is_null($assignmentNumber) || is_null($courseId)) {
         $res->error('Invalid Assignment');
      }

      $v = new Validate();
      $v->numbers('assignmentNumber', $assignmentNumber, "Invalid Assignment")->minvalue(1);
      $v->numbers('courseId', $courseId, "Invalid Assignment")->minvalue(1);
      $errors = $v->errors();

      if ($errors) {
         $res->error('Invalid Assignment');
      }

      if ($type == 'text') {
         $content = $req->body()->content ?? null;

         $s = new Sanitize();
         $content = $s->string($content);
         $content = utf8_encode($content);
      } else {
         $content = ($req->files_exists() == true && $req->files()->content->error == 0) ? $req->files()->content : null;

         if ($type == 'image') {
            // upload answer image
            $up = new Upload();
            $up->image('content', $content, "Answer Image was not uploaded", [
               "image/png", "image/jpeg", "image/gif", "image/bmp"
            ]);
            $up->max(30, 'Mb');
            $up->upload("tutorials/answers/", Encrypt::hash());

            $errors = $up->errors();
            if ($errors) {
               $res->error('Upload failed. File must be less than 30mb.', [$errors['content']]);
            }
            $content = $up->uri('content');
         } else if ($type == 'audio') {
            // upload answer audio
            $up = new Upload();
            $up->audio('content', $content, "Answer Audio was not uploaded", [
               "audio/mp3", "audio/mpeg", "audio/webm", "audio/wav", "audio/ogg"
            ]);
            $up->max(30, 'Mb');
            $up->upload("tutorials/answers/", Encrypt::hash());

            $errors = $up->errors();
            if ($errors) {
               $res->error('Upload failed. File must be less than 30mb.', [$errors['content']]);
            }
            $content = $up->uri('content');
         } else if ($type == 'video') {
            // upload answer video
            $up = new Upload();
            $up->video('content', $content, "Answer Video was not uploaded", [
               "video/mpeg", "video/mp4", "video/ogg", "video/mp2t", "video/3gpp", "video/webm"
            ]);
            $up->max(30, 'Mb');
            $up->upload("tutorials/answers/", Encrypt::hash());

            $errors = $up->errors();
            if ($errors) {
               $res->error('Upload failed. File must be less than 30mb.', [$errors['content']]);
            }
            $content = $up->uri('content');
         }
      }

      $studentDetails = (new StudentModel())->getStudent($student)[0];
      $course = (new CourseModel())->getCourse($courseId)[0];
      (new NotificationsModel())->addNotification('admin', "{$studentDetails['firstname']} {$studentDetails['lastname']} answered the assignment question on course: {$course['course']}.", "/admin/courses/$courseId/assignments/$student/ratings");

      $msg = <<<HTML
            <div>
               <h3>Hello Admin</h3>
               <p>{$studentDetails['firstname']} {$studentDetails['lastname']} answered the assignment question on course: {$course['course']}.</p>
            </div>
      HTML;
      Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", "info@spicyguitaracademy.com:Administrator", 'Assignment Answer', $student);

      $mdl = new AssignmentAnswerModel();
      $mdl->addAnswer($courseId, $assignmentNumber, $type, $content, $student);

      $sAMdl = new StudentAssignmentModel();
      $sAMdl->updateStatus($courseId, $assignmentNumber, $student, 'answered');
      $res->success('Answer is submitted', ['path' => $content]);
   }

   public function getAssignmentAnswers(Request $req, Response $res)
   {
      $student = User::$email;
      $courseId = $req->params()->courseId ?? null;
      $assignmentNumber = $req->params()->assignmentNumber ?? null;

      if (is_null($assignmentNumber) || is_null($courseId)) {
         $res->error('Invalid Assignment');
      }

      $aAMdl = new AssignmentAnswerModel();
      $answers = $aAMdl->getAnswers($courseId, $assignmentNumber, $student);

      $count = 0;
      foreach ($answers as $answer) {
         if ($answer['type'] == 'text')
            $answers[$count]['content'] = utf8_decode($answer['content']);
         $count++;
      }

      $res->success("Assignment answers", $answers);
   }

   public function invitefriend(Request $req, Response $res)
   {
      // temporary
      $email = User::$email;

      $friend = trim($req->body()->friend) ?? null;
      $mdl = new StudentModel();

      $refCode = $mdl->getStudent($email)[0]['referral_code'] ?? 'No Referral Code';

      $v = new Validate();
      $v->email("friend", $friend, "Invalid friend's email");
      $error = $v->errors();

      if (!is_null($friend) && !$error) {

         $msg = <<<HTML
         <div>
            <h3>Hi, </h3>
            <p>Your friend ($email) has invited you to join Spicy Guitar Academy, a top African online guitar Learning platform where guitar learning has been made Easy for beginners to advanced.</p>
            <p>Now you can become the Guitarist you Dream of.</p><br />

            <p>Download the Spicy Guitar Academy application <a href="https://play.google.com/store/apps/details?id=com.spicyguitaracademy">Here</a> and register with the Invitation code <b>$refCode</b>.</p><br>

            <p>We can't wait to have you on board. 🎸🎸🎶🎉</p>
         </div>
HTML;
         $send = Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", $friend, "Your Friend Invites You.", 'info@spicyguitaracademy.com:Spicy Guitar Academy');

         if ($send == true) {
            $res->success('Invitation has been sent');
         } else {
            $res->error('Invitation was not sent. Please try again.');
         }
      } else {
         $res->error('Invitation was not sent. Please try again.', $error['friend']);
      }
   }

   public function uploadAvatar(Request $req, Response $res)
   {
      // temporary
      $email = User::$email;
      $avatar = $req->files()->avatar ?? null;

      if (!is_null($avatar)) {
         // if video is sent, upload video and update the database
         $up = new Upload();
         $up->image('avatar', $avatar, "Image was not uploaded", ["image/jpeg", "image/png"]);
         $up->upload("avatars/", Encrypt::hash());

         $errors = $up->errors();
         if ($errors) {
            $res->error('Image was not uploaded');
         }
         $path = $up->uri('avatar');

         $mdl = new StudentModel();
         $mdl->updateAvatar($email, $path);

         $res->success('Image was uploaded', ['path' => $path]);
      } else {
         $res->error('Invalid image');
      }
   }

   public function allLessons(Request $req, Response $res)
   {
      // return all resources
      $mdl = new LessonModel();
      $lessons = $mdl->getLimitedLessons();

      if (count($lessons) > 0) {
         $res->success('All lessons', $lessons);
      } else {
         $res->error('No lesson');
      }
   }

   public function freeLessons(Request $req, Response $res)
   {
      // return all resources
      $mdl = new LessonModel();
      $lessons = $mdl->getFreeLessons();

      if (count($lessons) > 0) {
         $res->success('Free lessons', $lessons);
      } else {
         $res->error('No free lesson');
      }
   }

   public function addLessonComment(Request $req, Response $res)
   {
      $email = User::$email;
      $comment = $req->body()->comment ?? '';
      $receiver = $req->body()->receiver ?? '';
      $lessonId = $req->body()->lessonId ?? null;

      if ($lessonId == null) {
         $res->error('Invalid lesson id');
         exit;
      }

      if ($comment == '') {
         $res->error('No comment');
         exit;
      }

      if ($receiver == '') {
         $res->error('No receiver');
         exit;
      }

      $s = new Sanitize();
      $comment = $s->string($comment);
      // encode comment to utf8
      $comment = utf8_encode($comment);

      $commentMdl = new StudentCommentsModel();
      $response = $commentMdl->addComment($lessonId, $comment, $email, $receiver);

      // notify the receiver
      $student = (new StudentModel())->getStudent($email)[0];
      (new NotificationsModel())->addNotification($receiver, "You have a reply from {$student['firstname']} {$student['lastname']} -- $comment", "/admin/student/qa?student=$email&lessonId=$lessonId");

      $msg = <<<HTML
      <div>
         <h3>You have a reply from {$student['firstname']} {$student['lastname']}</h3>
         <p>$comment</p>
      </div>
HTML;
      // Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", $receiver, "You have a reply from {$student['firstname']} {$student['lastname']}", $email);

      // send notifications to all the admins
      $adMdl = new TutorModel();
      $tutors = $adMdl->getTutors();

      $lMdl = new LessonModel();
      $lessonDetails = $lMdl->getLesson($lessonId)[0];

      foreach ($tutors as $tutor) {

         $receiver = $tutor['email'];

         // notify the receiver
         $student = (new StudentModel())->getStudent($email)[0];
         (new NotificationsModel())->addNotification($receiver, "There is a new comment on the lesson ({$lessonDetails['lesson']}) from {$student['firstname']} {$student['lastname']} -- $comment", "/admin/student/qa?student=$email&lessonId=$lessonId");

         $msg = <<<HTML
      <div>
         <h3>There is a new comment on the lesson ({$lessonDetails['lesson']}) from {$student['firstname']} {$student['lastname']}</h3>
         <p>$comment</p>
      </div>
HTML;
         // Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", "info@spicyguitaracademy.com:Spicy Guitar Academy", "A new comment on a lesson", $email);
      }

      // send to info account
      // Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Administrator", $receiver, "A new comment on a lesson", $email);

      if ($response == true) {
         $res->success('Added successfully');
      } else {
         $res->error('Failed to add');
      }
   }

   public function getLessonComments(Request $req, Response $res)
   {
      $email = User::$email;
      $lessonId = $req->params()->lessonId ?? null;

      if ($lessonId == null) {
         $res->error('Invalid lesson id');
         exit;
      }

      $commentMdl = new StudentCommentsModel();
      $comments = $commentMdl->getComments($lessonId, $email);

      if ($comments == []) {
         $res->error('No comments');
      } else {
         $tutor = [];
         $tMdl = new TutorModel();
         $count = 0;
         foreach ($comments as $comment) {
            $comments[$count]['comment'] = utf8_decode($comments[$count]['comment']);
            if ($comment['sender'] != $email) {
               // this is a tutor
               $tutorDetails = $tMdl->getTutor($comment['sender']);
               $tutor['name'] = $tutorDetails[0]['firstname'] . ' ' . $tutorDetails[0]['lastname'];
               $tutor['avatar'] = $tutorDetails[0]['avatar'];
               $comments[$count]['tutor'] = $tutor;
            }
            $count++;
         }
         $res->success('Lesson comments', $comments);
      }
   }

   public function addForumMessageAsAdmin(Request $req, Response $res)
   {
      $email = User::$email;
      $comment = $req->body()->comment ?? '';
      $categoryId = $req->body()->categoryId ?? '';
      $replyId = $req->body()->replyId ?? null;

      $s = new Sanitize();
      $comment = $s->string($comment);
      $comment = utf8_encode($comment);

      if ($categoryId == null) {
         $res->redirect(SERVER . '/admin/chatforums/' . $categoryId);
      }

      if ($replyId == null) {
         $res->redirect(SERVER . '/admin/chatforums/' . $categoryId);
      }

      if ($comment == '') {
         $res->redirect(SERVER . '/admin/chatforums/' . $categoryId);
      }

      $commentMdl = new ForumsModel();
      $commentMdl->addMessage($categoryId, $comment, $email, $replyId, true);

      // if reply id, send notification to sender
      if ($replyId !== '0') {
         $replyMsg = $commentMdl->getMessage($replyId)[0];
         $notificationComment = utf8_decode($comment);

         $from = "";
         if ($replyMsg['is_admin'] == '1') {
            $student = (new StudentModel())->getStudent($email)[0];
            $from = "{$student['firstname']} {$student['lastname']}";
            (new NotificationsModel())->addNotification($replyMsg['sender'], "You have a reply from $from -- $notificationComment", "/admin/chatforums/$categoryId");
         } else {
            $tutor = (new TutorModel())->getTutor($email)[0];
            $from = "Admin {$tutor['firstname']} {$tutor['lastname']}";
            (new NotificationsModel())->addNotification($replyMsg['sender'], "You have a reply from $from -- $notificationComment", "/forums");
         }

         $msg = <<<HTML
      <div>
         <h3>You have a reply from $from</h3>
         <p>$notificationComment</p>
      </div>
HTML;
         Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", $replyMsg['sender'], "You have a reply from $from", $email);
      }

      $res->redirect(SERVER . '/admin/chatforums/' . $categoryId);
   }

   public function addForumMessage(Request $req, Response $res)
   {
      $email = User::$email;
      $comment = $req->body()->comment ?? '';
      $categoryId = $req->body()->categoryId ?? '';
      $replyId = $req->body()->replyId ?? null;

      $s = new Sanitize();
      $comment = $s->string($comment);
      $comment = utf8_encode($comment);

      if ($categoryId == null) {
         $res->error('Invalid category id');
         exit;
      }

      if ($replyId == null) {
         $res->error('Invalid reply id');
         exit;
      }

      if ($comment == '') {
         $res->error('No comment');
         exit;
      }

      $commentMdl = new ForumsModel();
      $response = $commentMdl->addMessage($categoryId, $comment, $email, $replyId);

      // if reply id, send notification to sender
      if ($replyId !== null && $replyId > 0) {
         $replyMsg = $commentMdl->getMessage($replyId)[0];
         $notificationComment = utf8_decode($comment);

         if ($replyMsg['is_admin'] == '1') {
            $student = (new StudentModel())->getStudent($email)[0];
            $from = "{$student['firstname']} {$student['lastname']}";
            (new NotificationsModel())->addNotification($replyMsg['sender'], "You have a reply from $from -- $notificationComment", "/admin/chatforums/$categoryId");
         } else {
            $tutor = (new TutorModel())->getTutor($email)[0];
            $from = "Admin {$tutor['firstname']} {$tutor['lastname']}";
            (new NotificationsModel())->addNotification($replyMsg['sender'], "You have a reply from $from -- $notificationComment", "/forums");
         }

         $msg = <<<HTML
      <div>
         <h3>You have a reply from $from</h3>
         <p>$notificationComment</p>
      </div>
HTML;
         Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", $replyMsg['sender'], "You have a reply from $from", $email);
      }

      
      // send notifications to all the admins
      $adMdl = new TutorModel();
      $tutors = $adMdl->getTutors();
      $notificationComment = utf8_decode($comment);
   
         $msg = <<<HTML
      <div>
         <p>There is a new message on the Forum from {$student['firstname']} {$student['lastname']}</p>
         <p>$notificationComment</p>
      </div>
HTML;

      foreach ($tutors as $tutor) {
         $receiver = $tutor['email'];
         // notify the receiver
         $student = (new StudentModel())->getStudent($email)[0];
         (new NotificationsModel())->addNotification($receiver, "There is a new message on the Forum from {$student['firstname']} {$student['lastname']} -- $comment", "/admin/chatforums/$categoryId");
         Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", $receiver, "A new message on the Forum", $email);
      }

      Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", "info@spicyguitaracademy.com:Administrator", "A new message on the Forum", $email);

      if ($response == true) {
         $res->success('Added successfully');
      } else {
         $res->error('Failed to add');
      }
   }

   public function getMessages(Request $req, Response $res)
   {
      $email = User::$email;
      $categoryId = $req->params()->categoryId ?? null;

      if ($categoryId == null) {
         $res->error('Invalid category id');
         exit;
      }

      $commentMdl = new ForumsModel();
      $comments = $commentMdl->getMessages($categoryId);

      if ($comments == []) {
         $res->error('No comments');
      } else {
         $tutor = [];
         $student = [];
         $tMdl = new TutorModel();
         $sMdl = new StudentModel();
         $count = 0;
         foreach ($comments as $comment) {
            $comments[$count]['comment'] = utf8_decode($comments[$count]['comment']);
            if ($comment['sender'] != $email) {
               if ($comment['is_admin'] == true) {
                  // is admin
                  $tutorDetails = $tMdl->getTutor($comment['sender']);
                  $tutor['name'] = $tutorDetails[0]['firstname'] . ' ' . $tutorDetails[0]['lastname'];
                  $tutor['avatar'] = $tutorDetails[0]['avatar'];
                  $comments[$count]['tutor'] = $tutor;
               } else {
                  // this is another student
                  $studentDetails = $sMdl->getStudent($comment['sender']);
                  $student['name'] = $studentDetails[0]['firstname'] . ' ' . $studentDetails[0]['lastname'];
                  $student['avatar'] = $studentDetails[0]['avatar'];
                  $comments[$count]['student'] = $student;
               }
            }
            $count++;
         }
         $res->success('Lesson comments', $comments);
      }
   }

   public function contactUs(Request $req, Response $res)
   {
      $email = trim($req->body()->email);
      $subject = trim($req->body()->subject);
      $message = trim($req->body()->message);

      // validate email
      $v = new Validate();
      $v->email("email", $email, "Invalid Email")->min(1)->max(100);
      $errors = $v->errors();

      if ($errors) {
         $res->error("Invalid email address");
      }

      //   update token for user and send mail to the user
      $msg = <<<HTML
         <div>
            <p>Hi Admin, here is a message from a student ($email)</p>
            <p>$message</p>
         </div>
HTML;
      $send = Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy Student", "info@spicyguitaracademy.com", "Contact Us ($subject)", $email);

      if ($send == true) {
         $res->success("Message sent successfully, We will reply you shortly.");
      } else {
         $res->error("Message was not sent, please try again");
      }
   }

   public function func(Request $req, Response $res)
   {
      // temporary
      $email = User::$email;
   }
}
