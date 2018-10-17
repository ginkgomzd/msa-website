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
        <h2>User management</h2>
        <div class="form-group">
            <div class="row">
                <div class="col-md-9">
                    <div class="row">
                        <label for="user_id">Select user:</label>
                        <select name="user_id" id="">
                            <option value="0" selected>select user</option>
                            <?php foreach ( $clients as $client ) { ?>
                            <option value="<?php echo $client->client; ?>">
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

        function User(name) {
            $.ajax({
                type: "GET",
                url: "/msa_test/wp-content/plugins/users/clients_api.php",
                contentType: "application/json; charset=utf-8",
                data: {
                    'name': name
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, item) {
                        $('#category_table tbody').append('<tr><td><input type="text" name="count" value="' + i + '"><input type="text" name="remove' + i + '" id="remove' + i + '"><input type="text" name="id' + i + '" id="id" value="' + i + '"></td><td>' + data[i].pname + '</td><td><input type="checkbox" id="lfront' + i + '" class="front" name="lfront' + i + '"></td><td><input type="checkbox" id="lmail' + i + '" class="front" name="lmail' + i + '"></td></tr>');

                        if (data[i].isFrontActive === '1') {
                            $('#lfront' + i + '').prop('checked', true);
                        }

                        if (data[i].isMailActive === '1') {
                            $('#lmail' + i + '').prop('checked', true);
                        }

                        $('#lfront' + i + '').click(function() {
                            $('#remove' + i + '').val('remove');
                        });

                    });
                },
                complete: function() {}
            });
        }

        $('select').on('change', function() {
            $("#tbodyid").empty();
            var id = $(this).val()
            User(id);
            $("#button_select").append("<a href='admin.php?page=Clients+users&id="+id+"'><input type='button' value='Edit Users' /></a>");
            $('#update').show();
        });




    });

</script>
<?php

if (isset($_POST['submit'])) {
    $count=$_POST['count'];
    $table_name='profile_match';
 
    for($x=0; $x <= $count; $x++){
        
        if(isset($_POST['remove'.$x])){
            
            $remove=$_POST['remove'.$x];
 
            if($remove==='remove'){
                $id = $_POST['id'.$x];

                $a = $id + 1;

                $update_query="UPDATE `profile_match` SET `isFrontActive` = '0' WHERE `profile_match`.`id` = $a";
                $result = $wpdb->get_results($update_query,object);

            }
        }
    }

}

?>
