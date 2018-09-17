<?php

function showProfileMatches(array $profileMatches, $client) {
  if (!count($profileMatches)) {
    return;
  }

  echo "<h2>Profiles</h2>\n";
  foreach ($profileMatches as $pMatch) {
    echo "<p>Matched profile <em>{$pMatch['pname']}></em> for $client on: " . implode(', ', $pMatch['keywords']) . "</p>\n";
  }
}
