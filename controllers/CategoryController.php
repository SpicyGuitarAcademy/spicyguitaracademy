<?php
namespace Controllers;
use Framework\Http\Http;
use Framework\Http\Request;
use Framework\Http\Response;
use App\Services\Auth;
use App\Services\Validate;
use App\Services\Upload;
use Models\CategoryModel;
use Framework\Cipher\Encrypt;

class CategoryController
{

   public function index(Request $req, Response $res)
   {
      // return all resources
      $mdl = new CategoryModel();

      $categories = $mdl->getCategories();

      if (!$categories) {
         $res->send(
            $res->render('admin/categories.html', [
               "empty" => true
            ])
         );
      }

      // TODO: consider api's too by returning json instead
      $res->send(
         $res->render('admin/categories.html', [
            "empty" => false,
            "categories" => json_encode($categories)
         ])
      );

   }

   public function create(Request $req, Response $res)
   {
      
      $category = trim($req->body()->category);
      $thumbnail = ($req->files_exists() == true) ? $req->files()->thumbnail : null ;
      
      $v = new Validate();

      // validate
      $v->letters("category", $category, "Invalid Category Title")->max(15);
      $errors = $v->errors();

      if ($errors) {
         $res->send(
            $res->render('admin/new-category.html', [
               'error' => $errors['category']
            ])
         );
      } elseif ($thumbnail == null) {
         $res->send(
            $res->render('admin/new-category.html', [
               'error' => "Thumbnail is required!"
            ])
         );
      }

      $up = new Upload();
      $up->image('thumbnail', $thumbnail, "Category Thumbnail was not uploaded", ["image/jpeg"]);
      $up->upload("thumbnails/", Encrypt::hash());

      $errors = $up->errors();

      if ($errors) {
         $res->send(
            $res->render('admin/new-category.html', [
               'error' => $errors['thumbnail']
            ])
         );
      }

      $path = $up->uri('thumbnail');

      $mdl = new CategoryModel();
      if ($mdl->addCategory($category, $path) == true) {

         $res->route('/admin/categories');

      } else {
         // unlink uploaded file
         unlink(STORAGE_DIR . $path);

         $res->send(
            $res->render('admin/new-category.html', [
               'error' => "Category not added."
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
   }

   public function delete(Request $req, Response $res)
   {
      // remove a resouce
   }

}
