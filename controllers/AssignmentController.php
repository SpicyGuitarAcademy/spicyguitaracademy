<?php

namespace Controllers;

use Framework\Http\Request;
use Framework\Http\Response;
use App\Services\Validate;
use App\Services\Sanitize;
use App\Services\Upload;
use App\Services\User;
use Framework\Cipher\Encrypt;
use Framework\Mail\Mail;
use Models\AssignmentAnswerModel;
use Models\TutorModel;
use Models\AssignmentModel;
use Models\CourseAssignmentModel;
use Models\CourseModel;
use Models\NotificationsModel;
use Models\StudentAssignmentModel;
use Models\StudentCourseModel;
use Models\StudentModel;

class AssignmentController
{

   public function index(Request $req, Response $res)
   {
      if ($req->params_exists()) {
         $id = $req->params()->id;

         // validate
         $v = new Validate();
         $v->numbers("id", $id, "Invalid Id!")->minvalue(1);
         $errors = $v->errors();

         if ($errors) {
            $res->redirect($req->referer() ?? '/admin/dashboard');
         }

         // Sanitize
         $s = new Sanitize();
         $id = $s->numbers($id);

         $mdl = new CourseAssignmentModel();
         $assignments = $mdl->getAssignments($id) ?? [];

         $res->send(
            $res->render('admin/course-assignments.html', [
               "courseId" => $id,
               "assignments" => json_encode($assignments)
            ])
         );
      }
   }

   public function courseAssignmentAnswers(Request $req, Response $res)
   {
      $courseId = $req->params()->courseId;
      $assignmentNumber = $req->params()->assignmentNumber;
      $student = $req->query()->student ?? null;

      // validate
      $v = new Validate();
      $v->numbers("courseId", $courseId, "Invalid Course Id!")->minvalue(1);
      $v->numbers("assignmentNumber", $assignmentNumber, "Invalid Assingment Number!")->minvalue(1);
      if ($student != null) {
         $v->email("student", $student, "Invalid student email!")->min(1);
      }
      $errors = $v->errors();

      if ($errors) {
         $res->redirect($req->referer() ?? '/admin/dashboard');
      }

      // Sanitize
      $s = new Sanitize();
      $courseId = $s->numbers($courseId);
      $assignmentNumber = $s->numbers($assignmentNumber);
      if ($student != null) {
         $student = $s->email($student);
      }

      if ($student == null) {

         $sAMdl = new StudentAssignmentModel();
         $aAMdl = new AssignmentAnswerModel();
         $ratedAssignments = [];
         $unratedAssignments = [];

         $assignments = $sAMdl->getUnreviewedAssignments($courseId, $assignmentNumber);
         // exit(json_encode($assignments));
         foreach ($assignments as $assignment) {
            $answers = $aAMdl->getAnswers($courseId, $assignmentNumber, $assignment['student']);
            if (count($answers) > 0) {
               $unratedAssignments[] = $assignment;
            }
         }

         $assignments = $sAMdl->getReviewedAssignments($courseId, $assignmentNumber);
         foreach ($assignments as $assignment) {
            $answers = $aAMdl->getAnswers($courseId, $assignmentNumber, $assignment['student']);
            if (count($answers) > 0) {
               $ratedAssignments[] = $assignment;
            }
         }

         $res->send(
            $res->render('admin/course-assignment-students.html', [
               "courseId" => $courseId,
               "ratedAssignments" => json_encode($ratedAssignments),
               "unratedAssignments" => json_encode($unratedAssignments),
            ])
         );
      } else {

         $cAMdl = new CourseAssignmentModel();
         $questions = $cAMdl->getAssignmentQuestions($courseId, $assignmentNumber);

         $aAMdl = new AssignmentAnswerModel();
         $answers = $aAMdl->getAnswers($courseId, $assignmentNumber, $student);

         $sAMdl = new StudentAssignmentModel();
         $rating = $sAMdl->getAssignment($student, $courseId, $assignmentNumber)[0]['rating'] ?? 0;

         $res->send(
            $res->render('admin/course-assignment-answers.html', [
               "courseId" => $courseId,
               "assignmentNumber" => $assignmentNumber,
               "student" => $student,
               "questions" => json_encode($questions),
               "answers" => json_encode($answers),
               "rating" => $rating
            ])
         );
      }
   }

   public function studentAssignmentRatings(Request $req, Response $res)
   {
      $courseId = $req->params()->courseId;
      $student = $req->params()->student;

      // validate
      $v = new Validate();
      $v->numbers("courseId", $courseId, "Invalid Course Id!")->minvalue(1);
      $v->email("student", $student, "Invalid Student!")->min(1);
      $errors = $v->errors();

      if ($errors) {
         $res->redirect($req->referer() ?? '/admin/dashboard');
      }

      $sAMdl = new StudentAssignmentModel();
      $assignments = $sAMdl->getAssignmentsForStudent($student, $courseId);

      $sCMdl = new StudentCourseModel();
      $rating = $sCMdl->getAssignmentRating($courseId, $student)[0]['assignment_rating'];

      $res->send(
         $res->render('admin/course-student-assignment-ratings.html', [
            "courseId" => $courseId,
            "student" => $student,
            "rating" => $rating,
            "assignments" => json_encode($assignments),
         ])
      );
   }

   public function updateAverageRating(Request $req, Response $res)
   {
      $courseId = $req->body()->courseId;
      $student = $req->body()->student;

      // validate
      $v = new Validate();
      $v->numbers("courseId", $courseId, "Invalid Course Id!")->minvalue(1);
      $v->email("student", $student, "Invalid Student!")->min(1);
      $errors = $v->errors();

      if ($errors) {
         $res->redirect($req->referer() ?? '/admin/dashboard');
      }

      $sAMdl = new StudentAssignmentModel();
      $assignments = $sAMdl->getAssignmentsForStudent($student, $courseId);

      $ratings = 0;
      $count = 0;
      foreach ($assignments as $assignment) {
         $ratings += intval($assignment['rating']);
         $count++;
      }
      $rating = round($ratings / $count);

      $sCMdl = new StudentCourseModel();
      $sCMdl->updateAssignmentRating($courseId, $student, $rating);

      $ratingComment = $rating >= 3
         ? "Congratulations, you have a $rating star rating, you can proceed to the next course."
         : "You scored below average, please study the lessons carefully and attempt the assignment again.";

      $tutor = (new TutorModel())->getTutor(User::$email)[0];
      (new NotificationsModel())
         ->addNotification(
            $student,
            "Assignment Rating from Admin {$tutor['firstname']} {$tutor['lastname']} -- $ratingComment"
         );

      $msg = <<<HTML
            <div>
               <h3>You have a reply from Admin {$tutor['firstname']} {$tutor['lastname']}</h3>
               <p>$ratingComment</p>
            </div>
      HTML;
      Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", $student, 'Assignment Review', 'info@spicyguitaracademy.com:Spicy Guitar Academy');

      $res->redirect($req->referer());
   }

   public function updateRating(Request $req, Response $res)
   {
      $courseId = $req->body()->courseId ?? null;
      $assignmentNumber = $req->body()->assignmentNumber ?? null;
      $student = $req->body()->student ?? null;
      $rating = $req->body()->rating ?? null;

      $cAMdl = new CourseAssignmentModel();
      $questions = $cAMdl->getAssignmentQuestions($courseId, $assignmentNumber);

      $aAMdl = new AssignmentAnswerModel();
      $answers = $aAMdl->getAnswers($courseId, $assignmentNumber, $student);

      $data = [
         "courseId" => $courseId,
         "assignmentNumber" => $assignmentNumber,
         "rating" => $rating,
         "student" => $student,
         "questions" => json_encode($questions),
         "answers" => json_encode($answers),
      ];

      if (is_null($assignmentNumber) || is_null($courseId)) {
         $data['errors'] = ['Invalid Assignment'];
         $res->send(
            $res->render('admin/course-assignment-answers.html', $data)
         );
      }

      $v = new Validate();
      $v->numbers('assignmentNumber', $assignmentNumber, "Invalid Assignment")->minvalue(1);
      $v->numbers('courseId', $courseId, "Invalid Assignment")->minvalue(1);
      $v->numbers('rating', $rating, "Invalid Rating")->minvalue(1);
      $errors = $v->errors();

      if ($errors) {
         $data['errors'] = json_encode($errors);
         $res->send(
            $res->render('admin/course-assignment-answers.html', $data)
         );
      }

      // Sanitize
      $s = new Sanitize();
      $rating = $s->numbers($rating);

      $cMdl = new CourseModel();
      $courseTitle = $cMdl->getCourse($courseId)[0]['course'];

      $ratingsComment = "You scored a rating of $rating star for Question $assignmentNumber in Course: $courseTitle.";

      $amdl = new StudentAssignmentModel();
      $amdl->updateRating($courseId, $assignmentNumber, $student, $rating);

      $tutor = (new TutorModel())->getTutor(User::$email)[0];
      (new NotificationsModel())
         ->addNotification(
            $student,
            "Assignment Rating from Admin {$tutor['firstname']} {$tutor['lastname']} -- $ratingsComment"
         );

      $msg = <<<HTML
            <div>
               <h3>You have a reply from Admin {$tutor['firstname']} {$tutor['lastname']}</h3>
               <p>$ratingsComment</p>
            </div>
      HTML;
      Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", $student, 'Assignment Review', 'info@spicyguitaracademy.com:Spicy Guitar Academy');

      $res->redirect($req->referer() ?? '/admin/dashboard');
   }

   public function answerAssignmentAsAdmin(Request $req, Response $res)
   {
      $courseId = $req->body()->courseId ?? null;
      $assignmentNumber = $req->body()->assignmentNumber ?? null;
      $student = $req->body()->student ?? null;
      $type = $req->body()->type ?? null;

      $cAMdl = new CourseAssignmentModel();
      $questions = $cAMdl->getAssignmentQuestions($courseId, $assignmentNumber);

      $aAMdl = new AssignmentAnswerModel();
      $answers = $aAMdl->getAnswers($courseId, $assignmentNumber, $student);

      $sAMdl = new StudentAssignmentModel();
      $rating = $sAMdl->getAssignment($student, $courseId, $assignmentNumber)[0]['rating'] ?? 0;

      $data = [
         "courseId" => $courseId,
         "assignmentNumber" => $assignmentNumber,
         "student" => $student,
         "questions" => json_encode($questions),
         "answers" => json_encode($answers),
         "rating" => $rating
      ];

      if (is_null($assignmentNumber) || is_null($courseId)) {
         $data['errors'] = ['Invalid Assignment'];
         $res->send(
            $res->render('admin/course-assignment-answers.html', $data)
         );
      }

      $v = new Validate();
      $v->numbers('assignmentNumber', $assignmentNumber, "Invalid Assignment")->minvalue(1);
      $v->numbers('courseId', $courseId, "Invalid Assignment")->minvalue(1);
      $errors = $v->errors();

      if ($errors) {
         $data['errors'] = json_encode($errors);
         $res->send(
            $res->render('admin/course-assignment-answers.html', $data)
         );
      }

      if ($type == 'text') {
         $content = $req->body()->text ?? null;

         // exit(json_encode($content));
         $s = new Sanitize();
         $content = $s->string($content);
         $content = utf8_encode($content);
      } else {
         if ($type == 'image') {
            // upload answer image
            $content = ($req->files_exists() == true && $req->files()->image->error == 0) ? $req->files()->image : null;
            $up = new Upload();
            $up->image('content', $content, "Comment Image was not uploaded", [
               "image/png", "image/jpeg", "image/gif", "image/bmp"
            ]);
            $up->max(30, 'Mb');
            $up->upload("tutorials/answers/", Encrypt::hash());

            $errors = $up->errors();
            if ($errors) {
               $data['errors'] = json_encode($errors);
               $res->send(
                  $res->render('admin/course-assignment-answers.html', $data)
               );
            }
            $content = $up->uri('content');
         } else if ($type == 'audio') {
            // upload answer audio
            $content = ($req->files_exists() == true && $req->files()->audio->error == 0) ? $req->files()->audio : null;
            $up = new Upload();
            $up->audio('content', $content, "Comment Audio was not uploaded", [
               "audio/mp3", "audio/mpeg", "audio/webm", "audio/wav", "audio/ogg"
            ]);
            $up->max(30, 'Mb');
            $up->upload("tutorials/answers/", Encrypt::hash());

            $errors = $up->errors();
            if ($errors) {
               $data['errors'] = json_encode($errors);
               $res->send(
                  $res->render('admin/course-assignment-answers.html', $data)
               );
            }
            $content = $up->uri('content');
         } else if ($type == 'video') {
            // upload answer video
            $content = ($req->files_exists() == true && $req->files()->video->error == 0) ? $req->files()->video : null;
            $up = new Upload();
            $up->video('content', $content, "Comment Video was not uploaded", [
               "video/mpeg", "video/mp4", "video/ogg", "video/mp2t", "video/3gpp", "video/webm"
            ]);
            $up->max(30, 'Mb');
            $up->upload("tutorials/answers/", Encrypt::hash());

            $errors = $up->errors();
            if ($errors) {
               $data['errors'] = json_encode($errors);
               $res->send(
                  $res->render('admin/course-assignment-answers.html', $data)
               );
            }
            $content = $up->uri('content');
         }
      }

      $notificationContent = "";
      switch ($type) {
         case 'text':
            $notificationContent = utf8_decode($content);
            break;
         case 'image':
            $notificationContent = "An image media file";
            break;
         case 'audio':
            $notificationContent = "An audio media file";
            break;
         case 'video':
            $notificationContent = "A video media file";
            break;
      }
      
      $tutor = (new TutorModel())->getTutor(User::$email)[0];
      (new NotificationsModel())->addNotification($student, "Assignment Review from  Admin {$tutor['firstname']} {$tutor['lastname']} -- $notificationContent");

      $msg = <<<HTML
            <div>
               <h3>You have a reply from Admin {$tutor['firstname']} {$tutor['lastname']}</h3>
               <p>$notificationContent</p>
            </div>
      HTML;
      Mail::asHTML($msg)->send("info@spicyguitaracademy.com:Spicy Guitar Academy", $student, 'Assignment Review', 'info@spicyguitaracademy.com:Spicy Guitar Academy');

      $mdl = new AssignmentAnswerModel();
      $mdl->addAdminAnswer($courseId, $assignmentNumber, $type, $content, $student, User::$email);
      $res->route("/admin/courses/$courseId/assignment/$assignmentNumber/answers?student=$student");
   }

   public function create(Request $req, Response $res)
   {
      // create a resource
      $courseId = $req->params()->id;
      $type = $req->body()->type;
      $assignmentNumber = $req->body()->assignmentNumber;
      $assignmentOrder = $req->body()->assignmentOrder;

      $data = [
         "courseId" => $courseId,
         "assignmentNumber" => $assignmentNumber,
         "assignmentOrder" => $assignmentOrder
      ];

      // validate
      $v = new Validate();
      $v->numbers("id", $courseId, "Invalid Course Id!")->minvalue(1);
      $v->numbers("assignmentNumber", $assignmentNumber, "Invalid Assignment Number!")->minvalue(1);
      $v->numbers("assignmentOrder", $assignmentOrder, "Invalid Assignment Order!")->minvalue(1);
      $errors = $v->errors();

      if ($errors) {
         $data['errors'] = json_encode($errors);
         $res->send(
            $res->render('admin/course-assignments.html', $data)
         );
      }

      $content = "";

      if ($type == 'text') {
         $text = $req->body()->text;
         // Sanitize
         $s = new Sanitize();
         $content = $s->string($text);
         $content = utf8_encode($content);
      } else {

         if ($type == 'image') {
            $image = ($req->files_exists() == true && $req->files()->image->error == 0) ? $req->files()->image : null;
            // upload assignment image
            $up = new Upload();
            $up->image('image', $image, "Assignment Image was not uploaded", ["image/png", "image/jpeg", "image/gif", "image/bmp"]);
            $up->upload("tutorials/assignments/", Encrypt::hash());

            $errors = $up->errors();

            if ($errors) {
               $data['errors'] = json_encode([$errors['image']]);
               $res->send(
                  $res->render('admin/course-assignments.html', $data)
               );
            }

            $content = $up->uri('image');
         } else if ($type == 'audio') {
            $audio = ($req->files_exists() == true && $req->files()->audio->error == 0) ? $req->files()->audio : null;
            // upload assignment audio
            $up = new Upload();
            $up->audio('audio', $audio, "Assignment Audio was not uploaded", ["audio/mp3", "audio/mpeg", "audio/webm", "audio/wav", "audio/ogg"]);
            $up->upload("tutorials/assignments/", Encrypt::hash());

            $errors = $up->errors();

            if ($errors) {
               $data['errors'] = json_encode([$errors['audio']]);
               $res->send(
                  $res->render('admin/course-assignments.html', $data)
               );
            }

            $content = $up->uri('audio');
         } else if ($type == 'video') {
            $video = ($req->files_exists() == true && $req->files()->video->error == 0) ? $req->files()->video : null;
            // upload assignment video
            $up = new Upload();
            $up->video('video', $video, "Assignment Video was not uploaded", ["video/mpeg", "video/mp4", "video/ogg", "video/mp2t", "video/3gpp", "video/webm"]);
            $up->upload("tutorials/assignments/", Encrypt::hash());

            $errors = $up->errors();

            if ($errors) {
               $data['errors'] = json_encode([$errors['video']]);
               $res->send(
                  $res->render('admin/course-assignments.html', $data)
               );
            }

            $content = $up->uri('video');
         }
      }

      $mdl = new CourseAssignmentModel();
      // $tmdl = new TutorModel();
      // $tutor = $tmdl->getTutorId(User::$email)[0]['id'];
      $added = $mdl->addAssignment($courseId, $assignmentNumber, $assignmentOrder, $type, $content);
      if ($added != false) {
         // then added is the last inserted id
         $res->route("/admin/courses/$courseId/assignments");
      } else {
         // unlink uploaded file
         if ($type != 'text') unlink(STORAGE_DIR . $content);

         $data['errors'] = json_encode(["Assignment was not added!"]);
         $res->send(
            $res->render('admin/course-assignments.html', $data)
         );
      }
      // }
   }

   public function delete(Request $req, Response $res)
   {
      // remove a resouce
      if ($req->params_exists()) {
         $courseId = $req->params()->id;
         $assignmentId = $req->params()->assignment;

         $mdl = new CourseAssignmentModel();
         $assignment = $mdl->getAssignment($assignmentId)[0];
         if ($assignment['type'] != 'text') {
            // unlink uploaded file
            unlink(STORAGE_DIR . $assignment['content']);
         }
         $mdl->removeAssignment($assignmentId);

         $res->route("/admin/courses/$courseId/assignments");
      }
   }

   // public function new(Request $req, Response $res)
   // {
   //    if ($req->params_exists()) {
   //       $id = $req->params()->id;

   //       // validate
   //       $v = new Validate();
   //       $v->numbers("id", $id, "Invalid Id!")->minvalue(1);
   //       $errors = $v->errors();

   //       if ($errors) {
   //          $res->redirect($req->referer() ?? '/admin/dashboard');
   //       }

   //       // Sanitize
   //       $s = new Sanitize();
   //       $id = $s->numbers($id);

   //       $res->send(
   //          $res->render('admin/new-course-assignment.html', [
   //             'courseId' => $id
   //          ])
   //       );
   //    }
   // }

   // public function edit(Request $req, Response $res)
   // {
   //    if ($req->params_exists()) {
   //       $id = $req->params()->id;

   //       // validate
   //       $v = new Validate();
   //       $v->numbers("id", $id, "Invalid Id!")->minvalue(1);
   //       $errors = $v->errors();

   //       if ($errors) {
   //          $res->redirect($req->referer() ?? '/admin/dashboard');
   //       }

   //       // Sanitize
   //       $s = new Sanitize();
   //       $id = $s->numbers($id);

   //       $mdl = new AssignmentModel();
   //       $assignment = $mdl->getAssignment($id);

   //       $res->send(
   //          $res->render('admin/edit-assignment.html', [
   //             'courseId' => $id,
   //             'assignment' => json_encode($assignment[0])
   //          ])
   //       );
   //    }
   // }

   // public function update(Request $req, Response $res)
   // {
   //    // update a resource
   //    if ($req->params_exists()) {
   //       $courseId = $req->params()->id;

   //       $note = trim($req->body()->note);
   //       $video = ($req->files_exists() == true && $req->files()->video->error == 0) ? $req->files()->video : null;

   //       $data = [
   //          'note' => $note,
   //       ];

   //       $v = new Validate();

   //       // validate
   //       $v->numbers("id", $courseId, "Invalid Course Id!")->minvalue(1);
   //       $v->any("note", $note, "Invalid Note")->max(65535);
   //       $errors = $v->errors();

   //       if ($errors) {
   //          $data['errors'] = json_encode($errors);
   //          $res->send(
   //             $res->render('admin/edit-assignment.html', $data)
   //          );
   //       }

   //       // Sanitize
   //       $s = new Sanitize();
   //       $note = $s->string($note);
   //       $courseId = $s->numbers($courseId);

   //       $mdl = new AssignmentModel();

   //       if ($video != null) {
   //          // upload assignment video
   //          $up = new Upload();
   //          $up->video('video', $video, "Assignment Video was not uploaded", ["video/mp4"]);
   //          $up->upload("tutorials/assignments/", Encrypt::hash());

   //          $errors = $up->errors();

   //          if ($errors) {
   //             $data['errors'] = json_encode([$errors['video']]);
   //             $res->send(
   //                $res->render('admin/edit-assignment.html', $data)
   //             );
   //          }

   //          $path = $up->uri('video');

   //          $added = $mdl->updateAssignmentVideo($courseId, $path);
   //          if ($added == false)  unlink(STORAGE_DIR . $path);
   //       }


   //       $added = $mdl->updateAssignmentNote($courseId, $note);
   //       $res->route("/admin/courses/$courseId/assignment");
   //    }
   // }
}
