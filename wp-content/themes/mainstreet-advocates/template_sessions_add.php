<?php
/**
 * Created by PhpStorm.
 * User: ljubi
 * Date: 12/14/2018
 * Time: 10:11 AM
 */
/* Template Name: Sessions_add */



if ( ! is_user_logged_in() ) {
	auth_redirect();
} else {
	if ( ! current_user_can( 'edit_pages' ) ) {
		wp_die( '<h1>' . __( 'Not allowed to access this page.' ) . '</h1>',
			500 );
	}

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	//include( '../../../wp-load.php' );
	global $wpdb;
	$data = array();
	foreach ( $_POST as $key => $value ) {
		if ( $key === 'is_active' && $value === 'on' ) {
			$data[ $key ] = 1;
		} else {
			$data[ $key ] = $value;
		}
		if ( $key === 'carryover' && $value === 'on' ) {
			$data[ $key ] = 1;
		}
	}
	$wpdb->insert( 'session_info', $data );
	if ( $wpdb->insert_id ) {
		$session_id = $wpdb->insert_id;
		//update legislation
		$wpdb->update( 'legislation', array( "session_id" => $session_id ), array(
			"session" => $data['session_year'],
			"state"   => $data['session_state']
		) );
	}
	wp_redirect( site_url() . '/sessions-list/');
	exit;
}

	get_header();
	$user = MSAvalidateUserRole();
	$sesions_list = $user->getSessitionListFromLegislation();

}
?>

    <div class="container">
        <div class="row" style="display: block;">
            <div class="col-md-10">
                <h2 class="mt-5 mb-5">Add new session</h2>
                <form action="" method="POST">
                    <div class="form-group row">
                        <label for="session_name" class="col-md-3 col-lg-2 col-form-label">Session Name</label>
                        <div class="col-md-9 col-lg-10">
                        <input type="text" class="form-control col-md-8" id="session_name" placeholder="Session Name" name="session_name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="session_info" class="col-md-3 col-lg-2 col-form-label">Info</label>
                        <div class="col-md-9 col-lg-10">
                        <textarea class="form-control" rows="5" id="session_info" name="session_info" placeholder="Info"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="session_state" class="col-md-3 col-lg-2 col-form-label">Sessions</label>
                        <div class="col-md-9 col-lg-10">
                        <select class="form-control" id="session_year" name="session_year">
                        <option value="">Select Session</option>
						<?php foreach ( $sesions_list as $session ) { ?>
                            <option value="<?php echo $session->session ?>"><?php echo $session->session ?></option>
						<?php } ?>
                    </select>
                    </div>
                    </div>
                    <div class="form-group row">
                        <label for="session_state" class="col-md-3 col-lg-2 col-form-label">State</label>
                        <div class="col-md-9 col-lg-10">
                        <select class="form-control" id="session_state" name="session_state">
                        <option value="">Select State</option>
                    </select>
                    </div>
                    </div>
                    <div class="form-group row">
                        <label for="start_date" class="col-md-3 col-lg-2 col-form-label">Start Date</label>
                        <div class="col-md-9 col-lg-10">
                        <input type="date" id="start_date" name="start_date">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="end_date" class="col-md-3 col-lg-2 col-form-label">End Date</label>
                        <div class="col-md-9 col-lg-10">
                        <input type="date" id="end_date" name="end_date">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="prefiling" class="col-md-3 col-lg-2 col-form-label">Prefiling</label>
                        <div class="col-md-9 col-lg-10">
                        <input type="text" class="form-control" id="prefiling" name="prefiling" placeholder="Prefiling">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="convene_date" class="col-md-3 col-lg-2 col-form-label">Convene date</label>
                        <div class="col-md-9 col-lg-10">
                        <input type="date" id="convene_date" name="convene_date">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="adjourn_date" class="col-md-3 col-lg-2 col-form-label">Adjourn date</label>
                        <div class="col-md-9 col-lg-10">
                        <input type="date" id="adjourn_date" name="adjourn_date">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="additional_info" class="col-md-3 col-lg-2 col-form-label">Additional info</label>
                        <div class="col-md-9 col-lg-10">
                        <textarea class="form-control" rows="5" id="additional_info" name="additional_info" placeholder="Additional info...."></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3 col-lg-2 col-sm-2">Session Carry Over</div>
                        <div class="col-md-9 col-lg-10 col-sm-10">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="carryover" name="carryover">
                                <label class="form-check-label" for="carryover">
                            Yes
                        </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3 col-lg-2 col-sm-2">Session Active</div>
                        <div class="col-md-3 col-lg-2 col-sm-10">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active">
                                <label class="form-check-label" for="is_active">
                            Yes
                        </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="text-right">
                            <button type="button" class="button grey" value="Cancel">Cancel</button>
                            <button type="submit" class="button mr-0 gradient-bg" value="submit">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
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

            function populateStates(session) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo get_site_url() ?>/wp-content/themes/mainstreet-advocates/sessionapi.php',
                    data: {
                        "action": 'query',
                        "session": session
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        $.each(response, function(index, row) {
                            if (row['state'] in states) {
                                $('#session_state').append('<option value="' + row['state'] + '">' + states[row['state']] + '</option>');
                            }
                        })
                    }
                })

            }

            $('#session_year').on('change', function() {
                $('#session_state').empty().append('<option selected="selected" value="">Slect state</option>');
                if (this.value) {
                    populateStates(this.value);
                }
            });
        });
    </script>

    <?php get_footer() ?>