<?php /* Template Name:  */ ?>
<?php get_header() ?>
<?php $url = get_site_url(); ?>

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

<script type="text/javascript" charset="utf8"
        src="<?php echo get_template_directory_uri() ?>/js/select2.min.js"></script>
<style>
    .fas-star-tooltiptext,.far-star-tooltiptext{
        visibility: hidden;
        width: 120px;
        background-color: black;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;

        /* Position the tooltip */
        position: absolute;
        z-index: 1;
    }

    .fas.fa-star:hover .fas-star-tooltiptext {
        visibility: visible;
    }
    .far.fa-star:hover .far-star-tooltiptext {
        visibility: visible;
    }
</style>
<?php
$user_id    = get_current_user_id();
$categories = explode( ",", getCategoriesByUser( $user_id ) );
?>

<div class="main list-page">
    <a class="map-toggle-btn" href="<?php echo get_site_url() ?>/dashboard/"><i class="fas fa-map-marker-alt"></i></a>
    <div class="container-fluid">
        <h2>Legislation lists 4</h2>
        <div>
            <div class="form-group">
                <label>Type of document:</label>
                <select name="document_type" id="document_type" class="select-b">
                    <option value="">Type of document</option>
                    <option value="legislation">Legislation</option>
                    <option value="regulation">Regulation</option>
                    <option value="hearings">Hearings</option>
                </select>
            </div>
        </div>
        <table id="legislation" class="display" width="100%" cellspacing="0">
            <thead>
            <tr>
                <th>ID</th>
                <th>Priority</th>
                <th>Notes</th>
                <th>State</th>
                <th>Session</th>
                <th>Type</th>
                <th>Number</th>
                <th>Sponsor(s)</th>
                <th>Title</th>
                <th>Abstract</th>
                <th>Status</th>
                <th>Categorie(s)</th>
                <th>Latest Action</th>
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
WTF
<?php get_footer(); ?>
<script>
    $(document).ready(function () {

        var param = '%';

        function updateTables(param) {
            if (param === "regulation") {
                //$('#legislation thead').empty();
                $('#legislation tbody').empty();
                $('#legislation tfoot').remove();
                $('#legislation_info').remove();
                $('#legislation_paginate').remove();
            }
        }

        function myfunction(param) {
            $('#legislation thead tr').replaceWith("      <th>ID</th>\n" +
                "                <th>Priority</th>\n" +
                "                <th>Notes</th>\n" +
                "                <th>State</th>\n" +
                "                <th>Session</th>\n" +
                "                <th>Type</th>\n" +
                "                <th>Number</th>\n" +
                "                <th>Sponsor(s)</th>\n" +
                "                <th>Title</th>\n" +
                "                <th>Abstract</th>\n" +
                "                <th>Status</th>\n" +
                "                <th>Categorie(s)</th>\n" +
                "                <th>Latest Action</th>");
            var e;
            (e = $('#legislation')).dataTable({
                //destroy: true,
                //clear: true,
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
                "sAjaxSource": '<?php echo get_site_url() ?>/wp-content/themes/mainstreet-advocates/legapi.php?cat=' + param + '',
                "aoColumns": [{
                    mData: 'id',
                    "mRender": function (source, type, val) {
                        return "<a href='<?php echo $url; ?>/detailed-view/?id=" + source + "'>" + source + "</a>";
                    }
                },
                    {
                        mData: 'isPriority',
                        "mRender": function (source, type, row, val) {
                            return source == '1' ? '<i class="fas fa-star"><span class="fas-star-tooltiptext">Bill has been prioritized</span></i>' : '<i class="far fa-star"><span class="far-star-tooltiptext">Bill has not been prioritized</span></i>'
                        }
                    },
                    {
                        mData: 'bookmark_note',
                        "mRender": function (source, type, row, val) {
                            return source == '1' ? '<i class="fas fa-bookmark"></i>' : '<i class="far fa-bookmark"></i>'
                        }
                    },
                    {
                        mData: 'state'
                    }, {
                        mData: 'session'
                    },
                    {
                        mData: 'type'
                    }, {
                        mData: 'number'
                    }, {
                        mData: 'sponsor_name',
                        "mRender": function (source, type, row, val) {
                            return "<a href='" + row.sponsor_url + "' target='_blank'>" + source + "</a>";
                        }
                    }, {
                        mData: 'title',
                        "mRender": function (source, type, row, val) {
                            return "<a href='<?php echo $url; ?>/detailed-view/?id=" + row.id + "'>" + source + "</a>";
                        }
                    },
                    {
                        mData: 'abstract'
                    },
                    {
                        mData: 'status_val'
                    },
                    {
                        mData: 'categories'
                    },
                    {
                        mData: 'status_standard_val'
                    }
                ]
            }),e.on("click",".far.fa-star",function () {
                var id = $(this).closest("tr").find("td:first a").html();
                var element = $(this);
                $.ajax({
                    type: 'POST',
                    url: '<?php echo get_site_url() ?>/wp-content/themes/mainstreet-advocates/legapi.php',
                    data: {"id": id, "type": "legislation", "action": "priority"},
                    dataType: 'JSON',
                    success: function (response) {
                        console.log(response.status);
                        if (response.status === true) {
                            element.removeClass('far').addClass('fas');
                        }
                    }
                })
            })
        }

        myfunction('legislation');
        //myfunctionRegulation('regulation');
        $('#document_type').on('change', function () {
            if (this.value) {
                var value = this.value;
                //$('#legislation').dataTable().fnDestroy();
                //updateTables(value);
                if (value === 'regulation') {
                    myfunctionRegulation(value)
                } else if (value === 'legislation') {
                    myfunction(value);
                }
            }
            //myfunction(value);
        });
        function check(element) {

        }

    });

</script>
