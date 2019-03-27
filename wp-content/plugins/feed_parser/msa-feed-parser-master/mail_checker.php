<?php
/**
 * Created by PhpStorm.
 * User: ljubi
 * Date: 12/16/2018
 * Time: 1:32 AM
 */



require_once  "vendor/autoload.php";
require_once "/var/www/stage/htdocs/wp-load.php";

use MainStreetAdvocates\IMAP\Mailbox;
use MainStreetAdvocates\Feed;

$downloaddir =  dirname(__FILE__,4 ). '/uploads/xmlbills/';

try {
	$mailbox = new Mailbox( "{zm-share-1.io.mk}INBOX", "test0@ivote.mk", "P@ssw0rd", $downloaddir );
} catch ( \MainStreetAdvocates\IMAP\Exception $e ) {
	die('Error not able to connect to mail');
}

// Read all messages into an array:
$mailsIds = $mailbox->searchMailbox('ALL');
if(!$mailsIds) {
	die('Mailbox is empty, exiting script.');
}

$count = 0;
$mail_count = 0;
$hearingcounts = 0;
$legislationcount = 0;
$regulationcount = 0;
$invaliddocuments = 0;

if(!empty($mailsIds)){
	foreach ($mailsIds as $key=>$id){
		$mail_count++;
		try {
			$mail        = $mailbox->getMail( $mailsIds[ $key ] );
			$attachments = $mail->getAttachments();
			foreach ( $attachments as $attachment ) {
				$ext = pathinfo( $attachment->filePath, PATHINFO_EXTENSION );
				if ( $ext === 'xml' ) {
					$feed = new Feed();
					$feed->loadFile( $attachment->filePath );
					$result = $feed->process();
					foreach ($result as $object){
						$objectclass = get_class($object);
						$class = explode('\\',$objectclass)[2];
						$count++;
						switch ($class){
							case 'Hearing':
								$hearingcounts++;
								break;
							case 'Legislation':
								$legislationcount++;
								break;
							case 'Regulation':
								$regulationcount++;
								break;
						}
					}
				} else {
					//Invalid attachment will be ignored and deleted
					unlink( $attachment->filePath );
				}
			}
			$mailbox->deleteMail( $mailsIds[ $key ] );
		}catch (Exception $e){
			unlink( $attachment->filePath );
			$invaliddocuments ++;
		}
	}
	if ($count > 0 ) {
		$subject = "Mail Reports " . date('Y-m-d');
		$body = "<h2>Mail Report For Mail Checking Performed at: <b>" . date( 'Y-m-d H:m:s' ) . "</b></h2>";
		$body .= "<p>Total Mails Processed:  " . $mail_count . "</p>";
		$body .= "<p>Total Bills Processed:  " . $count . "</p>";
		$body .= "<p>Hearings: " . $hearingcounts . "</p>";
		$body .= "<p>Legislation: " . $legislationcount . " </p>";
		$body .= "<p>Regulation: " . $regulationcount . "</p>";
		if ( $invaliddocuments > 0 ) {
			$body .= "<br><p><b>Please NOTE there are currently" . $invaliddocuments . " invalid documents inside mailbox, please login to mail and clear those mails! Those files have been deleted from system. </b></p>";
		}
		$http_headers = array( 'Content-Type: text/html; charset=UTF-8' );
		$a            = wp_mail( "ljubisa.dobric@live.com", $subject, $body, $http_headers );
	}
}
