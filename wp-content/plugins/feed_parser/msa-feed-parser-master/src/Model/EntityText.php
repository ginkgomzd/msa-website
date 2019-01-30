<?php

namespace MainStreetAdvocates\Model;

use Goutte\Client;
use MainStreetAdvocates\IMAP\Exception;
use Psr\Log\InvalidArgumentException;

class EntityText extends \GinkgoStreetLabs\Model {

	public $id;
	public $entity_id;
	public $content;
	public $entity_type;

	function __construct() {
		parent::__construct();
	}

	protected function getTableName() {
		return 'entity_text';
	}

	public function getContent( $url ) {
		//TODO check with Slobodan should we save error somewhere
		$client = new Client();

		$page     = $client->request( 'GET', $url );
		$response = $client->getResponse();
		if ( $response->getStatus() === 200 ) {
			try {
				$this->content = $page->html();
			} catch (\Exception $e) {

			}
		}
	}
}
