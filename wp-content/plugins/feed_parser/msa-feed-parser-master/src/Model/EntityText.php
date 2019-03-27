<?php

namespace MainStreetAdvocates\Model;

use Goutte\Client;
use GuzzleHttp\RequestOptions;
use MainStreetAdvocates\IMAP\Exception;
use Psr\Log\InvalidArgumentException;

class EntityText extends \GinkgoStreetLabs\Model {

	public $id;
	public $entity_id;
	public $content;
	public $entity_type;
	public $text_type;

	function __construct() {
		parent::__construct();
	}

	protected function getTableName() {
		return 'entity_text';
	}

	public function getContent( $url,$external_id ) {


		$client = new Client();
		$guzzle = $client->getClient();

			$getinfo = $guzzle->head($url,
				[RequestOptions::ALLOW_REDIRECTS => true]);
			$content_type = $getinfo->getHeader('Content-Type')[0];
			$ext = 'html';
			if(strpos($content_type,'html') !== false){
				$ext = 'html';
			}else if(strpos($content_type,'pdf') !== false){
				$ext = 'pdf';
			}

			$downloaddir = "/var/www/stage/htdocs/wp-content/uploads/bills_entity/"; //TODO this needs to be changed on server
			$downloadpath = $downloaddir .$external_id ."." . $ext;

			$response = $guzzle->get($url, [
				RequestOptions::ALLOW_REDIRECTS => true, //allow server redirects
				RequestOptions::SINK => $downloadpath
			]);

			$response->getBody()->getContents();
			$this->text_type = $ext;

			$this->content = $downloadpath;


	}
}
