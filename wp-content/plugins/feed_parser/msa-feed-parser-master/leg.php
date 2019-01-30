<?php
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/profileMatch.inc";

use GinkgoStreetLabs\PdoDebugger as pdoDbg;
use GinkgoStreetLabs\PdoConnection;

/* DEV: */
ini_set('display_errors', 1);
define('DEBUG', FALSE); // DISABLE FOR PRODUCTION
define('DEBUG_VERBOSE_SQL', FALSE);
/* END DEV */

$client = filter_input(INPUT_GET, 'client', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK);
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (is_null($client) || is_null($id)){
	echo "<br/>Please call page with additional get parameters &client={int}&id={int} <br/>e.g ". get_site_url(). "/wp-admin/admin.php?page=legislations&client=1&id=2";
	wp_die();
}
$db = new PdoConnection();

$legStatement = $db->prepare('SELECT l.*, m.id as m_id, m.pname, k.keyword
  FROM legislation l
  INNER JOIN profile_match m
  ON l.id = m.entity_id
  AND m.entity_type = :entity_type
  -- really want this to be an inner join, but the parser seems to be skipping some keywords
  LEFT JOIN profile_keyword k
  ON m.id = k.profile_match_id
  WHERE l.id = :id');
$params = array(
  ':entity_type' => 'legislation',
  ':id' => $id
);
pdoDbg::debugStatement($legStatement, $params);
$legStatement->execute($params);
$legData = $legStatement->fetchAll(PDO::FETCH_OBJ);
$profileMatches = [];
foreach ($legData as $data) {
  // this data is the same in every row, so we don't need to populate it over and over
  if (!isset($leg)) {
    $leg = (object) [
      'abstract' => $data->abstract,
      'number' => $data->number,
      'session' => $data->session,
      'sponsor_name' => $data->sponsor_name,
      'sponsor_url' => $data->sponsor_url,
      'state' => $data->state,
      'title' => $data->title,
      'type' => $data->type,
    ];
  }

  if (!isset($profileMatches[$data->m_id])) {
    $profileMatches[$data->m_id] = [
      'pname' => $data->pname,
      'keywords' => [],
    ];
  }

  $profileMatches[$data->m_id]['keywords'][] = $data->keyword;
}
$sponsor = isset($leg->sponsor_url) ? "<a href=\"$leg->sponsor_url\">$leg->sponsor_name</a>" : $leg->sponsor_name;
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <title><?= $leg->title ?> (<?= $client ?>)</title>
  </head>
  <style type="text/css">
    dt {
      float:left;
      width: 100px;
      font-weight: bold;
    }
  </style>
  <body>
    <h1><?= $leg->title ?></h1>
    <h2>Bill Information</h2>
    <dl>
      <dt>Session</dt>
      <dd><?= $leg->session ?></dd>
      <dt>State</dt>
      <dd><?= $leg->state ?></dd>
      <dt>Type</dt>
      <dd><?= $leg->type ?></dd>
      <dt>Number</dt>
      <dd><?= $leg->number ?></dd>
      <dt>Sponsor</dt>
      <dd><?= $sponsor ?></dd>
      <dt>
    </dl>
    <h2>Abstract</h2>
    <p><?= $leg->abstract ?></p>
    <?php showProfileMatches($profileMatches, $client); ?>
  </body>
</html>
