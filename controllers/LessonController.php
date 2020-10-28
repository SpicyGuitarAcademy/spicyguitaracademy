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
use Models\CourseModel;
use Models\LessonModel;
use Models\QuickLessonModel;

class LessonController
{

   public function index(Request $req, Response $res)
   {
      // return all resources
      
      // get all courses by their category
      $mdl = new CourseModel();
      $beginners = $mdl->getCoursesByCategory(1);
      $amateur = $mdl->getCoursesByCategory(2);
      $intermediate = $mdl->getCoursesByCategory(3);
      $advanced = $mdl->getCoursesByCategory(4);

      $lmdl = new LessonModel();

      $beginnerLessons = [];
      foreach ($beginners as $course) {
         $id = $course['id'];
         $course = $course['course'];
         $lessons = $lmdl->getLessonsByCourse($id);
         $beginnerLessons[] = [
            'id' => $id,
            'course' => $course,
            'lessons' => $lessons ?? []
         ];
      }

      $amateurLessons = [];
      foreach ($amateur as $course) {
         $id = $course['id'];
         $course = $course['course'];
         $lessons = $lmdl->getLessonsByCourse($id);
         $amateurLessons[] = [
            'id' => $id,
            'course' => $course,
            'lessons' => $lessons ?? []
         ];
      }

      $intermediateLessons = [];
      foreach ($intermediate as $course) {
         $id = $course['id'];
         $course = $course['course'];
         $lessons = $lmdl->getLessonsByCourse($id);
         $intermediateLessons[] = [
            'id' => $id,
            'course' => $course,
            'lessons' => $lessons ?? []
         ];
      }

      $advancedLessons = [];
      foreach ($advanced as $course) {
         $id = $course['id'];
         $course = $course['course'];
         $lessons = $lmdl->getLessonsByCourse($id);
         $advancedLessons[] = [
            'id' => $id,
            'course' => $course,
            'lessons' => $lessons ?? []
         ];
      }

      $res->send(
         $res->render("admin/lessons.html", [
            "beginners" => json_encode($beginnerLessons),
            "amateur" => json_encode($amateurLessons),
            "intermediate" => json_encode($intermediateLessons),
            "advanced" => json_encode($advancedLessons)
         ])
      );

   }

   public function new(Request $req, Response $res)
   {
      $course = $req->query()->course ?? '';
      
      $mdl = new CourseModel();

      $beginners = $mdl->getCoursesByCategory(1);
      $amateur = $mdl->getCoursesByCategory(2);
      $intermediate = $mdl->getCoursesByCategory(3);
      $advanced = $mdl->getCoursesByCategory(4);

      $res->send(
         $res->render('admin/new-lesson.html', [
            "course" => $course,
            "beginners" => json_encode($beginners ?? []),
            "amateurs" => json_encode($amateur ?? []),
            "intermediates" => json_encode($intermediate ?? []),
            "advanceds" => json_encode($advanced ?? [])
         ])
      );
   }

   public function create(Request $req, Response $res)
   {
      // courses
      $mdl = new CourseModel();
      $beginners = $mdl->getCoursesByCategory(1);
      $amateur = $mdl->getCoursesByCategory(2);
      $intermediate = $mdl->getCoursesByCategory(3);
      $advanced = $mdl->getCoursesByCategory(4);

      $course = trim($req->body()->course);
      $lesson = trim($req->body()->lesson);
      $description = (trim($req->body()->description) != '') ? trim($req->body()->description) : 'No Description' ;
      $thumbnail = ($req->files_exists() == true && $req->files()->thumbnail->error == 0) ? $req->files()->thumbnail : null ;
      
      $data = [
         "course" => $course,
         "beginners" => json_encode($beginners ?? []),
         "amateurs" => json_encode($amateur ?? []),
         "intermediates" => json_encode($intermediate ?? []),
         "advanceds" => json_encode($advanced ?? []),
         'lesson' => $lesson,
         'description' => $description
      ];

      $v = new Validate();

      // validate
      $v->numbers("course", $course, "Invalid Course")->minvalue(1);
      $v->alphanumeric("lesson", $lesson, "Invalid Title")->max(20);
      $v->any("description", $description, "Invalid Description")->max(100);
      $errors = $v->errors();

      if ($errors) {
         $data['errors'] = json_encode($errors);
         $res->send(
            $res->render('admin/new-lesson.html', $data)
         );
      }

      // Sanitize
      $s = new Sanitize();
      $course = $s->numbers($course);
      $lesson = $s->string($lesson);
      $description = $s->string($description);

      if ($thumbnail != null) {
         // upload thumbnail
         $up = new Upload();
         $up->image('thumbnail', $thumbnail, "Lesson Thumbnail was not uploaded", ["image/jpeg"]);
         $up->upload("thumbnails/", Encrypt::hash());

         $errors = $up->errors();

         if ($errors) {
            $data['errors'] = json_encode([$errors['thumbnail']]);
            $res->send(
               $res->render('admin/new-lesson.html', $data)
            );
         }
      
         $path = $up->uri('thumbnail');
      } else {
         $path = STORAGE_PATH . 'thumbnails/default.jpg';
      }

      $mdl = new LessonModel();
      $added = $mdl->addLesson($course, $lesson, $description, $path, User::$fullname ?? 'No Tutor');


      if ($added != false) {

         // add the lesson to the quick lessons
         $mdl = new QuickLessonModel();
         $mdl->addQLesson($added);

         // then added is the last inserted id
         $res->route("/admin/lessons/edit/$added");

      } else {
         // unlink uploaded file
         if ($thumbnail != null) unlink(STORAGE_DIR . $path) ;

         $data['errors'] = json_encode(["Lesson was not added!"]);
         $res->send(
            $res->render('admin/new-lesson.html', $data)
         );
      }
      
   }

   public function edit(Request $req, Response $res)
   {
      if ($req->params_exists()) {
         $id = $req->params()->id;
         $section = $req->query()->section ?? 1;

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
         $section = $s->numbers($section);
         
         // courses
         $mdl = new CourseModel();
         $beginners = $mdl->getCoursesByCategory(1);
         $amateur = $mdl->getCoursesByCategory(2);
         $intermediate = $mdl->getCoursesByCategory(3);
         $advanced = $mdl->getCoursesByCategory(4);

         $mdl = new LessonModel();
         $lesson = $mdl->getLesson($id);

         if ($lesson) {
            $lesson = $lesson[0];
            // get the lesson featured status
            $mdl = new QuickLessonModel();
            $featured = $mdl->getQLesson($id)[0];

            $res->send(
               $res->render('admin/edit-lesson.html', [
                  "id" => $id,
                  "section" => $section,
                  "course" => $lesson['course'], // ?*
                  "beginners" => json_encode($beginners ?? []),
                  "amateurs" => json_encode($amateur ?? []),
                  "intermediates" => json_encode($intermediate ?? []),
                  "advanceds" => json_encode($advanced ?? []),
                  // -------------------------------------------
                  "lesson" => $lesson['lesson'] ?? '',
                  "description" => $lesson['description'] ?? '',
                  "thumbnail" => $lesson['thumbnail'] ?? '',
                  "low_video" => $lesson['low_video'] ?? '',
                  "high_video" => $lesson['high_video'] ?? '',
                  "audio" => $lesson['audio'] ?? '',
                  "tablature" => $lesson['tablature'] ?? '',
                  "practice" => $lesson['practice_audio'] ?? '',
                  "note" => $lesson['note'] ?? '',
                  "price" => $featured['price'],
                  "status" => $featured['status'],
                  "free" => $featured['free']
               ])
            );
         } else {
            $res->redirect($req->referer() ?? '/admin/dashboard');
         }
      } else {
         $res->redirect($req->referer() ?? '/admin/dashboard');
      }
   }

   public function read(Request $req, Response $res) {
      $lesson = $req->params()->lesson ?? null;
      
      if (!is_null($lesson)) {

         $s = new Sanitize();
         $lesson = $s->numbers($lesson);

         $mdl = new LessonModel();
         $lesson = $mdl->getLesson($lesson)[0];

         $res->send(
            $res->json(['lesson' => $lesson])
         );

      } else {
         $res->send(
            $res->json(['error' => 'No Course'])
         );
      }

   }

   private function editContent($id)
   {
      // courses
      $mdl = new CourseModel();
      $beginners = $mdl->getCoursesByCategory(1);
      $amateur = $mdl->getCoursesByCategory(2);
      $intermediate = $mdl->getCoursesByCategory(3);
      $advanced = $mdl->getCoursesByCategory(4);

      $mdl = new LessonModel();
      $lesson = $mdl->getLesson($id);

      if ($lesson) {
         $lesson = $lesson[0];
         // get the lesson featured status
         $mdl = new QuickLessonModel();
         $featured = $mdl->getQLesson($id)[0];
      
         return [
            "id" => $id,
            "course" => $lesson['course'], // ?*
            "beginners" => json_encode($beginners ?? []),
            "amateurs" => json_encode($amateur ?? []),
            "intermediates" => json_encode($intermediate ?? []),
            "advanceds" => json_encode($advanced ?? []),
            // -------------------------------------------
            "lesson" => $lesson['lesson'] ?? '',
            "description" => $lesson['description'] ?? '',
            "thumbnail" => $lesson['thumbnail'] ?? '',
            "low_video" => $lesson['low_video'] ?? '',
            "high_video" => $lesson['high_video'] ?? '',
            "audio" => $lesson['audio'] ?? '',
            "tablature" => $lesson['tablature'] ?? '',
            "practice" => $lesson['practice'] ?? '',
            "note" => $lesson['note'] ?? '',
            "price" => $featured['price'],
            "status" => $featured['status'],
            "free" => $featured['free']
         ];
      } else {
         return [];
      }

   }

   public function updateLowVideo(Request $req, Response $res)
   {
      $id = $req->body()->id ?? '';
      $low_video = ($req->files_exists() == true && $req->files()->low_video->error == 0) ? $req->files()->low_video : null ;
      
      if ($id == '') {
         $data = $this->editContent($id);
         $data['errors'] = json_encode(["Invalid Id!"]);
         $res->send(
            $res->render("admin/edit-lesson.html", $data)
         );
      } elseif ($low_video == null) {
         $data = $this->editContent($id);
         $data['errors'] = json_encode(["Video (Low) was not uploaded!"]);
         $res->send(
            $res->render("admin/edit-lesson.html", $data)
         );
      } else {
         $up = new Upload();

         $up->video('low_video', $low_video, "Video (Low) was not uploaded", ["video/mp4"]);
         $up->upload("tutorials/videos/", Encrypt::hash());

         $errors = $up->errors();

         if ($errors) {
            $data = $this->editContent($id);
            $data['errors'] = json_encode(["Video (Low) was not uploaded!"]);
            $res->send(
               $res->render("admin/edit-lesson.html", $data)
            );
         }
      
         $path = $up->uri('low_video');
         
         $mdl = new LessonModel();
         $added = $mdl->updateLowVideo($id, $path);

         $res->route("/admin/lessons/edit/$id");
      }
   }

   public function updateHighVideo(Request $req, Response $res)
   {
      $id = $req->body()->id ?? '';
      $high_video = ($req->files_exists() == true && $req->files()->high_video->error == 0) ? $req->files()->high_video : null ;
      
      if ($id == '') {
         $data = $this->editContent($id);
         $data['errors'] = json_encode(["Invalid Id!"]);
         $res->send(
            $res->render("admin/edit-lesson.html", $data)
         );
      } elseif ($high_video == null) {
         $data = $this->editContent($id);
         $data['errors'] = json_encode(["Video (High) was not uploaded!"]);
         $res->send(
            $res->render("admin/edit-lesson.html", $data)
         );
      } else {
         $up = new Upload();

         $up->video('high_video', $high_video, "Video (High) was not uploaded", ["video/mp4"]);
         $up->upload("tutorials/videos/", Encrypt::hash());

         $errors = $up->errors();

         if ($errors) {
            $data = $this->editContent($id);
            $data['errors'] = json_encode(["Video (High) was not uploaded!"]);
            $res->send(
               $res->render("admin/edit-lesson.html", $data)
            );
         }
      
         $path = $up->uri('high_video');
         
         $mdl = new LessonModel();
         $added = $mdl->updateHighVideo($id, $path);

         $res->route("/admin/lessons/edit/$id");
      }
   }

   public function updateAudio(Request $req, Response $res)
   {
      $id = $req->body()->id ?? '';
      $audio = ($req->files_exists() == true && $req->files()->audio->error == 0) ? $req->files()->audio : null ;
      
      if ($id == '') {
         $data = $this->editContent($id);
         $data['errors'] = json_encode(["Invalid Id!"]);
         $res->send(
            $res->render("admin/edit-lesson.html", $data)
         );
      } elseif ($audio == null) {
         $data = $this->editContent($id);
         $data['errors'] = json_encode(["Audio was not uploaded!"]);
         $res->send(
            $res->render("admin/edit-lesson.html", $data)
         );
      } else {
         $up = new Upload();

         $up->audio('audio', $audio, "Audio was not uploaded", ["audio/mp3", "audio/mpeg"]);
         $up->upload("tutorials/audios/", Encrypt::hash());

         $errors = $up->errors();

         if ($errors) {
            $data = $this->editContent($id);
            $data['errors'] = json_encode(["Audio was not uploaded!"]);
            $res->send(
               $res->render("admin/edit-lesson.html", $data)
            );
         }
      
         $path = $up->uri('audio');
         
         $mdl = new LessonModel();
         $added = $mdl->updateAudio($id, $path);

         $res->route("/admin/lessons/edit/$id");
      }
   }

   public function updatePractice(Request $req, Response $res)
   {
      $id = $req->body()->id ?? '';
      $practice = ($req->files_exists() == true && $req->files()->practice->error == 0) ? $req->files()->practice : null ;
      
      if ($id == '') {
         $data = $this->editContent($id);
         $data['errors'] = json_encode(["Practice was not uploaded!"]);
         $res->send(
            $res->render("admin/edit-lesson.html", $data)
         );
      } elseif ($practice == null) {
         $data = $this->editContent($id);
         $data['errors'] = json_encode(["Practice was not uploaded!"]);
         $res->send(
            $res->render("admin/edit-lesson.html", $data)
         );
      } else {
         $up = new Upload();

         $up->audio('practice', $practice, "Practice was not uploaded", ["audio/mp3", "audio/mpeg"]);
         $up->upload("tutorials/practice/", Encrypt::hash());

         $errors = $up->errors();

         if ($errors) {
            $data = $this->editContent($id);
            $data['errors'] = json_encode(["Practice was not uploaded!"]);
            $res->send(
               $res->render("admin/edit-lesson.html", $data)
            );
         }
      
         $path = $up->uri('practice');
         
         $mdl = new LessonModel();
         $added = $mdl->updatePractice($id, $path);

         $res->route("/admin/lessons/edit/$id");
      }
   }

   public function updateTablature(Request $req, Response $res)
   {
      $id = $req->body()->id ?? '';
      $tablature = ($req->files_exists() == true && $req->files()->tablature->error == 0) ? $req->files()->tablature : null ;
      
      if ($id == '') {
         $data = $this->editContent($id);
         $data['errors'] = json_encode(["Invalid Id!"]);
         $res->send(
            $res->render("admin/edit-lesson.html", $data)
         );
      } elseif ($tablature == null) {
         $data = $this->editContent($id);
         $data['errors'] = json_encode(["Tablature was not uploaded!"]);
         $res->send(
            $res->render("admin/edit-lesson.html", $data)
         );
      } else {
         // upload low video
         $up = new Upload();

         $up->document('tablature', $tablature, "Tablature was not uploaded", ["application/pdf"]);
         $up->upload("tutorials/tablatures/", Encrypt::hash());

         $errors = $up->errors();

         if ($errors) {
            $data = $this->editContent($id);
            $data['errors'] = json_encode(["Tablature was not uploaded!"]);
            $res->send(
               $res->render("admin/edit-lesson.html", $data)
            );
         }
      
         $path = $up->uri('tablature');
         
         $mdl = new LessonModel();
         $added = $mdl->updateTablature($id, $path);

         $res->route("/admin/lessons/edit/$id");
      }
   }

   public function updateNote(Request $req, Response $res)
   {
      $id = $req->body()->id ?? '';
      $note = $req->body()->note ?? '';
      
      $v = new Validate();
      $v->numbers("id", $id, "Invalid Id!")->minvalue(1);
      $v->any("note", $note, "Invalid Note!")->min(1)->max(65535);
      $errors = $v->errors();

      if ($errors) {
         $data = $this->editContent($id);
         $data['errors'] = json_encode(["Note was not updated!"]);
         $res->send(
            $res->render("admin/edit-lesson.html", $data)
         );
      } else {
         $mdl = new LessonModel();
         $added = $mdl->updateNote($id, $note);

         $res->route("/admin/lessons/edit/$id");
      }
   }

   public function updateDetails(Request $req, Response $res)
   {
      $id = $req->body()->id ?? '';
      $course = trim($req->body()->course);
      $lesson = trim($req->body()->lesson);
      $description = (trim($req->body()->description) != '') ? trim($req->body()->description) : 'No Description' ;
      $thumbnail = ($req->files_exists() == true && $req->files()->thumbnail->error == 0) ? $req->files()->thumbnail : null ;
      
      // validate
      $v = new Validate();
      $v->numbers("id", $id, "Invalid Id!")->minvalue(1);
      $v->numbers("course", $course, "Invalid Course")->minvalue(1);
      $v->alphanumeric("lesson", $lesson, "Invalid Title")->max(20);
      $v->any("description", $description, "Invalid Description")->max(100);
      $errors = $v->errors();

      if ($errors) {
         $data = $this->editContent($id);
         $data['errors'] = json_encode($errors);
         $res->send(
            $res->render("admin/edit-lesson.html", $data)
         );
      } else {

         // Sanitize
         $s = new Sanitize();
         $course = $s->numbers($course);
         $lesson = $s->string($lesson);
         $description = $s->string($description);

         if ($thumbnail != null) {
            // upload thumbnail
            $up = new Upload();
            $up->image('thumbnail', $thumbnail, "Lesson Thumbnail was not updated", ["image/jpeg"]);
            $up->upload("thumbnails/", Encrypt::hash());

            $errors = $up->errors();

            if ($errors) {
               $data = $this->editContent($id);
               $data['errors'] = json_encode([$errors['thumbnail']]);
               $res->send(
                  $res->render('admin/edit-lesson.html', $data)
               );
            }
         
            $path = $up->uri('thumbnail');
            (new LessonModel)->updateThumbnail($id, $path);
         }

         $mdl = new LessonModel();
         $added = $mdl->updateLesson($id, $course, $lesson, $description, User::$fullname ?? 'No Tutor');

         $res->route("/admin/lessons/edit/$id");
      }
   }

   public function updateFeatured(Request $req, Response $res)
   {
      $id = $req->body()->id ?? '';
      $price = $req->body()->price ?? null;
      $status = isset($req->body()->status) ? true : false ;
      $free = isset($req->body()->free) ? true : false ;

      $v = new Validate();
      $v->numbers("id", $id, "Invalid Id!")->minvalue(1);
      $v->amount("price", $price, "Invalid Price!")->minvalue(0);
      $errors = $v->errors();

      $mdl = new QuickLessonModel();
      // $mdl->updateFree($free);
      
      if (($errors || is_null($price)) && $free == false) {
         $data = $this->editContent($id);
         $data['errors'] = json_encode([$errors['price']]);
         $res->send(
            $res->render("admin/edit-lesson.html", $data)
         );
      } else {
         $mdl = new QuickLessonModel();
         $added = $mdl->updateFeatured($id, $status, $price, $free);

         $res->route("/admin/lessons/edit/$id");
      }
   }

   public function delete(Request $req, Response $res)
   {
      // remove a resouce
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
         (new LessonModel)->removeLesson($id);
         
         // remove from quick lesson
         // (new QuickLessonModel)
            
         $res->route('/admin/lessons');
         
      } else {
         $res->redirect($req->referer() ?? '/admin/dashboard');
      }
   }

}
