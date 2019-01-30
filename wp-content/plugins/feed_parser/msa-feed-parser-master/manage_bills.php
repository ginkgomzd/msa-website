<?php

include( '../wp-load.php' );

$clients = getAllClients();

?>
<script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
      integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<div class="alert alert-success" style="display: none;">
</div>
<div class="alert alert-danger"  style="display: none;">
</div>
<form enctype="multipart/form-data" method="post" action="">
    <div class="form-row">
        <div class="form-group col-md-3">
            <label for="update_status">Status:</label>
            <select id="update_status" class="form-control" autocomplete="off">
                <option value="">Select Update</option>
                <option value="last_update" selected>Last Update</option>
                <option value="last_7">Last 7 Days</option>
                <option value="last_30">Last 30 Days</option>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="client_id">Client:</label>
            <select id="client_id" class="form-control" autocomplete="off">
                <option value="" selected>Select Client</option>
				<?php foreach ( $clients as $client ) { ?>
                    <option value="<?php echo $client->id; ?>">
						<?php echo $client->client; ?>
                    </option>
				<?php } ?>
            </select>
        </div>
        <div class="form-group  col-md-3 ">
            <input type="text" id="approve_upload_import_ids" hidden>
            <input type="submit" id="approve_upload" class="btn btn-primary" value="Approve Upload" name="submit">
        </div>
    </div>
</form>
<div class="content-area">
    <div class="row">
        <h4>Legislation list: Total <span class="legislationcount">0</span> for this import </h4>
        <table class="table table-bordered legislation-table">
            <thead class="thead-light">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">State</th>
                <th scope="col">Session</th>
                <th scope="col">Type</th>
                <th scope="col">Number</th>
                <th scope="col">Title</th>
                <th scope="col">Categories</th>
                <th scope="col">Priority</th>
                <th scope="col">Hide</th>
                <th scope="col">Add Note</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <br>
    <div class="row">
        <h4>Regulation list: Total <span class="regulationlistcount">0</span> for this import </h4>
        <table class="table table-bordered regulation-table">
            <thead class="thead-light">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">State</th>
                <th scope="col">Agency Name</th>
                <th scope="col">Type</th>
                <th scope="col">State Action Type</th>
                <th scope="col">Description</th>
                <th scope="col">Register Date</th>
                <th scope="col">Register Citation</th>
                <th scope="col">Priority</th>
                <th scope="col">Hide</th>
                <th scope="col">Add Note</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <br>
    <div class="row">
        <h4>Hearing list: Total <span class="hearinglistcount">0</span> for this import </h4>
        <table class="table table-bordered hearing-table">
            <thead class="thead-light">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Date</th>
                <th scope="col">Time</th>
                <th scope="col">House</th>
                <th scope="col">Committee</th>
                <th scope="col">Place</th>
                <th scope="col">Priority</th>
                <th scope="col">Hide</th>
                <th scope="col">Add Note</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <br>
    <div class="col-md-10">
        <form action="" method="POST">
            <div class="form-group col-md-3">
                <label for="user_list">Client Users:</label>
                <select id="user_list" class="form-control" autocomplete="off">
                    <option value="" selected>Select User</option>
                </select>
            </div>
            <div class="form-group col-md-10">
                <label for="additional_info" class="col-sm-2 col-form-label">Comments</label>
                <div class="col-md-12">
                    <textarea class="form-control" rows="5" id="bill_comments" name="bill_comments"
                              placeholder="Comments for email.."></textarea>
                </div>
            </div>
            <div class="form-group col-md-10">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="gridCheck">
                    <label class="form-check-label" for="gridCheck">
                        Select All Users in client
                    </label>
                </div>
            </div>
            <div class="form-group  col-md-3 ">
                <input type="submit" id="update" class="btn btn-primary" value="Send Email" name="submit">
            </div>
        </form>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" id="note_bill_id" hidden>
                <input type="text" id="note_bill_type" hidden>
                <textarea name="name" rows="12" cols="45" placeholder="Enter text here..."
                          id='new_notes_text'></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="new_note_add">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var upload_import_ids;
        var legislationTable = $('.legislation-table').DataTable({
            dom: 'Bfrtip',
            responsive: true,
            searching: false,
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        //return data +' ('+ row[0]+')';
                        if (data !== null) {
                            return '<input type="checkbox" data-id="' + row[0] + '" data-type="legislation" id="bill_prioritization" name="bill_prioritization" checked/>';
                        } else {
                            return '<input type="checkbox" data-id="' + row[0] + '" data-type="legislation" id="bill_prioritization" name="bill_prioritization"/>';
                        }
                    },
                    "targets": 7
                },
                {
                    "render": function (data, type, row) {
                        //return data +' ('+ row[0]+')';
                        if (data !== null) {
                            return '<input type="checkbox" data-id="' + row[0] + '" data-type="legislation" id="bill_hiding" name="bill_hiding" checked/>';
                        } else {
                            return '<input type="checkbox" data-id="' + row[0] + '" data-type="legislation" id="bill_hiding" name="bill_hiding"/>';
                        }
                    },
                    "targets": 8
                },
                {
                    "render": function (data, type, row) {
                        //return data +' ('+ row[0]+')';
                        return '<a href="#" class="bill_add_note" data-id="' + row[0] + '" data-type="legislation">Add Note</a>';

                    },
                    "targets": 9
                }
            ]
        });

        var hearingTable = $('.hearing-table').DataTable({
            dom: 'Bfrtip',
            responsive: true,
            searching: false,
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        //return data +' ('+ row[0]+')';
                        if (data !== null) {
                            return '<input type="checkbox" data-id="' + row[0] + '" data-type="hearing" id="bill_prioritization" name="bill_prioritization" checked/>';
                        } else {
                            return '<input type="checkbox" data-id="' + row[0] + '" data-type="hearing" id="bill_prioritization" name="bill_prioritization"/>';
                        }
                    },
                    "targets": 6
                },
                {
                    "render": function (data, type, row) {
                        //return data +' ('+ row[0]+')';
                        if (data !== null) {
                            return '<input type="checkbox" data-id="' + row[0] + '" data-type="hearing" id="bill_hiding" name="bill_hiding" checked/>';
                        } else {
                            return '<input type="checkbox" data-id="' + row[0] + '" data-type="hearing" id="bill_hiding" name="bill_hiding"/>';
                        }
                    },
                    "targets": 7
                },
                {
                    "render": function (data, type, row) {
                        //return data +' ('+ row[0]+')';
                        return '<a href="#" class="bill_add_note" data-id="' + row[0] + '" data-type="hearing">Add Note</a>';

                    },
                    "targets": 8
                }
            ]
        });

        var regulationTable = $('.regulation-table').DataTable({
            dom: 'Bfrtip',
            responsive: true,
            searching: false,
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        //return data +' ('+ row[0]+')';
                        if (data !== null) {
                            return '<input type="checkbox" data-id="' + row[0] + '" data-type="regulation" id="bill_prioritization" name="bill_prioritization" checked/>';
                        } else {
                            return '<input type="checkbox" data-id="' + row[0] + '" data-type="regulation" id="bill_prioritization" name="bill_prioritization"/>';
                        }
                    },
                    "targets": 8
                },
                {
                    "render": function (data, type, row) {
                        //return data +' ('+ row[0]+')';
                        if (data !== null) {
                            return '<input type="checkbox" data-id="' + row[0] + '" data-type="regulation" id="bill_hiding" name="bill_hiding" checked/>';
                        } else {
                            return '<input type="checkbox" data-id="' + row[0] + '" data-type="regulation" id="bill_hiding" name="bill_hiding"/>';
                        }
                    },
                    "targets": 9
                },
                {
                    "render": function (data, type, row) {
                        //return data +' ('+ row[0]+')';
                        return '<a href="#" class="bill_add_note" data-id="' + row[0] + '" data-type="regulation">Add Note</a>';

                    },
                    "targets": 10
                }
            ]
        });

        $("table").on("click", "#bill_prioritization", function () {
            var status = 'enable';
            if (!$(this).is(':checked')) {
                status = 'disable'
            }
            var data = {
                id: $(this).data('id'),
                type: $(this).data('type'),
                action: "priority",
                status: status,
                client_id: $('#client_id').val()
            };
            $.ajax({
                type: 'POST',
                url: '<?php echo get_site_url(); ?>/wp-content/themes/mainstreet-advocates/legapi.php',
                data: data,
                dataType: 'JSON',
                success: function (response) {
                    // TODO discuss how we should handle this
                    console.log(response);
                }
            })
        });

        $("table").on("click", "#bill_hiding", function () {
            var status = 'enable';
            if (!$(this).is(':checked')) {
                status = 'disable'
            }
            var data = {
                id: $(this).data('id'),
                type: $(this).data('type'),
                action: "hide",
                status: status,
                client_id: $('#client_id').val()
            };
            $.ajax({
                type: 'POST',
                url: '<?php echo get_site_url(); ?>/wp-content/themes/mainstreet-advocates/legapi.php',
                data: data,
                dataType: 'JSON',
                success: function (response) {
                    // TODO discuss how we should handle this
                    console.log(response);
                }
            })
        });

        $("table").on("click", ".bill_add_note", function () {
            console.log($(this).data('id'));
            $('#note_bill_id').val($(this).data('id'));
            $('#note_bill_type').val($(this).data('type'));
            $('#new_notes_text').val('');
            $('.modal').modal();
        });


        $('#new_note_add').on('click',function () {
            var text_notes = $('#new_notes_text').val() //.replace(/\r\n|\r|\n/g,"\n");
            $.ajax({
                type: 'POST',
                url: '<?php echo get_site_url() ?>/wp-content/themes/mainstreet-advocates/notes_api.php',
                data: {
                    action: "add",
                    note_text: text_notes,
                    type: $('#note_bill_type').val(),
                    id: $('#note_bill_id').val(),
                    client_id: $('#client_id').val()
                },
                dataType: 'JSON',
                success: function(response) {
                   if(response.status){
                       $('.modal').modal('hide')
                   }
                }
            })
        });

        function ClientUsers(id) {
            var update_status = $('#update_status').val();
            $.ajax({
                type: "POST",
                url: "/msa_test/wp-content/plugins/users/users_api.php",
                data: {
                    'client_id': id,
                    'status': update_status
                },
                dataType: "JSON",
                success: function (data) {
                    $('#user_list').empty();
                    $('#user_list').append('<option value="" selected>Select User</option>');
                    $.each(data, function (i, item) {
                        $('#user_list').append('<option value="' + data[i].ID + '">' + data[i].user_login + '</option>')

                    });
                },
                complete: function () {
                }
            });
        }

        function getClientData(id) {
            var update_status = $('#update_status').val();
            $.ajax({
                type: "POST",
                url: "<?php echo plugins_url( '/manage_bills_api.php', __FILE__ );?>",
                data: {
                    'client_id': id,
                    'status': update_status,

                },
                dataType: "JSON",
                success: function (data) {
                    upload_import_ids = data.import_ids;
                    $('#approve_upload_import_ids').val(data.import_ids.join());
                    $('.legislation-table tbody tr').remove();
                    legislationTable.clear().draw();
                    var legislation_counter = 0;
                    $.each(data.legislation, function (i, item) {
                        legislationTable.row.add([data.legislation[i].id,
                            data.legislation[i].state,
                            data.legislation[i].session,
                            data.legislation[i].type,
                            data.legislation[i].number,
                            data.legislation[i].title,
                            data.legislation[i].categories,
                            data.legislation[i].priority,
                            data.legislation[i].hidden,
                            data.legislation[i].id]).draw(false);
                        legislation_counter++;
                    });
                    $('.legislationcount').html(legislation_counter);
                    //---- regulation
                    $('.regulation-table tbody tr').remove();
                    var regulation_counter = 0;
                    regulationTable.clear().draw();
                    $.each(data.regulation, function (i, item) {
                        regulationTable.row.add([data.regulation[i].id,
                            data.regulation[i].state,
                            data.regulation[i].agency_name,
                            data.regulation[i].type,
                            data.regulation[i].state_action_type,
                            data.regulation[i].description,
                            data.regulation[i].register_date,
                            data.regulation[i].register_citation,
                            data.regulation[i].priority,
                            data.regulation[i].hidden,
                            data.regulation[i].id]).draw(false);
                        regulation_counter++;
                    });
                    $('.regulationlistcount').html(regulation_counter);
                    //---- hearing
                    $('.hearing-table tbody tr').remove();
                    var hearing_counter = 0;
                    hearingTable.clear().draw();
                    $.each(data.hearing, function (i, item) {
                       hearingTable.row.add([
                            data.hearing[i].id,
                            data.hearing[i].date,
                            data.hearing[i].time,
                            data.hearing[i].house,
                            data.hearing[i].committee,
                            data.hearing[i].place,
                            data.hearing[i].priority,
                            data.hearing[i].hidden,
                            data.hearing[i].id
                        ]).draw(false);
                        hearing_counter++;
                    });
                    $('.hearinglistcount').html(hearing_counter);
                }
            });
        }

        $('#approve_upload').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?php echo plugins_url( '/manage_bills_api.php', __FILE__ );?>",
                data: {
                    import_ids: upload_import_ids,
                    action: 'update_import_ids',
                    client: $.trim($("#client_id :selected").text())
                },
                dataType: "JSON",
                success: function (response) {
                    if(response.status){
                        $('.alert-success').show().text(response.message).delay(3000).fadeOut();
                    }else{
                        $('.alert-danger').show().text(response.message).delay(3000).fadeOut();
                    }
                }
            });
        });

        $('#test').click(function () {
            $(".modal").modal({backdrop: true});
        });

        $('#client_id').on('change', function () {
            $("#tbodyid tr").remove();
            var id = $(this).val();
            //ClientUsers(id);
            getClientData(id);
        });
    });
</script>

