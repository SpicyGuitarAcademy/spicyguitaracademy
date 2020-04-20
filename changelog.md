# ChangeLog for InitFramework

# View
   - On the views, you can now remove the {{resources('')}} function when calling assets
   + To use asset files, just use @assets followed by the asset directory
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

  - One less directory in th eroot directory (i.e. routes)
  + The routes are now methods (setWebRoutes, setApiRoutes) in the App Class