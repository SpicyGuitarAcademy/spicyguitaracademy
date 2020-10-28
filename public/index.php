<?php
// namespace App;
use Framework\Handler\IException;
use Framework\Http\Http;
use Framework\Http\Request;
use Framework\Http\Response;

// Include autoload for composer packages
include_once '../vendor/autoload.php';
// Setup Configurations
include_once '../app/config.php';

// Start Application 😉
$http = new Http();

// Now let's Route 🚀🚀🚀



$http->group('guest');
/* 
   ----------------------------------------------------------------
   Guest Routes
   ----------------------------------------------------------------

   All public routes for guest users

   ----------------------------------------------------------------
*/

$http->get('/', function(Request $req, Response $res) {
   $res->redirect('admin');
});




$http->group('user');
/* 
   ----------------------------------------------------------------
   User Routes
   ----------------------------------------------------------------

   All routes for authenticated users

   ----------------------------------------------------------------
*/

$http->auth('web')->get('/dashboard', function (Request $req, Response $res) {
   die('In');
});

$http->auth('web')->get('/logout', function (Request $req, Response $res) {
   \App\Services\Auth::session_logout( $req, $res );
});

$http->group('admin');
/* 
   ----------------------------------------------------------------
   Admin Routes
   ----------------------------------------------------------------

   All routes for authenticated admin users

   ----------------------------------------------------------------
*/

$http->get('/admin', function(Request $req, Response $res) {
   $res->redirect('admin/login');
});

// Auth
$http->get('/admin/login', function(Request $req, Response $res) {
   // die(\App\Services\User::$group);
   if ($req->query_exists() && isset($req->query()->redirect)) {
      $res->send(
         $res->render('welcome.html', [
            "redirect" => $req->query()->redirect
         ]), 200
      );
   } else {
      $res->send(
         $res->render('admin/login.html'), 200
      );
   }
});

$http->auth('web')->get('/admin/logout', function (Request $req, Response $res) {
   \App\Services\Auth::session_logout( $req, $res );
});

$http->csrf()->post('/admin/auth', 'AuthController@authAdminLogin');

// Dashboard
$http->auth('web')->guard('admin','tutor')->get('/admin/dashboard', function(Request $req, Response $res) {
   $res->send(
      $res->render('admin/dashboard.html'), 200
   );
});

// Categories
$http->auth('web')->guard('admin','tutor')->get('/admin/categories', 'CategoryController@index');

$http->auth('web')->guard('admin','tutor')->get('/admin/categories/new', function ($req, $res) {
   $res->send(
      $res->render('admin/new-category.html')
   );
});

$http->auth('web')->guard('admin','tutor')->csrf()->post('/admin/add-category', 'CategoryController@create');

// Courses
$http->auth('web')->guard('admin','tutor')->get('/admin/courses', 'CourseController@index');

$http->auth('web')->guard('admin','tutor')->get('/admin/courses/new', 'CourseController@new');

$http->auth('web')->guard('admin','tutor')->csrf()->post('/admin/courses/add', 'CourseController@create');

$http->auth('web')->guard('admin','tutor')->get('/admin/courses/edit/{id}', 'CourseController@edit');

$http->auth('web')->guard('admin','tutor')->csrf()->patch('/admin/courses/update', 'CourseController@update');

$http->auth('web')->guard('admin','tutor')->csrf()->delete('/admin/courses/delete/{id}', 'CourseController@delete');

// Assignments

$http->auth('web')->guard('admin','tutor')->get('/admin/courses/{id}/assignment', 'AssignmentController@index');

$http->auth('web')->guard('admin','tutor')->get('/admin/courses/{id}/assignment/new', 'AssignmentController@new');

$http->auth('web')->guard('admin','tutor')->csrf()->post('/admin/courses/{id}/assignment/add', 'AssignmentController@create');

$http->auth('web')->guard('admin','tutor')->get('/admin/courses/{id}/assignment/edit', 'AssignmentController@edit');

$http->auth('web')->guard('admin','tutor')->csrf()->patch('/admin/courses/{id}/assignment/update', 'AssignmentController@update');

$http->auth('web')->guard('admin','tutor')->csrf()->delete('/admin/courses/{id}/assignment/delete', 'AssignmentController@delete');

// lessons
$http->auth('web')->guard('admin','tutor')->get('/admin/lessons', 'LessonController@index');

$http->auth('web')->guard('admin','tutor')->get('/admin/lessons/new', 'LessonController@new');

$http->auth('web')->guard('admin','tutor')->csrf()->post('/admin/lessons/add', 'LessonController@create');

$http->auth('web')->guard('admin','tutor')->get('/admin/lessons/edit/{id}', 'LessonController@edit');

$http->auth('web')->guard('admin','tutor')->delete('/admin/lessons/delete/{id}', 'LessonController@delete');

$http->auth('web')->guard('admin','tutor')->csrf()->put('/admin/lessons/update/low-video', 'LessonController@updateLowVideo');

$http->auth('web')->guard('admin','tutor')->csrf()->put('/admin/lessons/update/high-video', 'LessonController@updateHighVideo');

$http->auth('web')->guard('admin','tutor')->csrf()->put('/admin/lessons/update/audio', 'LessonController@updateAudio');

$http->auth('web')->guard('admin','tutor')->csrf()->put('/admin/lessons/update/practice', 'LessonController@updatePractice');

$http->auth('web')->guard('admin','tutor')->csrf()->put('/admin/lessons/update/tablature', 'LessonController@updateTablature');

$http->auth('web')->guard('admin','tutor')->csrf()->patch('/admin/lessons/update/note', 'LessonController@updateNote');

$http->auth('web')->guard('admin','tutor')->csrf()->patch('/admin/lessons/update/details', 'LessonController@updateDetails');

$http->auth('web')->guard('admin','tutor')->csrf()->patch('/admin/lessons/update/featured', 'LessonController@updateFeatured');

// Featured Lessons
$http->auth('web')->guard('admin','tutor')->get('/admin/lessons/featured', 'FeaturedLessonController@index');

$http->auth('web')->guard('admin','tutor')->get('/admin/lessons/free', 'FeaturedLessonController@free');

// tutors
$http->auth('web')->guard('admin')->get('/admin/tutors', 'TutorController@index');

$http->auth('web')->guard('admin')->get('/admin/tutors/new', 'TutorController@new');

$http->auth('web')->guard('admin')->csrf()->post('/admin/tutors/add', 'TutorController@create');

$http->auth('web')->guard('admin')->csrf()->patch('/admin/tutors/update-status', 'TutorController@updateStatus');


/* 
   ----------------------------------------------------------------
   API Routes
   ----------------------------------------------------------------

   All public routes for authenticated APIs

   ----------------------------------------------------------------
*/

$http->group('api');

// ->auth('api')

$http->post('/api/register','StudentController@register');

$http->post('/api/login','AuthController@authApiLogin');

$http->get('/api/paystack/key', function(Request $req, Response $res) {

});

$http->auth('api')->guard('student')->get('/api/subscription/plans','SubscriptionController@plans');

$http->auth('api')->guard('student')->get('/api/subscription/status','SubscriptionController@status');

// $http->auth('api')->guard('student')->get('/api/subscription/remaining','SubscriptionController@daysRemaining');

$http->auth('api')->guard('student')->post('/api/subscription/initiate','SubscriptionController@initiatePayment');

$http->auth('api')->guard('student')->post('/api/subscription/verify/{reference}','SubscriptionController@verifyPayment');

// $http->auth('api')->guard('student')->post('/api/subscription/subscribe','SubscriptionController@subscribe');

// student statistics
$http->auth('api')->guard('student')->get('/api/student/statistics','StudentController@stats');

// student select's a cateogry
$http->auth('api')->guard('student')->post('/api/student/category/select','StudentController@chooseCategory');

// update student lesson status
// $http->auth('api')->guard('student')->patch('/api/student/lesson/update','StudentController@chooseCategory');

// list all courses
$http->auth('api')->guard('student')->get('/api/course/all','CourseController@getAllCourses');

// list lessons in a course
$http->auth('api')->guard('student')->get('/api/course/{course}/lessons','CourseController@getCourseLessons');

// get a lesson
$http->auth('api')->guard('student')->get('/api/lesson/{lesson}','LessonController@read');

// list student studying course
$http->auth('api')->guard('student')->get('/api/student/courses/studying','StudentController@studyingCourses');

// list lessons in a course
$http->auth('api')->guard('student')->get('/api/student/course/lessons','StudentController@studyingCourses');

// get a lesson for the student
$http->auth('api')->guard('student')->get('/api/student/lesson/{lesson}','StudentController@getStudentLesson');

// get next lesson in a course
$http->auth('api')->guard('student')->get('/api/student/lesson/{lesson}/next','StudentController@nextLesson');

// get previous lesson in a course
$http->auth('api')->guard('student')->get('/api/student/lesson/{lesson}/previous','StudentController@previousLesson');

$http->auth('api')->guard('student')->post('/api/student/assignment/answer', 'StudentController@answerAssignment');

$http->auth('api')->guard('student')->get('/api/courses/search', 'CourseController@search');

$http->auth('api')->guard('student')->post('/api/invite-a-friend', 'StudentController@invitefriend');

$http->auth('api')->guard('student')->post('/api/student/avatar/update','StudentController@uploadAvatar');

$http->auth('api')->guard('student')->get('/api/student/quicklessons','StudentController@quickLessons');

$http->auth('api')->guard('student')->get('/api/student/quicklessons/all','StudentController@allQuickLessons');

$http->auth('api')->guard('student')->get('/api/student/freelessons','StudentController@freeLessons');

$http->auth('api')->guard('student')->get('/api/student/quicklesson/{lesson}','StudentController@quickLesson');

$http->end();