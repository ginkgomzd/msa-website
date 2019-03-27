<?php
/**
 * Created by PhpStorm.
 * User: ljubi
 * Date: 2/9/2019
 * Time: 1:19 AM
 */
include( '../../../../wp-load.php' );

class EmailPreview {
	private $wpdb;

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
	}
	/**
	 * @param $abbr
	 */
	public function abbrevationState( $abbr ) {
		$states = [
			"AL" => "Alabama",
			"AK" => "Alaska",
			"AS" => "American Samoa",
			"AZ" => "Arizona",
			"AR" => "Arkansas",
			"CA" => "California",
			"CO" => "Colorado",
			"CT" => "Connecticut",
			"DE" => "Delaware",
			"DC" => "District Of Columbia",
			"FM" => "Federated States Of Micronesia",
			"FL" => "Florida",
			"GA" => "Georgia",
			"GU" => "Guam",
			"HI" => "Hawaii",
			"ID" => "Idaho",
			"IL" => "Illinois",
			"IN" => "Indiana",
			"IA" => "Iowa",
			"KS" => "Kansas",
			"KY" => "Kentucky",
			"LA" => "Louisiana",
			"ME" => "Maine",
			"MH" => "Marshall Islands",
			"MD" => "Maryland",
			"MA" => "Massachusetts",
			"MI" => "Michigan",
			"MN" => "Minnesota",
			"MS" => "Mississippi",
			"MO" => "Missouri",
			"MT" => "Montana",
			"NE" => "Nebraska",
			"NV" => "Nevada",
			"NH" => "New Hampshire",
			"NJ" => "New Jersey",
			"NM" => "New Mexico",
			"NY" => "New York",
			"NC" => "North Carolina",
			"ND" => "North Dakota",
			"MP" => "Northern Mariana Islands",
			"OH" => "Ohio",
			"OK" => "Oklahoma",
			"OR" => "Oregon",
			"PW" => "Palau",
			"PA" => "Pennsylvania",
			"PR" => "Puerto Rico",
			"RI" => "Rhode Island",
			"SC" => "South Carolina",
			"SD" => "South Dakota",
			"TN" => "Tennessee",
			"TX" => "Texas",
			"UT" => "Utah",
			"VT" => "Vermont",
			"VI" => "Virgin Islands",
			"VA" => "Virginia",
			"WA" => "Washington",
			"WV" => "West Virginia",
			"WI" => "Wisconsin",
			"WY" => "Wyoming",
			"US" => 'Federal'
		];
		return $states[$abbr];
	}

	function generateLegislationMail( $response ) {
		$body = "";
		if ( ! empty( $response ) ) {
			foreach ( $response as $state => $bills ) {
				if ( $state !== 'bill_counter' ) {
					if ( count( $response[ $state ] ) > 1 ) {
						$value = " bills ";
					} else {
						$value = " bill ";
					}
					$body .= "<h4><b>" . $this->abbrevationState($state ) . " - " . count( $response[ $state ] ) . " " . $value . "</b></h4>";
					foreach ( $bills as $bill ) {
						$body .= '<table id="queried-bills" style="border: 1px solid gray; width:900px;">';
						$body .= '<tr>
                        <td style="border-bottom: 1px solid gray; border-right: 1px solid gray; width: 100px;"><b>Session/Number:</b></td>
                        <td style="border-bottom: 1px solid gray;" target = "_blank">
                            <b> ' . $bill->session . ' / ' . $bill->state . ' ' . $bill->type . ' ' . $bill->number . '</b>
                        </td>
                      </tr>';
						$body .= '<tr>
                          <td style="border-bottom: 1px solid gray; border-right: 1px solid gray; width: 100px;"><b>Sponsor:</b></td>
                          <td style="border-bottom: 1px solid gray;">' . $bill->sponsor_name . '</td>
                        </tr>';
						$body .= '<tr>
                          <td style="border-bottom: 1px solid gray; border-right: 1px solid gray; width: 100px;" ><b>Title:</b></td>
                          <td style="border-bottom: 1px solid gray;"><a href="' . get_site_url() . "/detailed-view/?id=" . $bill->id . '" style="color:blue; text-decoration: underline;" target = "_blank" >' . $bill->title . '</a></td>
                        </tr>';
						$body .= '	<tr>
                      <td style="border-bottom: 1px solid gray; border-right: 1px solid gray; width: 100px;"><b>Description:</b></td>
                      <td style="border-bottom: 1px solid gray;">' . $bill->abstract . '~</td>
                    </tr>';
						$body .= '	<tr>
                          <td style="border-right: 1px solid gray; width: 100px; border-bottom: 1px solid gray; " ><b>Status:</b></td>
                          <td style = "border-bottom: 1px solid gray;"><a href="' . $bill->status_url . '" style="color:blue; text-decoration: underline;" target = "_blank">' . $bill->status_val . '</a></td>
                        </tr>';
						$body .= '<tr><td style=" border-right: 1px solid gray; width: 100px; border-bottom: 1px solid gray; "><b>' . ( ( count( $bill->categories ) > 1 ) ? "Categories:" : "Category:" ) . '</b></td>
                          <td style="border-bottom: 1px solid gray;">' . implode( ", ", $bill->categories ) . '</td></tr>';
						$body .= '<tr>
                        <td style=" border-right: 1px solid gray; width: 100px; "><b>Keyword(s):</b></td>
                        <td>' . ( ! empty( $bill->keywords ) ? implode( ", ", $bill->keywords ) : "~" ) . '</td></tr>';
						$body .= '</table>';
						$body .= '<br>';
					}
				}
			}
		} else {
			if ( empty( $this->count ) || $this->count == 0 ) {
				$body .= '<p><b>No legislation was added or updated for ' . date( 'F j, Y' ) . ' </b></p><br />';
			}
		}

		return $body;
	}

	function generateRegulationMail( $response ) {
		$body = "";
		if ( ! empty( $response ) ) {
			foreach ( $response as $state => $bills ) {
				if ( $state !== 'bill_counter' ) {
					if ( count( $response[ $state ] ) > 1 ) {
						$value = " bills ";
					} else {
						$value = " bill ";
					}
					$body .= "<h4><b>" . $this->abbrevationState($state ) . " - " . count( $response[ $state ] ) . " " . $value . "</b></h4>";
					foreach ( $bills as $bill ) {
						$body .= '<table id="queried-reg"  style="border: 1px solid gray; width:900px;">';
						$body .= '<tr>
                      <td style="border-bottom: 1px solid gray; border-right: 1px solid gray; width: 100px;"><b>State: </b></td>
                      <td style="border-bottom: 1px solid gray;"><b>' . $bill->state . '</b></td>
                    </tr>';
						$body .= '	<tr>
                      <td style="border-bottom: 1px solid gray; border-right: 1px solid gray; width: 100px;"><b>Department: </b></td>
                      <td style="border-bottom: 1px solid gray;">' . $bill->agency_name . '</td>
                    </tr>';
						$body .= '	<tr>
                      <td style="border-bottom: 1px solid gray; border-right: 1px solid gray; width: 100px;"><b>Proposed: </b></td>
                      <td style="border-bottom: 1px solid gray;">' . $bill->description . '</td>
                    </tr>';
						$body .= '	<tr>
                          <td style="border-right: 1px solid gray; width: 100px; border-bottom: 1px solid gray; "><b>Register Entry:</b></td>
                          <td  style="border-bottom: 1px solid gray;" ><a href="' . $bill->full_text_url . '" style="color:blue; text-decoration: underline;" target = "_blank">Click here for link</a></td>
                        </tr>';
						$body .= '<tr><td style=" border-right: 1px solid gray; width: 100px; border-bottom: 1px solid gray; "><b>' . ( ( count( $bill->categories ) > 1 ) ? "Categories:" : "Category:" ) . '</b></td>
                          <td style="border-bottom: 1px solid gray;">' . implode( ", ", $bill->categories ) . '</td></tr>';
						$body .= '<tr>
                        <td style=" border-right: 1px solid gray; width: 100px; "><b>Keyword(s):</b></td>
                        <td>' . ( ! empty( $bill->keywords ) ? implode( ", ", $bill->keywords ) : "~" ) . '</td></tr>';
						$body .= '</table> <br>';
					}
				}
			}
		} else {
			$body .= '<p><b>No regulations were added or updated for ' . date( 'F j, Y' ) . ' </b></p>';
		}

		return $body;
	}

	function generateUpcomingHearingMail( $user ) {
		global $wpdb;
		$response                 = [];
		$response['bill_counter'] = 0;
		$result                   = $wpdb->get_results( "SELECT * FROM hearing WHERE date BETWEEN NOW() AND (NOW() + INTERVAL 14 DAY)" );
		foreach ( $result as $hearing ) {
			try {
				$_bill = $user->validateBillForUser( $hearing->id, 'hearing' );
			} catch ( Exception $e ) {
				$_bill = null;
			}
			if ( $_bill !== null ) {
				if ( ! isset( $response[ $_bill->state ] ) ) {
					$response[ $_bill->state ] = [];
				}
				array_push( $response[ $_bill->state ], $_bill );
				$response['bill_counter'] ++;
			}
		}
	}

	function generateHearingMail( $response ) {
		$body = "";
		if ( ! empty( $response ) ) {
			foreach ( $response as $state => $bills ) {
				if ( $state !== 'bill_counter' ) {
					if ( count( $response[ $state ] ) > 1 ) {
						$value = " bills ";
					} else {
						$value = " bill ";
					}
					$body .= '<p><b>' . date( 'F j, Y', strtotime( $state ) ) . '</b></p>';
					foreach ( $bills as $bill ) {
						$body .= '<table id="queried-reg"  style="border: 1px solid gray; width:900px;">';
						if ( ! empty( $bill->hearing_legislation ) ) {
							$body .= '<tr>
	                      <td style="border-bottom: 1px solid gray; border-right: 1px solid gray; width: 100px;"><b>Title: </b></td>
	                      <td style="border-bottom: 1px solid gray;"><b>' .
							         $bill->state . " " . $bill->hearing_legislation->type . " " .
							         $bill->hearing_legislation->number . " " .
							         ucwords( $bill->hearing_legislation->title ) . '</b></td></tr>';
						}
						$body .= ' <tr>
                      <td style="border-bottom: 1px solid gray; border-right: 1px solid gray; width: 100px;"><b>Place:</b></td>
                      <td style="border-bottom: 1px solid gray;">' . $bill->place . '</td>
                    </tr>';
						$body .= '<tr>
                      <td style="border-bottom: 1px solid gray; border-right: 1px solid gray; width: 100px;" ><b>Date:</b></td>
                      <td style="border-bottom: 1px solid gray;">' . date( 'M j, Y', strtotime( $bill->date ) ) . '</td>
                    </tr>';
						$body .= '<tr>
                      <td style="border-bottom: 1px solid gray; border-right: 1px solid gray; width: 100px;"><b>Time:</b></td>
                      <td style="border-bottom: 1px solid gray;">' . $bill->time . '</td>
                    </tr>';
						$body .= '<tr>
                      <td style="border-right: 1px solid gray; width: 100px;"><b>Committee:</b></td>
                      <td>' . $bill->committee . '</td>
                    </tr>';
						$body .= '</table> <br>';
					}
				}
			}

			return $body;
		}
	}

	function generatePartPreview( $import_ids, $user, $type ) {
		global $wpdb;
		$data                     = implode( ',', $import_ids );
		$response                 = [];
		$response['bill_counter'] = 0;
		$import_ids               = $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT(document_id) FROM last_updated
									WHERE import_table_id IN (%s)", $data ) );
		foreach ( $import_ids as $import_id ) {
			try {
				$_bill = $user->validateBillForUser( $import_id, $type );
			} catch ( Exception $e ) {
				$_bill = null;
			}
			if ( $_bill !== null ) {
				if ( $type === 'hearing' ) {
					if ( ! isset( $response[ $_bill->date ] ) ) {
						$response[ $_bill->date ] = [];
					}
					array_push( $response[ $_bill->date ], $_bill );
				} else {
					if ( ! isset( $response[ $_bill->state ] ) ) {
						$response[ $_bill->state ] = [];
					}
					array_push( $response[ $_bill->state ], $_bill );
				}
				$response['bill_counter'] ++;
			}
		}

		return $response;
	}

	function generateDailyEmailSubject( $client ) {
		return 'MSA Capitol Monitor Daily Report - ' . $client;
	}

	/**
	 * We are sending email preview to all staff users
	 *
	 * @param $user_id
	 * @param $fetching_date
	 * @param $client_id
	 */
	function sendEmailPreview( $user_id, $fetching_date, $client_id ) {
		if ( $user_id !== '' && $fetching_date !== '' && $client_id !== '' ) {

			$user    = new MSAUser( $user_id );
			$subject = $this->generateDailyEmailSubject( $user->client );
			$body    = $this->generateUserEmail( $user_id, $fetching_date, $client_id );

			$http_headers = array( 'Content-Type: text/html; charset=UTF-8' );

			$base        = new MSABase();
			$staff_users = $base->getStaffUsers();
			foreach ( $staff_users as $staff_user ) {
				wp_mail( $staff_user->user_email, $subject, $body, $http_headers );
			}
			echo json_encode( [ "status" => true, "message" => "Preview Emails Have Been Sent To Staff Users!" ] );
		} else {
			echo json_encode( [ 'status' => false, "message" => "Needed parameters are missing!" ] );
		}
	}

	function sendDailyEmailUser( $user_id, $fetching_date, $client_id ) {
		$error_msg = "";
		if ( $user_id !== '' && $fetching_date !== '' && $client_id !== '' ) {
			try {
				$user         = new MSAUser( $user_id );
				$subject      = $this->generateDailyEmailSubject( $user->client );
				$body         = $this->generateUserEmail( $user_id, $fetching_date, $client_id );
				$http_headers = array( 'Content-Type: text/html; charset=UTF-8' );
				$result       = wp_mail( $user->user_email, $subject, $body, $http_headers );
				$importids = $this->getImportID($fetching_date,$client_id);
				foreach ($importids as $importid ){
					$sql = "INSERT INTO import_daily_mails (import_id,user_id,client_id,daily_mail_sent,daily_mail_sent_time) VALUES(%d,%d,%d,true,NOW()) ON DUPLICATE KEY UPDATE daily_mail_sent = true, daily_mail_sent_time = NOW()";
					$sql = $this->wpdb->prepare($sql,$importid,$user_id,$client_id);
					$this->wpdb->query($sql);
				}
			} catch ( Exception $e ) {
				$error_msg = $e;
			}

			if ( $result ) {
				echo json_encode( [ 'status' => true, "message" => "Email was successfully sent to client!" ] );
			} else {
				echo json_encode( [ 'status' => false, "message" => "Email was not sent to client." . $error_msg ] );
			}
		} else {
			echo json_encode( [ 'status' => false, "message" => "Needed parameters are missing!" ] );
		}
	}

	/**
	 * @param $client_id
	 * @param $fetching_date
	 */
	function sendDailyEmailClientUsers( $client_id, $fetching_date ) {
		if ( $client_id !== '' && $fetching_date !== '' ) {
			$base  = new MSABase();
			$users = $base->getClientUsers( $client_id, [ 'ID', 'user_email' ] );
			$importids = $this->getImportID($fetching_date,$client_id);
			foreach ( $users as $user ) {
				$_user        = new MSAUser( $user['ID'] );
				$subject      = $this->generateDailyEmailSubject( $_user->client );
				$body         = $this->generateUserEmail( $user['ID'], $fetching_date, $client_id );
				$http_headers = array( 'Content-Type: text/html; charset=UTF-8' );
				wp_mail( $_user->user_email, $subject, $body, $http_headers );
				foreach ($importids as $importid ){
					$sql = "INSERT INTO import_daily_mails (import_id,user_id,client_id,daily_mail_sent,daily_mail_sent_time) VALUES(%d,%d,%d,true,NOW()) ON DUPLICATE KEY UPDATE daily_mail_sent = true, daily_mail_sent_time = NOW()";
					$sql = $this->wpdb->prepare($sql,$importid,$_user->user_id,$client_id);
					$this->wpdb->query($sql);
				}
			}
			echo json_encode( [ 'status'  => true,
			                    "message" => "Emails have been sent to all users belonging to client!"
			] );
		} else {
			echo json_encode( [ 'status' => false, "message" => "Needed parameters are missing!" ] );
		}
	}
	private function getImportID($fetching_date,$client_id){
		$import_ids  = $this->wpdb->get_col( $this->wpdb->prepare( "SELECT id FROM import_table
															WHERE fetching_date = %s
															AND client_id = %d 
															AND curation_date IS NOT NULL", $fetching_date, $client_id ) );
		return $import_ids;
	}
	function generateUserEmail( $user_id, $fetching_date, $client_id ) {
		global $wpdb;
		// TODO get all legislation id's that have been approved for that day
		// TODO check if those ids have country and categories interested for user
		// TODO create final list of bills based on above check
		// TODO create body for email

		$body = "";
		/*$legislation_import_id = $wpdb->get_col($wpdb->prepare("SELECT id FROM import_table
															WHERE fetching_date = %s
															AND client_id = %d
															AND curation_date IS NOT NULL",$fetching_date,$client_id));*/
		$import_ids  = $wpdb->get_results( $wpdb->prepare( "SELECT id,entity_type FROM import_table
															WHERE fetching_date = %s
															AND client_id = %d 
															AND curation_date IS NOT NULL", $fetching_date, $client_id ), OBJECT );
		$grouppedids = [ 'legislation' => [], 'regulation' => [], 'hearing' => [] ];
		foreach ( $import_ids as $import_id ) {
			array_push( $grouppedids[ $import_id->entity_type ], $import_id->id );
		}
		//print_r($grouppedids);
		$user = new MSAUser( $user_id );

		foreach ( $grouppedids as $group => $ids ) {
			$response = $this->generatePartPreview( $ids, $user, $group );
			if ( $group === 'legislation' ) {
				$this->legs = $response;
			} else if ( $group === 'regulation' ) {
				$this->regs = $response;
			} else if ( $group === 'hearing' ) {
				$this->hrgs = $response;
			}
			//print_r($a);
		}

		$comment = getUserComments( $user_id, $fetching_date, $client_id );
		if ( $comment['status'] === true ) {
			$body .= "<h2> Staff Comment: </h2>";
			$body .= "<p>{$comment['data']->comment}</p>";
		}
		$body .= "<h2>Bill Counts</h2><p> Bills added or updated: " . $this->legs['bill_counter'] . "</p>
					<p> Regulations added or updated: " . $this->regs['bill_counter'] . "</p>
					<p> Upcoming hearings:" . $this->hrgs['bill_counter'] . "</p><hr>";
		$body .= "<p><b>LEGISLATION (" . $this->legs['bill_counter'] . " overall)</b><hr></p>";
		$body .= $this->generateLegislationMail( $this->legs );
		$body .= "<p><b>REGULATION (" . $this->regs['bill_counter'] . " overall)</b><hr></p>";
		$body .= $this->generateRegulationMail( $this->regs );
		$body .= "<p><b>UPCOMING HEARINGS (" . $this->hrgs['bill_counter'] . " overall)</b></br><hr></p>";
		//$this->generateUpcomingHearingMail($user);
		$body .= $this->generateHearingMail( $this->hrgs );
		$body .= "<p><a href = '". get_site_url() ."'><img src = 'http://mainstreetadvocates.com/templates/mainstreet/images/logo.jpg' alt = 'Powered By MSA Capitol Manager' title = 'Powered By MSA Capitol Manager'/></a></p>";
		return $body;
	}

	function generatePreviewEmail( $user_id, $fetching_date, $client_id ) {
		global $wpdb;

		$body = "";
		/*$legislation_import_id = $wpdb->get_col($wpdb->prepare("SELECT id FROM import_table
															WHERE fetching_date = %s
															AND client_id = %d 
															AND curation_date IS NOT NULL",$fetching_date,$client_id));*/
		$import_ids  = $wpdb->get_results( $wpdb->prepare( "SELECT id,entity_type FROM import_table
															WHERE fetching_date = %s
															AND client_id = %d 
															AND curation_date IS NOT NULL", $fetching_date, $client_id ), OBJECT );
		$grouppedids = [ 'legislation' => [], 'regulation' => [], 'hearing' => [] ];
		foreach ( $import_ids as $import_id ) {
			array_push( $grouppedids[ $import_id->entity_type ], $import_id->id );
		}
		//print_r($grouppedids);
		$user = new MSAUser( $user_id );
		foreach ( $grouppedids as $group => $ids ) {
			$response = $this->generatePartPreview( $ids, $user, $group );
			if ( $group === 'legislation' ) {
				$this->legs = $response;
			} else if ( $group === 'regulation' ) {
				$this->regs = $response;
			} else if ( $group === 'hearing' ) {
				$this->hrgs = $response;
			}
			//print_r($a);
		}
		$comment = getUserComments( $user_id, $fetching_date, $client_id );
		if ( $comment['status'] === true ) {
			$body .= "<h2> Staff Comment: </h2>";
			$body .= "<p>{$comment['data']->comment}</p>";
		}
		$body .= "<h2>Bill Counts</h2><p> Bills added or updated: " . $this->legs['bill_counter'] . "</p>
					<p> Regulations added or updated: " . $this->regs['bill_counter'] . "</p>
					<p> Upcoming hearings:" . $this->hrgs['bill_counter'] . "</p><hr>";
		$body .= "<p><b>LEGISLATION (" . $this->legs['bill_counter'] . " overall)</b><hr></p>";
		$body .= $this->generateLegislationMail( $this->legs );
		$body .= "<p><b>REGULATION (" . $this->regs['bill_counter'] . " overall)</b><hr></p>";
		$body .= $this->generateRegulationMail( $this->regs );
		$body .= "<p><b>UPCOMING HEARINGS (" . $this->hrgs['bill_counter'] . " overall)</b></br><hr></p>";
		//$this->generateUpcomingHearingMail($user);
		$body .= $this->generateHearingMail( $this->hrgs );

		echo $body;
	}
}

function getClientImports( $client_id ) {
	global $wpdb;
	$result = $wpdb->get_results(
		$wpdb->prepare( "SELECT DISTINCT DATE(xml_import_timestamp) as import_date FROM import_table 
								WHERE client_id = %d
								ORDER BY xml_import_timestamp DESC 
								LIMIT 25 ",
			$client_id ),
		OBJECT );

	return $result;
}

function addImportComment( $client_id, $status, $user_id, $comments, $all_users = false ) {

	global $wpdb;
	//select import ids as they need to be grouped

	if ( $all_users === 'true' ) {
		$base         = new MSABase();
		$client_users = $base->getClientUsers( $client_id, [ 'ID', 'user_email' ] );
	}


	$import_ids        = $wpdb->get_col( $wpdb->prepare( 'SELECT id FROM import_table WHERE client_id = %d AND fetching_date = %s',
		$client_id, $status ) );
	$string_import_ids = implode( ',', $import_ids );

	$comment = $wpdb->insert( 'import_mail_comments', [ 'comment' => $comments ], [ '%s' ] );
	if ( $comment ) {
		$comment_id = $wpdb->insert_id;
	}
	$import_ids = $wpdb->get_results( $wpdb->prepare( 'SELECT id FROM import_table WHERE client_id = %d AND fetching_date = %s',
		$client_id, $status ), OBJECT );
	if ( ! empty( $client_users ) ) {
		foreach ( $client_users as $user ) {
			foreach ( $import_ids as $import_id ) {
				$sql = "INSERT INTO import_daily_mails (import_id,comment_id,user_id,client_id) VALUES(%d,%d,%d,%d) ON DUPLICATE KEY UPDATE comment_id = %d";
				$sql = $wpdb->prepare($sql,$import_id->id,$comment_id, $user['ID'],$client_id,$comment_id);
				$wpdb->query($sql);
			}
		}
	} else {
		foreach ( $import_ids as $import_id ) {
			$sql = "INSERT INTO import_daily_mails (import_id,comment_id,user_id,client_id) VALUES(%d,%d,%d,%d) ON DUPLICATE KEY UPDATE comment_id = %d";
			$sql = $wpdb->prepare($sql,$import_id->id,$comment_id, $user_id,$client_id,$comment_id);
			$wpdb->query($sql);
		}
	}

	echo json_encode( [ 'status' => true, 'message' => "Comments have been added / updated!" ] );
}

function getUserComments( $user_id, $status, $client_id ) {

	global $wpdb;
	$import_ids  = $wpdb->get_results( $wpdb->prepare( 'SELECT id FROM import_table WHERE client_id = %d AND fetching_date = %s',
		$client_id, $status ), OBJECT );
	$_import_ids = [];
	foreach ( $import_ids as $import_id ) {
		array_push( $_import_ids, $import_id->id );
	}
	$_string_import_ids = implode( ',', $_import_ids );
	$comment            = $wpdb->get_row( $wpdb->prepare( 'SELECT DISTINCT(imc.id),comment FROM import_mail_comments AS imc 
 													LEFT JOIN import_daily_mails AS idm ON imc.id = idm.comment_id
 													WHERE idm.import_id IN (%s) AND idm.user_id = %d;', $_string_import_ids, $user_id ),
		OBJECT );
	// TODO handle error message
	$response = [];
	if ( $comment ) {
		$response['status'] = true;
		$response['data']   = $comment;
	} else {
		$response['status'] = false;
	}

	return $response;
}

if ( isset( $_POST['action'] ) ) {
	$action = $_POST['action'];
	switch ( $action ) {
		case 'get_imports':
			echo json_encode( getClientImports( $_POST['client_id'] ) );
			break;
		case 'add_email_comment':
			addImportComment( $_POST['client_id'], $_POST['status'], $_POST['user_id'], $_POST['comments'], $_POST['all_users'] );
			break;
		case 'get_user_comments':
			echo json_encode( getUserComments( $_POST['user_id'], $_POST['status'], $_POST['client_id'] ) );
			break;
		case 'get_preview_email':
			$mail = new EmailPreview();
			$mail->generatePreviewEmail( $_POST['user_id'], $_POST['status'], $_POST['client_id'] );
			break;
		case 'send_email_preview':
			$mail = new EmailPreview();
			$mail->sendEmailPreview( $_POST['user_id'], $_POST['status'], $_POST['client_id'] );
			break;
		case 'send_daily_email_user':
			$mail = new EmailPreview();
			$mail->sendDailyEmailUser( $_POST['user_id'], $_POST['status'], $_POST['client_id'] );
			break;
		case 'send_daily_mail_all':
			$mail = new EmailPreview();
			$mail->sendDailyEmailClientUsers( $_POST['client_id'], $_POST['status'] );
			break;
	}
}