<?php /* Template Name: Legislation_list */ ?>
<?php get_header(); ?>

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
$client=esc_attr( get_the_author_meta( 'company', get_current_user_id()) );

$query = "SELECT * FROM `legislation` 
        INNER join profile_match on legislation.id = profile_match.entity_id    
        where profile_match.client='$client' 
        and profile_match.entity_type='legislation'";

$legData = $wpdb->get_results($query);

//var_dump($legData);

?>

<div class="main">
  <h2>Legislations lists</h2>       
  <table id="legislation" class="table table-striped">
    <thead>
      <tr>
        <th><i class="fa fa-star" aria-hidden="true"></i>
</th>
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
     <?php foreach ($legData as $data) { ?>
      <tr>
       <td><input type="checkbox" name="rate" value="rate"></td>
        <td><?php echo $data->session; ?></td>
        <td><?php echo $data->state; ?></td>
        <td><?php echo $data->type; ?></td>  
        <td><?php echo $data->number; ?></td>
        <td><?php echo $data->sponsor_name; ?></td>
        <td><?php echo $data->title; ?></td> 
        <td><?php echo $data->abstract; ?></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<?php get_footer(); ?>
<script>
$(document).ready( function () {
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
        ],
        colReorder: true
    } );
    
} );
</script>