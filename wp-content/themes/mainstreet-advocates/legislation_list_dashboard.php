<?php /* Template Name: Legislation_list_dashboard */ ?>
<?php get_header(); ?>
<?php 

    $cat = $_GET["cat"];
    $state = $_GET["st"];
    $priority = $_GET["pr"]; 
    $bills = $_GET["bl"];

?>

<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>

<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>

<?php 
    global $wpdb;

    if($cat ==='undefined' ) {
        $cat='%';  
        
    } 

    if($priority ===null ) {
        $priority='%';  
    } 

//var_dump($cat);
//var_dump($state);
//var_dump($priority);
//var_dump($bills);

    $client=esc_attr( get_the_author_meta( 'company', get_current_user_id()) );

    $query = "SELECT * , profile_match.pname FROM `legislation` 
            INNER join profile_match on legislation.id = profile_match.entity_id    
            where profile_match.client='$client' 
            and profile_match.entity_type='legislation'
            and legislation.state='$state'
            and profile_match.pname like '$cat'
            and legislation.isPriority like '$priority'
            group by profile_match.entity_id order by legislation.id ASC";

    $legData = $wpdb->get_results($query);

?>

<div class="main" style="margin-left:5%; width:90%">
  <h2>Legislations lists</h2>       
  <table id="legislation" class="table table-striped">
    <thead>
      <tr>
        <th>Priority</th>
        <th>Session</th>
        <th>State</th> 
        <th>Type</th>
        <th>Number</th>
        <th>Sponsor(s)</th>
        <th>Title</th>
        <th>Abstract</th>
        <th>Updated</th>
        <th>Status</th>
        <th>Categorie(s)</th> 
        <th>Latest Action</th>
      </tr>
    </thead>
    <tbody>
     <?php foreach ($legData as $data) {
       ?>
      <tr>
       <td style="text-align: center;">
          <?php if($data->isPriority === '1'){ ?> 
                <i class="star fa fa-star fa-lg" aria-hidden="true" id="<?php echo $data->number; ?>"></i>
           <?php } else { ?>
               <i class="star fa fa-star-o fa-lg" aria-hidden="true" id="<?php echo $data->number; ?>"></i>
           <?php } ?>
       </td>
        <td><?php echo $data->session; ?></td>
        <td><?php echo $data->state; ?></td>
        <td><?php echo $data->type; ?></td>  
        <td><?php echo $data->number; ?></td>
        <td><?php echo $data->sponsor_name; ?></td>
        <td><?php echo $data->title; ?></td> 
        <td><?php echo $data->abstract; ?></td>
        <td></td>
        <td></td>
        <td><?php echo $data->pname; ?></td>
        <td></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<?php get_footer(); ?>
<script>
$(document).ready( function () {
    
// * Call up datatables */
   $('#legislation').DataTable( {
        dom: 'Bfrtip',
        buttons: ['print',
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 0, ':visible' ]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            'colvis'
        ]
    } );
    
    
// * Mark as priority */
    $( ".star" ).click(function() {
    
        var id = $(this).attr('id');
        $(this).toggleClass('fa-star fa-star-o');

       $.ajax({
                    type: "POST",
                    url: "/msa_test/wp-content/themes/mainstreet-advocates/add-priority.php",
                    data: ({value:id}),
                    success: function(data) {
                    }
                });

    });

    
} );
</script>