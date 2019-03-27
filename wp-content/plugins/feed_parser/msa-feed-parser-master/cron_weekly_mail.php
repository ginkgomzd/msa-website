<?php
/**
 * Created by PhpStorm.
 * User: ljubi
 * Date: 2/15/2019
 * Time: 11:27 PM
 */
require_once "/var/www/stage/htdocs/wp-load.php";
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ExcelGenerator {
	private $client_id;
	private $client_name;

	public function __construct( $client_name ) {
		$this->client_name = $client_name;
	}

	function get_tiny_url( $url ) {
		$ch      = curl_init();
		$timeout = 5;
		curl_setopt( $ch, CURLOPT_URL, 'http://tinyurl.com/api-create.php?url=' . $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
		$data = curl_exec( $ch );
		curl_close( $ch );

		return $data;
	}

	private function generateExcelNotesForBill( $notes ) {
		$_notes = "";
		$len = count($notes);
		foreach ( $notes as $key => $note ) {
			if ($key == $len - 1) {
				$_notes .= $note->user->data->user_nicename . " : " . $note->note_text;
			}else {
				$_notes .= $note->user->data->user_nicename . " : " . $note->note_text . "\n";
			}
		}

		return $_notes;
	}

	public function generateLegislationExcel( $weekly_legislations ) {
		$name        = $this->client_name . ' Weekly Bills ' . date( 'm.d.Y' );
		$spreadsheet = new Spreadsheet();
		$spreadsheet->getProperties()
		            ->setCreator( 'MSA' );
// Set default font
		$spreadsheet->getDefaultStyle()
		            ->getFont()
		            ->setName( 'Arial' )
		            ->setSize( 10 );
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue( 'A1', 'State' )
		      ->setCellValue( 'B1', 'Type' )
		      ->setCellValue( 'C1', 'Number' )
		      ->setCellValue( 'D1', 'Session' )
		      ->setCellValue( 'E1', 'Sponsor' )
		      ->setCellValue( 'F1', 'Title' )
		      ->setCellValue( 'G1', 'Abstract' )
		      ->setCellValue( 'H1', 'Last Updated' )
		      ->setCellValue( 'I1', 'Status' )
		      ->setCellValue( 'J1', 'Categories' )
		      ->setCellValue( 'K1', 'Latest Action' )
		      ->setCellValue( 'L1', 'Priority' )
		      ->setCellValue( 'M1', 'Notes' )
		      ->setCellValue( 'N1', 'Link To Bill' );

		$sheet->getColumnDimension( 'A' )->setWidth( 5 );
		$sheet->getColumnDimension( 'B' )->setWidth( 8 );
		$sheet->getColumnDimension( 'C' )->setWidth( 8 );
		$sheet->getColumnDimension( 'D' )->setWidth( 8 );
		$sheet->getColumnDimension( 'E' )->setWidth( 15 );
		$sheet->getColumnDimension( 'F' )->setWidth( 30 );
		$sheet->getColumnDimension( 'G' )->setWidth( 30 );
		$sheet->getColumnDimension( 'H' )->setWidth( 15 );
		$sheet->getColumnDimension( 'I' )->setWidth( 30 );
		$sheet->getColumnDimension( 'J' )->setWidth( 30 );
		$sheet->getColumnDimension( 'K' )->setWidth( 30 );
		$sheet->getColumnDimension( 'L' )->setWidth( 10 );
		$sheet->getColumnDimension( 'M' )->setWidth( 30 );
		$sheet->getColumnDimension( 'N' )->setWidth( 30 );
		//user . ':' . comment
		$sheet->getStyle( 'A1:N1' )
		      ->getFill()
		      ->setFillType( \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID )
		      ->getStartColor()
		      ->setARGB( Color::COLOR_BLACK );
		$sheet->getStyle( 'A1:N1' )->getFont()->getColor()->setARGB( Color::COLOR_WHITE );
		$sheet->getStyle( 'A1:N1' )->getAlignment()->setHorizontal( 'center' );

		$counter = 1;
		foreach ( $weekly_legislations as $legislation ) {
			$counter ++;
			if ( ! isset( $legislation->priority ) ) {
				echo $legislation->priority;
				if ( $legislation->priority !== null ) {
					$legislation->isPriority = 'Yes';
				} else {
					$legislation->isPriority = 'No';
				};
			} else {
				$legislation->isPriority = 'No';
			}
			$_note = $this->generateExcelNotesForBill( $legislation->notes );
			$sheet->setCellValue( "A" . $counter, $legislation->state )
			      ->setCellValue( 'B' . $counter, $legislation->type )
			      ->setCellValue( 'C' . $counter, $legislation->number )
			      ->setCellValue( 'D' . $counter, $legislation->session )
			      ->setCellValue( 'E' . $counter, $legislation->sponsor_name )
			      ->setCellValue( 'F' . $counter, $legislation->title )
			      ->setCellValue( 'G' . $counter, $legislation->abstract )
			      ->setCellValue( 'H' . $counter, $legislation->last_updated )
			      ->setCellValue( 'I' . $counter, $legislation->status_standard_val )
			      ->setCellValue( 'J' . $counter, implode( ',', $legislation->categories ) )
			      ->setCellValue( 'K' . $counter, $legislation->status_val )
			      ->setCellValue( 'L' . $counter, $legislation->isPriority )
			      ->setCellValue( 'N' . $counter, $this->get_tiny_url( "http://localhost/msa_test/detailed-view/?id=" . $legislation->id ) )
			      ->getCell( 'M' . $counter )->setValue( $_note )
			      ->getStyle( 'M' . $counter )->getAlignment()->setWrapText( true );
			/*$sheet->getCell('M'.$counter)->setValue($_note)
			      ->getCell('N'.$counter)->getHyperlink()->setUrl($this->get_tiny_url( "http://localhost/msa_test/detailed-view/?id=" . $legislation->id ));*/
		}
		$sheet->getStyle( 'A1:N' . $counter )->getBorders()->getAllBorders()->setBorderStyle( \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN );
		$sheet->getStyle( "A2:N" . $counter )->getAlignment()->setWrapText( true );
		$sheet->setTitle( $this->client_name . ' Weekly Bills' );

		$writer = new Xlsx( $spreadsheet );
		$writer->save( $name . '.xlsx' );

		return $name . '.xlsx';
	}

	public function generateRegulationExcel( $weekly_regulations ) {
		$name        = $this->client_name . " Weekly Regulations " . date( 'm.d.Y' );
		$spreadsheet = new Spreadsheet();
		$spreadsheet->getProperties()
		            ->setCreator( 'MSA' );
// Set default font
		$spreadsheet->getDefaultStyle()
		            ->getFont()
		            ->setName( 'Arial' )
		            ->setSize( 10 );

		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue( 'A1', 'State' )
		      ->setCellValue( 'B1', 'Agency Name' )
		      ->setCellValue( 'C1', 'Type' )
		      ->setCellValue( 'D1', 'Description' )
		      ->setCellValue( 'E1', 'Categories' )
		      ->setCellValue( 'F1', 'Link' );
		$sheet->getColumnDimension( 'A' )->setWidth( 10 );
		$sheet->getColumnDimension( 'B' )->setWidth( 30 );
		$sheet->getColumnDimension( 'C' )->setWidth( 15 );
		$sheet->getColumnDimension( 'D' )->setWidth( 30 );
		$sheet->getColumnDimension( 'E' )->setWidth( 30 );
		$sheet->getColumnDimension( 'F' )->setWidth( 30 );
		$sheet->getStyle( 'A1:F1' )
		      ->getFill()
		      ->setFillType( \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID )
		      ->getStartColor()
		      ->setARGB( Color::COLOR_BLACK );
		$sheet->getStyle( 'A1:F1' )->getFont()->getColor()->setARGB( Color::COLOR_WHITE );
		$sheet->getStyle( 'A1:F1' )->getAlignment()->setHorizontal( 'center' );
		$counter = 1;
		foreach ( $weekly_regulations as $regulation ) {
			$counter ++;
			$sheet->setCellValue( "A" . $counter, $regulation->state )
			      ->setCellValue( 'B' . $counter, $regulation->agency_name )
			      ->setCellValue( 'C' . $counter, $regulation->type )
			      ->setCellValue( 'D' . $counter, $regulation->description )
			      ->setCellValue( 'E' . $counter, implode( ',', $regulation->categories ) )
			      ->setCellValue( 'F' . $counter, $this->get_tiny_url( "http://localhost/msa_test/regulation-detail-view/?id=" . $regulation->id ) );
		}
		$sheet->getStyle( 'A1:F' . $counter )->getBorders()->getAllBorders()->setBorderStyle( \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN );
		$sheet->getStyle( "A2:F" . $counter )->getAlignment()->setWrapText( true );
		$sheet->setTitle( $this->client_name . ' Weekly Regulations' );

		$writer = new Xlsx( $spreadsheet );
		$writer->save( $name . '.xlsx' );

		return $name . '.xlsx';
	}
}

class WeeklyMail {

	private $client_id;
	private $wpdb;
	private $last_week_imports;
	private $msaBase;
	public $client_users = [];
	private $hearing = [];
	private $regulation = [];
	private $clientregulation = [];
	private $clientlegislation = [];
	private $legislation = [];
	private $mail_subject;
	private $client_name;

	public function __construct( $client_id, $client_name ) {
		$this->client_id   = $client_id;
		$this->client_name = $client_name;
		global $wpdb;
		$this->wpdb    = $wpdb;
		$this->msaBase = new MSABase();
		$this->setClientUsers();
	}

	public function generateBodyHeader() {
		$date = date( 'F d, Y' );
		$body = "<p>Attached are your weekly reports detailing those bills and regulations that have been added or updated during the week ending {$date} </p> ";
		$body .= "<p>Here are you statistics overall and of the week:</p>";

		return $body;
	}

	//TODO set sheet title
	public function generateBodyFooter() {
		$body = "All the Best,<br>";
		$body .= "<p>Kevin Canan<br>
				Managing Partner<br>
				MainStreet Advocates<br>
				1000 Potomac Street, NW<br>
				Suite 200<br>
				Washington DC 20007<br>
				Direct Dial (202) 965-7373<br>
				Mobile (202) 709-8704<br></p>";

		return $body;
	}

	public function generateWeekGlance(){
		$body = "<ul>";
		$_word = new NumberFormatter("en", NumberFormatter::SPELLOUT);
		$convenethisweek = $this->wpdb->get_row('SELECT COUNT(*) as covenecount FROM session_info WHERE  YEARWEEK(convene_date, 1) = YEARWEEK(CURDATE(), 1);');
		if ($convenethisweek->covenecount == 0){
			$body .= "<li><b>No states</b> are scheduled to convene this week.</li>";
		}else {
			$ucWord = ucwords($_word->format($convenethisweek->covenecount));
			if ($convenethisweek->covenecount == 1){
				$body .= "<li><b>{$ucWord} State</b> is scheduled to convene this week.</li>";
			}else{
				$body .= "<li><b>{$ucWord} States</b> are scheduled to convene this week.</li>";
			}
		}
		$adjournthisweek = $this->wpdb->get_row('SELECT COUNT(*) as adjourncount FROM session_info WHERE  YEARWEEK(adjourn_date, 1) = YEARWEEK(CURDATE(), 1)');

		if ($adjournthisweek->adjourncount == 0){
			$body .= "<li><b>No states</b> are scheduled to adjourn from this week. </li>";
		}else {
			$ucWord = ucwords($_word->format($adjournthisweek->adjourncount));
			if ($adjournthisweek->adjourncount == 1){
				$body .= "<li><b>{$ucWord} State</b> is scheduled to adjourn from this week. </li>";
			}else{
				$body .= "<li><b>{$ucWord} States</b> are scheduled to adjourn from this week.</li>";
			}
		}

		$convenelastweek = $this->wpdb->get_row('SELECT COUNT(*) as covenecount FROM session_info WHERE  YEARWEEK(convene_date, 1) = YEARWEEK(CURDATE(), 1) - 1;');
		if ($convenelastweek->covenecount == 0){
			$body .= "<li><b>No states</b> convened last week.</li>";
		}else {
			$ucWord = ucwords($_word->format($convenelastweek->covenecount));
			if ($convenelastweek->covenecount == 1){
				$body .= "<li><b>{$ucWord} State</b> convened last week.</li>";
			}else{
				$body .= "<li><b>{$ucWord} States</b> convened last week.</li>";
			}
		}

		$adjournlastweek = $this->wpdb->get_row('SELECT COUNT(*) as adjourncount FROM session_info WHERE  YEARWEEK(adjourn_date, 1) = YEARWEEK(CURDATE(), 1) -1');

		if ($adjournlastweek->adjourncount == 0){
			$body .= "<li><b>No states</b> adjourned last week.</li>";
		}else {
			$ucWord = ucwords($_word->format($adjournlastweek->adjourncount));
			if ($adjournlastweek->adjourncount == 1){
				$body .= "<li><b>{$ucWord} State</b> adjourned last week. </li>";
			}else{
				$body .= "<li><b>{$ucWord} States</b> adjourned last week.</li>";
			}
		}
		$body .= "</ul>";
		return $body;
	}
	public function generateBody( $weekly_regulation, $weekly_bills, $total_regulation, $total_bills ) {
		$body = "";
		$body .= $this->generateBodyHeader();
		$body .= "<p>Total Number of Bills: {$total_bills}</p>";
		$body .= "<p>Bills Added or Updated Last Week: {$weekly_bills}</p>";
		$body .= "<p>Total number of Regulations: {$total_regulation}</p>";
		$body .= "<p>Regulations Added or Updated Last Week: {$weekly_regulation}</p>";
		$body .= "<p>Legislatures this week at-a-glance:</p>";
		$body .= $this->generateWeekGlance();
		$body .= "<p>Feel free to contact me with any questions.</p>";
		$body .= $this->generateBodyFooter();

		return $body;
	}

	private function group_by( $array, $key, $arr_push = false, $field ) {
		$return = array();
		foreach ( $array as $val ) {
			if ( $arr_push ) {
				if ( ! key_exists( $val[ $key ], $return ) ) {
					$return[ $val[ $key ] ] = [];
				}
				array_push( $return[ $val[ $key ] ], $val[ $field ] );
			} else {
				$return[ $val[ $key ] ][] = $val;
			}
		}

		return $return;
	}

	// TODO - DOB see if we go interval
	public function getImportsLastWeek() {
		$imports = $this->wpdb->get_results( "SELECT DISTINCT(import_id),entity_type FROM import_daily_mails as idm
										LEFT JOIN import_table ON idm.import_id = import_table.id
										WHERE idm.client_id = {$this->client_id} and DATE(daily_mail_sent_time) > (NOW() - INTERVAL 7 DAY) AND entity_type <> 'hearing';", ARRAY_A );

		$this->last_week_imports = $this->group_by( $imports, 'entity_type', true, 'import_id' );
	}


	public function setClientUsers() {
		$_client_users = $this->msaBase->getClientUsers( $this->client_id, [ 'ID', 'user_email' ] );
		foreach ( $_client_users as $user ) {
			array_push( $this->client_users, $user['ID'] );
		}
	}

	public function getClientStats() {

		foreach ( $this->client_users as $user_id ) {
			$_user                    = new MSAUser( $user_id );
			$_user->weekly_bills      = [];
			$_user->weekly_regulation = [];
			$_user->user_regulation   = [];
			$_user->user_bills        = [];
			$this->generateMailSubject();
			if ( ! empty( $this->regulation ) ) {
				foreach ( $this->regulation as $regulation ) {
					$bill = $_user->validateExistingBillForUser( $regulation ,'ismailactive' );
					if ( $bill !== null ) {
						array_push( $_user->weekly_regulation, $bill );
					}
				}
			}

			if ( ! empty( $this->legislation ) ) {
				foreach ( $this->legislation as $legislation ) {
					if ( $legislation !== null ) {
						$bill = $_user->validateExistingBillForUser( $legislation,'ismailactive');
						if ( $bill !== null ) {
							array_push( $_user->weekly_bills, $bill );
						}
					}
				}
			}
			if ( ! empty( $this->clientregulation ) ) {
				foreach ( $this->clientregulation as $regulation ) {
					if ( $regulation !== null ) {
						$bill = $_user->validateExistingBillForUser( $regulation,'ismailactive' );
						if ( $bill !== null ) {
							array_push( $_user->user_regulation, $bill );
						}
					}
				}
			}
			if ( ! empty( $this->clientlegislation ) ) {
				foreach ( $this->clientlegislation as $legislation ) {
					if ( $legislation !== null ) {
						$bill = $_user->validateExistingBillForUser( $legislation,'ismailactive' );
						if ( $bill !== null ) {
							array_push( $_user->user_bills, $bill );
						}
					}
				}
			}
			$_body   = $this->generateBody( count( $_user->weekly_regulation ), count( $_user->weekly_bills ), count( $_user->user_regulation ), count( $_user->user_bills ) );
			$exelgen = new ExcelGenerator( $_user->client );
			$_files  = [];
			if ( ! empty( $_user->weekly_regulation ) ) {
				$regulation_file = $exelgen->generateRegulationExcel( $_user->weekly_regulation );
				array_push( $_files, $regulation_file );
			}
			if ( ! empty( $_user->weekly_bills ) ) {
				$weekly_bills_file = $exelgen->generateLegislationExcel( $_user->weekly_bills );
				array_push( $_files, $weekly_bills_file );
			}
			$http_headers = array( 'Content-Type: text/html; charset=UTF-8' );
			echo $_user->user_email;
			wp_mail( $_user->user_email, $this->mail_subject, $_body, $http_headers, $_files );

			foreach ( $_files as $file ) {
				unlink( $file);
			}
		}
	}
	// TODO validate which documents belong to particular user
	// TODO create count of documents per user based on filtering that is performed e.g category ,state

	public function getBill( $entity_type, $id ) {
		$query = "SELECT * FROM";
		switch ( $entity_type ) {
			case MSABase::REGULATION:
				$query .= " regulation";
				break;
			case MSABase::HEARING:
				$query .= " hearing";
				break;
			case MSABase::LEGISLATION:
				$query .= " legislation";
				break;
		}
		$query .= " WHERE ID = %s";
		$row   = $this->wpdb->get_row( $this->wpdb->prepare(
			$query,
			$id ),
			OBJECT );
		if ( $row !== null ) {
			$new_bill = new MSABill( $entity_type, $row, $this->client_id );

			return $new_bill;
		} else {
			return null;
		}
	}

	public function getClientRegulation() {
		$this->all_regulation = $this->wpdb->get_results( "SELECT DISTINCT(regulation.id)  FROM regulation
                 LEFT JOIN hidden_bills as hb ON regulation.id = hb.bill_id AND hb.entity_type = 'regulation' AND hb.client_id = {$this->client_id}
                 WHERE regulation.id IN (SELECT DISTINCT(entity_id) as id FROM client_settings as cs
                 INNER JOIN profile_match as pm ON cs.category=pm.pname AND cs.client_id = pm.client_id
                 WHERE cs.client_id = {$this->client_id} AND type = 'category' AND ismailactive = 1 and entity_type = 'regulation') AND hidden_timestamp IS NULL;" );

	}

	public function getClientLegislation() {
		$this->all_legislation = $this->wpdb->get_results( "SELECT leg.id FROM legislation AS leg
        LEFT JOIN session_info ON leg.session_id = session_info.id
        LEFT JOIN hidden_bills as hb ON leg.id = hb.bill_id AND entity_type = 'legislation' AND client_id = {$this->client_id}
        WHERE leg.id IN (SELECT DISTINCT(entity_id) as id FROM client_settings as cs
        INNER JOIN profile_match as pm ON cs.category=pm.pname AND cs.client_id = pm.client_id
        WHERE cs.client_id = {$this->client_id} AND type = 'category' AND ismailactive= 1 and entity_type = 'legislation') AND is_active = 1 AND hidden_timestamp IS NULL;" );

	}

	public function getImportBills( $data ): Array {
		$import_ids = implode( ',', $data );
		$result     = $this->wpdb->get_col( $this->wpdb->prepare( "SELECT DISTINCT(document_id) FROM last_updated
									WHERE import_table_id IN (%s)", $import_ids ) );
		return $result;
	}

	public function getClientBills() {
		foreach ( $this->last_week_imports as $category => $ids ) {
			$ids = $this->getImportBills( $ids );
			foreach ( $ids as $bill_id ) {
				$_bill = $this->getBill( $category, $bill_id );
				if ( $_bill !== null ) {
					array_push( $this->$category, $_bill );
				}
			}
		}

		if ( isset( $this->all_regulation ) ) {
			foreach ( $this->all_regulation as $reg ) {
				$_bill = $this->getBill( 'regulation', $reg->id );
				if ( $_bill !== null ) {
					array_push( $this->clientregulation, $_bill );
				}
			}
		}
		if ( isset( $this->all_legislation ) ) {
			foreach ( $this->all_legislation as $leg ) {
				$_bill = $this->getBill( 'legislation', $leg->id );
				if ( $_bill !== null ) {
					array_push( $this->clientlegislation, $_bill );
				}
			}
		}
	}

	public function getHearing() {
		return $this->hearing;
	}

	public function generateMailSubject() {
		$this->mail_subject = $this->client_name . ' Weekly State Legislative and Regulatory Reports for the week ending ' . date( 'F d' );
	}
}

$clients = $wpdb->get_results( 'SELECT * FROM user_clients', OBJECT );

foreach ( $clients as $client ) {
	$wm = new WeeklyMail( $client->id, $client->client );
	$wm->generateWeekGlance();
	if(!empty($wm->client_users)){
		$wm->getImportsLastWeek();
		$wm->getClientRegulation();
		$wm->getClientLegislation();
		$wm->getClientBills();
		$wm->getClientStats();
	}
}
