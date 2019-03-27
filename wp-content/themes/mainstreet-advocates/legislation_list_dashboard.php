<?php /* Template Name: Legislation_list */ ?>
<?php get_header(); ?>
<?php 

    $cat = $_GET["cat"];
    $cat = trim($cat);
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

<script type="text/javascript" charset="utf8" src="<?php echo get_template_directory_uri() ?>/js/select2.min.js"></script>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

<?php 
    global $wpdb;

    if($cat === 'null' ) {
        $cat='test';  
        
    } 

    if($priority === 'null' ) {
        $priority='%';  
    } 
////
//var_dump($cat);
//////var_dump($state);
//var_dump($priority);
//////var_dump($bills);
    $user_id=get_current_user_id();
    $client=esc_attr( get_the_author_meta( 'company', get_current_user_id()) );
    

//    $query = "SELECT * , profile_match.pname FROM `legislation` 
//            INNER join profile_match on legislation.id = profile_match.entity_id    
//            where profile_match.client='$client' 
//            and profile_match.entity_type='legislation'
//            and legislation.state='$state'
//            and pname like '$cat'
//            and legislation.isPriority like '$priority'
//            group by profile_match.entity_id order by legislation.id ASC"; 

    /*$query = "SELECT * FROM `legislation`
                where state='$state' 
                and isPriority like '$priority'";*/

    $legData = $wpdb->get_results($query);
        

              
?>

<div class="main list-page">
    <!-- <a class="map-toggle-btn" href="#"><i class="fas fa-map-marker-alt"></i></a>-->
    <div class="container-fluid">
        <h2>Legislations lists1</h2>
        <table id="legislation" class="table table-striped">
            <thead>
                <tr>
                    <th>Priority</th>
                    <th>Session</th>
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
            <tfoot class="footerStyle">
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
            <tbody>


                <?php if($cat !== 'test' ) {       
                   
      
        foreach ($legData as $data) {

                    $catClientCheck = getCategoriesByID($data->id);
                    $catClientCheck = strtolower($catClientCheck);
            
                    $clientCheck = getCleint($data->id);


        
        if( strpos($catClientCheck,strtolower($cat)) !== false and  $clientCheck === $client ) {

?>
                <tr>
                    <td style="text-align: center;">
                        <!--                       <?php echo $data->isPriority ?>-->
                        <?php if($data->isPriority === '1'){ ?>
                        <i class="star fa fa-star fa-lg" aria-hidden="true" id="<?php echo $data->number; ?>"></i>
                        <p style="display:none">
                            <?php echo $data->isPriority ?>
                        </p>
                        <?php } else { ?>
                        <i class="star fa fa-star-o fa-lg" aria-hidden="true" id="<?php echo $data->number; ?>"></i>
                        <p style="display:none">
                            <?php echo $data->isPriority ?>
                        </p>
                        <?php } ?>
                    </td>
                    <td>
                        <?php echo $data->session; ?>
                    </td>
                    <td>
                        <?php echo $data->type; ?>
                    </td>
                    <td>
                        <?php echo $data->number; ?>
                    </td>
                    <td>
                        <?php echo $data->sponsor_name; ?>
                    </td>
                    <td>
                        <a href="detailed-view/?id=<?php echo $data->id; ?>">
                            <?php echo $data->title; ?>
                        </a>
                    </td>
                    <td>
                        <?php echo $data->abstract; ?>
                    </td>
                    <td></td>
                    <td></td>
                    <td>
                        <?php echo getCategoriesByID($data->id)?>
                    </td>
                    <td></td>
                </tr>
                <?php } 

} 
          

} else {

        foreach ($legData as $data) {
            
                   $catCheck = getCategoriesByID($data->id);
                   $clientCheck = getCleint($data->id);

        if($clientCheck === $client ) {
?>
                <tr>
                    <td style="text-align: center;">
                        <?php if($data->isPriority === '1'){ ?>
                        <i class="star fa fa-star fa-lg" aria-hidden="true" id="<?php echo $data->number; ?>"></i>
                        <p style="display:none">
                            <?php echo $data->isPriority ?>
                        </p>
                        <?php } else { ?>
                        <i class="star fa fa-star-o fa-lg" aria-hidden="true" id="<?php echo $data->number; ?>"></i>
                        <p style="display:none">
                            <?php echo $data->isPriority ?>
                        </p>
                        <?php } ?>
                    </td>
                    <td>
                        <?php echo $data->session; ?>
                    </td>
                    <td>
                        <?php echo $data->type; ?>
                    </td>
                    <td>
                        <?php echo $data->number; ?>
                    </td>
                    <td>
                        <?php echo $data->sponsor_name; ?>
                    </td>
                    <td>
                        <a href="detailed-view/?id=<?php echo $data->id; ?>">
                            <?php echo $data->title; ?>
                        </a>
                    </td>
                    <td>
                        <?php echo $data->abstract; ?>
                    </td>
                    <td></td>
                    <td></td>
                    <td>
                        <?php echo getCategoriesByID($data->id)?>
                    </td>
                    <td></td>
                </tr>
                <?php   } 
    }
} ?>


            </tbody>
        </table>
    </div>
</div>
<?php get_footer(); ?>
<script>
    $(document).ready(function() {

        // * Call up datatables */
        $('#legislation').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: ['print',
                {
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [0, ':visible']
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
            initComplete: function() {
                this.api().columns([1, 2, 3]).every(function() {
                    var column = this;
                    var select = $('<select class="select2"><option value=""></option></select>')
                        .appendTo($(column.footer()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
            }

        });

        // * Add 2select */
        $('.select2').select2();


        // * Mark as priority */
        $(".star").click(function() {

            var id = $(this).attr('id');
            $(this).toggleClass('fa-star fa-star-o');

            $.ajax({
                type: "POST",
                url: "<?php echo get_site_url() ?>/wp-content/themes/mainstreet-advocates/add-priority.php",
                data: ({
                    value: id
                }),
                success: function(data) {}
            });

        });


    });
</script>