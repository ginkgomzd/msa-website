<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous">
</script>
<?php
// Include the library
include( '../wp-load.php' ); 
global $wpdb;

$args = array(
    'orderby' => 'user_nicename',
    'order'   => 'ASC'
);
$users = get_users( $args );
$json = file_get_contents(get_site_url().'/wp-content/themes/mainstreet-advocates/states.json');
$states=json_decode($json); 
?>

<form enctype="multipart/form-data" method="post" action="">
    <div class="col-md-12">
        <h2>User management</h2>
        <div class="form-group">
            <div class="row">
               <div class="col-md-9">
                <label for="user_id">Select user:</label>
                <select name="user_id" id="">
                    <option value="0" selected>select user</option>
                    <?php foreach ( $users as $user ) { ?>
                    <option value="<?php echo $user->id ?>">
                        <?php echo $user->display_name ?>
                    </option>
                    <?php } ?>
                </select>
                </div>
                <div class="col-md-3 ">
                    <input type="submit" id="update" class="btn btn-primary"  value="Update" name="submit">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <table class="table thead-dark table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>State</th>
                            <th>Published</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $s=0;
                        foreach($states as $state){ 
                        $s++;
                        ?>
                        <tr>
                            <td>
                                <input type="hidden" name="state_abr<?php echo $s ?>" value="<?php echo $state->abbreviation ?>">
                                <input type="text" name="state_name<?php echo $s ?>" value="<?php echo $state->name ?>" readonly></td>
                            <td><input type="checkbox" class="state" id="US-<?php echo $state->abbreviation; ?>" class="state" name="state_publish<?php echo $s ?>"></td>
                        </tr>
                        <?php  } ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <table class="table thead-dark table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Legislation Bills Category</th>
                            <th>Frontend</th>
                            <th>E-mail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
                        
                        $categories = explode(",",getCategoriesByClient('Intuit'));
                        //var_dump($categories);
                        foreach ($categories as $category) {
                                    $leg_string = str_replace(' ', '', $category);
                        ?>
                        <tr>
                            <td><input type="text" class="category" name="category<?php echo $i ?>" value="<?php echo trim($category); ?>" readonly class="col-md-12"></td>
                            <td><input type="checkbox" id="lf<?php echo trim($leg_string); ?>" class="front" name="lfront<?php echo $i ?>"></td>
                            <td><input type="checkbox" id="mf<?php echo trim($leg_string); ?>" class="mail" name="lmail<?php echo $i ?>"></td>
                        </tr>
                        <?php $i++;  };  ?>
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
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
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
                url: "/msa_test/wp-content/plugins/users/users_api.php",
                contentType: "application/json; charset=utf-8",
                data: {
                    'id': id
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, item) {
                        var state = (data[i].state);
                        var front = (data[i].lfront);
                        if(front !== null){
                          var stripFront = front.replace(/ /g,'');
                        }

                        var mail = (data[i].lmail);
                         if(mail !== null){
                          var stripMail = mail.replace(/ /g,'');
                        }
                        

                        $("#US-" + state + "").prop('checked', true);
                        $("#lf" + stripFront + "").prop('checked', true);
                        $("#mf" + stripMail + "").prop('checked', true);

                    });
                },
                complete: function() {}
            });
        }

        $('select').on('change', function() {
            $('#update').show();
            var id = $(this).val()
            $(".state").prop('checked', false);
            $(".front").prop('checked', false);
            $(".mail").prop('checked', false);
            User(id);
        });

    });

</script>
<?php

if (isset($_POST['submit'])) {
    $table_name='user_profile';
    $user_id=$_POST['user_id'];
    
    global $wpdb;
    $wpdb->delete( $table_name, array( 'user_id' => $user_id ) );
    
    for($x=1; $x<$s; $x++){
        
            $state_name=($_POST['state_name'.$x]);
            $state_abr=($_POST['state_abr'.$x]);
            $state_publish=($_POST['state_publish'.$x]);

            if($state_publish==='on'){
                    $wpdb->insert($table_name, array(
                            'user_id' => $user_id,
                            'state' => $state_abr
                            ),array(
                            '%s',
                            '%s')
                    );
                };
        
    }

    for($a=1; $a<$i; $a++){
            $cat=($_POST['category'.$a]);
            $front=($_POST['lfront'.$a]);
            $mail=($_POST['lmail'.$a]);

                if($mail==='on'){
                    $wpdb->insert($table_name, array(
                            'user_id' => $user_id,
                            'lmail' => $cat
                            ),array(
                            '%s',
                            '%s')
                    );
                };
        
                if($front==='on'){
                    $wpdb->insert($table_name, array(
                            'user_id' => $user_id,
                            'lfront' => $cat
                            ),array(
                            '%s',
                            '%s')
                    );
                };
    }

}

?>