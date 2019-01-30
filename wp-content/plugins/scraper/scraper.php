<?php
// Include the library
include( '../wp-load.php' ); ?>
    <form enctype="multipart/form-data" method="post" action="">
        <div>
            <h2>Document uploads</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Legislation document</td>
                        <td><input name="upload-submit" type="submit" class="btn btn-primary" value="Upload"></td>
                    </tr>
                    <tr>
                        <td>Regulation document</td>
                        <td><input type="submit" class="btn btn-primary" value="Upload"></td>
                        
                    </tr>
                </tbody>
            </table>
        </div>
    </form>

    <?php

if (isset($_POST['upload-submit'])) {
/*** Create a new dom object ***/ 
$dom = new \DOMDocument('1.0', 'UTF-8');

// set error level
$internalErrors = libxml_use_internal_errors(true);

//checking urls needs to be imported
global $wpdb;
$query = "SELECT id,full_text_url,external_id FROM `legislation` WHERE textUploaded IS NULL LIMIT 15";
$legData = $wpdb->get_results($query);
$count=0;
//inserting inside database
foreach($legData as $row){
    $url = $row->full_text_url;
    $entityID=$row->external_id;
    // Retrieve the DOM from a given URL
    $html = file_get_contents($url);; 
    $dom->loadHTML($html);
    $content = $dom->saveHTML();
//    $htmlcode = wp_kses(string $content);
//    $htmlcode  = wp_kses_post( $content ); 
    $htmlcode  = esc_sql( $content ); 


    $queryContent = "INSERT INTO `entity_text` (`id`, `date`, `entity_id`, `content`) VALUES (NULL, CURRENT_TIMESTAMP, '$entityID', '$htmlcode');";
    $insert=$wpdb->get_results($queryContent);
    
    if($insert>0){
        $queryUpdated = "UPDATE `legislation` SET `textUploaded` = '1' WHERE external_id = '$entityID';";
        $update=$wpdb->get_results($queryUpdated);
        $count++;
    }

}
   
        echo '<div class="alert alert-success" role="alert"><p>'.$count.' doccuments succesfully uploaded</p></div>';

//
// Restore error level
libxml_use_internal_errors($internalErrors);
    
}

    
?>
