<?php

use MainStreetAdvocates\Feed;

/* DEV: */
ini_set('display_errors', 1);
define('DEBUG', TRUE); // DISABLE FOR PRODUCTION
define('DEBUG_VERBOSE_SQL', true);
/* END DEV */

/***
 * Determine relative path we are installed in.
 **/
$chopFrom = strrpos(__DIR__, 'htdocs') + strlen('htdocs');
$appRoot = substr(__DIR__, $chopFrom);



require_once __DIR__ . "/vendor/autoload.php";

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <title>Feed Parser</title>
  </head>
  <body>
    <h1>Upload Feed</h1>
<!--    <form enctype="multipart/form-data" method="post" action="<?php echo $appRoot ?>/">-->
       <form enctype="multipart/form-data" method="post" action="">
        <input name="feed-upload" type="file" required />
        <input name="feed-upload-submit" type="submit" />
       
      </form>
      <?php

//    $upload = '/msa-feed-parser-master/tmp/';
//      $upload = '/msa-feed-parser-mastertmp/tmp/';
      $uloaded_file='';
    
    if (isset($_POST['feed-upload-submit'])) {
        
        $submission = $_FILES['feed-upload'];
        $uloaded_file='../wp-content/uploads/tmp/' . $_FILES['feed-upload']['name'];
        
        if(!move_uploaded_file($_FILES['feed-upload']['tmp_name'],$uloaded_file )){
            die('Error uploading file - check destination is writeable.');
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
