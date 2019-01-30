<?php
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/profileMatch.inc";

use GinkgoStreetLabs\PdoConnection;
use GinkgoStreetLabs\PdoDebugger;

/* DEV: */
ini_set('display_errors', 1);
define('DEBUG', FALSE); // DISABLE FOR PRODUCTION
define('DEBUG_VERBOSE_SQL', TRUE);
/* END DEV */

$client = filter_input(INPUT_GET, 'client', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK);
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$db = new PdoConnection();

$regStatement = $db->prepare('SELECT r.*, m.id as m_id, m.pname, k.keyword
  FROM regulation r
  INNER JOIN profile_match m
  ON r.id = m.entity_id
  AND m.entity_type = :entity_type
  -- really want this to be an inner join, but the parser seems to be skipping some keywords
  LEFT JOIN profile_keyword k
  ON m.id = k.profile_match_id
  WHERE r.id = :id
  AND m.client = :client');
$params = array(
  ':entity_type' => 'regulation',
  ':id' => $id,
  ':client' => $client,
);
PdoDebugger::debugStatement($regStatement, $params);
$regStatement->execute($params);
$regData = $regStatement->fetchAll(PDO::FETCH_OBJ);

$profileMatches = [];
foreach ($regData as $data) {
  // this data is the same in every row, so we don't need to populate it over and over
  if (!isset($reg)) {
    $reg = (object) [
      'agency_name' => $data->agency_name,
      'code_citation' => $data->code_citation,
      'description' => $data->description,
      'register_citation' => $data->register_citation,
      'register_date' => $data->register_date,
      'state' => $data->state,
      'tracking_key' => $data->tracking_key,
      'type' => "$data->type ($data->state_action_type)",
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
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <title><?= $reg->code_citation ?> (<?= $client ?>)</title>
  </head>
  <style type="text/css">
    dt {
      float:left;
      width: 100px;
      font-weight: bold;
    }
  </style>
  <body>
    <h1><?= $reg->code_citation ?></h1>
    <h2>Regulation Information</h2>
    <dl>
      <dt>Agency Name</dt>
      <dd><?= $reg->agency_name ?></dd>
      <dt>Code Citation</dt>
      <dd><?= $reg->code_citation ?></dd>
      <dt>Register Citation</dt>
      <dd><?= $reg->register_citation ?></dd>
      <dt>Register Date</dt>
      <dd><?= $reg->register_date ?></dd>
      <dt>State</dt>
      <dd><?= $reg->state ?></dd>
      <dt>Tracking Key</dt>
      <dd><?= $reg->tracking_key ?></dd>
      <dt>Type</dt>
      <dd><?= $reg->type ?></dd>
    </dl>
    <h2>Description</h2>
    <p><?= $reg->description ?></p>
    <?php showProfileMatches($profileMatches, $client); ?>
  </body>
</html>
