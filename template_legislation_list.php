<?php /* Template Name: Legislation_list_dashboard */ ?>
<?php
if ( ! is_user_logged_in() ) {
	auth_redirect();
} else {
	get_header();
	$user         = MSAvalidateUserRole();
	$url          = get_site_url();
	$get_r     = filter_input( INPUT_GET, 'document_type', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK );
	$get_category = filter_input( INPUT_GET, 'document_category', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK );
	$get_state    = filter_input( INPUT_GET, 'document_state', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK );
}
?>
<link rel="stylesheet" type="text/css"
      href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/theme8.css" type="text/css"
      media="screen"/>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.js"></script>
<script type="text/javascript" charset="utf8"
        src="<?php echo get_template_directory_uri() ?>/js/select2.min.js"></script>
<script type="text/javascript" src="https://addevent.com/libs/atc/1.6.1/atc.min.js" async defer></script>
<script type="text/javascript">
    window.addeventasync = function () {
        addeventatc.settings({
            appleical: {show: true, text: "iCal"},
            google: {show: true, text: "Google"},
            outlook: {show: true, text: "Outlook"},
            outlookcom: {show: false, text: "Outlook.com <em>(online)</em>"},
            yahoo: {show: false, text: "Yahoo <em>(online)</em>"}
        });
    };
    $("#list").on("mouseenter", "td", function () {
        $(this).attr('title', this.innerText);
    });
</script>
<style>
    /*td{
        font-size: 15px;
        max-width: 0;
        max-height: 20px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }*/
    tr {
        height: 20px;
    }

    .btn-clear-search-filter {
        display: none;
    }
</style>

<div class="main list-page">
    <a class="map-toggle-btn" href="<?php echo get_site_url() ?>/dashboard/"><i class="fas fa-map-marker-alt"></i></a>
    <div class="container-fluid">
        <h2>Legislations lists</h2>
        <div class="list-filter">
            <div class="form-group">
                <label>Document:</label>
                <select name="document_type" id="document_type" class="select-b" autocomplete="off">
					<?php
					$document_types = [ 'legislation', 'regulation', 'hearing' ];
					foreach ( $document_types as $type ) {
						if ( isset( $get_type ) and $get_type === $type ) {
							echo "<option value='{$type}' selected>" . ucfirst( $type ) . "</option>";
						} else {
							echo "<option value='{$type}'>" . ucfirst( $type ) . "</option>";
						}
					}
					?>
                </select>
            </div>
            <div class="form-group">
                <label>Category:</label>
                <select name="document_category" id="document_category" class="multiple" multiple="multiple"
                        autocomplete="off">
                    <option value="">Category</option>
					<?php
					foreach ( $user->user_categories as $category => $value ) {
						if ( $value['isfrontactive'] === 1 or $value['isfrontactive'] === '1' ) {
							if ( isset( $get_category ) and $get_category === $category ) {
								echo "<option value='{$category}' selected>{$category}</option>";
							} else {
								echo "<option value='{$category}'>{$category}</option>";
							}
						}
					}
					?>
                </select>
            </div>
            <div class="form-group">
                <label>State:</label>
                <select name="document_federal" id="document_federal" class="multiple" multiple="multiple"
                        autocomplete="off">
                    <option value="*">Select State</option>
					<?php
					foreach ( $user->getActiveUserStats() as $state ) {
						if ( isset( $get_state ) and $get_state === $state ) {
							echo "<option value='{$state}' selected>{$state}</option>";
						} else {
							echo "<option value='{$state}'>{$state}</option>";
						}
					}
					if ( isset( $get_state ) and $get_state === 'US' ) {
						echo '<option value="US" selected>Federal level (US states)</option>';
					} else {
						echo '<option value="US">Federal level (US states)</option>';
					}
					?>
                </select>
            </div>
            <div class="form-group">
                <label>Search:</label>
                <input type="text" id="custom_search" placeholder="Full Text Search" name="search" autocomplete="off">
            </div>
            <div class="form-group">
                <button class="btn-clear-search-filter">Clear Search Filter</button>
            </div>
        </div>
        <div id="dataTableDiv">

        </div>
    </div>
</div>

<?php get_footer(); ?>
<script>
    $(document).ready(function () {
        var state_timezones = {
            "AL": "America/Chicago",
            "AK": "America/Anchorage",
            "AS": "Pacific/Pago_Pago",
            "AZ": "America/Phoenix",
            "AR": "America/Chicago",
            "CA": "America/Los_Angeles",
            "CO": "America/Denver",
            "CT": "America/New_York",
            "DE": "America/New_York ",
            "DC": "America/New_York",
            "FL": "America/New_York",
            "GA": "America/New_York",
            "GU": "Pacific/Guam",
            "HI": "Pacific/Honolulu",
            "ID": "America/Denver",
            "IL": "America/Chicago",
            "IN": "America/Indiana/Indianapolis",
            "IA": "America/Chicago",
            "KS": "America/Chicago",
            "KY": "America/New_York",
            "LA": "America/Chicago",
            "ME": "America/New_York",
            "MH": "Pacific/Majuro",
            "MD": "America/New_York",
            "MA": "America/New_York",
            "MI": "America/Detroit",
            "MN": "America/Chicago",
            "MS": "America/Chicago",
            "MO": "America/Chicago",
            "MT": "America/Denver",
            "NE": "America/Chicago",
            "NV": "America/Los_Angeles",
            "NH": "America/New_York",
            "NJ": "America/New_York",
            "NM": "America/Denver",
            "NY": "America/New_York",
            "NC": "America/New_York",
            "ND": "America/Chicago",
            "MP": "Pacific/Saipan",
            "OH": "America/New_York",
            "OK": "America/Chicago",
            "OR": "America/Los_Angeles",
            "PW": "Pacific/Palau",
            "PA": "America/New_York",
            "PR": "America/Puerto_Rico",
            "RI": "America/New_York",
            "SC": "America/New_York",
            "SD": "America/Chicago",
            "TN": "America/Chicago",
            "TX": "America/Chicago",
            "UT": "America/Denver",
            "VT": "America/New_York",
            "VI": "America/St_Thomas",
            "VA": "America/New_York",
            "WA": "America/Los_Angeles",
            "WV": "America/New_York",
            "WI": "America/Chicago",
            "WY": "America/Denver"
        };
        var regulation_columnList = [{
            "data": "id",
            "title": "ID",
            "mRender": function (source, type, val) {
                return "<a href='<?php echo $url; ?>/regulation-detail-view/?id=" + source + "'>" + source + "</a>";
            }
        }, {
            "data": "isPriority",
            "title": "Priority",
            "mRender": function (source, type, row, val) {
                return source == '1' ? '<i class="fas fa-star"><span class="fas-star-tooltiptext">Bill has been prioritized</span></i>' : '<i class="far fa-star"><span class="far-star-tooltiptext">Bill has not been prioritized</span></i>'
            }
        }, {
            "data": "bookmark_note",
            "title": "Bookmark",
            "mRender": function (source, type, row, val) {
                return source == '1' ? '<i class="fas fa-bookmark"></i>' : '<i class="far fa-bookmark"></i>'
            }
        }, {
            "data": "state",
            "title": "State"
        }, {
            "data": "agency_name",
            "title": "Agency Name"
        }, {
            "data": "type",
            "title": "Type"
        }, {
            "data": "state_action_type",
            "title": "State Action Type"
        }, {
            "data": "register_date",
            "title": "Register Date"
        }, {
            "data": "categories",
            "title": "Categories"
        }
        ];
        var legislation_columnList = [{
            "data": "id",
            "title": "ID",
            "mRender": function (source, type, val) {
                return "<a href='<?php echo $url; ?>/detailed-view/?id=" + source + "'>" + source + "</a>";
            }
        }, {
            "data": "isPriority",
            "title": "Priority",
            "mRender": function (source, type, row, val) {
                return source == '1' ? '<i class="fas fa-star"><span class="fas-star-tooltiptext">Bill has been prioritized</span></i>' : '<i class="far fa-star"><span class="far-star-tooltiptext">Bill has not been prioritized</span></i>'
            }
        }, {
            "data": "bookmark_note",
            "title": "Bookmark Note",
            "mRender": function (source, type, row, val) {
                return source == '1' ? '<i class="fas fa-bookmark"></i>' : '<i class="far fa-bookmark"></i>'
            }
        }, {
            "data": "state",
            "title": "State"
        }, {
            "data": "session",
            "title": "Session"
        }, {
            "data": "type",
            "title": "Type"
        }, {
            "data": "number",
            "title": "Number"
        }, {
            "data": "sponsor_name",
            "title": "Sponsor",
            "mRender": function (source, type, row, val) {
                return "<a href='" + row.sponsor_url + "' target='_blank'>" + source + "</a>";
            }
        }, {
            "data": "title",
            "title": "Title",
            "mRender": function (source, type, row, val) {
                return "<a href='<?php echo $url; ?>/detailed-view/?id=" + row.id + "'>" + source + "</a>";
            }
        }, {
            "data": "abstract",
            "title": "Abstract"
        }, {
            "data": "last_update_date",
            "title": "Updated"
        }, {
            "data": 'status_val',
            "title": "Status"
        }, {
            "data": 'categories',
            "title": "Categories"
        }, {
            "data": 'status_standard_val',
            "title": "Latest action"
        }
        ];
        var hearings_columnList = [
            {
                "data": "id",
                "title": "ID",
                "mRender": function (source, type, val) {
                    return "<a href='<?php echo $url; ?>/hearing-detailed-view/?id=" + source + "'>" + source + "</a>";
                }
            }, {
                "data": "isPriority",
                "title": "Priority",
                "mRender": function (source, type, row, val) {
                    return source == '1' ? '<i class="fas fa-star"><span class="fas-star-tooltiptext">Bill has been prioritized</span></i>' : '<i class="far fa-star"><span class="far-star-tooltiptext">Bill has not been prioritized</span></i>'
                }
            }, {
                "data": "bookmark_note",
                "title": "Bookmark",
                "mRender": function (source, type, row, val) {
                    return source == '1' ? '<i class="fas fa-bookmark"></i>' : '<i class="far fa-bookmark"></i>'
                }
            }, {
                "data": "date",
                "title": "Date"
            }, {
                "data": "time",
                "title": "Time"
            }, {
                "data": "house",
                "title": "House"
            }, {
                "data": "committee",
                "title": "Committee"
            }, {
                "data": "place",
                "title": "Place"
            }, {
                "data": null,
                "title": "Action",
                "mRender": function (source, type, row, val) {
                    addeventatc.refresh();
                    var leg_title = ('leg_title' in row) ? row.leg_title : "No Information";
                    return '<div title="Add to Calendar" class="addeventatc" data-styling="none">\n' +
                        '\t    <img src="<?php echo get_template_directory_uri();?>/gfx/icon-calendar-t1.svg" alt="" style="width:18px;" />\n' +
                        '\t    <span class="start">' + row.date + ' ' + row.time + '</span>\n' +
                        '\t    <span class="timezone">' + state_timezones[row.state] + '</span>\n' +
                        '\t    <span class="title">Hearing - ' + row.committee + '</span>\n' +
                        '\t    <span class="location">' + row.place + '</span>\n' +
                        '\t    <span class="description">Bill Legislation Number: ' + row.external_id + ' <br>Bill/legislation title: ' + leg_title + ' </span>\n' +
                        '\t    <span class="all_day_event">true</span>\n' +
                        '\t</div>';

                }
            }
        ];

        function loadDataTable(type) {
            var dataSrc = [];
            //var searchcolumns = [];
            $('#dataTableDiv').empty();
            $('#dataTableDiv').append('<table cellpadding="0" cellspacing="0" border="0" class="dataTable table table-striped display"  width="100%" id="list"> </table>');
            $('.container-fluid>h2').text(type.charAt(0).toUpperCase() + type.slice(1) + ' list');
            var columnList = "";

            if (type == 'legislation') {
                columnList = legislation_columnList;
                //searchcolumns = [7, 8, 9, 11, 12, 13];
            } else if (type == 'regulation') {
                columnList = regulation_columnList;
                //searchcolumns = [4, 5, 6, 8];
            } else if (type == 'hearing') {
                columnList = hearings_columnList;
                //searchcolumns = [4, 5, 6];
            }
            var ajax_url = '<?php echo $url; ?>/wp-content/themes/mainstreet-advocates/legapi.php?cat=' + type;
            if ($("#document_category").val()) {
                ajax_url += '&category=' + $("#document_category").val();
            }
            if ($("#document_federal").val()) {
                ajax_url += '&federal=' + $("#document_federal").val();
            }
            if ($("#custom_search").val()) {
                ajax_url += '&text_filter=' + $("#custom_search").val();
            }
            (myTable = $('#list')).DataTable({
                dom: 'Bfrtip',
                responsive: true,
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
                            columns: ':visible',
                        },
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    },
                    'colvis'
                ],
                "Processing": true,
                "serverSide": true,
                //"sPaginationType": "full_numbers",
                "ajax": ajax_url,
                aoColumns: columnList,
                responsive: true,
                paging: true,
                searching: false,
                "columnDefs": [
                    (type === 'legislation' ? {"orderable": false, "targets": [1, 2, 10, 12]} : {
                        "orderable": false,
                        "targets": [1, 2, -1]
                    })
                    //{ "orderable": false, "targets": [1, 2,10,12] }
                ]
                /*'initComplete': function () {
                   /* var api = this.api();

                    // Populate a dataset for autocomplete functionality
                    // using data from first, second and third columns
                    api.cells('tr', searchcolumns).every(function () {
                        // Get cell data as plain text
                        var data = $('<div>').html(this.data()).text();
                        if (dataSrc.indexOf(data) === -1) {
                            dataSrc.push(data);
                        }
                    });

                    // Sort dataset alphabetically
                    dataSrc.sort();

                    // Initialize Typeahead plug-in
                    $('.dataTables_filter input[type="search"]', api.table().container())
                        .typeahead({
                                source: dataSrc,
                                afterSelect: function (value) {
                                    api.search(value).draw();
                                }
                            }
                        );
                }*/
            }), myTable.on("click", ".fa-star", function () {
                //handle priority clicked
                var id = $(this).closest("tr").find("td:first a").html();
                var element = $(this);
                var status = "enable";
                if (element.hasClass('fas')) {
                    status = "disable";
                }
                var type = $('#document_type').val();
                if (type === '') {
                    alert("Please select type of document first!");
                    return;
                }
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $url; ?>/wp-content/themes/mainstreet-advocates/legapi.php',
                    data: {"id": id, "type": type, "action": "priority", "status": status},
                    dataType: 'JSON',
                    success: function (response) {
                        if (status === 'disable') {
                            element.removeClass('fas').addClass('far');
                        } else {
                            element.removeClass('far').addClass('fas');
                        }
                    }
                })
            })

        }

        loadDataTable($('#document_type').val());


        $('#document_type').on('change', function () {
            if (this.value) {
                var value = this.value;
                loadDataTable(value);
            }
        });

        $('#document_category').on('change', function () {
            if ($('#document_type').val() != '') {
                loadDataTable($('#document_type').val());
            } else {
                alert("Please select type of document first!");
            }
        });

        $('#document_federal').on('change', function () {
            if ($('#document_type').val() != '') {
                loadDataTable($('#document_type').val());
            } else {
                alert("Please select type of document first!");
            }
        });

        $('.btn-clear-search-filter').on('click', function () {
            $('#custom_search').val('');
            loadDataTable($('#document_type').val());
            $('.btn-clear-search-filter').hide();
        });

        $('.select-b').select2({
                placeholder: {
                    id: '', // the value of the option
                    text: 'Select an option',
                }
            }
        );

        /*$('#custom_search').on( 'keyup', function (e) {
            //if(e.which == 13) {
            if($(this).val().length > 3) {
                if ($('#document_type').val() != '') {
                    //loadDataTable($('#document_type').val());
                    $('.btn-clear-search-filter').show();
                } else {
                    alert("Please select type of document first!");
                }
            }
            //}
        } );*/

        $('#custom_search').typeahead({
            minLength: 3,
            source: function (query, process) {
                return $.post(
                    '<?php echo $url; ?>/wp-content/themes/mainstreet-advocates/legapi.php',
                    { searchFilter: query },
                    function (data) {
                        return process(data);
                    },'json');
            }

        }).on( 'keyup', function (e) {
            if(e.which == 13) {
                if ($('#document_type').val() != '') {
                    loadDataTable($('#document_type').val());
                    $('.btn-clear-search-filter').show();
                } else {
                    alert("Please select type of document first!");
                }
            }
        } );

        $('.multiple').select2({
            multiple: true,
            placeholder: {
                id: '1', // the value of the option
                text: 'Select an option',
            },
            tokenSeparators: [',', ' ']

        });
    });

</script>
