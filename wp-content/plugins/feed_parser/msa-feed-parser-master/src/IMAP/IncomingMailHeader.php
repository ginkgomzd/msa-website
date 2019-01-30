<?php
/**
 * Created by PhpStorm.
 * User: ljubi
 * Date: 12/16/2018
 * Time: 1:29 AM
 */

namespace MainStreetAdvocates\IMAP;


class IncomingMailHeader {

	/** @var int|string $id The IMAP message ID - not the "Message-ID:"-header of the email */
	public $id;
	public $date;
	public $headersRaw;
	public $headers;
	public $subject;

	public $fromName;
	public $fromAddress;

	public $to = array();
	public $toString;
	public $cc = array();
	public $bcc = array();
	public $replyTo = array();

	public $messageId;
}