<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous">
</script>
<?php
// Include the library
include( '../wp-load.php' ); 
global $wpdb;

$clients = getAllClients();

?>

<form enctype="multipart/form-data" method="post" action="">
    <div class="col-md-12">
        <h2>Clients management</h2>
        <div class="form-group">
            <div class="row">
                <div class="col-md-9">
                    <div class="row">
                        <label for="client_id">Select Client:</label>
                        <select name="client_id" id="">
                            <option value="0" selected>Select Client</option>
                            <?php foreach ( $clients as $client ) { ?>
                            <option value="<?php echo $client->id; ?>">
                                <?php echo $client->client; ?>
                            </option>
                            <?php } ?>
                        </select>
                        <div id="button_select">
                        </div>
                    </div>
                </div>
                <div class="col-md-3 ">
                    <input type="submit" id="update" class="btn btn-primary" value="Update" name="submit">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <table class="table thead-dark table-striped table-bordered" id="category_table">
                    <thead>
                        <tr>
                            <th>Legislation Bills Category</th>
                            <th>Frontend</th>
                            <th>E-mail</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyid">
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <table class="table thead-dark table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Regulations Bills Category</th>
                            <th>E-mail</th>
                            <th>Frontend</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>

<script>
    $('#update').hide();

    $(document).ready(function() {

        function User(id) {
            $.ajax({
                type: "GET",
                url: "/msa_test/wp-content/plugins/users/clients_api.php",
                contentType: "application/json; charset=utf-8",
                data: {
                    'id': id
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, item) {
                        $('#category_table tbody').append('<tr><td>' + data[i].pname + '</td><td><input type="hidden" name="removefront_' + data[i].id + '" id="removefront_' + data[i].id + '" value="0"><input type="checkbox" id="' + data[i].id + '_frontend" class="front" name="' + data[i].id + '_frontend" onclick="this.previousSibling.value=1-this.previousSibling.value"></td><td><input type="hidden" name="removemail_' + data[i].id + '" id="removemail_' + data[i].id + '" value="0"><input type="checkbox" id="' + data[i].id + '_mail"class="front" name="' + data[i].id + '_mail" onclick="this.previousSibling.value=1-this.previousSibling.value"></td></tr>');

                        if (data[i].isFrontActive === '1') {
                            $('#'+ data[i].id + '_frontend').prop('checked', true);
                        }

                        if (data[i].isMailActive === '1') {
                            $('#'+ data[i].id + '_mail').prop('checked', true);
                        }
                    });
                },
                complete: function() {}
            });
        }

        $('select').on('change', function() {
            $("#tbodyid").empty();
            var id = $(this).val()
            User(id);
            if(!$('#button_select').is(':empty')){
                $('#button_select').empty();
            }
            $("#button_select").append("<a href='admin.php?page=Clients+users&id="+id+"'><input type='button' value='Edit Users' /></a>");
            $('#update').show();
        });




    });

</script>
<?php
if (isset($_POST['submit']) and $_POST['submit'] === 'Update' and isset($_POST['client_id'])) {
        $client_id = $_POST['client_id'];
        foreach ($_POST as $field => $value){
            if (strpos($field,'removefront_') !== false and $value == 1){
                updateprofileForClient($field,"isFrontActive",$client_id);
            }else if(strpos($field,'removemail_') !== false and $value == 1){
	            updateprofileForClient($field,"isMailActive",$client_id);
            }
        }
}

?>
