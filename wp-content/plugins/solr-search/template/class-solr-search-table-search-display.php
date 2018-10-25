<?php

if ( ! defined( 'ABSPATH' ) ) exit;


$solrSearch = new Solr_Search_Connector();

$solrSearch->setQueries(array('*:*'));

get_search_form();


?>

<div class="main list-page">
    <!-- <a class="map-toggle-btn" href="#"><i class="fas fa-map-marker-alt"></i></a>-->
    <div class="container-fluid">
        <h2>Legislations lists</h2>
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


            <?php if ($cat !== 'test') {
                foreach ($legData as $data) {
                    $catCheck = getCategories($data->id);
                    $clientCheck = getCleint($data->id);

//        var_dump($catCheck);

                    if (strpos($catCheck, $cat) !== false and $clientCheck === $client) {

                        ?>
                        <tr>
                            <td style="text-align: center;">
<!--                       <?php echo $data->isPriority ?>-->
                                <?php if ($data->isPriority === '1') { ?>
                                    <i class="star fa fa-star fa-lg" aria-hidden="true"
                                       id="<?php echo $data->number; ?>"></i>
                                    <p style="display:none">
                                        <?php echo $data->isPriority ?>
                                    </p>
                                <?php } else { ?>
                                    <i class="star fa fa-star-o fa-lg" aria-hidden="true"
                                       id="<?php echo $data->number; ?>"></i>
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
                            <td><a href="detailed-view/?id=<?php echo $data->id; ?>">
                                    <?php echo $data->title; ?></a></td>
                            <td>
                                <?php echo $data->abstract; ?>
                            </td>
                            <td></td>
                            <td></td>
                            <td>
                                <?php echo getCategories($data->id) ?>
                            </td>
                            <td></td>
                        </tr>
                    <?php }

                }


            } else {

//    var_dump('enters');
                foreach ($legData as $data) {

//                   var_dump($data);

                    $catCheck = getCategories($data->id);
                    $clientCheck = getCleint($data->id);
                    ?>
                    <tr>
                        <td style="text-align: center;">
                            <?php if ($data->isPriority === '1') { ?>
                                <i class="star fa fa-star fa-lg" aria-hidden="true"
                                   id="<?php echo $data->number; ?>"></i>
                                <p style="display:none">
                                    <?php echo $data->isPriority ?>
                                </p>
                            <?php } else { ?>
                                <i class="star fa fa-star-o fa-lg" aria-hidden="true"
                                   id="<?php echo $data->number; ?>"></i>
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
                        <td><a href="detailed-view/?id=<?php echo $data->id; ?>">
                                <?php echo $data->title; ?></a></td>
                        <td>
                            <?php echo $data->abstract; ?>
                        </td>
                        <td></td>
                        <td></td>
                        <td>
                            <?php echo getCategories($data->id) ?>
                        </td>
                        <td></td>
                    </tr>


                <?php }

            } ?>


            </tbody>
        </table>
    </div>
</div>
<?php get_footer(); ?>
<script>
    $(document).ready(function () {

        // * Call up datatables */
        $('#legislation').DataTable({
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
            initComplete: function () {
                this.api().columns([1, 2, 3]).every(function () {
                    var column = this;
                    var select = $('<select class="select2"><option value=""></option></select>')
                        .appendTo($(column.footer()).empty())
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
            }
        });

        // * Add 2select */
        $('.select2').select2();

        // * Mark as priority */
        $(".star").click(function () {

            var id = $(this).attr('id');
            $(this).toggleClass('fa-star fa-star-o');

            $.ajax({
                type: "POST",
                url: "/msa_test/wp-content/themes/mainstreet-advocates/add-priority.php",
                data: ({
                    value: id
                }),
                success: function (data) {
                }
            });

        });
    });

</script>
