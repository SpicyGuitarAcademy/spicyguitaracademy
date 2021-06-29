<?php
namespace Controllers;
use Framework\Http\Http;
use Framework\Http\Request;
use Framework\Http\Response;
use App\Services\Auth;
use Models\QuickLessonModel;

class FeaturedLessonController
{

   public function index(Request $req, Response $res)
   {
      // return all resources
      $mdl = new QuickLessonModel();
      $lessons = $mdl->getQLessons();

      // echo json_encode($lessons);
      // die();
      
      $res->send(
         $res->render('admin/featured-lessons.html', [
            "lessons" => json_encode($lessons)
         ])
      );
   }

   public function free(Request $req, Response $res)
   {
      // return all resources
      $mdl = new QuickLessonModel();
      $lessons = $mdl->getFLessons();

      $res->send(
         $res->render('admin/free-lessons.html', [
            "lessons" => json_encode($lessons)
         ])
      );
   }
   
   public function read(Request $req, Response $res)
   {
      // return a resource
   }

   public function update(Request $req, Response $res)
   {
      // update a resource
   }

   public function delete(Request $req, Response $res)
   {
      // remove a resouce
   }

}
