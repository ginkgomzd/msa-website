<?php
$id=$_POST['value'];
require( '../../../wp-load.php' );
global $wpdb;

echo $id;

$exist = $wpdb->get_results("Select isPriority from legislation WHERE `legislation`.`number` = $id; ",OBJECT );
$true = $exist[0]->isPriority;

if($true==='1'){
    $result = $wpdb->get_results("UPDATE `legislation` SET `isPriority` = 'NULL' WHERE `legislation`.`number` = $id; ");
} else {
   $result = $wpdb->get_results("UPDATE `legislation` SET `isPriority` = '1' WHERE `legislation`.`number` = $id; ");
}
    

?>