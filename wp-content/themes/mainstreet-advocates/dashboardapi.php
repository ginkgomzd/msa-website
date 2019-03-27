<?php
/**
 * Created by PhpStorm.
 * User: ljubi
 * Date: 12/16/2018
 * Time: 12:18 AM
 */
include( '../../../wp-load.php' );
$user = MSAvalidateUserRole();
//$data = $user->dashboardMain();
/*
foreach ($data['data'] as $state => $value) { ?> {
	"id": "US-<?php echo $state; ?>",
	"modalUrl": "<?php echo get_site_url() ?>/dashboard-list/?cat=" + parameter + "&st=<?php echo $state; ?>",
	"selectable": true,
	"value": <?php echo $value['total'];?>,
	"legalisation": <?php echo $value['legislation'];?>,
	"regulations":<?php echo $value['regulation'];?>
	},
<?php  } ?>*/
//$data = array('data'=>[["id"=>"US-AL","selectable"=>true,"value"=>1,"legalisation"=>1,"regulations"=>0,"modalUrl"=>"http://localhost/msa_test/dashboard-list/?cat=legislation&st=AK"],["id"=>"US-CA","selectable"=>true,"value"=>1,"legalisation"=>1,"regulations"=>0,"modalUrl"=>"http://localhost/msa_test/dashboard-list/?cat=legislation&st=AK"]]);
//echo json_encode($data);
$priority = null;
$type = null;
$category = null;
$status = null;
if(isset($_POST['priority']) && $_POST['priority'] !== '') {$priority = $_POST['priority'];}
if($_POST['type'] !== ''){
	$type = $_POST['type'];
}
if(isset($_POST['category']) && $_POST['category'] !== ''){
	$category = $_POST['category'];
}
if(isset($_POST['status']) && $_POST['status'] !== ''){
	$status = $_POST['status'];
}
if($user->solr_active) {
	$end_result = $user->dashboardManagerSolrMain( $type,$category,$priority,$status);
	echo json_encode($end_result);
}else {
	echo json_encode( $user->dashboardManager( $_POST['category'], $_POST['type'], $_POST['state'], $priority ) );
}
?>