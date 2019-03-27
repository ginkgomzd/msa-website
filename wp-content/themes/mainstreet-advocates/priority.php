<?php

require( '../wp-load.php' );
global $wpdb;
//var_dump($wpdb);

$result = $wpdb->get_results("SELECT meta_value FROM mv_postmeta WHERE meta_key = 'author' or meta_key = 'author1' or meta_key = 'author2' group by meta_value");
$x=json_encode($result);

echo $x;
?>