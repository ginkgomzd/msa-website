<?php /* Template Name: Legislation_list_dashboard */ ?>
<?php get_header() ?>
<?php $url = get_site_url(); ?>

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

<?php 
    $user_id=get_current_user_id();
    $categories = explode(",",getCategoriesByUser($user_id)); 
?>

<div class="main list-page">
    <a class="map-toggle-btn" href="<?php echo get_site_url()?>/dashboard/"><i class="fas fa-map-marker-alt"></i></a>
    <div class="container-fluid">
        <h2>Legislations lists</h2>
        <div>
            <div class="form-group">
                <select name="category" id="category" class="select-b">
                    <option value="null">Select Category</option>
                   <?php foreach ($categories as $category) { ?>
                    <option value="<?php echo trim($category); ?>"><?php echo $category;?></option>
                   <?php } ?>
            </select>
            </div>
        </div>
        <table id="legislation" class="display" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>State</th>
                    <th>Session</th>
                    <th>Type</th>
                    <th>Number</th>
                    <th>Sponsor(s)</th>
                    <th>Title</th>
                    <th>Categorie(s)</th>
                </tr>
            </thead>

            <tfoot>
                <tr>
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
        </table>
    </div>
</div>

<?php get_footer(); ?>
<script>
    $(document).ready(function() {
        
        var param = '%';

        function myfunction(param) {
            $('#legislation').dataTable({
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
                "bProcessing": true,
                "sAjaxSource": '/msa_test/wp-content/themes/mainstreet-advocates/legapi.php?cat=' + param + '',
                "aoColumns": [{
                        mData: 'id',
                        "mRender": function ( source, type, val ) {
                            return "<a href='<?php echo $url; ?>/detailed-view/?id="+source+"'>"+source+"</a>";
                          }
                    },
                    {
                        mData: 'state'
                    },{
                        mData: 'session'
                    },
                    {
                        mData: 'type'
                    },{
                        mData: 'number'
                    },{
                        mData: 'sponsor_name',
                        "mRender": function ( source, type, row, val ) {
                            return "<a href='"+row.sponsor_url+"' target='_blank'>"+source+"</a>";
                          }
                    },{
                        mData: 'title',
                        "mRender": function ( source, type, row, val ) {
                            return "<a href='<?php echo $url; ?>/detailed-view/?id="+row.id+"'>"+source+"</a>";
                          }
                    },{
                        mData: 'categories'
                    }
                ]
            });
        }

        myfunction(param);
        
        $('#category').on('change', function() {
            var value = this.value; 
            $('#example').dataTable().fnDestroy();
             myfunction(value);
        });



    });

</script>
