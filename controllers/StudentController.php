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
use Models\AuthModel;
use Models\TutorModel;
use Models\StudentModel;
use Models\StudentCategoryModel;
use Models\StudentCourseModel;
use Models\StudentLessonModel;
use Models\StudentAssignmentModel;
use Models\CourseModel;
use Models\LessonModel;
use Models\AssignmentModel;
use Models\StudentSubscriptionModel;
use Models\QuickLessonModel;

class StudentController
{
   
   public function register(Request $req, Response $res) {
      
      // create a resource
      $firstname = trim($req->body()->firstname);
      $lastname = trim($req->body()->lastname);
      $email = trim($req->body()->email);
      $telephone = trim($req->body()->telephone);
      $password = trim($req->body()->password);
      $cpassword = trim($req->body()->cpassword);
      
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

      if ($errors) {
         $data['errors'] = $errors;
         $res->send(
            $res->json($data)
         );
      }

      // No errors, sanitize fields
      $s = new Sanitize();
      $firstname = $s->string($firstname);
      $lastname = $s->string($lastname);
      $email = $s->email($email);
      $telephone = $s->string($telephone);

      $amdl = new AuthModel();
      $mdl = new StudentModel();

      if ($amdl->emailExists($email) == true) {
         $data['errors'] = ['Email already exists. Try another email!'];
         $res->send(
            $res->json($data)
         );
      }

      $role = "student";
      $add = $amdl->addAuthDetails($email, Encrypt::hashPassword($password), $role);
      if ($add == false) {
         $data['errors'] = ['Account was not created. Try again!'];
         $res->send(
            $res->json($data)
         );
      }
      
      if ($mdl->addStudent($firstname, $lastname, $email, $telephone, STORAGE_PATH . 'avatars/default.png') == true) {
         $data['success'] = 'Account was created.';
         $res->send(
            $res->json($data)
         );
      } else {
         $data['errors'] = ['Account was not created. Try again!'];
         $res->send(
            $res->json($data)
         );
      }
   }

   public function chooseCategory(Request $req, Response $res) {
      
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

         // add all courses for this category for the user
         $mdl = new CourseModel();
         $courses = $mdl->getCoursesByCategory($category);

         // die(json_encode($courses));

         if (count($courses) > 0)  {
             $mdl = new StudentCourseModel();
             foreach ($courses as $course) {
                $id = $course['id'];
                $mdl->addCourseForStudent($category, $id, $email);
             }
             
             // make the first course active
             $first = $courses[0]['id'];
             $this->makeCourseActive($first, $email);
         }

         $res->send(
            $res->json([
                "status"=> true,
               "message" => "Student Category is Selected"
            ])
         );

      } else {
         $res->send(
            $res->json([
                "status"=> true,
               "message" => $v->errors()
            ])
         );
      }
      
   }

   private function makeCourseActive($id, $email) {

      $mdl = new StudentCourseModel();
      $mdl->updateCourseStatus($id, $email, 1);

      // add all lessons in this course to the studentlesson tbl
      $mdl = new LessonModel();
      $lessons = $mdl->getLessonsByCourse($id);

      $mdl = new StudentLessonModel();
      foreach ($lessons as $lesson) {
         $lid = $lesson['id'];
         $mdl->addLessonForStudent($lid, $email, "NORMAL", $id);
      }

      // if there is an assignment for this course, add it to the studentassignment tbl
      $mdl = new AssignmentModel();
      $assignment = $mdl->getAssignment($id);
      // die(json_encode($assignment));
      if ($assignment != []) {
         $ass = $assignment[0];

         $id = $ass['id'];
         $tutor = $ass['tutor_id'];
         $course = $ass['course_id'];

         $mdl = new StudentAssignmentModel();
         $mdl->addAssignmentForStudent($email, $course, $id, $tutor);
      }

   }

   public function studyingCourses(Request $req, Response $res) {
      // temporary
      $email = User::$email;

      $mdl = new StudentCourseModel();
      $courses = $mdl->getActiveCourse($email);

      // get total accessible lessons and total accessed lessons
      $mdl = new StudentLessonModel();
      $new = [];
      foreach ($courses as $course) {
         $id = $course['id'];
         $stats = $mdl->getStats($email, $id);
         $course['done'] = $stats[0];
         $course['total'] = $stats[1];
         $new[] = $course;
      }

      $res->send(
         $res->json(['courses' => $new])
      );
   }

   public function stats(Request $req, Response $res) {
      // temporary
      $email = User::$email;

      // get the active category
      $mdl = new StudentCategoryModel();
      $categoryId = $mdl->getActiveCategory($email)[0]['category_id'] ?? null;

      if (!is_null($categoryId)) {
         
         // get completed courses and total courses
         $mdl = new StudentCourseModel();
         list($done, $total) = $mdl->getStats($email, $categoryId);

         $countCoursesDone = count($done);
         $countCoursesTotal = count($total);

         // get completed lessons and total lessons
         $mdl = new LessonModel();
         $smdl = new StudentLessonModel();

        // exit(json_encode($smdl->getStats($email, 2)));

         $ldone = $ltotal = 0;
         foreach ($total as $course) {
            list($_1, $_2) = $smdl->getStats($email, $course['course_id']);
            // $_2 = $mdl->getLessonsByCourse($course['course_id']);

            $ldone += $_1;
            $ltotal += $_2;
         }

         $countLessonsDone = $ldone;
         $countLessonsTotal = $ltotal;

         $res->send(
            $res->json([
               "stats" => [
                  "takenCourses"=>$countCoursesDone,
                  "allCourses"=>$countCoursesTotal,
                  "takenLessons"=>$countLessonsDone,
                  "allLessons"=>$countLessonsTotal
               ],
               "status" => true,
               "category" => $categoryId
            ])
         );
         
      } else {
         $res->send(
            $res->json(['status' => false,'error' => 'No Courses or Lessons taken'])
         );
      }

   }

   public function getStudentLesson(Request $req, Response $res) {
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

         $res->send(
            $res->json(['lesson' => $lesson])
         );

      } else {
         $res->send(
            $res->json(['error' => 'No Lesson'])
         );
      }

   }

   public function nextLesson(Request $req, Response $res) {
      // temporary
      $email = User::$email;
      $currentlesson = $req->params()->lesson ?? null;
      $currentcourse = $req->query()->course ?? null;
      
      if (!is_null($currentlesson) && !is_null($currentcourse)) {

         $s = new Sanitize();
         $currentlesson = $s->numbers($currentlesson);
         $currentcourse = $s->numbers($currentcourse);

         // get the next lesson id
         $mdl = new StudentLessonModel();
         $lessonId = $mdl->getNextLesson($email, $currentcourse, $currentlesson)[0]['lesson_id'] ?? null;

         // die(!is_null($lessonId));

         if (!is_null($lessonId)) {
            $mdl = new LessonModel();
            $lesson = $mdl->getLesson($lessonId)[0];

            // update the next lesson status
            $mdl = new StudentLessonModel();
            $mdl->updateLessonStatus($lessonId, $email, 1);

            $res->send(
               $res->json(['lesson' => $lesson])
            );

         } else {
            // last lesson in the course

            // check for assignment
            $mdl = new StudentAssignmentModel();
            $assignmentId = $mdl->getAvailableAssignment($email, $currentcourse)[0]['assignment_id'] ?? null;

            if (!is_null($assignmentId)) {
               // get the assignment

               $mdl = new AssignmentModel();
               $assignment = $mdl->getAssignment($currentcourse)[0];

               $res->send(
                  $res->json(["assignment" => $assignment])
               );
            } else {
               $res->send(
                  $res->json(['error'=>'No more lessons for this course.'])
               );
            }
         }

      } else {
         $res->send(
            $res->json(['error' => 'No Course'])
         );
      }

   }

   public function previousLesson(Request $req, Response $res) {
      // temporary
      $email = User::$email;
      $currentlesson = $req->params()->lesson ?? null;
      $currentcourse = $req->query()->course ?? null;
      
      if (!is_null($currentlesson) && !is_null($currentcourse)) {

         $s = new Sanitize();
         $currentlesson = $s->numbers($currentlesson);
         $currentcourse = $s->numbers($currentcourse);

         // get the previous lesson id
         $mdl = new StudentLessonModel();
         $lessonId = $mdl->getPreviousLesson($email, $currentcourse, $currentlesson)[0]['lesson_id'] ?? null;

         // die(!is_null($lessonId));

         if (!is_null($lessonId)) {
            $mdl = new LessonModel();
            $lesson = $mdl->getLesson($lessonId)[0];

            // update the next lesson status
            $mdl = new StudentLessonModel();
            $mdl->updateLessonStatus($lessonId, $email, 1);

            $res->send(
               $res->json(['lesson' => $lesson])
            );

         } else {
            // last lesson in the course
            $res->send(
               $res->json(['error'=>'No more previous lessons for this course.'])
            );
         }

      } else {
         $res->send(
            $res->json(['error' => 'No Course'])
         );
      }

   }

   public function answerAssignment(Request $req, Response $res) {
      // temporary
      $email = User::$email;
      $assignment = $req->body()->assignment ?? null;
      $note = $req->body()->note ?? null;
      $video = $req->files()->video ?? null;

      if (is_null($assignment)) {
         $res->send(
            $res->json(['error'=>'Invalid Assignment'])
         );
      }

      $v = new Validate();
      $v->numbers('assignment', $assignment, "Invalid Assignment")->minvalue(1);
      $errors = $v->errors();

      if ($errors) {
         $res->send(
            $res->json(['error'=>'Invalid Assignment'])
         );
      }
      
      if (!is_null($video)) {
         // if video is sent, upload video and update the database
         $up = new Upload();
         $up->video('video', $video, "Assignment Video was not uploaded", ["video/mp4"]);
         $up->upload("tutorials/answers/", Encrypt::hash());

         $errors = $up->errors();
         if ($errors) {
            $res->send(
               $res->json(['error'=>$errors['video']])
            );
         }
         $path = $up->uri('video');

         $mdl = new StudentAssignmentModel();
         $mdl->answerAsVideo($email, $assignment, $path);

         // notify the tutor
         // send a mail to the tutor
         // add to notification table

         $res->send(
            $res->json(['success'=>'Answer is submitted.'])
         );

      } elseif (!is_null($note)) {

         $s = new Sanitize();
         $note = $s->string($note);

         $mdl = new StudentAssignmentModel();
         $mdl->answerAsNote($email, $assignment, $note);

         // notify the tutor
         // send a mail to the tutor
         // add to notification table

         $res->send(
            $res->json(['success'=>'Answer is submitted.'])
         );

      } else {
         $res->send(
            $res->json(['error'=>'Invalid Answer.'])
         );
      }
      
   }

   public function invitefriend(Request $req, Response $res) {
      // temporary
      $email = User::$email;
      
      $friend = $req->body()->friend ?? null;

      $v = new Validate();
      $v->email("friend", $friend, "Invalid friend's email");
      $error = $v->errors();

      if (!is_null($friend) && !$error) {

         $msg = <<<HTML
         <div>
            <h3>Hi, </h3>
            <p>Your friend ($email) has invited you to join in and learn how to become a professional guitar player.</p><br>

            <p>Click <a href="https://spicyguitaracademy.com">Here</a> to join.</p><br>

            <p>We can't wait to have you on board. 🙂🎉</p>
         </div>
HTML;
         $send = Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", $friend, "Your Friend Invites You.", 'info@spicyguitaracademy.com:Spicy Guitar Academy');

         if ($send == true) {
            $res->send(
               $res->json(['success'=>'Invitation has been sent.'])
            );
         } else {
            $res->send(
               $res->json(['error'=>"Invitation was not sent. Please try again."])
            );
         }

      } else {
         $res->send(
            $res->json(['error'=>$error['friend']])
         );
      }
   }

   public function uploadAvatar(Request $req, Response $res) {
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
            $res->send(
               $res->json(['error'=>$errors['avatar']])
            );
         }
         $path = $up->uri('avatar');

         $mdl = new StudentModel();
         $mdl->updateAvatar($email, $path);

         $res->send(
            $res->json(['success'=>'Image was uploaded.'])
         );
      } else {
         $res->send(
            $res->json(['error'=>'Invalid Image.'])
         );
      }

   }
   
   public function quickLessons(Request $req, Response $res) {
      // temporary
      $email = User::$email;

      $mdl = new StudentSubscriptionModel();
      $lessons = $mdl->getSubscribedQuickLessons($email);

      
     $res->send(
        $res->json([
           "lessons" => $lessons
        ])
     );

   }
   
   public function allQuickLessons(Request $req, Response $res) {
       $mdl = new QuickLessonModel();
       $lessons = $mdl->getQLessons();
       $lmdl = new LessonModel();
      
      $all = [];
      foreach ($lessons as $lesson) {
          $old = ['price'=>$lesson['price'],'free'=>$lesson['free']];
          $new = $lmdl->getLesson($lesson['lesson_id'])[0];
          
          $all[] = array_merge($old,$new);
      }

      $res->send(
         $res->json([
            "lessons" => $all
         ])
      );
       
   }

   public function quickLesson(Request $req, Response $res) {
      // temporary
      $email = User::$email;

      $lesson = $req->params()->lesson;
      $v = new Validate();

      $v->numbers('lesson', $lesson, "Invalid lesson");
      $error = $v->errors();

      if (!$error) {
         $mdl = new StudentSubscriptionModel();
         $lesson = $mdl->getSubscribedQuickLesson($email, $lesson);

         if ($lesson == []) {
            $res->send(
               $res->json(['error'=>'Student need to subscribe for this lesson'])
            );
         } else {
            $res->send(
               $res->json([
                  "lesson" => $lesson
               ])
            );
         }
      } else {
         $res->send(
            $res->json(['error'=>'Invalid lesson'])
         );
      }

   }


   public function freeLessons(Request $req, Response $res)
   {
      // return all resources
      $mdl = new QuickLessonModel();
      $lmdl = new LessonModel();
      $lessons = $mdl->getFLessons();

      $free = [];
      foreach ($lessons as $lesson) {
          $free[] = $lmdl->getLesson($lesson['lesson_id'])[0] ?? null;
      }

      $res->send(
         $res->json([
            "lessons" => $free
         ])
      );
   }

   public function func(Request $req, Response $res) {
      // temporary
      $email = User::$email;

   }

}
