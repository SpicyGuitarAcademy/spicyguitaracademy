<?php
// namespace App;

use App\Services\CurrencyConverter;
use App\Services\PayPalClient;
// use App\Services\PaypalService;
use App\Services\User;
use Framework\Handler\IException;
use Framework\Http\Http;
use Framework\Http\Request;
use Framework\Http\Response;
use Models\NotificationsModel;

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
   $res->send(
      $res->render('homepage.v4.html')
   );
});

$http->get('/terms', function (Request $req, Response $res) {
   $res->send(
      $res->render('terms.html')
   );
});

$http->get('/privacy', function (Request $req, Response $res) {
   $res->send(
      $res->render('privacy.html')
   );
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
   $mdl = new NotificationsModel();
   $notifications = $mdl->getNotifications(User::$email);
   $adminNotifications = $mdl->getNotifications('admin');

   $res->send(
      $res->render('admin/dashboard.html', [
         'notifications' => count($notifications),
         'adminNotifications' => count($adminNotifications)
      ]),
      200
   );
});

// Profile
$http->auth('web')->guard('admin', 'tutor')->get('/admin/profile', 'TutorController@profile');

$http->auth('web')->guard('admin', 'tutor')->post('/admin/profile/uploadavatar', 'TutorController@uploadavatar');

$http->auth('web')->guard('admin', 'tutor')->post('/admin/profile/updateprofile', 'TutorController@updateprofile');

// Categories
$http->auth('web')->guard('admin', 'tutor')->privilege('CATEGORIES')->get('/admin/categories', 'CategoryController@index');

$http->auth('web')->guard('admin', 'tutor')->privilege('CATEGORIES')->get('/admin/categories/new', function ($req, $res) {
   $res->send(
      $res->render('admin/new-category.html')
   );
});

$http->auth('web')->guard('admin', 'tutor')->privilege('CATEGORIES')->csrf()->post('/admin/add-category', 'CategoryController@create');

// Courses
$http->auth('web')->guard('admin', 'tutor')->privilege('COURSES')->get('/admin/courses', 'CourseController@index');

$http->auth('web')->guard('admin', 'tutor')->privilege('COURSES')->get('/admin/courses/new', 'CourseController@new');

$http->auth('web')->guard('admin', 'tutor')->privilege('COURSES')->csrf()->post('/admin/courses/add', 'CourseController@create');

$http->auth('web')->guard('admin', 'tutor')->privilege('COURSES')->get('/admin/courses/edit/{id}', 'CourseController@edit');

$http->auth('web')->guard('admin', 'tutor')->privilege('COURSES')->csrf()->patch('/admin/courses/update', 'CourseController@update');

$http->auth('web')->guard('admin', 'tutor')->privilege('COURSES')->csrf()->delete('/admin/courses/delete/{id}', 'CourseController@delete');

// Assignments & Answers

// TODO: UPDATE SQL

// alter student_assignment table to allow for total ratings
// ALTER TABLE `student_course_tbl` ADD `assignment_rating` INT NOT NULL DEFAULT '0' AFTER `date_started`;

// create the course_assignment table
// CREATE TABLE `iffpeomy_spicyguitar_db`.`course_assignment` ( `id` INT NOT NULL AUTO_INCREMENT ,  `course_id` INT NOT NULL ,  `assignment_number` INT NOT NULL DEFAULT '1' ,  `assignment_order` INT NOT NULL DEFAULT '1' ,  `type` VARCHAR(10) NOT NULL ,  `content` TEXT NOT NULL ,    PRIMARY KEY  (`id`),    INDEX  (`course_id`)) ENGINE = InnoDB;

// update the course assignment
// ALTER TABLE `course_assignment` CHANGE `id` `id` DOUBLE NOT NULL AUTO_INCREMENT, CHANGE `course_id` `course_id` DOUBLE NOT NULL;

// create student assignment
// CREATE TABLE `iffpeomy_spicyguitar_db`.`student_assignment` ( `id` DOUBLE NOT NULL AUTO_INCREMENT , `course_id` DOUBLE NOT NULL , `assignment_number` INT NOT NULL , `student_id` DOUBLE NOT NULL , `rating` INT NOT NULL DEFAULT '0' , PRIMARY KEY (`id`), INDEX (`course_id`), INDEX (`student_id`)) ENGINE = InnoDB;

// create assignment answer
// CREATE TABLE `iffpeomy_spicyguitar_db`.`assignment_answer` ( `id` DOUBLE NOT NULL AUTO_INCREMENT , `course_id` DOUBLE NOT NULL , `assignment_number` INT NOT NULL , `type` VARCHAR(10) NOT NULL , `content` TEXT NOT NULL , `student_id` DOUBLE NULL , `tutor_id` DOUBLE NULL , `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`), INDEX (`course_id`)) ENGINE = InnoDB;

// ALTER TABLE `student_assignment` CHANGE `student_id` `student` VARCHAR(40) NOT NULL;

// ALTER TABLE `assignment_answer` CHANGE `student_id` `student` VARCHAR(40) NULL DEFAULT NULL, CHANGE `tutor_id` `tutor` VARCHAR(40) NULL DEFAULT NULL;

// ALTER TABLE `student_assignment` ADD `status` VARCHAR(10) NOT NULL DEFAULT 'pending' COMMENT 'pending, answered, reviewed' AFTER `rating`;

// ALTER TABLE `forums` CHANGE `comment` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

// =============

// DROP TABLE `assignment_tbl`

$http->auth('web')->guard('admin', 'tutor')->privilege('ASSIGNMENTS')->get('/admin/courses/{id}/assignments', 'AssignmentController@index');

$http->auth('web')->guard('admin', 'tutor')->privilege('ASSIGNMENTS')->get('/admin/courses/{courseId}/assignment/{assignmentNumber}/answers', 'AssignmentController@courseAssignmentAnswers');

$http->auth('web')->guard('admin', 'tutor')->privilege('ASSIGNMENTS')->get('/admin/courses/{courseId}/assignments/{student}/ratings', 'AssignmentController@studentAssignmentRatings');

$http->auth('web')->guard('admin', 'tutor')->privilege('ASSIGNMENTS')->csrf()->patch('/admin/assignment/update-average-rating', 'AssignmentController@updateAverageRating');

$http->auth('web')->guard('admin', 'tutor')->privilege('ASSIGNMENTS')->csrf()->post('/admin/assignment/admin/answer', 'AssignmentController@answerAssignmentAsAdmin');

$http->auth('web')->guard('admin', 'tutor')->privilege('ASSIGNMENTS')->csrf()->patch('/admin/assignment/answer/update-rating', 'AssignmentController@updateRating');

$http->auth('web')->guard('admin', 'tutor')->privilege('ASSIGNMENTS')->csrf()->post('/admin/courses/{id}/assignments/add', 'AssignmentController@create');

$http->auth('web')->guard('admin', 'tutor')->privilege('ASSIGNMENTS')->csrf()->delete('/admin/courses/{id}/assignments/{assignment}/delete', 'AssignmentController@delete');

// $http->auth('web')->guard('admin', 'tutor')->privilege('ASSIGNMENTS')->get('/admin/courses/{id}/assignment/new', 'AssignmentController@new');

// $http->auth('web')->guard('admin', 'tutor')->privilege('ASSIGNMENTS')->get('/admin/courses/{id}/assignment/edit', 'AssignmentController@edit');

// $http->auth('web')->guard('admin', 'tutor')->privilege('ASSIGNMENTS')->csrf()->patch('/admin/courses/{id}/assignment/update', 'AssignmentController@update');

// lessons
$http->auth('web')->guard('admin', 'tutor')->privilege('LESSONS')->get('/admin/lessons', 'LessonController@index');

$http->auth('web')->guard('admin', 'tutor')->privilege('LESSONS')->get('/admin/lessons/new', 'LessonController@new');

$http->auth('web')->guard('admin', 'tutor')->privilege('LESSONS')->csrf()->post('/admin/lessons/add', 'LessonController@create');

$http->auth('web')->guard('admin', 'tutor')->privilege('LESSONS')->get('/admin/lessons/edit/{id}', 'LessonController@edit');

$http->auth('web')->guard('admin', 'tutor')->privilege('LESSONS')->delete('/admin/lessons/delete/{id}', 'LessonController@delete');

$http->auth('web')->guard('admin', 'tutor')->privilege('LESSONS')->csrf()->put('/admin/lessons/update/low-video', 'LessonController@updateLowVideo');

$http->auth('web')->guard('admin', 'tutor')->privilege('LESSONS')->csrf()->put('/admin/lessons/update/high-video', 'LessonController@updateHighVideo');

$http->auth('web')->guard('admin', 'tutor')->privilege('LESSONS')->csrf()->put('/admin/lessons/update/audio', 'LessonController@updateAudio');

$http->auth('web')->guard('admin', 'tutor')->privilege('LESSONS')->csrf()->put('/admin/lessons/update/practice', 'LessonController@updatePractice');

$http->auth('web')->guard('admin', 'tutor')->privilege('LESSONS')->csrf()->put('/admin/lessons/update/tablature', 'LessonController@updateTablature');

$http->auth('web')->guard('admin', 'tutor')->privilege('LESSONS')->csrf()->patch('/admin/lessons/update/note', 'LessonController@updateNote');

$http->auth('web')->guard('admin', 'tutor')->privilege('LESSONS')->csrf()->patch('/admin/lessons/update/details', 'LessonController@updateDetails');

// Featured Courses
$http->auth('web')->guard('admin', 'tutor')->privilege('FEATURED COURSES')->get('/admin/courses/featured', 'CourseController@getFeaturedCourses');

$http->auth('web')->guard('admin', 'tutor')->privilege('FEATURED COURSES')->get('/admin/courses/featured/new', 'CourseController@newFeaturedCourses');

$http->auth('web')->guard('admin', 'tutor')->privilege('FEATURED COURSES')->post('/admin/courses/featured/create', 'CourseController@createFeaturedCourse');

$http->auth('web')->guard('admin', 'tutor')->privilege('FEATURED COURSES')->csrf()->delete('/admin/courses/featured/delete/{id}', 'CourseController@deleteFeaturedCourse');

$http->auth('web')->guard('admin', 'tutor')->privilege('FEATURED COURSES')->get('/admin/courses/featured/select', 'CourseController@selectFeaturedCourses');

$http->auth('web')->guard('admin', 'tutor')->privilege('FEATURED COURSES')->post('/admin/courses/featured/select/submit', 'CourseController@AddLessonsForFeaturedCourse');

$http->auth('web')->guard('admin', 'tutor')->privilege('FEATURED COURSES')->get('/admin/courses/featured/lessons', 'CourseController@getFeaturedCoursesLessons');

$http->auth('web')->guard('admin', 'tutor')->privilege('FEATURED COURSES')->post('/admin/courses/featured/lessons/update', 'CourseController@updateLessonsForFeaturedCourse');

$http->auth('web')->guard('admin', 'tutor')->privilege('FEATURED COURSES')->get('/admin/courses/featured/edit/{id}', 'CourseController@editFeaturedCourse');

$http->auth('web')->guard('admin', 'tutor')->privilege('FEATURED COURSES')->csrf()->patch('/admin/courses/featured/update', 'CourseController@updateFeaturedCourse');

$http->auth('web')->guard('admin', 'tutor')->privilege('FEATURED COURSES')->csrf()->patch('/admin/courses/featured/update/order', 'CourseController@updateFeaturedCourseOrder');

$http->auth('web')->guard('admin', 'tutor')->privilege('FREE LESSONS')->get('/admin/lessons/free', 'LessonController@free');

$http->auth('web')->guard('admin', 'tutor')->privilege('FREE LESSONS')->patch('/admin/lessons/free/update/order', 'LessonController@updateFreeLessonOrder');

// students
$http->auth('web')->guard('admin', 'tutor')->privilege('STUDENTS')->get('/admin/students', 'TutorController@students');

$http->auth('web')->guard('admin', 'tutor')->privilege('STUDENTS')->get('/admin/student/details', 'TutorController@studentDetails');

$http->auth('web')->guard('admin', 'tutor')->privilege('STUDENTS')->csrf()->patch('/admin/student/details/spicyunits/update', 'TutorController@addSpicyUnits');

$http->auth('web')->guard('admin', 'tutor')->privilege('STUDENTS')->csrf()->patch('/admin/student/details/category/update', 'TutorController@makeCategoryActive');

$http->auth('web')->guard('admin', 'tutor')->privilege('STUDENTS')->csrf()->patch('/admin/student/details/category/override', 'TutorController@overrideCategory');

$http->auth('web')->guard('admin', 'tutor')->privilege('STUDENTS')->csrf()->patch('/admin/student/details/access/update', 'TutorController@updateStudentStatus');

// chat forums
$http->auth('web')->guard('admin', 'tutor')->privilege('CHAT FORUMS')->get('/admin/chatforums', 'TutorController@loadForums');

$http->auth('web')->guard('admin', 'tutor')->privilege('CHAT FORUMS')->get('/admin/chatforums/{category}', 'TutorController@loadForumMessages');

$http->auth('web')->guard('admin', 'tutor')->privilege('CHAT FORUMS')->csrf()->post('/admin/chatforums/add', 'StudentController@addForumMessageAsAdmin');

// Questions and Answers
$http->auth('web')->guard('admin', 'tutor')->privilege('QUESTIONS & ANSWERS')->get('/admin/student/qa', 'TutorController@loadQuestionsAnswers');

$http->auth('web')->guard('admin', 'tutor')->privilege('QUESTIONS & ANSWERS')->csrf()->post('/admin/student/qa/answer', 'TutorController@addLessonComment');

// tutors
$http->auth('web')->guard('admin', 'tutor')->privilege('TUTORS')->get('/admin/tutors', 'TutorController@index');

$http->auth('web')->guard('admin', 'tutor')->privilege('TUTORS')->get('/admin/tutors/new', 'TutorController@new');

$http->auth('web')->guard('admin', 'tutor')->privilege('TUTORS')->get('/admin/tutors/privilege/{tutor}', 'TutorController@privilege');

$http->auth('web')->guard('admin', 'tutor')->privilege('TUTORS')->csrf()->patch('/admin/tutors/privilege/update/{tutor}', 'TutorController@updatePrivilege');

$http->auth('web')->guard('admin', 'tutor')->privilege('TUTORS')->csrf()->post('/admin/tutors/add', 'TutorController@create');

$http->auth('web')->guard('admin', 'tutor')->privilege('TUTORS')->csrf()->patch('/admin/tutors/update-status', 'TutorController@updateStatus');

// Subscription plans
$http->auth('web')->guard('admin', 'tutor')->privilege('SUBSCRIPTION PLANS')->get('/admin/subscriptions', 'SubscriptionController@subscriptions');

$http->auth('web')->guard('admin', 'tutor')->privilege('SUBSCRIPTION PLANS')->post('/admin/subscriptions/updateprice', 'SubscriptionController@updateprice');

$http->auth('web')->guard('admin', 'tutor')->privilege('SUBSCRIPTION PLANS')->post('/admin/subscriptions/updatedescription', 'SubscriptionController@updatedescription');

// Transactions
$http->auth('web')->guard('admin', 'tutor')->privilege('TRANSACTIONS')->get('/admin/transactions', 'TransactionController@index');

// Send notifications
$http->auth('web')->guard('admin', 'tutor')->privilege('SEND NOTIFICATIONS')->get('/admin/students/notify', 'TutorController@notifyStudents');

$http->auth('web')->guard('admin', 'tutor')->privilege('SEND NOTIFICATIONS')->csrf()->post('/admin/students/notify/send', 'TutorController@sendNotifications');

// Admin notifications
$http->auth('web')->guard('admin', 'tutor')->get('/admin/notifications', function (Request $req, Response $res) {
   $mdl = new NotificationsModel();
   $adminNotifications = $mdl->getAdminNotifications(User::$email);

   $count = 0;
   foreach ($adminNotifications as $notification) {
      $adminNotifications[$count]['created_at'] = date("d/m/Y h:m A", $notification['created_at']);
      $adminNotifications[$count]['message'] = utf8_decode($notification['message']);
      $count++;
   }

   $res->send(
      $res->render('admin/notifications.html', [
         "notifications" => json_encode($adminNotifications)
      ])
   );
});

$http->auth('web')->guard('admin', 'tutor')->csrf()->patch('/admin/notification/markasread', function (Request $req, Response $res) {
   $mdl = new NotificationsModel();
   $notificationId = $req->body()->notificationId ?? null;
   $mdl->updateNotificationStatus(User::$email, $notificationId);

   $res->redirect($req->referer());
});

// ->privilege('NOTIFICATIONS')

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

$http->auth('api')->guard('student')->get('/api/notifications', function (Request $req, Response $res) {
   $mdl = new NotificationsModel();
   $notifications = $mdl->getNotifications(User::$email);

   $count = 0;
   foreach ($notifications as $notification) {
      $notifications[$count]['message'] = utf8_decode($notification['message']);
      $count++;
   }

   $res->success('Your notifications', ["notifications" => $notifications]);
});

$http->auth('api')->guard('student')->post('/api/notification/markasread', function (Request $req, Response $res) {
   $mdl = new NotificationsModel();
   $notificationId = $req->body()->notificationId ?? null;
   $mdl->updateNotificationStatus(User::$email, $notificationId);

   $res->success('Done');
});

$http->auth('api')->guard('student')->post('/api/device/verify', 'AuthController@verifyAccountDevice');

$http->auth('api')->guard('student')->post('/api/device/reset', 'AuthController@resetAccountDevice');

$http->auth('api')->guard('student')->get('/api/subscription/plans', 'SubscriptionController@plans');

$http->auth('api')->guard('student')->get('/api/subscription/status', 'SubscriptionController@status');

$http->auth('api')->guard('student')->post('/api/subscription/spicyunits/complete-subscription', 'SubscriptionController@completeSubscriptionPaymentWithSpicyUnits');

$http->auth('api')->guard('student')->post('/api/subscription/spicyunits/complete-featured', 'SubscriptionController@completeFeaturedPaymentWithSpicyUnits');

$http->auth('api')->guard('student')->post('/api/subscription/{medium}/initiate', 'SubscriptionController@initiatePayment');

$http->auth('api')->guard('student')->post('/api/subscription/{medium}/initiate-featured', 'SubscriptionController@initiateFeaturedPayment');

$http->auth('api')->guard('student')->post('/api/subscription/{medium}/verify/{reference}', 'SubscriptionController@verifyPayment');

$http->auth('api')->guard('student')->post('/api/subscription/{medium}/verify-featured/{reference}', 'SubscriptionController@verifyFeaturedPayment');

// student statistics
$http->auth('api')->guard('student')->get('/api/student/statistics', 'StudentController@stats');

// student selects a cateogry
$http->auth('api')->guard('student')->post('/api/student/category/select', 'StudentController@chooseCategory');

// student selects another cateogry
$http->auth('api')->guard('student')->post('/api/student/category/re-select', 'StudentController@rechooseCategory');

$http->auth('api')->guard('student')->post('/api/student/category/complete', 'StudentController@completeCategory');

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
// ->auth('api')->guard('student')
$http->get('/api/student/featuredcourses/all', 'StudentController@allFeaturedCourses');

$http->auth('api')->guard('student')->get('/api/student/featuredcourses/bought', 'StudentController@boughtFeaturedCourses');

// list lessons in a course
$http->auth('api')->guard('student')->get('/api/course/{course}/lessons', 'CourseController@getCourseLessons');

// list lessons in a featured course
$http->auth('api')->guard('student')->get('/api/course/featured/{course}/lessons', 'CourseController@getApiFeaturedCourseLessons');

// get a lesson ??
$http->auth('api')->guard('student')->get('/api/lesson/{lesson}', 'LessonController@read');

// get course assignment
$http->auth('api')->guard('student')->get('/api/course/{course}/assignment', 'StudentController@getMyCourseAssignment');

$http->auth('api')->guard('student')->get('/api/student/course/{courseId}/assignment/{assignmentNumber}/answers', 'StudentController@getAssignmentAnswers');

$http->auth('api')->guard('student')->post('/api/student/assignment/answer', 'StudentController@answerAssignment');

// get a lesson for the student
$http->auth('api')->guard('student')->get('/api/student/lesson/{lesson}', 'StudentController@getStudentLesson');

// get next lesson in a course
// $http->auth('api')->guard('student')->get('/api/student/lesson/{lesson}/next', 'StudentController@nextLesson');

// // get previous lesson in a course
// $http->auth('api')->guard('student')->get('/api/student/lesson/{lesson}/previous', 'StudentController@previousLesson');

// $http->auth('api')->guard('student')->get('/api/courses/search', 'CourseController@search');

$http->auth('api')->guard('student')->post('/api/student/invite-a-friend', 'StudentController@invitefriend');

$http->auth('api')->guard('student')->post('/api/commentlesson', 'StudentController@addLessonComment');

$http->auth('api')->guard('student')->get('/api/lesson/{lessonId}/comments', 'StudentController@getLessonComments');

$http->auth('api')->guard('student')->post('/api/student/avatar/update', 'StudentController@uploadAvatar');

// ->auth('api')->guard('student')
$http->get('/api/student/freelessons', 'StudentController@freeLessons');



$http->auth('api')->guard('student')->post('/api/forum/message', 'StudentController@addForumMessage');

$http->auth('api')->guard('student')->get('/api/forums/{categoryId}/messages', 'StudentController@getMessages');

$http->post('/api/forgotpassword', 'AuthController@forgotPassword');

$http->post('/api/verify', 'AuthController@verifyAccount');

$http->post('/api/resetpassword', 'AuthController@resetPassword');

$http->auth('api')->guard('student')->get('/api/student/profile', 'StudentController@getProfile');

$http->auth('api')->guard('student')->get('/api/student/request-referral-code', 'StudentController@requestReferralCode');

$http->auth('api')->guard('student')->post('/api/account/updateprofile', 'AuthController@updateprofile');

$http->auth('api')->guard('student')->post('/api/account/updatepassword', 'AuthController@updatepassword');

$http->post('/api/contactus', 'StudentController@contactUs');

$http->end();
