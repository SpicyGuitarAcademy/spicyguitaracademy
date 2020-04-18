# ChangeLog for InitFramework

# View
   - On the views, you can now remove the {{resources('')}} function when calling assets
   + To use asset files, just use @assets followed by the asset directory
     E.g "@assets/css/main.css"
     This would be converted to "public/assets/css/main.css"

   - On the views, you can now remove the {{storage('')}} function when calling storage files
   + To use storage files, just call the storage file as "storage/your_image.png"

   - On the views, you can now call route ends without the {{route('')}} function
   + To call and endpoint, use "./your_endpoint/1" (prefered) or "your_endpoint/1"
