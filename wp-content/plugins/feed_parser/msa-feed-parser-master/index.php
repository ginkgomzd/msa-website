<?php
use MainStreetAdvocates\IMAP;
use MainStreetAdvocates\Feed;

add_action( 'wp_loaded', 'wp_mail' );
/* DEV: */
ini_set('display_errors', 1);
ini_set('max_execution_time',0);
define('DEBUG', TRUE); // DISABLE FOR PRODUCTION
define('DEBUG_VERBOSE_SQL', FALSE);
/* END DEV */

/***
 * Determine relative path we are installed in.
 **/

$chopFrom = strrpos(__DIR__, 'htdocs') + strlen('htdocs');
$appRoot = substr(__DIR__, $chopFrom);

require_once __DIR__ . "/vendor/autoload.php";
$user = MSAvalidateUserRole();

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
  <head>
    <title>Feed Parser</title>
  </head>
  <body>
    <h1>Upload Feed</h1>
<!--    <form enctype="multipart/form-data" method="post" action="<?php echo $appRoot ?>/">-->

           <form enctype="multipart/form-data" method="post" action="" class="form-inline">
               <div class="form-group row">
                   <label for="feed-upload">Select XML File:</label>
                   <input name="feed-upload" type="file" required />
               </div>

               <div class="form-group row">
            <!--<input name="feed-upload-submit" type="submit" />-->
               <button type="submit" class="btn btn-primary" name="feed-upload-submit">Submit Query</button>
               </div>
           </form>
      <?php

//    $upload = '/msa-feed-parser-master/tmp/';
//      $upload = '/msa-feed-parser-mastertmp/tmp/';
      $uloaded_file='';
    
    if (isset($_POST['feed-upload-submit'])) {
        
        $submission = $_FILES['feed-upload'];
        $uloaded_file='../wp-content/uploads/xmlbills/' . $_FILES['feed-upload']['name'];
        
        if(!move_uploaded_file($_FILES['feed-upload']['tmp_name'],$uloaded_file )){
            die('Error uploading file - check destination is writable.');
        }
        
////        var_dump($_POST);
//      $submission = $_FILES['feed-upload'];
//       $upload = '/msa-feed-parser-master/tmp/' . $submission['name'];
//      move_uploaded_file($submission['tmp_name'], $upload);
    }

    if (isset($_POST['feed-upload-submit']) && file_exists($uloaded_file)
      || DEBUG && file_exists($uloaded_file) // re-use submission
      ) {
      $feed = new Feed();
      $feed->loadFile($uloaded_file);
      $result = $feed->process();

      $msg = $result ? 'File uploaded, parsed, and saved to the database.' : 'A problem occurred.';
      echo "<p>$msg</p>";
    }
?>
  </body>
</html>
