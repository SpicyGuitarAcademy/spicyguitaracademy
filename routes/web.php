<?php
use Core\Route;

function setWebRoute()
{
   // Sample
   // Route::get('/api/uri', 'UniqueName', 'Controller@Method');
   // Route::post('/api/uri', 'UniqueName', 'Controller@Method');

   Route::get('/','home','HomeController@index');
   Route::get('/users','users','HomeController@users');
}

?>