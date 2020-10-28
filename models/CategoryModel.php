<?php
namespace Models;
use Framework\Database\Model;

class CategoryModel extends Model
{
   public function __construct()
   {
      parent::__construct('category_tbl');
   }

   // write wonderful model codes...

   public function getCategories()
   {
      return $this->read('*');
   }

   public function addCategory($category, $thumbnail)
   {
      return $this->create([
         'category' => $category,
         'thumbnail' => $thumbnail
      ]);
   }

}
