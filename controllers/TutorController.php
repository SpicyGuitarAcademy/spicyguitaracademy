<?php

namespace Controllers;

use Framework\Http\Http;
use Framework\Http\Request;
use Framework\Http\Response;
use App\Services\Auth;
use App\Services\Validate;
use App\Services\Sanitize;
use App\Services\Upload;
use App\Services\User;
use Framework\Cipher\Encrypt;
use Framework\Mail\Mail;
use Models\TutorModel;
use Models\AuthModel;
use Models\CategoryModel;
use Models\CourseModel;
use Models\ForumsModel;
use Models\StudentModel;
use Models\LessonModel;
use Models\NotificationsModel;
use Models\StudentCategoryModel;
use Models\StudentCommentsModel;
use Models\StudentCourseModel;
use Models\StudentLessonModel;
use Models\StudentSubscriptionModel;

class TutorController
{

   public function index(Request $req, Response $res)
   {
      $mdl = new TutorModel();
      $tutors = $mdl->getTutors();

      $res->send(
         $res->render('admin/tutors.html', [
            'tutors' => json_encode($tutors)
         ])
      );
   }

   public function privilege(Request $req, Response $res)
   {
      $tutor = trim($req->params()->tutor ?? '');

      if ($tutor == "") {
         $res->redirect($req->referer());
      }

      $mdl = new TutorModel();
      $amdl = new AuthModel();
      $privilege = $amdl->getAuthPrivileges($tutor)[0]['privileges'];
      $privilegeArr = explode(',', $privilege);

      $tutorDetails = $mdl->getTutor($tutor)[0];
      $tutorName = $tutorDetails['firstname'] . ' ' . $tutorDetails['lastname'];

      $privilegeList = [
         'CATEGORIES', 'COURSES', 'ASSIGNMENTS', 'LESSONS', 'FEATURED COURSES', 'FREE LESSONS', 'SUBSCRIPTION PLANS', 'STUDENTS', 'TUTORS', 'TRANSACTIONS', 'CHAT FORUMS', 'QUESTIONS & ANSWERS', 'SEND NOTIFICATIONS'
      ];

      $res->send(
         $res->render('admin/tutorprivileges.html', [
            'tutor' => $tutor,
            'tutorName' => $tutorName,
            'privileges' => json_encode($privilegeArr),
            'privilegeList' => json_encode($privilegeList)
         ])
      );
   }

   public function notifyStudents(Request $req, Response $res)
   {
      $mdl = new StudentModel();
      $students = $mdl->getAllStudents();
      $res->send(
         $res->render('admin/notifystudents.html', [
            'students' => json_encode($students)
         ])
      );
   }

   public function sendNotifications(Request $req, Response $res)
   {

      $subject = trim($req->body()->subject ?? '');
      $message = trim($req->body()->message ?? '');
      $students = $_POST['students'];

      foreach ($students as $student) {
         (new NotificationsModel())->addNotification($student, "$subject -- $message");
      }

      $msg = <<<HTML
      <div>
         <h3>Hi there,</h3>
         <p>$message</p>
      </div>
HTML;
      Mail::asHTML($msg)->sendMultiple("info@spicyguitaracademy.com:Spicy Guitar Academy", $students, $subject, 'info@spicyguitaracademy.com:Spicy Guitar Academy');

      $res->redirect($req->referer());
   }

   public function updatePrivilege(Request $req, Response $res)
   {
      $tutor = trim($req->params()->tutor ?? '');
      $privilege = $_POST['privilege'];

      if ($tutor == "") {
         $res->redirect($req->referer());
      }

      $privileges = implode(",", $privilege);
      $amdl = new AuthModel();
      $privilege = $amdl->updatePrivileges($tutor, $privileges);

      $res->redirect($req->referer());
   }

   public function loadForums(Request $req, Response $res)
   {
      $res->send(
         $res->render('admin/chatforums.html')
      );
   }

   function getMessage($messages, $id)
   {
      foreach ($messages as $message) {
         // echo "=== $id, {$message['id']} ===";
         if ($message['id'] == $id) {
            // echo $message['comment'];
            return $message['comment'];
         }
      }
   }

   public function loadForumMessages(Request $req, Response $res)
   {
      $email = User::$email;
      $categoryId = $req->params()->category ?? 1;

      switch ($categoryId) {
         case 1:
            $categoryLabel = 'Beginners';
            break;
         case 2:
            $categoryLabel = 'Amateurs';
            break;
         case 3:
            $categoryLabel = 'Intermediate';
            break;
         case 4:
            $categoryLabel = 'Advanced';
            break;
         default:
            $categoryLabel = 'Beginners';
            break;
      }

      $commentMdl = new ForumsModel();
      $comments = $commentMdl->getMessages($categoryId);

      if ($comments != []) {
         $tutor = [];
         $student = [];
         $tMdl = new TutorModel();
         $sMdl = new StudentModel();
         $count = 0;
         $comments == array_reverse($comments);
         foreach ($comments as $comment) {
            $comments[$count]['comment'] = utf8_decode($comments[$count]['comment']);
            // if ($comment['sender'] != $email) {
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

            if (intval($comments[$count]['reply_id']) != 0) {
               $comments[$count]['reply_msg'] = $this->getMessage($comments, $comments[$count]['reply_id']) ?? '';
               // echo $comments[$count]['reply_msg'];
            }
            // }
            $count++;
         }
      }

      $res->send(
         $res->render('admin/chatforumsmessages.html', [
            'messages' => json_encode($comments),
            'categoryId' => $categoryId,
            'categoryLabel' => $categoryLabel
         ])
      );
   }

   public function students(Request $req, Response $res)
   {
      $mdl = new StudentModel();
      $query = trim($req->query()->query) ?? "";

      if ($query == "") {
         $students = $mdl->getAllStudents();
      } else {
         $students = $mdl->searchAllStudents($query);
      }

      $res->send(
         $res->render('admin/students.html', [
            'students' => json_encode($students)
         ])
      );
   }

   public function overrideCategory(Request $req, Response $res)
   {
      $student = trim($req->body()->student);
      $categoryId = trim($req->body()->categoryId);

      $sMdl = new StudentModel();
      $studentId = $sMdl->getStudent($student)[0]['id'];

      // add student category
      $mdl = new StudentCategoryModel();
      $mdl->addStudentCategory($student, $categoryId);

      $category = (new CategoryModel())->getCategoryById($categoryId)[0];
      (new NotificationsModel())->addNotification($student, "You have been moved to {$category['category']} Category");

      $msg = <<<HTML
            <div>
               <p>You have been moved to {$category['category']} Category</p>
            </div>
      HTML;
      Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", $student, 'Category Changed', 'info@spicyguitaracademy.com:Spicy Guitar Academy');

      $res->redirect($req->referer());
   }

   public function makeCategoryActive(Request $req, Response $res)
   {
      $student = trim($req->body()->student);
      $categoryId = trim($req->body()->categoryId);

      $mdl = new StudentCategoryModel();
      $mdl->makeCategoryActive($student, $categoryId);

      $category = (new CategoryModel())->getCategoryById($categoryId)[0];
      (new NotificationsModel())->addNotification($student, "You have have been moved to {$category['category']} Category");

      $msg = <<<HTML
            <div>
               <p>You have have been moved to {$category['category']} Category</p>
            </div>
      HTML;
      Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", $student, 'Category Changed', 'info@spicyguitaracademy.com:Spicy Guitar Academy');

      $res->redirect($req->referer());
   }

   public function studentDetails(Request $req, Response $res)
   {
      $student = trim($req->query()->student);
      $page = trim($req->query()->page ?? '');

      $sMdl = new StudentModel();
      $studentDetails = $sMdl->getStudent($student);
      $studentname = $studentDetails[0]['firstname'] . ' ' . $studentDetails[0]['lastname'];

      $list = [];
      $categories = [];

      if ($page == 'category') {
         $catMdl = new CategoryModel();
         $mdl = new StudentCategoryModel();
         $list = $mdl->listStudentCategory($student);
         $categories = $catMdl->getCategories();

         $count = 0;
         foreach ($list as $item) {
            // get category label
            $list[$count]['categoryLabel'] = $catMdl->getCategoryById($item['category_id'])[0]['category'];
            $count++;
         }
      }

      if ($page == 'courses') {
         $catMdl = new CategoryModel();
         $cMdl = new CourseModel();
         $mdl = new StudentCourseModel();
         $list = $mdl->listStudentCourses($student);

         $count = 0;
         foreach ($list as $item) {
            // get category label
            $list[$count]['categoryLabel'] = $catMdl->getCategoryById($item['category_id'])[0]['category'];

            // get course title
            $list[$count]['courseLabel'] = $cMdl->getCourse($item['course_id'])[0]['course'];
            $count++;
         }
      }

      if ($page == 'featured') {
         $catMdl = new CategoryModel();
         $cMdl = new CourseModel();
         $mdl = new StudentCourseModel();
         $list = $mdl->listStudentFeaturedCourses($student);

         $count = 0;
         foreach ($list as $item) {
            // get category label
            $list[$count]['categoryLabel'] = $catMdl->getCategoryById($item['category_id'])[0]['category'];

            // get course title
            $list[$count]['courseLabel'] = $cMdl->getCourse($item['course_id'])[0]['course'];
            $count++;
         }
      }

      if ($page == 'lessons') {
         $cMdl = new CourseModel();
         $lMdl = new LessonModel();
         $mdl = new StudentLessonModel();
         $list = $mdl->listStudentLessons($student);

         $count = 0;
         foreach ($list as $item) {
            // get course title
            $list[$count]['courseLabel'] = $cMdl->getCourse($item['course_id'])[0]['course'];

            // get lesson label
            $list[$count]['lessonLabel'] = $lMdl->getLesson($item['lesson_id'])[0]['lesson'];
            $count++;
         }
      }

      if ($page == 'subscription') {
         $mdl = new StudentSubscriptionModel();
         $list = $mdl->listStudentSubscriptionHistory($student);
      }

      $authstatus = (new AuthModel())->getAuthStatus($student)[0]['status'];

      $res->send(
         $res->render('admin/studentdetails.html', [
            'page' => $page,
            'student' => $student,
            'studentname' => $studentname,
            'list' => json_encode($list),
            'authstatus' => $authstatus,
            'categories' => json_encode($categories)
         ])
      );
   }

   public function updateStudentStatus(Request $req, Response $res)
   {
      $email = $req->body()->email ?? '';
      $status = $req->body()->status ?? '';

      if (!in_array($status, ["active", "blocked", "inactive"])) {
         $res->redirect($req->referer());
      } else {
         $mdl = new AuthModel();
         $mdl->updateStatus($email, $status);

         $res->redirect($req->referer());
      }
   }

   public function loadQuestionsAnswers(Request $req, Response $res)
   {
      $email = User::$email;
      $student = trim($req->query()->student ?? '');
      $lessonId = trim($req->query()->lessonId ?? '');

      $mdl = new StudentCommentsModel();
      $group = $mdl->getCommentGroup();

      $lessonMdl = new LessonModel();
      $sMdl = new StudentModel();
      $tMdl = new TutorModel();
      $count = 0;
      foreach ($group as $item) {
         $studentDetails = $sMdl->getStudent($item['sender']);
         $group[$count]['sendername'] = $studentDetails[0]['firstname'] . ' ' . $studentDetails[0]['lastname'];
         $lessondetails = $lessonMdl->getLesson($item['lesson_id']);
         $group[$count]['lessondetails'] = $lessondetails[0];
         $count++;
      }

      if ($student !== '' && $lessonId !== '') {
         $comments = $mdl->getCommentsForAdmin($lessonId, $student);

         $lessondetails = $lessonMdl->getLesson($lessonId);
         $lesson = $lessondetails[0]['lesson'];

         $count = 0;
         foreach ($comments as $item) {
            $studentDetails = $sMdl->getStudent($item['sender']);
            $comments[$count]['comment'] = utf8_decode($comments[$count]['comment']);
            $comments[$count]['sendername'] = $studentDetails[0]['firstname'] . ' ' . $studentDetails[0]['lastname'];
            if ($comments[$count]['sendername'] == " ") {
               // is admin
               $studentDetails = $tMdl->getTutor($item['sender']);
               $comments[$count]['sendername'] = $studentDetails[0]['firstname'] . ' ' . $studentDetails[0]['lastname'];
            }
            $count++;
         }
      }

      $res->send(
         $res->render('admin/questions-answers.html', [
            'group' => json_encode($group),
            'comments' => json_encode($comments ?? []),
            'lessonId' => $lessonId,
            'receiver' => $student,
            'lesson' => $lesson ?? ''
         ])
      );
   }

   public function addLessonComment(Request $req, Response $res)
   {
      $email = User::$email;
      $comment = $req->body()->comment ?? '';
      $receiver = $req->body()->receiver ?? '';
      $lessonId = $req->body()->lessonId ?? null;

      if ($lessonId == null) {
         $res->redirect(SERVER . '/admin/student/qa');
      }

      if ($comment == '') {
         $res->redirect(SERVER . "/admin/student/qa?student=$receiver&lessonId=$lessonId");
      }

      if ($receiver == '') {
         $res->redirect(SERVER . "/admin/student/qa");
      }

      $commentMdl = new StudentCommentsModel();
      $commentMdl->addComment($lessonId, $comment, $email, $receiver);

      // notify the receiver
      $tutor = (new TutorModel())->getTutor($email)[0];
      (new NotificationsModel())->addNotification($receiver, "You have a reply from {$tutor['firstname']} {$tutor['lastname']} -- $comment", "/forums");

      $msg = <<<HTML
            <div>
               <h3>You have a reply from Admin {$tutor['firstname']} {$tutor['lastname']}</h3>
               <p>$comment</p>
            </div>
      HTML;
      Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", $receiver, 'You have a reply', 'info@spicyguitaracademy.com:Spicy Guitar Academy');

      $res->redirect(SERVER . "/admin/student/qa?student=$receiver&lessonId=$lessonId");
   }

   public function new(Request $req, Response $res)
   {
      $res->send(
         $res->render('admin/new-tutor.html')
      );
   }

   public function create(Request $req, Response $res)
   {
      $firstname = trim($req->body()->firstname);
      $lastname = trim($req->body()->lastname);
      $email = trim($req->body()->email);
      $telephone = trim($req->body()->telephone);
      $role = trim($req->body()->role);
      $password = trim($req->body()->password);
      $cpassword = trim($req->body()->cpassword);

      $data = [
         "firstname" => $firstname,
         "lastname" => $lastname,
         "email" => $email,
         "telephone" => $telephone,
         "role" => $role,
         "password" => $password,
         "cpassword" => $cpassword
      ];

      $v = new Validate();

      // validate
      $v->letters("firstname", $firstname, "Invalid Firstname")->max(20);
      $v->letters("lastname", $lastname, "Invalid Lastname")->max(20);
      $v->email("email", $email, "Invalid Email")->min(1)->max(100);
      $v->telephone("telephone", $telephone, "Invalid Telephone")->max(20);
      $v->letters("role", $role, "Invalid Role")->max(10);
      $v->password("password", $password, "Invalid Password")->min(8);
      $errors = $v->errors();

      // check cpassword
      if ($cpassword !== $password) {
         $errors['cpasswprd'] = "Password and Confirm Password must be the same!";
      }

      if ($errors) {
         $data['errors'] = json_encode($errors);
         $res->send(
            $res->render('admin/new-tutor.html', $data)
         );
      }

      // No errors, sanitize fields
      $s = new Sanitize();
      $firstname = $s->string($firstname);
      $lastname = $s->string($lastname);
      $email = $s->email($email);
      $telephone = $s->string($telephone);
      $role = $s->string($role);

      $amdl = new AuthModel();
      $mdl = new TutorModel();

      if ($amdl->emailExists($email) == true) {
         $data['errors'] = json_encode(['Email already exists. Try another email!']);
         $res->send(
            $res->render('admin/new-tutor.html', $data)
         );
      }

      $add = $amdl->addAuthDetails($email, Encrypt::hashPassword($password), $role);
      if ($add == false) {
         $data['errors'] = json_encode(['Account was not created. Try again!']);
         $res->send(
            $res->render('admin/new-tutor.html', $data)
         );
      }

      if ($mdl->addTutor($firstname, $lastname, $email, $telephone, date('Y')) == true) {
         $msg = <<<HTML
         <div>
            <h3>Welcome to Spicy Guitar Academy</h3>
            <p>Here is your login credentials:</p>
            <p>Email: $email <br>Password: $password</p>
         </div>
   HTML;
         Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", $email, 'Welcome to Spicy Guitar Academy', 'info@spicyguitaracademy.com:Spicy Guitar Academy');

         $res->redirect(SERVER . "/admin/tutors?msg=Tutor was created successfully;Tutor's login credentials are:<br>Email: $email, Password: $password");
      } else {
         $data['errors'] = json_encode(['Account was not created. Try again!']);
         $res->send(
            $res->render('admin/new-tutor.html', $data)
         );
      }
   }

   public function profile(Request $req, Response $res)
   {
      $Tutor = new TutorModel();
      $email = User::$email;
      $tutor = $Tutor->where("email = '$email'")->read("*")[0];

      $res->send(
         $res->render('admin/profile.html', [
            "tutor" => json_encode($tutor)
         ]),
         200
      );
   }

   public function updateprofile(Request $req, Response $res)
   {
      $firstname = trim($req->body()->firstname);
      $lastname = trim($req->body()->lastname);
      $twitter = trim($req->body()->twitter);
      $telephone = trim($req->body()->telephone);
      $experience = trim($req->body()->experience);

      $v = new Validate();

      // validate
      $v->letters("firstname", $firstname, "Invalid Firstname")->max(20);
      $v->letters("lastname", $lastname, "Invalid Lastname")->max(20);
      $v->telephone("telephone", $telephone, "Invalid Telephone")->max(20);
      $errors = $v->errors();

      if ($errors) {
         $data['errors'] = json_encode($errors);
         $res->send(
            $res->json([
               'status' => false,
               'message' => 'Profile not updated',
               'error' => implode(", ", $errors)
            ])
         );
      }

      // No errors, sanitize fields
      $s = new Sanitize();
      $firstname = $s->string($firstname);
      $lastname = $s->string($lastname);
      $twitter = $s->string($twitter);
      $telephone = $s->string($telephone);
      $experience = $s->string($experience);

      $Tutor = new TutorModel();

      $email = User::$email;
      if ($Tutor->where("email = '$email'")->update([
         'firstname' => $firstname,
         'lastname' => $lastname,
         'twitter' => $twitter,
         'telephone' => $telephone,
         'experience' => date("Y", strtotime($experience))
      ])) {
         $res->send(
            $res->json([
               'status' => true,
               'message' => 'Profile updated'
            ])
         );
      } else {
         $res->json([
            'status' => false,
            'message' => 'Profile not updated'
         ]);
      }
   }

   public function uploadavatar(Request $req, Response $res)
   {
      $avatar = ($req->files_exists() == true && $req->files()->avatar->error == 0) ? $req->files()->avatar : null;

      if ($avatar != null) {
         // upload assignment video
         $up = new Upload();
         $up->image('avatar', $avatar, "Profile Picture was not uploaded", ["image/jpeg", "image/png", "image/gif", "image/bmp"]);
         $up->upload("avatars/", Encrypt::hash());

         $errors = $up->errors();

         if ($errors) {
            $res->send(
               $res->json([
                  'status' => false,
                  'message' => "Profile Picture was not uploaded",
                  'error' => implode(", ", $errors['avatar'])
               ])
            );
         } else {
            $path = $up->uri('avatar');
            $Tutor = new TutorModel();
            $email = User::$email;
            $Tutor->where("email = '$email'")->update([
               'avatar' => $path
            ]);
            $res->send(
               $res->json([
                  'status' => true,
                  'message' => "Profile Picture was uploaded",
                  'path' => $path
               ])
            );
         }
      } else {
         $res->send(
            $res->json([
               'status' => false,
               'message' => "Profile Picture was not uploaded",
               'error' => "File was not uploaded"
            ])
         );
      }
   }

   public function updateStatus(Request $req, Response $res)
   {
      $email = $req->body()->email ?? '';
      $status = $req->body()->status ?? '';

      $v = new Validate();
      $v->email("email", $email, "Invalid Email!");
      $errors = $v->errors();

      if ($errors || !in_array($status, ["active", "blocked", "inactive"])) {
         $res->redirect($req->referer());
      } else {
         $mdl = new AuthModel();
         $updated = $mdl->updateStatus($email, $status);

         // 
         $res->route("/admin/tutors?msg=Account Updated Successfully");
      }
   }

   public function delete(Request $req, Response $res)
   {
      // remove a resouce
   }
}
