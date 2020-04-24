# ChangeLog for InitFramework

# View
  - On the views, you can now remove the {{resources('')}} function when calling assets
  + To use asset files, just use @assets followed by the asset directory.
    E.g "@assets/css/main.css"
    This would be converted to "public/assets/css/main.css"
    Note: Any line on your view file with "@assets/, would be converted to "public/assets/
    @assets would not be converted, @assets/ would not be converted

  - On the views, you can now remove the {{storage('')}} function when calling storage files
  + To use storage files, just call the storage file as "storage/your_image.png"

  - On the views, you can now call route ends without the {{route('')}} function
  + To call and endpoint, use "./your_endpoint/1" (prefered) or "your_endpoint/1"

# Routing
  `Every application is supposed to have an entry point, like a main(List<String> args) function. For web application in php, traditionally this is the index.php page which returns a html page. But Init Framework has one major indpoint which is the public/index.php. It is in this entry file that other entry points are defined both for the web and for apis.`

  - One less directory in the root directory (i.e. routes)
  + The routes are now methods (setWebRoutes, setApiRoutes) in the App Class

  - The routes are not in the methods (setWebRoutes, setApiRoutes)
  + The methods are in the __construct method

  + Routing became more fun as you can now determine the datatype of your route parameter:
  E.g Digits (:d), Alphabets (:a), Alphabets with spaces, underscore & hyphen (:a+), Aphnahumeric (:x), Aphnahumeric with spaces, underscore & hyphen (:x+).

  + You can now handle a request from the index.php file and send back a response to the client, thank to anonymous functions.

# Http
  + The Http Class has been introduced to handle all the http related actions except those of reuest and response classes.

# Request
  + This new class handles all that has to do with http requests.

# Request
  + This new class handles all that has to do with http responses.
  + It can accomodate sending any form of response code.

# Framework
  + The framework now has the site on maintenance mode feature which comes with its own html page; it comes along with the error 404 html page too.

  + Now you can use composer packages in your projects, yayy. 💃🏼💃🏼

  + I just discovered a way of not using the gui method of generating files for the user. It works by registering the path to the engine file as an environment variable (which in our new version would be called `templatr`) then the user of the application can make requests to the templatr with `php $templatr <command>`. But this is for an ad hoc operation, I'd look for a better one.

# Documentation
  + For documenting Initframework, Templatr and MirrorJ - use Guzzle's style of documentation. And look for a green version of their blue color that would be used by Initframework.
  What color would Templatr and Mirror JS use?