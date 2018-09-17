<?php
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/profileMatch.inc";

use GinkgoStreetLabs\PdoConnection;
use GinkgoStreetLabs\PdoDebugger;

/* DEV: */
ini_set('display_errors', 1);
define('DEBUG', FALSE); // DISABLE FOR PRODUCTION
define('DEBUG_VERBOSE_SQL', FALSE);
/* END DEV */

$client = filter_input(INPUT_GET, 'client', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK);
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$db = new PdoConnection();

$stmt = $db->prepare('SELECT
    hearing.*, legislation.abstract
FROM
    hearing
        INNER JOIN
    legislation ON hearing.legislation_external_id = legislation.external_id
WHERE
    hearing.id = :id
;');
$params = array('id' => $id);
$stmt->execute($params);
PdoDebugger::debugStatement($stmt, $params);
$hearing = $stmt->fetchAll(PDO::FETCH_OBJ);
if (is_array($hearing)) {
  $hearing = array_pop($hearing);
}

$stmt = $db->prepare('SELECT m.id as m_id, m.pname, k.keyword
  FROM profile_match m
  -- really want this to be an inner join, but the parser seems to be skipping some keywords
  LEFT JOIN profile_keyword k
  ON m.id = k.profile_match_id
  WHERE m.entity_id = :id
  AND m.entity_type = :entity_type
  AND m.client = :client;');

$params = array(
  ':entity_type' => 'hearing',
  ':id' => $id,
  ':client' => $client,
);
$stmt->execute($params);
PdoDebugger::debugStatement($stmt, $params);
$matchData = $stmt->fetchAll(PDO::FETCH_OBJ);

$profileMatches = array();
foreach ($matchData as $data) {
  if (!isset($profileMatches[$data->m_id])) {
    $profileMatches[$data->m_id] = [
      'pname' => $data->pname,
      'keywords' => [],
    ];
  }

  $profileMatches[$data->m_id]['keywords'][] = $data->keyword;
}

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <title><?= $hearing->Committee ?> (<?= $client ?>)</title>
  </head>
  <style type="text/css">
    dt {
      float:left;
      width: 100px;
      font-weight: bold;
    }
  </style>
  <body>
    <h1><?= $hearing->committee ?></h1>
    <h2>Meeting Information</h2>
    <dl>
      <dt>House</dt>
      <dd><?= $hearing->house ?></dd>
      <dt>Committee</dt>
      <dd><?= $hearing->committee ?></dd>
      <dt>Place</dt>
      <dd><?= $hearing->place ?></dd>
      <dt>Date</dt>
      <dd><?= $hearing->date ?></dd>
      <dt>Time</dt>
      <dd><?= $hearing->time ?></dd>
    </dl>
    <h2>Abstract</h2>
    <p><?= $hearing->abstract ?></p>
    <?php showProfileMatches($profileMatches, $client); ?>
  </body>
</html>
