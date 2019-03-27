<?php
require_once __DIR__ . "/vendor/autoload.php";

use GinkgoStreetLabs\PdoConnection;
use GinkgoStreetLabs\PdoDebugger;

/* DEV: */
ini_set('display_errors', 1);
define('DEBUG', FALSE); // DISABLE FOR PRODUCTION
define('DEBUG_VERBOSE_SQL', FALSE);
/* END DEV */

$client = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK);
$db = new PdoConnection();

$legStatement = $db->prepare('SELECT l.id, l.title, l.abstract, l.session, l.state, l.type, l.number
  FROM legislation l
  INNER JOIN profile_match m
  ON l.id = m.entity_id
  AND m.entity_type = :entity_type
  WHERE m.client = :client');
$legStatement->execute(array(
  ':entity_type' => 'legislation',
  ':client' => $client,
));
$legs = $legStatement->fetchAll(PDO::FETCH_OBJ);

$regStatement = $db->prepare('SELECT r.id, r.tracking_key, r.state, r.description, r.type, r.state_action_type, r.agency_name
  FROM regulation r
  INNER JOIN profile_match m
  ON r.id = m.entity_id
  AND m.entity_type = :entity_type
  WHERE m.client = :client');
$regStatement->execute(array(
  ':entity_type' => 'regulation',
  ':client' => $client,
));
$regs = $regStatement->fetchAll(PDO::FETCH_OBJ);

$hearingStatement = $db->prepare('SELECT h.*
  FROM hearing h
  INNER JOIN profile_match m
  ON h.id = m.entity_id
  AND m.entity_type = :entity_type
  WHERE m.client = :client');
$params = array(
  ':entity_type' => 'hearing',
  ':client' => $client,
);
$hearingStatement->execute($params);
$hearings = $hearingStatement->fetchAll(PDO::FETCH_OBJ);
PdoDebugger::debugStatement($hearingStatement, $params);

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <title><?= $client ?></title>
  </head>
  <style type="text/css">
    td, th {
      padding: 5px;
    }
    thead tr {
      background: #002F5B;
      color: #FFFFFF;
    }
    tbody tr:nth-of-type(odd) {
      background: #EBEBED;
    }
    tbody tr:nth-of-type(even) {
      background: #A9ABAE;
    }
  </style>
  <body>
    <h1><?= $client ?></h1>

    <h2>Legislation</h2>
    <table>
      <thead>
        <tr>
          <th>Title</th>
          <th>Abstract</th>
          <th>Session</th>
          <th>State</th>
          <th>Type</th>
          <th>Number</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($legs as $leg): ?>
          <tr>
            <td><a href="leg.php?id=<?= $leg->id ?>&client=<?= $client ?>"><?= $leg->title ?></a></td>
            <td><?= $leg->abstract ?></td>
            <td><?= $leg->session ?></td>
            <td><?= $leg->state ?></td>
            <td><?= $leg->type ?></td>
            <td><?= $leg->number ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <h2>Regulations</h2>
    <table>
      <thead>
        <tr>
          <th>Tracking Key</th>
          <th>Description</th>
          <th>Agency</th>
          <th>State</th>
          <th>Type</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($regs as $reg): ?>
          <tr>
            <td><a href="reg.php?id=<?= $reg->id ?>&client=<?= $client ?>"><?= $reg->tracking_key ?></a></td>
            <td><?= $reg->description ?></td>
            <td><?= $reg->agency_name ?></td>
            <td><?= $reg->state ?></td>
            <td><?= "$reg->type ($reg->state_action_type)" ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <h2>Hearings</h2>
    <table>
      <thead>
        <tr>
          <th>House</th>
          <th>Committee</th>
          <th>Place</th>
          <th>Date</th>
          <th>Time</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($hearings as $hearing): ?>
          <tr>
            <td><a href="hearing.php?id=<?= $hearing->id ?>&client=<?= $client ?>"><?= $hearing->house ?></a></td>
            <td><?= $hearing->committee ?></td>
            <td><?= $hearing->place ?></td>
            <td><?= $hearing->date ?></td>
            <td><?= $hearing->time ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>


  </body>
</html>
