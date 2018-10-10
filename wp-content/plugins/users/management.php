<?php
// Include the library
include( '../wp-load.php' ); 
global $wpdb;

$args = array(
    'orderby' => 'user_nicename',
    'order'   => 'ASC'
);
$users = get_users( $args );
?>


<form enctype="multipart/form-data" method="post" action="">
    <div class="col-md-12">
        <h2>User management</h2>
                <div class="form-group">
  <label for="user_id">Select user:</label>
        <select name="user_id" id="">
            <?php foreach ( $users as $user ) { ?>
                    <option value="<?php echo $user->id ?>"><?php echo $user->display_name ?></option>
            <?php } ?>
        </select>
       </div>
        <div class="row">
            <div class="col-md-2">
                <table class="table thead-dark table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>State</th>
                            <th>Published</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-5">
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
                        $categories = explode(",",getCategoriesByClient('adm'));
//                        var_dump($categories);
                        foreach ($categories as $category) {
                           
                        ?>
                        <tr>
                            <td><input type="text" name="category<?php echo $i ?>" value="<?php echo $category; ?>" readonly class="col-md-12"></td>
                            <td><input type="checkbox" name="front<?php echo $i ?>"></td>
                            <td><input type="checkbox" name="mail<?php echo $i ?>"></td> 
                        </tr>
                      <?php $i++;  };  ?>
                    </tbody>
                </table>
            </div>            
            <div class="col-md-5">
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
                            <td>Legislation document</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Regulation document</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="submit" class="btn btn-primary" value="Upload" name="submit">
</form>

<?php

if (isset($_POST['submit'])) {
    $table_name='user_profile';
    $user_id=$_POST['user_id'];
    
    global $wpdb;

    for($a=1; $a<$i; $a++){
        
            $cat=($_POST['category'.$a]);
            $front=($_POST['front'.$a]);
            $mail=($_POST['mail'.$a]);
                
                if($mail==='on'){
                    $wpdb->insert($table_name, array(
                            'user_id' => $user_id,
                            'mail' => $cat
                            ),array(
                            '%s',
                            '%s')
                    );
                };
        
                if($front==='on'){
                    $wpdb->insert($table_name, array(
                            'user_id' => $user_id,
                            'front' => trim($cat)
                            ),array(
                            '%s',
                            '%s')
                    );
                };
    }
        


}
 
 
?>
