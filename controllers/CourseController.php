<?php

namespace Controllers;

use Framework\Http\Request;
use Framework\Http\Response;
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
      $description = (trim($req->body()->description) != '') ? trim($req->body()->description) : 'No Description';
      $order = trim($req->body()->order);
      $featured = 0;
      $featuredprice = 0;
      $thumbnail = ($req->files_exists() == true && $req->files()->thumbnail->error == 0) ? $req->files()->thumbnail : null;

      $data = [
         'category' => $category,
         'course' => $course,
         'description' => $description,
         'order' => $order,
         'featured' => $featured,
         'featuredprice' => $featuredprice,
         'categories' => json_encode($categories ?? [])
      ];

      $v = new Validate();

      // validate
      $v->numbers("category", $category, "Invalid Category")->minvalue(1);
      $v->alphanumeric("course", $course, "Invalid Title")->max(100);
      $v->any("description", $description, "Invalid Description")->max(500);
      $v->numbers("order", $order, "Invalid Order")->minvalue(1);
      if ($featured == true) {
         $v->numbers("featuredprice", $featuredprice, "Invalid Featured Price")->minvalue(1);
      }
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
      $added = $mdl->addCourse($category, $course, $description, $path, User::$fullname ?? 'No Tutor', $order, $featured, $featuredprice);
      if ($added != false) {

         // then added is the last inserted id
         $res->route('/admin/lessons/new?course=' . $added);
      } else {
         // unlink uploaded file
         if ($thumbnail != null) unlink(STORAGE_DIR . $path);

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
                  "order" => $course['ord'] ?? '',
                  "featured" => $course['featured'] ?? false,
                  "featuredprice" => $course['featuredprice'] ?? 0,
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
      $description = (trim($req->body()->description) != '') ? trim($req->body()->description) : 'No Description';
      $order = trim($req->body()->order);
      // $featured = isset($req->body()->featured) ? true : false;
      // $featuredprice = isset($req->body()->featured) ? $req->body()->featuredprice : 0;
      $featured = 0;
      $featuredprice = 0;
      $thumbnail = ($req->files_exists() == true && $req->files()->thumbnail->error == 0) ? $req->files()->thumbnail : null;

      $data = [
         'id' => $id,
         'category' => $category,
         'course' => $course,
         'description' => $description,
         'order' => $order,
         'featured' => $featured,
         'featuredprice' => $featuredprice,
         'categories' => json_encode($categories ?? [])
      ];

      $v = new Validate();

      // validate
      $v->numbers("id", $id, "Incalid Id!")->minvalue(1);
      $v->numbers("category", $category, "Invalid Category")->minvalue(1);
      $v->alphanumeric("course", $course, "Invalid Title")->max(100);
      $v->any("description", $description, "Invalid Description")->max(500);
      $v->numbers("order", $order, "Invalid Order")->minvalue(1);
      if ($featured == true) {
         $v->numbers("featuredprice", $featuredprice, "Invalid Featured Price")->minvalue(1);
      }
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

      $mdl->updateCourse($id, $category, $course, $description, User::$fullname ?? 'No Tutor', $order, $featured, $featuredprice);

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

      $res->success('All courses', [
         "beginners" => $beginners,
         "amateurs" => $amateur,
         "intermediates" => $intermediate,
         "advanceds" => $advanced
      ]);
   }

   public function getFeaturedCourses(Request $req, Response $res)
   {
      // return all resources
      $mdl = new CourseModel();
      $courses = $mdl->getFeaturedCourses();

      $res->send(
         $res->render('admin/featured-courses.html', [
            "courses" => json_encode($courses)
         ])
      );
   }

   public function updateFeaturedCourseOrder(Request $req, Response $res)
   {
      $course = trim($req->body()->course);
      $order = trim($req->body()->order);
      $mdl = new CourseModel();
      $mdl->updateFeaturedCourseOrder($course, $order);

      $res->redirect(SERVER . '/admin/courses/featured');
   }

   public function newFeaturedCourses(Request $req, Response $res)
   {
      // return input form to enter course details
      $res->send(
         $res->render('admin/new-featured-course.html', [
            'categories' => json_encode([
               [
                  "id" => "5",
                  "category" => "Featured"
               ]
            ])
         ])
      );
   }

   public function deleteFeaturedCourse(Request $req, Response $res)
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
         $res->route('/admin/courses/featured');
      } else {
         $res->redirect($req->referer() ?? '/admin/dashboard');
      }
   }

   public function createFeaturedCourse(Request $req, Response $res)
   {
      $category = trim($req->body()->category);
      $course = trim($req->body()->course);
      $description = (trim($req->body()->description) != '') ? trim($req->body()->description) : 'No Description';
      $featured = true;
      $featuredprice = $req->body()->featuredprice ?? 0;
      $thumbnail = ($req->files_exists() == true && $req->files()->thumbnail->error == 0) ? $req->files()->thumbnail : null;

      $data = [
         'category' => $category,
         'course' => $course,
         'description' => $description,
         // 'order' => $order,
         'featured' => $featured,
         'featuredprice' => $featuredprice,
         'categories' => json_encode([
            [
               "id" => "5",
               "category" => "Featured"
            ]
         ])
      ];

      $v = new Validate();

      // validate
      $v->numbers("category", $category, "Invalid Category")->minvalue(1);
      $v->alphanumeric("course", $course, "Invalid Title")->max(100);
      $v->any("description", $description, "Invalid Description")->max(500);
      // $v->numbers("order", $order, "Invalid Order")->minvalue(1);
      if ($featured == true) {
         $v->numbers("featuredprice", $featuredprice, "Invalid Featured Price")->minvalue(1);
      }
      $errors = $v->errors();

      if ($errors) {
         $data['errors'] = json_encode($errors);
         $res->send(
            $res->render('admin/new-featured-course.html', $data)
         );
      }

      // Sanitize
      $s = new Sanitize();
      $category = $s->numbers($category);
      $course = $s->string($course);
      $description = $s->string($description);
      $order = 0;

      if ($thumbnail != null) {
         // upload thumbnail
         $up = new Upload();
         $up->image('thumbnail', $thumbnail, "Course Thumbnail was not uploaded", ["image/jpeg"]);
         $up->upload("thumbnails/", Encrypt::hash());

         $errors = $up->errors();

         if ($errors) {
            $data['errors'] = json_encode([$errors['thumbnail']]);
            $res->send(
               $res->render('admin/new-featured-course.html', $data)
            );
         }

         $path = $up->uri('thumbnail');
      } else {
         $path = STORAGE_PATH . 'thumbnails/default.jpg';
      }

      $mdl = new CourseModel();
      $added = $mdl->addCourse($category, $course, $description, $path, User::$fullname ?? 'No Tutor', $order, $featured, $featuredprice);

      if ($added != false) {

         if (($req->files_exists() == true && $req->files()->featured_video->error == 0)) {
            $uploadFeaturedVideo = $this->uploadFeaturedVideo($req, $res);
            $path = "";

            if ($uploadFeaturedVideo['status'] == true) {
               // update path
               $path = $uploadFeaturedVideo['path'];
               $mdl->updateFeaturePreviewVideo($added, $path);
               // then added is the last inserted id
               $res->route('/admin/courses/featured/select?course=' . $added);
            } else {
               $data['errors'] = json_encode([$uploadFeaturedVideo['error']]);
               $res->send(
                  $res->render('admin/new-featured-course.html', $data)
               );
            }
         } else {
            $res->route('/admin/courses/featured/select?course=' . $added);
         }
      } else {
         $data['errors'] = json_encode(["Course was not added!"]);
         $res->send(
            $res->render('admin/new-featured-course.html', $data)
         );
      }
   }

   public function selectFeaturedCourses(Request $req, Response $res)
   {
      // return all lessons for selection
      $featuredCourse = $req->query()->course ?? '';

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

      $selectedLessons = $mdl->getFeaturedCourseLessons($featuredCourse);
      $selectedLessons = explode(" ", $selectedLessons[0]['featured_lessons']);

      $res->send(
         $res->render(
            "admin/select-featured-lessons.html",
            [
               "beginners" => json_encode($beginnerLessons),
               "amateur" => json_encode($amateurLessons),
               "intermediate" => json_encode($intermediateLessons),
               "advanced" => json_encode($advancedLessons),
               "featuredCourse" => $featuredCourse,
               "selectedLessons" => json_encode($selectedLessons)
            ]
         )
      );
   }

   public function AddLessonsForFeaturedCourse(Request $req, Response $res)
   {
      // ALTER TABLE `course_tbl` ADD `featured_lessons` TEXT DEFAULT NULL COMMENT 'a whitespace separated list of lessons in this featured course' AFTER `featuredprice`, ADD INDEX (`featured_lessons`);

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

      $course = $req->query()->course ?? '';
      $lessons = $_POST['courselessons'] ?? [];
      $lessons = join(" ", $lessons);

      $update = $mdl->updateFeaturedCourseLessons($course, $lessons);

      if ($update == true) {
         $courses = $mdl->getFeaturedCourses();

         $res->send(
            $res->render('admin/featured-courses.html', [
               "courses" => json_encode($courses)
            ])
         );
      } else {

         $selectedLessons = $mdl->getFeaturedCourseLessons($course);
         $selectedLessons = explode(" ", $selectedLessons[0]['featured_lessons']);

         $res->send(
            $res->render(
               "admin/select-featured-lessons.html",
               [
                  "beginners" => json_encode($beginnerLessons),
                  "amateur" => json_encode($amateurLessons),
                  "intermediate" => json_encode($intermediateLessons),
                  "advanced" => json_encode($advancedLessons),
                  "featuredCourse" => $course,
                  "selectedLessons" => json_encode($selectedLessons)
               ]
            )
         );
      }
   }

   public function editFeaturedCourse(Request $req, Response $res)
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
               $res->render('admin/edit-featured-course.html', [
                  "id" => $id,
                  "categories" => json_encode($categories ?? []),
                  "category" => $course['category'] ?? '',
                  "course" => $course['course'] ?? '',
                  "description" => $course['description'] ?? '',
                  "thumbnail" => $course['thumbnail'] ?? '',
                  "featured" => $course['featured'] ?? false,
                  "featuredprice" => $course['featuredprice'] ?? 0,
                  "featured_preview_video" => $course['featured_preview_video'] ?? '',
               ])
            );
         } else {
            $res->redirect($req->referer() ?? '/admin/dashboard');
         }
      } else {
         $res->redirect($req->referer() ?? '/admin/dashboard');
      }
   }

   public function updateFeaturedCourse(Request $req, Response $res)
   {
      // categories
      $categories = (new CategoryModel)->getCategories();

      $id = trim($req->body()->id);
      $category = trim($req->body()->category);
      $course = trim($req->body()->course);
      $description = (trim($req->body()->description) != '') ? trim($req->body()->description) : 'No Description';
      $order = 0;
      $featured = true;
      $featuredprice = isset($featured) ? $req->body()->featuredprice : 0;
      $thumbnail = ($req->files_exists() == true && $req->files()->thumbnail->error == 0) ? $req->files()->thumbnail : null;

      $mdl = new CourseModel();
      // $course = $mdl->getCourse($id)[0];

      $data = [
         'id' => $id,
         'categories' => json_encode($categories ?? []),
         'category' => $category,
         'course' => $course,
         'description' => $description,
         'order' => $order,
         'featured' => $featured,
         'featuredprice' => $featuredprice,
         // "featured_preview_video" => $course['featured_preview_video'] ?? '',
      ];

      $v = new Validate();

      // validate
      $v->numbers("id", $id, "Incalid Id!")->minvalue(1);
      // $v->numbers("category", $category, "Invalid Category")->minvalue(1);
      $v->alphanumeric("course", $course, "Invalid Title")->max(100);
      $v->any("description", $description, "Invalid Description")->max(500);
      if ($featured == true) {
         $v->numbers("featuredprice", $featuredprice, "Invalid Featured Price")->minvalue(1);
      }
      $errors = $v->errors();

      if ($errors) {
         $data['errors'] = json_encode($errors);
         $res->send(
            $res->render('admin/edit-featured-course.html', $data)
         );
      }

      // Sanitize
      $s = new Sanitize();
      $id = $s->numbers($id);
      // $category = $s->numbers($category);
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
               $res->render('admin/edit-featured-course.html', $data)
            );
         }

         $path = $up->uri('thumbnail');
      }

      $mdl->updateFeaturedCourse($id, $course, $description, User::$fullname ?? 'No Tutor', $order, $featured, $featuredprice);

      if ($thumbnail != null) $mdl->updateThumbnail($id, $path);

      if (($req->files_exists() == true && $req->files()->featured_video->error == 0)) {
         $uploadFeaturedVideo = $this->uploadFeaturedVideo($req, $res);

         if ($uploadFeaturedVideo['status'] == true) {
            // update path
            $path = $uploadFeaturedVideo['path'];
            $mdl->updateFeaturePreviewVideo($id, $path);
            // then added is the last inserted id
            $res->route('/admin/courses/featured');
         } else {
            $data['errors'] = json_encode([$uploadFeaturedVideo['error']]);
            $res->send(
               $res->render('admin/edit-featured-course.html', $data)
            );
         }
      } else {
         $res->route('/admin/courses/featured');
      }
   }

   public function uploadFeaturedVideo(Request $req, Response $res)
   {
      // ALTER TABLE `course_tbl` ADD `featured_preview_video` VARCHAR(100) NULL COMMENT 'a preview video to display to students before they decide to buy the course' AFTER `featuredprice`;
      $featured_video = ($req->files_exists() == true && $req->files()->featured_video->error == 0) ? $req->files()->featured_video : null;

      if ($featured_video == null) {
         return [
            "status" => false,
            "error" => "Featured Video was not uploaded!"
         ];
      } else {
         $up = new Upload();

         $up->video('featured_video', $featured_video, "Featured Video was not uploaded", ["video/mp4"]);
         $up->upload("tutorials/videos/", Encrypt::hash());

         $errors = $up->errors();
         if ($errors) {
            return [
               "status" => false,
               "error" => "Featured Video was not uploaded!"
            ];
         }
         $path = $up->uri('featured_video');

         return [
            "status" => true,
            "path" => $path
         ];
      }
   }

   public function getFeaturedCoursesLessons(Request $req, Response $res)
   {
      $featuredCourse = $req->query()->course ?? '';

      $mdl = new CourseModel();
      $lmdl = new LessonModel();
      $courseDetails = $mdl->getFeaturedCourseLessons($featuredCourse)[0];
      $lessons = $courseDetails['featured_lessons'];

      if ($lessons == "") $lessons = [];
      else {
         // convert to array
         $lessons = explode(" ", $lessons);

         $count = 0;
         // get lesson title and course title for each lesson
         foreach ($lessons as $lesson) {
            $lessonDetails = $lmdl->getLesson($lesson)[0];
            if ($lessonDetails == null) continue;
            $lessons[$count] = [
               'id' => $lesson,
               'title' => $lessonDetails['lesson'],
               'description' => $lessonDetails['description'],
               'course' => $mdl->getCourse($lessonDetails['course'])[0]['course']
            ];
            $count++;
         }
      }

      $res->send(
         $res->render('admin/featured-courses-lessons.html', [
            'lessons' => json_encode($lessons),
            'course' => $featuredCourse,
            'featuredCourse' => $courseDetails['course']
         ])
      );
   }

   public function updateLessonsForFeaturedCourse(Request $req, Response $res)
   {
      $course = trim($req->body()->course);
      $order = $_POST['order'];
      $rawLessons = $_POST['lesson'];

      // create a map of order and lesson
      $objs = [];
      $count = 0;
      foreach ($rawLessons as $lesson) {
         $objs[$count] = [
            'order' => $order[$count],
            'lesson' => $lesson
         ];
         $count++;
      }

      // sort lessons according to their order
      for ($i = 0; $i < count($objs); $i++) {
         for ($j = $i + 1; $j < count($objs); $j++) {
            if ($objs[$i]['order'] > $objs[$j]['order']) {
               $temp = $objs[$i];
               $objs[$i] = $objs[$j];
               $objs[$j] = $temp;
            }
         }
      }

      // extract lessons
      $lessons = [];
      foreach ($objs as $obj) {
         $lessons[] = $obj['lesson'];
      }

      $lessons = join(" ", $lessons);
      $mdl = new CourseModel();
      $update = $mdl->updateFeaturedCourseLessons($course, $lessons);

      if ($update == true) {
         $res->redirect(SERVER . '/admin/courses/featured');
      } else {
         $res->redirect($req->referer() ?? '/admin/dashboard');
      }
   }

   public function getCourseLessons(Request $req, Response $res)
   {
      $course = $req->params()->course ?? null;

      if (!is_null($course)) {

         $s = new Sanitize();
         $course = $s->numbers($course);

         $mdl = new LessonModel();
         $lessons = $mdl->getLessonsByCourse($course);

         if (count($lessons) > 0) {
            $res->success('Course lessons', $lessons);
         } else {
            $res->error('No lessons');
         }
      } else {
         $res->error('Invalid course');
      }
   }

   public function getCategoryLessons(Request $req, Response $res)
   {
      $category = $req->params()->category ?? null;

      if (!is_null($category)) {

         $s = new Sanitize();
         $course = $s->numbers($category);

         $cMdl = new CourseModel();
         $courses = $cMdl->getCoursesByCategory($category);

         $lMdl = new LessonModel();
         $lessons = [];

         foreach ($courses as $course) {
            $lessons =  array_merge($lessons, $lMdl->getLessonsByCourse($course['id']));
         }

         if (count($lessons) > 0) {
            $res->success('Category lessons', $lessons);
         } else {
            $res->error('No lessons');
         }
      } else {
         $res->error('Invalid category');
      }
   }

   public function getApiFeaturedCourseLessons(Request $req, Response $res)
   {
      $course = $req->params()->course ?? null;

      if (!is_null($course)) {

         $s = new Sanitize();
         $course = $s->numbers($course);

         $mdl = new CourseModel();
         $courseDetails = $mdl->getFeaturedCourseLessons($course)[0];
         $lessonIds = $courseDetails['featured_lessons'];

         // convert to array
         if ($lessonIds == "") $lessonIds = [];
         else {
            $lessonIds = explode(" ", $lessonIds);
         }

         $mdl = new LessonModel();
         $lessons = []; //$mdl->getLessonsByCourse($course);

         foreach ($lessonIds as $lesson) {
            $lessons[] = $mdl->getLesson($lesson)[0];
         }

         if (count($lessons) > 0) {
            $res->success('Course lessons', $lessons);
         } else {
            $res->error('No lessons');
         }
      } else {
         $res->error('Invalid course');
      }
   }


   public function search(Request $req, Response $res)
   {
      $query = $req->query()->q ?? '';

      $s = new Sanitize();
      $query = $s->string($query);

      $mdl = new CourseModel();
      $result = $mdl->search($query);

      //   $res->send(
      //      $res->json([
      //         "result"=>$result
      //      ])
      //   );

      if (count($result) > 0) {
         $res->success('Search results', $result);
      } else {
         $res->error('No result');
      }
   }
}
