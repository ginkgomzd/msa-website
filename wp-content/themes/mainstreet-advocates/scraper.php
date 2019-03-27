<?php
// Include the library
include( '../../../wp-load.php' );

/*** Create a new dom object ***/ 
$dom = new \DOMDocument('1.0', 'UTF-8');

// set error level
$internalErrors = libxml_use_internal_errors(true);

//checking urls needs to be imported
global $wpdb;
$query = "SELECT id,full_text_url FROM `legislation` where NOT (textUploaded <=> '1') LIMIT 5";
$legData = $wpdb->get_results($query);


//inaerting inside database
foreach($legData as $row){
    $url = $row->full_text_url;
    $entityID=$row->id;
    echo  $url;
    echo '<hr>';
    
    // Retrieve the DOM from a given URL
    $html = file_get_contents($url);; 
    $dom->loadHTML($html);
    $content = $dom->saveHTML();

    $htmlcode  = esc_sql( $content ); 


    $queryContent = "INSERT INTO `entity_text` (`id`, `date`, `entity_id`, `content`) VALUES (NULL, CURRENT_TIMESTAMP, '$entityID', '$htmlcode');";
    $insert=$wpdb->get_results($queryContent);
    if($insert>0){
        $queryUpdated = "UPDATE `legislation` SET `textUploaded` = '1' WHERE `legislation`.`id` = $entityID;";
        $update=$wpdb->get_results($queryUpdated);
    }

}


// Restore error level
libxml_use_internal_errors($internalErrors);


    
?>