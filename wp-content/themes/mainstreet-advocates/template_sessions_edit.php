<?php
/**
 * Created by PhpStorm.
 * User: ljubi
 * Date: 12/14/2018
 * Time: 11:54 AM
 */
/* Template Name: Sessions_edit */

if ( ! is_user_logged_in() ) {
	auth_redirect();
} else {
	if ( !current_user_can( 'edit_pages' ) ){
		wp_die(	'<h1>' . __( 'Not allowed to access this page.' ) . '</h1>',
			500);
	}
	if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
		//include( '../../../wp-load.php' );
		global $wpdb;
		$data = array();
		foreach ( $_POST as $key => $value ) {
			if ($key === 'is_active' && $value === 'on'){
				$data[$key] = 1;
			}else if(!isset($_POST['is_active'])){
				$data['is_active'] = 0;
			}else if ( $key !== 'id' ) {
				$data[ $key ] = $value;
			}

			if ($key === 'carryover' && $value === 'on'){
				$data[$key] = 1;
			}else if(!isset($_POST['carryover'])){
				$data['carryover'] = 0;
			}
		}
		$wpdb->update( 'session_info', $data, array( 'id' => $_POST['id'] ) );
		wp_redirect( site_url() . '/sessions-list/');
		exit;
	}
	if ( isset( $_GET['id'] ) ) {
		get_header();
		$user = MSAvalidateUserRole();
		$data = $user->getSessionInformationEdit($_GET['id']);
	}else{
		wp_die(	'<h1>' . __( 'Missing ID of session.' ) . '</h1>',
			500);
    }
}
?>
<div class="container-fluid">
    <div class="col-md-6">
        <form action="" method="POST">
            <input type="text" hidden name="id" value="<?php echo esc_html($data->id); ?>">
            <div class="form-group row">
                <label for="session_name" class="col-sm-2 col-form-label">Session Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="session_name" placeholder="Session Name"
                           name="session_name" value="<?php echo esc_html($data->session_name); ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="session_info" class="col-sm-2 col-form-label">Info</label>
                <div class="col-sm-10">
                    <textarea class="form-control" rows="5" id="session_info" name="session_info"
                              placeholder="Info"><?php echo esc_html($data->session_info); ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label for="session_state" class="col-sm-2 col-form-label">State</label>
                <div class="col-sm-10">
                    <select class="form-control" id="session_state" name="session_state">
                        <option value="">Select State</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="start_date" class="col-sm-2 col-form-label">Start Date</label>
                <div class="col-10">
                    <input type="date" id="start_date" name="start_date" value="<?php echo esc_html($data->start_date); ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="end_date" class="col-sm-2 col-form-label">End Date</label>
                <div class="col-10">
                    <input type="date" id="end_date" name="end_date" value="<?php echo $data->end_date; ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="prefiling" class="col-sm-2 col-form-label">Prefiling</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="prefiling" name="prefiling" placeholder="Prefiling"
                           value="<?php echo esc_html($data->prefiling); ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="convene_date" class="col-sm-2 col-form-label">Convene date</label>
                <div class="col-sm-10">
                    <input type="date" id="convene_date" name="convene_date" value="<?php echo $data->convene_date;?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="adjourn_date" class="col-sm-2 col-form-label">Adjourn date</label>
                <div class="col-sm-10">
                    <input type="date" id="adjourn_date" name="adjourn_date" value="<?php echo $data->adjourn_date;?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="additional_info" class="col-sm-2 col-form-label">Additional info</label>
                <div class="col-sm-10">
                    <textarea class="form-control" rows="5" id="additional_info" name="additional_info"
                              placeholder="Additional info...."><?php echo esc_html($data->additional_info); ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">Session Carry Over</div>
                <div class="col-sm-10">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="carryover" name="carryover"
	                        <?php echo( $data->carryover === '1' ? 'checked' : '' ); ?>>
                        <label class="form-check-label" for="carryover">
                            Yes
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">Secion Active</div>
                <div class="col-sm-10">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active"
                               name="is_active" <?php echo( $data->is_active === '1' ? 'checked' : '' ); ?>>
                        <label class="form-check-label" for="is_active">
                            Yes
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary" value="update">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        var states = {
            "AL": "Alabama",
            "AK": "Alaska",
            "AS": "American Samoa",
            "AZ": "Arizona",
            "AR": "Arkansas",
            "CA": "California",
            "CO": "Colorado",
            "CT": "Connecticut",
            "DE": "Delaware",
            "DC": "District Of Columbia",
            "FM": "Federated States Of Micronesia",
            "FL": "Florida",
            "GA": "Georgia",
            "GU": "Guam",
            "HI": "Hawaii",
            "ID": "Idaho",
            "IL": "Illinois",
            "IN": "Indiana",
            "IA": "Iowa",
            "KS": "Kansas",
            "KY": "Kentucky",
            "LA": "Louisiana",
            "ME": "Maine",
            "MH": "Marshall Islands",
            "MD": "Maryland",
            "MA": "Massachusetts",
            "MI": "Michigan",
            "MN": "Minnesota",
            "MS": "Mississippi",
            "MO": "Missouri",
            "MT": "Montana",
            "NE": "Nebraska",
            "NV": "Nevada",
            "NH": "New Hampshire",
            "NJ": "New Jersey",
            "NM": "New Mexico",
            "NY": "New York",
            "NC": "North Carolina",
            "ND": "North Dakota",
            "MP": "Northern Mariana Islands",
            "OH": "Ohio",
            "OK": "Oklahoma",
            "OR": "Oregon",
            "PW": "Palau",
            "PA": "Pennsylvania",
            "PR": "Puerto Rico",
            "RI": "Rhode Island",
            "SC": "South Carolina",
            "SD": "South Dakota",
            "TN": "Tennessee",
            "TX": "Texas",
            "UT": "Utah",
            "VT": "Vermont",
            "VI": "Virgin Islands",
            "VA": "Virginia",
            "WA": "Washington",
            "WV": "West Virginia",
            "WI": "Wisconsin",
            "WY": "Wyoming"
        };

        function populateStates() {
            for (var value in states) {
                if (value == <?php echo "'$data->session_state'";?>) {
                    $('#session_state').append('<option selected value="' + value + '">' + states[value] + '</option>');
                } else {
                    $('#session_state').append('<option value="' + value + '">' + states[value] + '</option>');
                }
            }

        }

        populateStates();
    });
</script>
<?php
if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	//include( '../../../wp-load.php' );
	global $wpdb;
	$data = array();
	foreach ( $_POST as $key => $value ) {
		if ($key === 'is_active' && $value === 'on'){
			$data[$key] = 1;
		}else if(!isset($_POST['is_active'])){
			$data['is_active'] = 0;
		}else if ( $key !== 'id' ) {
			$data[ $key ] = $value;
		}

		if ($key === 'carryover' && $value === 'on'){
			$data[$key] = 1;
		}else if(!isset($_POST['carryover'])){
			$data['carryover'] = 0;
        }
	}
	$wpdb->update( 'session_info', $data, array( 'id' => $_POST['id'] ) );
}

?>

<?php get_footer() ?>
