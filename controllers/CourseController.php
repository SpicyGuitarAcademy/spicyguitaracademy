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
use Models\CategoryModel;
use Models\CourseModel;
use Models\LessonModel;

class CourseController
{

   public function index(Request $req, Response $res)
   {
      // return all resources
      $mdl = new CourseModel();

      $beginners = $mdl->getCoursesByCategory(1);
      $amateur = $mdl->getCoursesByCategory(2);
      $intermediate = $mdl->getCoursesByCategory(3);
      $advanced = $mdl->getCoursesByCategory(4);

      // TODO: consider api's too by returning json instead
      $res->send(
         $res->render('admin/courses.html', [
            "beginners" => json_encode($beginners),
            "amateurs" => json_encode($amateur),
            "intermediates" => json_encode($intermediate),
            "advanceds" => json_encode($advanced)
         ])
      );
   }

   public function new(Request $req, Response $res)
   {
      $categories = (new CategoryModel)->getCategories();

      $res->send(
         $res->render('admin/new-course.html', [
            'categories' => json_encode($categories ?? [])
         ])
      );
   }

   public function create(Request $req, Response $res)
   {

      // categories
      $categories = (new CategoryModel)->getCategories();

      $category = trim($req->body()->category);
      $course = trim($req->body()->course);
      // NOTE: this is adding '' to description on empty field passed
      $description = (trim($req->body()->description) != '') ? trim($req->body()->description) : 'No Description' ;
      $order = trim($req->body()->order);
      $thumbnail = ($req->files_exists() == true && $req->files()->thumbnail->error == 0) ? $req->files()->thumbnail : null ;
      
      $data = [
         'category' => $category,
         'course' => $course,
         'description' => $description,
         'order' => $order,
         'categories' => json_encode($categories ?? [])
      ];

      $v = new Validate();

      // validate
      $v->numbers("category", $category, "Invalid Category")->minvalue(1);
      $v->alphanumeric("course", $course, "Invalid Title")->max(100);
      $v->any("description", $description, "Invalid Description")->max(500);
      $v->numbers("order", $order, "Invalid Order")->minvalue(1);
      $errors = $v->errors();

      if ($errors) {
         $data['errors'] = json_encode($errors);
         $res->send(
            $res->render('admin/new-course.html', $data)
         );
      }

      // Sanitize
      $s = new Sanitize();
      $category = $s->numbers($category);
      $course = $s->string($course);
      $description = $s->string($description);
      $order = $s->numbers($order);

      if ($thumbnail != null) {
         // upload thumbnail
         $up = new Upload();
         $up->image('thumbnail', $thumbnail, "Course Thumbnail was not uploaded", ["image/jpeg"]);
         $up->upload("thumbnails/", Encrypt::hash());

         $errors = $up->errors();

         if ($errors) {
            $data['errors'] = json_encode([$errors['thumbnail']]);
            $res->send(
               $res->render('admin/new-course.html', $data)
            );
         }
      
         $path = $up->uri('thumbnail');
      } else {
         $path = STORAGE_PATH . 'thumbnails/default.jpg';
      }

      $mdl = new CourseModel();
      $added = $mdl->addCourse($category, $course, $description, $path, User::$fullname ?? 'No Tutor', $order);
      if ($added != false) {

         // then added is the last inserted id
         $res->route('/admin/lessons/new?course='.$added);

      } else {
         // unlink uploaded file
         if ($thumbnail != null) unlink(STORAGE_DIR . $path) ;

         $data['errors'] = json_encode(["Course was not added!"]);
         $res->send(
            $res->render('admin/new-course.html', $data)
         );
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

         $categories = (new CategoryModel)->getCategories();
         $mdl = new CourseModel();
         $course = $mdl->getCourse($id);

         if ($course) {
            $course = $course[0];

            $res->send(
               $res->render('admin/edit-course.html', [
                  "id" => $id,
                  "categories" => json_encode($categories ?? []),
                  "category" => $course['category'] ?? '',
                  "course" => $course['course'] ?? '',
                  "description" => $course['description'] ?? '',
                  "thumbnail" => $course['thumbnail'] ?? '',
                  "order" => $course['ord'] ?? ''
               ])
            );
         } else {
            $res->redirect($req->referer() ?? '/admin/dashboard');
         }
      } else {
         $res->redirect($req->referer() ?? '/admin/dashboard');
      }
   }

   public function read(Request $req, Response $res)
   {
      // return a resource
   }

   public function update(Request $req, Response $res)
   {
      // categories
      $categories = (new CategoryModel)->getCategories();

      $id = trim($req->body()->id);
      $category = trim($req->body()->category);
      $course = trim($req->body()->course);
      $description = (trim($req->body()->description) != '') ? trim($req->body()->description) : 'No Description' ;
      $order = trim($req->body()->order);
      $thumbnail = ($req->files_exists() == true && $req->files()->thumbnail->error == 0) ? $req->files()->thumbnail : null ;
      
      $data = [
         'id' => $id,
         'category' => $category,
         'course' => $course,
         'description' => $description,
         'order' => $order,
         'categories' => json_encode($categories ?? [])
      ];

      $v = new Validate();

      // validate
      $v->numbers("id", $id, "Incalid Id!")->minvalue(1);
      $v->numbers("category", $category, "Invalid Category")->minvalue(1);
      $v->alphanumeric("course", $course, "Invalid Title")->max(100);
      $v->any("description", $description, "Invalid Description")->max(500);
      $v->numbers("order", $order, "Invalid Order")->minvalue(1);
      $errors = $v->errors();

      if ($errors) {
         $data['errors'] = json_encode($errors);
         $res->send(
            $res->render('admin/edit-course.html', $data)
         );
      }

      // Sanitize
      $s = new Sanitize();
      $id = $s->numbers($id);
      $category = $s->numbers($category);
      $course = $s->string($course);
      $description = $s->string($description);
      $order = $s->numbers($order);

      $mdl = new CourseModel();
      if ($thumbnail != null) {
         // upload thumbnail
         $up = new Upload();
         $up->image('thumbnail', $thumbnail, "Course Thumbnail was not uploaded", ["image/jpeg"]);
         $up->upload("thumbnails/", Encrypt::hash());

         $errors = $up->errors();

         if ($errors) {
            $data['errors'] = json_encode([$errors['thumbnail']]);
            $res->send(
               $res->render('admin/edit-course.html', $data)
            );
         }
      
         $path = $up->uri('thumbnail');
      }

      $mdl->updateCourse($id, $category, $course, $description, User::$fullname ?? 'No Tutor', $order);

      if ($thumbnail != null) $mdl->updateThumbnail($id, $path);

      // then added is the last inserted id
      $res->route('/admin/courses');

   }

   public function delete(Request $req, Response $res)
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

         (new CourseModel)->removeCourse($id);
         (new LessonModel)->removeLessonByCourse($id);
            
         $res->route('/admin/courses');
         
      } else {
         $res->redirect($req->referer() ?? '/admin/dashboard');
      }
   }

   public function getAllCourses(Request $req, Response $res)
   {
      
      $mdl = new CourseModel();

      $beginners = $mdl->getCoursesByCategory(1);
      $amateur = $mdl->getCoursesByCategory(2);
      $intermediate = $mdl->getCoursesByCategory(3);
      $advanced = $mdl->getCoursesByCategory(4);

      // TODO: consider api's too by returning json instead
      $res->send(
         $res->json([
            // "status" => true,
            // "data" => [
                "beginners" => $beginners,
                "amateurs" => $amateur,
                "intermediates" => $intermediate,
                "advanceds" => $advanced
            // ]
         ])
      );

   }

   public function getCourseLessons(Request $req, Response $res)
   {
      $course = $req->params()->course ?? null;

      if (!is_null($course)) {

         $s = new Sanitize();
         $course = $s->numbers($course);

         $mdl = new LessonModel();
         $lessons = $mdl->getLessonsByCourse($course);

         $res->send(
            $res->json(['lessons' => $lessons])
         );

      } else {
         $res->send(
            $res->json(['error' => 'No Course'])
         );
      }

   }

   public function search(Request $req, Response $res)
   {
      $query = $req->query()->q ?? '';

      $s = new Sanitize();
      $query = $s->string($query);

      $mdl = new CourseModel();
      $result = $mdl->search($query);

      $res->send(
         $res->json([
            "result"=>$result
         ])
      );
   }

}
