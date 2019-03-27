<?php
/**
 * Created by PhpStorm.
 * User: ljubi
 * Date: 12/13/2018
 * Time: 12:36 PM
 */
/* Template Name: Prioritized bills view */
if ( ! is_user_logged_in() ) {
    wp_die();
}
get_header();
$user = MSAvalidateUserRole();
$results = $user->getPrioritizedBillsList();
?>
<link rel="stylesheet" type="text/css"
      href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>

<script type="text/javascript" charset="utf8"
        src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<div class="main list-page">
    <a class="map-toggle-btn" href="<?php echo get_site_url() ?>/dashboard/"><i class="fas fa-map-marker-alt"></i></a>
    <div class="container-fluid">
        <h2>Prioritized Bill List</h2>
        <div id="dataTableDiv">
            <table cellpadding="0" cellspacing="0" border="0" class="dataTable table table-striped display"  width="100%" id="list">
                <thead>
                <tr>
                    <th>Bill ID</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Prioritized By</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($results as $priobill){
                            echo "<tr><td>{$priobill->id}</td><td>{$priobill->title}</td><td>{$priobill->entity_type}</td><td>{$priobill->display_name}</td><td>{$priobill->timestamp}</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php get_footer();?>
<script>
    $(document).ready(function () {
        (myTable = $('#list')).DataTable({
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
            ],
            "bProcessing": true,
            "sPaginationType": "full_numbers",
            responsive: true,
        })
    });
</script>
