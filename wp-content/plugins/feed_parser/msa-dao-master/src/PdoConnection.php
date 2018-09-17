<?php
namespace GinkgoStreetLabs;

use PDO;

define('GSL_PDO_CONFIGURATION_FILENAME', 'pdo_connection.conf');

class PdoConnection {
  /**
  * the PDO object
  * @var PDO
  */
  public $pdo;

  static function missingConstants() {
//    $depends = array('GSL_PDO_DB_DATABASE', 'GSL_PDO_DB_USER', 'GSL_PDO_DB_PASSWORD');
      $depends = array('DB_NAME', 'DB_NAME', 'DB_PASSWORD');
//      var_dump($depends);
    $failures = array();
    foreach ($depends as $config) {
      if (!defined($config)) $failures[] = $config;
    }
    return (empty($failures))? FALSE : $failures;
  }

  static function searchForConf() {
    $files = array();
    $fs = new \RecursiveDirectoryIterator(getcwd());
    $filter = new GslPdoConfFileFilter($fs);

    foreach (new \RecursiveIteratorIterator($filter) as $node) {
      $files[] = $node->getPathName();
    }
    return $files;
  }

  public function __construct() {
    if (self::missingConstants()) {
        $search = self::searchForConf();
        if (count($search)) {
          foreach ($search as $node) {
            if (!is_file($node) || !is_readable($node)) {
              continue;
            }
            require_once $node;
          }
        }
    }

    if ($missing = self::missingConstants()) {
        $missing = join($missing, ', ');
        throw new \Exception("Missing constant(s): $missing.");
    }

//    $this->pdo = new PDO('mysql:host=localhost;dbname='.GSL_PDO_DB_DATABASE
//      , GSL_PDO_DB_USER, GSL_PDO_DB_PASSWORD
      $this->pdo = new PDO('mysql:host=localhost;dbname='.DB_NAME
    , DB_USER, DB_PASSWORD
    );
  }

  function prepare($statement) {
    return $this->pdo->prepare($statement);
  }

}

class GslPdoConfFileFilter extends \RecursiveFilterIterator {

  public function accept() {
    $name = $this->current()->getFilename();
    // Skip hidden files and directories.
    // if ($name[0] === '.') {
    //   return FALSE;
    // }
    // if ($this->isDir()) {
    //   // Only recurse into intended subdirectories.
    //   return $name === 'wanted_dirname';
    // }
    // else {
    //   // Only consume files of interest.
    //   return strpos($name, 'wanted_filename') === 0;
    // }
    return ($this->isDir() ||
      strpos($name, GSL_PDO_CONFIGURATION_FILENAME) === 0)
    ;
  }

}
