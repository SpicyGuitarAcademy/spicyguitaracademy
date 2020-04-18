<?php
namespace Core;
use Core\Error;

class TemplateEngine
{
   /**
    * Read and Convert php snippets 
    * (@for, @foreach, @while, @do, @switch, @case, @if, @elseif ) 
    * in file to Only HTML codes.
    *
    * @param string $file
    * @return string $data
    */
   public function convertToHTML(string $file)
   {
      $page = "";
      // NB: newline character is not used so that the html code can still maintain its line numbering in case of error reporting
      $vars = "<?php use Core\Route; /* View Functions */ function route(string $"."name, array $"."params = null){ return Route::getUri($"."name, $"."params); } function resources(string $"."file){ return Route::$"."appUrl . '/resources/' . $"."file; } function storage(string $"."file) { return (trim($"."file) != '' && file_exists('../'.$"."file) == true) ? Route::$"."appUrl.$"."file : ''; } ?>";
      
      if ( isset($_SESSION["INIT_VIEW"]) ) {

         $vars .= "<?php ";

         foreach ($_SESSION["INIT_VIEW"] as $key => $value) {
            $value = str_replace('"',"\"",str_replace("'","\'",$value));
            $vars .=  "$" . "$key" . " = " . "'$value'" . "; ";
         }

         $vars .= "?>";

         // clear the logged view variables
         $_SESSION["INIT_VIEW"] = null;
      }

      if ( isset($_SESSION["INIT_VIEW_LOG"]) ) {

         $vars .= "<?php ";

         foreach ($_SESSION["INIT_VIEW_LOG"] as $key => $value) {
            $vars .=  "$" . "$key" . " = " . "'$value'" . "; ";
         }

         $vars .= "?>";
         
			// clear the logged view variables
			$_SESSION["INIT_VIEW_LOG"] = null;
      }

      $page .= $vars;

      // open file
      try {
         $reader = fopen($file,"r",true);
      } catch (\Throwable $th) {
         // handle error
         Error::internalError("Unable to open file <i><b>'$file'</b></i> ");
      }
      
      // get the data line by line
      while(!feof($reader)) {
         // get the next line
         $line = fgets($reader);

         // asset helper
         $line = $this->assetsHelper($line);

         // check for Import Snippet;
         $line = $this->replaceImportSnippets($line);

         // check for ForLoop Snippet
         $line = $this->replaceIfSnippets($line);

         // check for ForLoop Snippet
         $line = $this->replaceForLoopSnippets($line);

         // check for ForEach Snippet
         $line = $this->replaceForEachSnippets($line);

         // check for WhileLoop Snippet
         $line = $this->replaceWhileSnippets($line);

         // check for DoLoop Snippet
         $line = $this->replaceDoLoopSnippets($line);

         // check for Switch Snippet
         $line = $this->replaceSwitchSnippets($line);

         // check for Statement Snippet;
         $line = $this->replaceStmtSnippets($line);

         // check for curly braces
         $line = $this->replaceCurls($line);

         // check for fake curlys
         $line = $this->replaceFakeCurlys($line);

         // add line to data
         $page .= $line;
      }

      // close file 
      fclose($reader);

      // make the page valid for eval()
      $page = "?>" . $page;

      // return page
      return $page;

   }

   private function assetsHelper(string $line)
   {
      return str_replace("\"@assets/", "\"public/assets/", $line);
   }

   private function replaceIfSnippets(string $line)
   {
      $temp = $line;

      // $temp = strip_tags($temp);
      $temp = str_replace("\r\n", "", $temp);
      $temp = trim($temp);
      $temps = explode(" ", $temp);

      // if the first element is equal to the snippet keywords, expand codes
      if ($temps[0] == "@if"){
         $temps[0] = "<?php if";
         $temps[] = "{ ?>\n";
         $line = implode(" ", $temps);
         $line = $this->replaceCondTag($line);  
         return $line;
      }

      elseif ($temps[0] == "@elseif"){
         $temps[0] = "<?php } elseif";
         $temps[] = "{ ?> \n";
         $line = implode(" ", $temps);
         $line = $this->replaceCondTag($line);  
         return $line;
      }

      elseif ($temps[0] == "@else"){
         $temps[0] = "<?php } else";
         $temps[] = "{ ?>\n";
         $line = implode(" ", $temps);
         return $line;
      }

      elseif ($temps[0] == "@endif"){
         $temps[0] = "<?php } ?>\n";
         $line = $temps[0];
         return $line;
      }

      // else return the initial $line
      else{
         return $line;
      }
      
   }

   private function replaceForLoopSnippets(string $line)
   {
      $temp = $line;

      // $temp = strip_tags($temp);
      $temp = str_replace("\r\n", "", $temp);
      $temp = trim($temp);
      $temps = explode(" ", $temp);

      // if the first element is equal to the snippet keywords, expand codes
      if ($temps[0] == "@for"){
         $temps[0] = "<?php for";
         $temps[] = "{ ?>\n";
         $line = implode(" ", $temps);
         $line = $this->replaceCondTag($line);  
         return $line;
      }

      elseif ($temps[0] == "@endfor"){
         $temps[0] = "<?php } ?>\n";
         $line = $temps[0];
         return $line;
      }

      // else return the initial $line
      else{
         return $line;
      }
      
   }

   private function replaceForEachSnippets(string $line)
   {
      $temp = $line;

      // $temp = strip_tags($temp);
      $temp = str_replace("\r\n", "", $temp);
      $temp = trim($temp);
      $temps = explode(" ", $temp);

      // if the first element is equal to the snippet keywords, expand codes
      if ($temps[0] == "@foreach"){
         $temps[0] = "<?php foreach";
         $temps[] = "{ ?>\n";
         $line = implode(" ", $temps);
         return $line;
      }

      elseif ($temps[0] == "@endforeach"){
         $temps[0] = "<?php } ?>\n";
         $line = $temps[0];
         return $line;
      }

      // else return the initial $line
      else{
         return $line;
      }
      
   }

   private function replaceDoLoopSnippets(string $line)
   {
      $temp = $line;

      // $temp = strip_tags($temp);
      $temp = str_replace("\r\n", "", $temp);
      $temp = trim($temp);
      $temps = explode(" ", $temp);

      // if the first element is equal to the snippet keywords, expand codes
      if ($temps[0] == "@do"){
         $temps[0] = "<?php do";
         $temps[] = "{ ?>\n";
         $line = implode(" ", $temps);
         return $line;
      }

      elseif ($temps[0] == "@enddo"){
         $temps[0] = "<?php } while";
         $temps[] = "?>\n";
         $line = implode(" ", $temps);
         $line = $this->replaceCondTag($line);  
         return $line;
      }

      // else return the initial $line
      else{
         return $line;
      }
      
   }

   private function replaceWhileSnippets(string $line)
   {
      $temp = $line;

      // $temp = strip_tags($temp);
      $temp = str_replace("\r\n", "", $temp);
      $temp = trim($temp);
      $temps = explode(" ", $temp);

      // if the first element is equal to the snippet keywords, expand codes
      if ($temps[0] == "@while"){
         $temps[0] = "<?php while";
         $temps[] = "{ ?>\n";
         $line = implode(" ", $temps);
         $line = $this->replaceCondTag($line);  
         return $line;
      }

      elseif ($temps[0] == "@endwhile"){
         $temps[0] = "<?php } ?>\n";
         $line = $temps[0];
         return $line;
      }

      // else return the initial $line
      else{
         return $line;
      }
      
   }

   private function replaceSwitchSnippets(string $line)
   {
      $temp = $line;

      // $temp = strip_tags($temp);
      $temp = str_replace("\r\n", "", $temp);
      $temp = trim($temp);
      $temps = explode(" ", $temp);

      // if the first element is equal to the snippet keywords, expand codes
      if ($temps[0] == "@switch"){
         $temps[0] = "<?php switch";
         $temps[] = "{ ?>\n";
         $line = implode(" ", $temps);
         return $line;
      }

      elseif ($temps[0] == "@case"){
         $temps[0] = "<?php case";
         $temps[] = ": ?> \n";
         $line = implode(" ", $temps);
         return $line;
      }

      elseif ($temps[0] == "@default"){
         $temps[0] = "<?php default: ?>\n";
         $line = $temps[0];
         return $line;
      }

      elseif ($temps[0] == "@break"){
         $temps[0] = "<?php break; ?>\n";
         $line = $temps[0];
         return $line;
      }

      elseif ($temps[0] == "@endswitch"){
         $temps[0] = "<?php } ?>\n";
         $line = $temps[0];
         return $line;
      }

      // else return the initial $line
      else{
         return $line;
      }
      
   }

   private function replaceImportSnippets(string $line)
   {
      $temp = $line;

      // $temp = strip_tags($temp);
      $temp = str_replace("\r\n", "", $temp);
      $temp = trim($temp);
      $temps = explode(" ", $temp);

      // if the first element is equal to the snippet keywords, fetch view codes
      if ($temps[0] == "@import"){
         $filename = (trim($temps[1]) != "") ? "../resources/views/" . $temps[1] : "" ;

         $codes = (\file_exists($filename)) ? file_get_contents($filename) : "" ;
         $line = $codes;
         return $line;
      }

      // else return the initial $line
      else{
         return $line;
      }
   }

   private function replaceCondTag(string $line)
   {
      // less than or equal to
      $line = str_replace(".le", "<=", $line);

      // less than
      $line = str_replace(".lt", "<", $line);

      // greater than or equal to
      $line = str_replace(".ge", ">=", $line);

      // greater than
      $line = str_replace(".gt", ">", $line);

      // equal to
      $line = str_replace(".eq", "==", $line);

      // not equal to
      $line = str_replace(".ne", "!=", $line);

      // not equivalent to
      $line = str_replace(".eqq", "===", $line);

      // return line
      return $line;
   }

   private function replaceStmtSnippets(string $line)
   {
      $temp = $line;

      // $temp = strip_tags($temp);
      $temp = str_replace("\r\n", "", $temp);
      $temp = trim($temp);
      $temps = explode(" ", $temp);

      // if the first element is equal to the snippet keywords, expand codes
      if ($temps[0] == "@stmt"){
         $temps[0] = "<?php";
         $temps[] = "?>\n";
         $line = implode(" ", $temps);
         // $line = $this->replaceCondTag($line);
         return $line;
      }

      // else return the initial $line
      else{
         return $line;
      }
      
   }

   private function replaceCurls(string $line)
   {
      // if {{ and }} are found in the string
      if ( substr_count($line,"{{") == substr_count($line,"}}") ){
         $line = str_replace("{{", "<?=", $line);
         $line = str_replace("}}", "?>", $line);
      }

      return $line;
   }

   private function replaceFakeCurlys(string $line)
   {
      $line = preg_replace("/<\?= *\?>/", "{{}}", $line);
      
      return $line;
   }

}

?>