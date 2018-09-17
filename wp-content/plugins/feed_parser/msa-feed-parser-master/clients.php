<?php
require_once __DIR__ . "/vendor/autoload.php";

use GinkgoStreetLabs\PdoConnection;

/* DEV: */
ini_set('display_errors', 1);
define('DEBUG', FALSE); // DISABLE FOR PRODUCTION
define('DEBUG_VERBOSE_SQL', FALSE);
/* END DEV */

$db = new PdoConnection();
$statement = $db->prepare('SELECT DISTINCT client FROM profile_match');
$statement->execute();
$clients = $statement->fetchAll(PDO::FETCH_COLUMN, 0);

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <title>Clients</title>
  </head>
  <body>
    <h1>Clients</h1>
    <p>
      This is a very rudimentary user interface to provide a basic display of
      data parsed from State Track. Click a client name to get started.
    </p>
    <ul>
      <?php
      foreach ($clients as $client) {
        echo "<li><a href=\"client.php?id=$client\">$client</li>\n";
      }
      ?>
    </ul>
  </body>
</html>
