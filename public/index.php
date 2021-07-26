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

date_default_timezone_set(TIMEZONE);

// Start Application 😉
$http = new Http();

// Now let's Route 🚀🚀🚀



$http->group('guest');

$http->get('/', function (Request $req, Response $res) {

   $comments = [
      [
         'name' => 'Abiodun A.',
         'comment' => "Spicy Guitar Academy Is A Great Learning Hub For Any Guitarist That Want To Get On Top Of His/Her Craft. Whether You're A Beginner, Amateur Or Advance Guitar Player, The Academy Will Teach To Grow From Any Of These Categories To A More Advanced Level.",
         'avatar' => 'picture.png'
      ],
      [
         'name' => 'Abiodun B.',
         'comment' => "Spicy Guitar Academy Is A Great Learning Hub For Any Guitarist That Want To Get On Top Of His/Her Craft. Whether You're A Beginner, Amateur Or Advance Guitar Player, The Academy Will Teach To Grow From Any Of These Categories To A More Advanced Level.",
         'avatar' => 'picture.png'
      ],
      [
         'name' => 'Abiodun C.',
         'comment' => "Spicy Guitar Academy Is A Great Learning Hub For Any Guitarist That Want To Get On Top Of His/Her Craft. Whether You're A Beginner, Amateur Or Advance Guitar Player, The Academy Will Teach To Grow From Any Of These Categories To A More Advanced Level.",
         'avatar' => 'picture.png'
      ],
      [
         'name' => 'Abiodun D.',
         'comment' => "Spicy Guitar Academy Is A Great Learning Hub For Any Guitarist That Want To Get On Top Of His/Her Craft. Whether You're A Beginner, Amateur Or Advance Guitar Player, The Academy Will Teach To Grow From Any Of These Categories To A More Advanced Level.",
         'avatar' => 'picture.png'
      ]
   ];
   $res->send(
      $res->render('homepage.html'),
      // $res->render('homepage.html'
      // , [
      //    "studentcomments" => "Hi",
      //    "studentcomments" => $comments,
      //    // "studentcomments" => json_encode($comments)
      // ]),
      200
   );

   // ,['stories' => json_encode($comments)]
});

$http->get('/terms', function (Request $req, Response $res) {
   die('Terms and Condition');
});

$http->group('user');

// All routes for authenticated users

$http->auth('web')->get('/logout', function (Request $req, Response $res) {
   \App\Services\Auth::session_logout($req, $res);
});

$http->group('admin');
/* 
   ----------------------------------------------------------------
   Admin Routes
   ----------------------------------------------------------------

   All routes for authenticated admin users

   ----------------------------------------------------------------
*/

$http->get('/admin', function (Request $req, Response $res) {
   $res->redirect('admin/login');
});

// Auth
$http->get('/admin/login', function (Request $req, Response $res) {
   // die(\App\Services\User::$group);
   if ($req->query_exists() && isset($req->query()->redirect)) {
      $res->send(
         $res->render('welcome.html', [
            "redirect" => $req->query()->redirect
         ]),
         200
      );
   } else {
      $res->send(
         $res->render('admin/login.html'),
         200
      );
   }
});

$http->auth('web')->get('/admin/logout', function (Request $req, Response $res) {
   \App\Services\Auth::session_logout($req, $res);
});

$http->csrf()->post('/admin/auth', 'AuthController@authAdminLogin');

// Dashboard
$http->auth('web')->guard('admin', 'tutor')->get('/admin/dashboard', function (Request $req, Response $res) {
   $res->send(
      $res->render('admin/dashboard.html'),
      200
   );
});

// Profile
$http->auth('web')->guard('admin', 'tutor')->get('/admin/profile', 'TutorController@profile');

$http->auth('web')->guard('admin', 'tutor')->post('/admin/profile/uploadavatar', 'TutorController@uploadavatar');

$http->auth('web')->guard('admin', 'tutor')->post('/admin/profile/updateprofile', 'TutorController@updateprofile');

// Categories
$http->auth('web')->guard('admin', 'tutor')->get('/admin/categories', 'CategoryController@index');

$http->auth('web')->guard('admin', 'tutor')->get('/admin/categories/new', function ($req, $res) {
   $res->send(
      $res->render('admin/new-category.html')
   );
});

$http->auth('web')->guard('admin', 'tutor')->csrf()->post('/admin/add-category', 'CategoryController@create');

// Courses
$http->auth('web')->guard('admin', 'tutor')->get('/admin/courses', 'CourseController@index');

$http->auth('web')->guard('admin', 'tutor')->get('/admin/courses/new', 'CourseController@new');

$http->auth('web')->guard('admin', 'tutor')->csrf()->post('/admin/courses/add', 'CourseController@create');

$http->auth('web')->guard('admin', 'tutor')->get('/admin/courses/edit/{id}', 'CourseController@edit');

$http->auth('web')->guard('admin', 'tutor')->csrf()->patch('/admin/courses/update', 'CourseController@update');

$http->auth('web')->guard('admin', 'tutor')->csrf()->delete('/admin/courses/delete/{id}', 'CourseController@delete');

// Assignments & Answers

$http->auth('web')->guard('admin', 'tutor')->get('/admin/courses/{id}/assignment', 'AssignmentController@index');

$http->auth('web')->guard('admin', 'tutor')->csrf()->patch('/admin/assignment/answer/update-rating', 'AssignmentController@updateRating');

$http->auth('web')->guard('admin', 'tutor')->get('/admin/courses/{id}/assignment/new', 'AssignmentController@new');

$http->auth('web')->guard('admin', 'tutor')->csrf()->post('/admin/courses/{id}/assignment/add', 'AssignmentController@create');

$http->auth('web')->guard('admin', 'tutor')->get('/admin/courses/{id}/assignment/edit', 'AssignmentController@edit');

$http->auth('web')->guard('admin', 'tutor')->csrf()->patch('/admin/courses/{id}/assignment/update', 'AssignmentController@update');

$http->auth('web')->guard('admin', 'tutor')->csrf()->delete('/admin/courses/{id}/assignment/delete', 'AssignmentController@delete');

// lessons
$http->auth('web')->guard('admin', 'tutor')->get('/admin/lessons', 'LessonController@index');

$http->auth('web')->guard('admin', 'tutor')->get('/admin/lessons/new', 'LessonController@new');

$http->auth('web')->guard('admin', 'tutor')->csrf()->post('/admin/lessons/add', 'LessonController@create');

$http->auth('web')->guard('admin', 'tutor')->get('/admin/lessons/edit/{id}', 'LessonController@edit');

$http->auth('web')->guard('admin', 'tutor')->delete('/admin/lessons/delete/{id}', 'LessonController@delete');

$http->auth('web')->guard('admin', 'tutor')->csrf()->put('/admin/lessons/update/low-video', 'LessonController@updateLowVideo');

$http->auth('web')->guard('admin', 'tutor')->csrf()->put('/admin/lessons/update/high-video', 'LessonController@updateHighVideo');

$http->auth('web')->guard('admin', 'tutor')->csrf()->put('/admin/lessons/update/audio', 'LessonController@updateAudio');

$http->auth('web')->guard('admin', 'tutor')->csrf()->put('/admin/lessons/update/practice', 'LessonController@updatePractice');

$http->auth('web')->guard('admin', 'tutor')->csrf()->put('/admin/lessons/update/tablature', 'LessonController@updateTablature');

$http->auth('web')->guard('admin', 'tutor')->csrf()->patch('/admin/lessons/update/note', 'LessonController@updateNote');

$http->auth('web')->guard('admin', 'tutor')->csrf()->patch('/admin/lessons/update/details', 'LessonController@updateDetails');

// $http->auth('web')->guard('admin','tutor')->csrf()->patch('/admin/lessons/update/featured', 'LessonController@updateFeatured');

// Featured Courses
$http->auth('web')->guard('admin', 'tutor')->get('/admin/courses/featured', 'CourseController@getFeaturedCourses');

$http->auth('web')->guard('admin', 'tutor')->get('/admin/courses/featured/new', 'CourseController@newFeaturedCourses');

$http->auth('web')->guard('admin', 'tutor')->post('/admin/courses/featured/create', 'CourseController@createFeaturedCourse');

$http->auth('web')->guard('admin', 'tutor')->csrf()->delete('/admin/courses/featured/delete/{id}', 'CourseController@deleteFeaturedCourse');

$http->auth('web')->guard('admin', 'tutor')->get('/admin/courses/featured/select', 'CourseController@selectFeaturedCourses');

$http->auth('web')->guard('admin', 'tutor')->post('/admin/courses/featured/select/submit', 'CourseController@AddLessonsForFeaturedCourse');

$http->auth('web')->guard('admin', 'tutor')->get('/admin/courses/featured/lessons', 'CourseController@getFeaturedCoursesLessons');

$http->auth('web')->guard('admin', 'tutor')->post('/admin/courses/featured/lessons/update', 'CourseController@updateLessonsForFeaturedCourse');

$http->auth('web')->guard('admin', 'tutor')->get('/admin/courses/featured/edit/{id}', 'CourseController@editFeaturedCourse');

$http->auth('web')->guard('admin', 'tutor')->csrf()->patch('/admin/courses/featured/update', 'CourseController@updateFeaturedCourse');

$http->auth('web')->guard('admin', 'tutor')->get('/admin/lessons/free', 'LessonController@free');

// students
$http->auth('web')->guard('admin', 'tutors')->get('/admin/students', 'TutorController@students');

$http->auth('web')->guard('admin', 'tutors')->get('/admin/student/comments', 'TutorController@studentComments');

$http->auth('web')->guard('admin', 'tutors')->post('/admin/student/comments/addcomment', 'TutorController@addLessonComment');

// tutors
$http->auth('web')->guard('admin')->get('/admin/tutors', 'TutorController@index');

$http->auth('web')->guard('admin')->get('/admin/tutors/new', 'TutorController@new');

$http->auth('web')->guard('admin')->csrf()->post('/admin/tutors/add', 'TutorController@create');

$http->auth('web')->guard('admin')->csrf()->patch('/admin/tutors/update-status', 'TutorController@updateStatus');

// Subscription plans
$http->auth('web')->guard('admin')->get('/admin/subscriptions', 'SubscriptionController@subscriptions');

$http->auth('web')->guard('admin')->post('/admin/subscriptions/updateprice', 'SubscriptionController@updateprice');

$http->auth('web')->guard('admin')->post('/admin/subscriptions/updatedescription', 'SubscriptionController@updatedescription');

// Transactions
$http->auth('web')->guard('admin')->get('/admin/transactions', 'TransactionController@index');

/* 
   ----------------------------------------------------------------
   API Routes
   ----------------------------------------------------------------

   All public routes for authenticated APIs

   ----------------------------------------------------------------
*/

$http->group('api');

// ->auth('api')

$http->post('/api/register_student', 'StudentController@register');

$http->post('/api/login', 'AuthController@authApiLogin');

$http->get('/api/paystack/key', function (Request $req, Response $res) {
   $res->success('Paystack key', ['key' => PAYSTACK_PUBLIC_KEY]);
});

$http->auth('api')->guard('student')->get('/api/subscription/plans', 'SubscriptionController@plans');

$http->auth('api')->guard('student')->get('/api/subscription/status', 'SubscriptionController@status');

$http->auth('api')->guard('student')->post('/api/subscription/initiate', 'SubscriptionController@initiatePayment');

$http->auth('api')->guard('student')->post('/api/subscription/initiate-featured', 'SubscriptionController@initiateFeaturedPayment');

$http->auth('api')->guard('student')->post('/api/subscription/verify/{reference}', 'SubscriptionController@verifyPayment');

$http->auth('api')->guard('student')->post('/api/subscription/verify-featured/{reference}', 'SubscriptionController@verifyFeaturedPayment');

// student statistics
$http->auth('api')->guard('student')->get('/api/student/statistics', 'StudentController@stats');

// student select's a cateogry
$http->auth('api')->guard('student')->post('/api/student/category/select', 'StudentController@chooseCategory');

// make course active
$http->auth('api')->guard('student')->post('/api/student/course/activate', 'StudentController@activateNormalCourse');

// make lesson active
$http->auth('api')->guard('student')->post('/api/student/lesson/activate', 'StudentController@activateNormalLesson');

// make lesson active
$http->auth('api')->guard('student')->post('/api/student/lesson/activate-featured', 'StudentController@activateFeaturedLesson');

// list all courses
$http->auth('api')->guard('student')->get('/api/student/courses/all', 'StudentController@allCourses');

// list student studying course
$http->auth('api')->guard('student')->get('/api/student/courses/studying', 'StudentController@studyingCourses');

// list all featured courses
$http->auth('api')->guard('student')->get('/api/student/featuredcourses/all', 'StudentController@allFeaturedCourses');

$http->auth('api')->guard('student')->get('/api/student/featuredcourses/bought', 'StudentController@boughtFeaturedCourses');

// list lessons in a course
$http->auth('api')->guard('student')->get('/api/course/{course}/lessons', 'CourseController@getCourseLessons');

// TODO
// list lessons in a featured course
$http->auth('api')->guard('student')->get('/api/course/featured/{course}/lessons', 'CourseController@getApiFeaturedCourseLessons');

// get a lesson ??
$http->auth('api')->guard('student')->get('/api/lesson/{lesson}', 'LessonController@read');

// get course assignment
$http->auth('api')->guard('student')->get('/api/course/{course}/assignment', 'StudentController@getMyCourseAssignment');

// get a lesson for the student
$http->auth('api')->guard('student')->get('/api/student/lesson/{lesson}', 'StudentController@getStudentLesson');

// get next lesson in a course
$http->auth('api')->guard('student')->get('/api/student/lesson/{lesson}/next', 'StudentController@nextLesson');

// get previous lesson in a course
$http->auth('api')->guard('student')->get('/api/student/lesson/{lesson}/previous', 'StudentController@previousLesson');

$http->auth('api')->guard('student')->post('/api/student/assignment/answer', 'StudentController@answerAssignment');

// $http->auth('api')->guard('student')->get('/api/courses/search', 'CourseController@search');

$http->auth('api')->guard('student')->post('/api/student/invite-a-friend', 'StudentController@invitefriend');

$http->auth('api')->guard('student')->post('/api/commentlesson', 'StudentController@addLessonComment');

$http->auth('api')->guard('student')->get('/api/lesson/{lessonId}/comments', 'StudentController@getLessonComments');

$http->auth('api')->guard('student')->post('/api/student/avatar/update', 'StudentController@uploadAvatar');

$http->auth('api')->guard('student')->get('/api/student/freelessons', 'StudentController@freeLessons');



$http->auth('api')->guard('student')->post('/api/forum/message', 'StudentController@addForumMessage');

$http->auth('api')->guard('student')->get('/api/forums/{categoryId}/messages', 'StudentController@getMessages');

$http->post('/api/forgotpassword', 'AuthController@forgotPassword');

$http->post('/api/verify', 'AuthController@verifyAccount');

$http->post('/api/resetpassword', 'AuthController@resetPassword');

$http->auth('api')->guard('student')->post('/api/account/updateprofile', 'AuthController@updateprofile');

$http->auth('api')->guard('student')->post('/api/account/updatepassword', 'AuthController@updatepassword');

$http->post('/api/contactus', 'StudentController@contactUs');

$http->end();
