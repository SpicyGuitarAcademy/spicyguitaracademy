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
use Models\TutorModel;
use Models\AssignmentModel;
use Models\StudentAssignmentModel;

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

         $mdl = new AssignmentModel();
         $amdl = new StudentAssignmentModel();
         $assignment = $mdl->getAssignment($id)[0] ?? [];
         $answers = $amdl->getAnswers($id);

         $res->send(
            $res->render('admin/assignment.html', [
               "courseId" => $id,
               "assignment" => json_encode($assignment),
               "answers" => json_encode($answers)
            ])
         );

      }
   }
   
   public function updateRating(Request $req, Response $res) {
       $answerId = $req->body()->answerId ?? null;
       $rating = $req->body()->rating ?? null;
       $review = $req->body()->review ?? null;
       
       if (is_null($answerId) || is_null($rating)) {
           $res->redirect($req->referer() ?? '/admin/dashboard');
       }
       
       // Sanitize
       $s = new Sanitize();
       $rating = $s->numbers($rating);
       $review = $s->string($review);
       
       $amdl = new StudentAssignmentModel();
       $amdl->updateRating($answerId, $review, $rating);
       
       $res->redirect($req->referer() ?? '/admin/dashboard');
   }

   public function new(Request $req, Response $res)
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

         $res->send(
            $res->render('admin/new-assignment.html',[
               'courseId' => $id
            ])
         );
      }
   }

   public function create(Request $req, Response $res)
   {
      // create a resource
      if ($req->params_exists()) {
         $courseId = $req->params()->id;

         $note = trim($req->body()->note);
         $video = ($req->files_exists() == true && $req->files()->video->error == 0) ? $req->files()->video : null ;
         
         $data = [
            'note' => $note,
         ];

         $v = new Validate();

         // validate
         $v->numbers("id", $courseId, "Invalid Course Id!")->minvalue(1);
         $v->any("note", $note, "Invalid Note")->max(65535);
         $errors = $v->errors();

         if ($errors) {
            $data['errors'] = json_encode($errors);
            $res->send(
               $res->render('admin/new-assignment.html', $data)
            );
         }

         // Sanitize
         $s = new Sanitize();
         $note = $s->string($note);
         $courseId = $s->numbers($courseId);

         if ($video != null) {
            // upload assignment video
            $up = new Upload();
            $up->video('video', $video, "Assignment Video was not uploaded", ["video/mp4"]);
            $up->upload("tutorials/assignments/", Encrypt::hash());

            $errors = $up->errors();

            if ($errors) {
               $data['errors'] = json_encode([$errors['video']]);
               $res->send(
                  $res->render('admin/new-assignment.html', $data)
               );
            }
         
            $path = $up->uri('video');
         }

         $mdl = new AssignmentModel();
         $tmdl = new TutorModel();
         $tutor = $tmdl->getTutorId(User::$email)[0]['id'];
         $added = $mdl->addAssignment($courseId, $note, $path ?? null, $tutor);
         if ($added != false) {
            // then added is the last inserted id
            $res->route("/admin/courses/$courseId/assignment");

         } else {
            // unlink uploaded file
            if ($video != null) unlink(STORAGE_DIR . $path) ;

            $data['errors'] = json_encode(["Assignment was not added!"]);
            $res->send(
               $res->render('admin/new-assignment.html', $data)
            );
         }
      }
   }

   public function edit(Request $req, Response $res)
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

         $mdl = new AssignmentModel();
         $assignment = $mdl->getAssignment($id);

         $res->send(
            $res->render('admin/edit-assignment.html',[
               'courseId' => $id,
               'assignment' => json_encode($assignment[0])
            ])
         );
      }
   }

   public function read(Request $req, Response $res)
   {
      // return a resource
   }

   public function update(Request $req, Response $res)
   {
      // update a resource
      if ($req->params_exists()) {
         $courseId = $req->params()->id;

         $note = trim($req->body()->note);
         $video = ($req->files_exists() == true && $req->files()->video->error == 0) ? $req->files()->video : null ;
         
         $data = [
            'note' => $note,
         ];

         $v = new Validate();

         // validate
         $v->numbers("id", $courseId, "Invalid Course Id!")->minvalue(1);
         $v->any("note", $note, "Invalid Note")->max(65535);
         $errors = $v->errors();

         if ($errors) {
            $data['errors'] = json_encode($errors);
            $res->send(
               $res->render('admin/edit-assignment.html', $data)
            );
         }

         // Sanitize
         $s = new Sanitize();
         $note = $s->string($note);
         $courseId = $s->numbers($courseId);

         $mdl = new AssignmentModel();

         if ($video != null) {
            // upload assignment video
            $up = new Upload();
            $up->video('video', $video, "Assignment Video was not uploaded", ["video/mp4"]);
            $up->upload("tutorials/assignments/", Encrypt::hash());

            $errors = $up->errors();

            if ($errors) {
               $data['errors'] = json_encode([$errors['video']]);
               $res->send(
                  $res->render('admin/edit-assignment.html', $data)
               );
            }
         
            $path = $up->uri('video');

            $added = $mdl->updateAssignmentVideo($courseId,$path);
            if ($added == false)  unlink(STORAGE_DIR . $path) ;
         }

         
         $added = $mdl->updateAssignmentNote($courseId, $note);
         $res->route("/admin/courses/$courseId/assignment");
      }
   }

   public function delete(Request $req, Response $res)
   {
      // remove a resouce
      if ($req->params_exists()) {
         $courseId = $req->params()->id;

         $mdl = new AssignmentModel();
         $mdl->removeAssignment($courseId);

         $res->route("/admin/courses/$courseId/assignment");
      }
   }

}
