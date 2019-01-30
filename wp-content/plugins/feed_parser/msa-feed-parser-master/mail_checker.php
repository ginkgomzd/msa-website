<?php
/**
 * Created by PhpStorm.
 * User: ljubi
 * Date: 12/16/2018
 * Time: 1:32 AM
 */



require_once  "vendor/autoload.php";
use MainStreetAdvocates\IMAP\Mailbox;
use MainStreetAdvocates\Feed;

try {
	$mailbox = new Mailbox( "{zm-share-1.io.mk}INBOX", "test0@ivote.mk", "P@ssw0rd", __DIR__ );
} catch ( \MainStreetAdvocates\IMAP\Exception $e ) {
	die('Error not able to connect to mail');
}
// Read all messaged into an array:
$mailsIds = $mailbox->searchMailbox('ALL');
if(!$mailsIds) {
	die('Mailbox is empty, exiting script.');
}

if(!empty($mailsIds)){
	foreach ($mailsIds as $key=>$id){
		$mail = $mailbox->getMail($mailsIds[$key]);
		$attachments = $mail->getAttachments();
		foreach ($attachments as $attachment){
			$ext = pathinfo($attachment->filePath,PATHINFO_EXTENSION);
			if ($ext === 'xml') {
				$feed = new Feed();
				$feed->loadFile( $attachment->filePath );
				$result = $feed->process();
				//unlink( $attachment->filePath );
				$msg = $result ? 'File uploaded, parsed, and saved to the database.' : 'A problem occurred.';
				echo "<p>$msg</p>";
			}else{
				//Invalid attachment will be ignored and deleted
				unlink( $attachment->filePath );
			}
		}
		$mailbox->deleteMail($mailsIds[$key]);
	}
}
