<?php

namespace MainStreetAdvocates;

use MainStreetAdvocates\Model\EntityText;
use MainStreetAdvocates\Model\LastUpdated;
use MainStreetAdvocates\Model\ProfileMatch;
use MainStreetAdvocates\SimpleXMLHelper as Xml;

class FeedXmlModelMaps {

	/**
	 * Determines the type of feed in the passed path and returns an appropriate
	 * Model classname.
	 *
	 * @param $fileContent xml string
	 *
	 * @return ::class()
	 * @throws \Exception
	 * For unrecognized feed type.
	 */
	public static function getFeedType( $fileContents ) {
		// Only regulation feeds are known to start with an actions tag.
		if ( strpos( $fileContents, '<actions>' ) === 0 ) {
			return Model\Regulation::class;
		}
		// Legislation and hearing feeds are very similar, but only the former has
		// sponsor tags.
		elseif ( strpos( $fileContents, '<sponsor>' ) ) {
			return Model\Legislation::class;
		}
		// If it is not a legislation feed and it has one or more hearings tags,
		// then it is a hearing feed. Note that a self-closing hearing tag
		// (<hearings/>) is valid.
		elseif ( strpos( $fileContents, '<hearings>' ) ) {
			return Model\Hearing::class;
		} else {
			throw new \Exception( 'Feed type unknown.' );
		}
	}

	public static function getRootElement( $feedType, $simpleXML ) {
		switch ( $feedType ) {
			case Model\Legislation::class:
				return $simpleXML->bill;
				break;
			case Model\Regulation::class;
				return $simpleXML->action;
				break;
			case Model\Hearing::class;
				return $simpleXML->bill;
		}
	}

	/**
	 * Create a model object for each item in the feed.
	 *
	 * @param  array $feed array of SimpleXMLElement's
	 *
	 * @return void
	 */
	public static function createModels( $feed, $modelClass ) {
		$models = array();
		foreach ( $feed as $item ) {
			$model = new $modelClass();
			switch ( $modelClass ) {
				case Model\Legislation::class:
					$model = self::fillLegislation( $model, $item );
					break;
				case Model\Regulation::class;
					$model = self::fillRegulation( $model, $item );
					break;
				case Model\Hearing::class;
					$model = self::createHearingModels( $item );
					break;
			}
			if ( is_array( $model ) ) {
				// merge arrays to support nested models, e.g. Hearings:
				$models = array_merge( $models, $model );
			} else {
				$models[] = $model;
			}

		}

		return $models;
	}

	/**
	 * Populate Regulation members from SimpleXMLElement.
	 *
	 * @param  Regulation $reg obect to be filled
	 * @param  SimpleXMLElement $el property source
	 *
	 * @return Regulation
	 */
	public static function fillRegulation( Model\Regulation $reg, \SimpleXMLElement $el ) {
		$reg->external_id       = Xml::findSingle( $el, '@id' );
		$reg->tracking_key      = Xml::findSingle( $el, '@tracking_key' );
		$reg->state             = Xml::findSingle( $el, 'state' );
		$reg->agency_name       = Xml::findSingle( $el, 'agency_name' );
		$reg->type              = Xml::findSingle( $el, 'type' );
		$reg->state_action_type = Xml::findSingle( $el, 'state_action_type' );

		$full_text = Xml::findSingle( $el, 'full_texts/full_text' );
		if ( $full_text ) {
			$reg->full_text_id        = Xml::findSingle( $full_text, '@id' );
			$reg->full_text_url       = Xml::findSingle( $full_text, 'full_text_url' );
			$reg->full_text_local_url = Xml::findSingle( $full_text, 'full_text_local_url' );
			$reg->full_text_type      = Xml::findSingle( $full_text, 'full_text_type' );
		}

		$reg->description = Xml::findSingle( $el, 'description' );

		$reg->code_citation     = Xml::findSingle( $el, 'code_citations/code_citation' );
		$reg->register_date     = Xml::findSingle( $el, 'register/@date' );
		$reg->register_citation = Xml::findSingle( $el, 'register/citation' );
		$reg->register_url      = Xml::findSingle( $el, 'register/url' );

		$reg->setTexts( self::createActionTextModels( $el ) );
		$reg->setMatches( self::createProfileMatchModels( $el ) );
		$reg->setEntityText(self::createEntityTextModel($reg->full_text_url,$reg->full_text_local_url));

		return $reg;
	}

	/**
	 * Creates entity text model and returns html content of url
	 * @param full_text_$url
	 * @return array
	 */
	private static function createEntityTextModel($full_text_url,$full_text_local_url){
		$models = [];
		if ($full_text_url !== '' || $full_text_local_url !== '') {
			$model = new EntityText();
			try {
				if ( $full_text_url !== '' ) {
					$model->getContent( $full_text_url );
				} else {
					$model->getContent( $full_text_local_url );
				}
			}catch (\Exception $e){
				 return $models;
			}
			if($model->content !== NULL) {
				$models[] = $model;
			}
		}
		return $models;
	}
	/**
	 *
	 * @param Model\ProfileMatch $match
	 * @param \SimpleXMLElement $el
	 *
	 * @return Model\ProfileMatch
	 */
	private static function fillProfileMatch( Model\ProfileMatch $match, \SimpleXMLElement $el ) {
		$match->external_id = Xml::findSingle( $el, '@id' );

		$pname = Xml::findSingle( $el, 'pname' );
		//Regs delimit pname fields with '>', else use '|'
		$delimiter    = ( strpos( $pname, '|' ) === false ) ? '>' : '|';
		$pname_fields = explode( $delimiter, $pname );

		$match->pname = array_pop( $pname_fields );
		//find in database
		$user_client         = new Model\UserClients();
		$user_client->client = array_shift( $pname_fields );
		if ( $user_client->returnID() === false ) {
			$user_client->save();
			$user_client->insertClientStates();
		}
		$match->client_id           = $user_client->id;
		$keywords                   = Xml::find( $el, '*/keyword' );
		$client_settings            = new Model\ClientSettings();
		$client_settings->client_id = $user_client->id;
		$client_settings->category  = $match->pname;
		$client_settings->type      = 'category';
		$client_settings->save();
		if ( is_array( $keywords ) ) {
			foreach ( $keywords as $keyword ) {
				$client_settings_keywoard            = new Model\ClientSettings();
				$client_settings_keywoard->client_id = $match->client_id;
				$client_settings_keywoard->category  = $keyword;
				$client_settings_keywoard->type      = 'keyword';
				$client_settings_keywoard->save();
			}
			$keywords =
				array_map( function ( $keyword ) {
					// TODO move above foreach inside this

					// bill profile keywords
					$model          = new Model\ProfileKeyword();
					$model->keyword = $keyword;

					//create client settings for keywords
					return $model;
				}, $keywords );

			$match->setKeywords( $keywords );
		}

		return $match;
	}

	private static function fillLastUpdated(LastUpdated $last_updated,$el){
		$pname = Xml::findSingle( $el, 'pname' );
		//Regs delimit pname fields with '>', else use '|'
		$delimiter    = ( strpos( $pname, '|' ) === false ) ? '>' : '|';
		$pname_fields = explode( $delimiter, $pname );

		$user_client         = new Model\UserClients();
		$user_client->client = array_shift( $pname_fields );
		if ( $user_client->returnID() === false ) {
			$user_client->save();
			$user_client->insertClientStates();
		}
		$last_updated->import_id           = '1';
		$last_updated->client_id           = $user_client->id;
		return $last_updated;
	}

	private static function createLastUpdatedModels(\SimpleXMLElement $el){
		$profiles = Xml::find( $el, 'profiles/profile' );
		if ( is_a( $profiles, 'SimpleXMLElement' ) ) {
			$profiles = [ $profiles ];
		}

		$models = array();

		if ( is_array( $profiles ) ) {
			foreach ( $profiles as $profile ) {
				$model = new Model\LastUpdated();
				self::fillLastUpdated($model,$profile);
				$models[] = $model;
			}
		}
		return $models;
	}

	private static function fillBillMatch( Model\RelatedBills $match, \SimpleXMLElement $el ) {

		$match->url    = Xml::findSingle( $el, 'url' );
		$match->type   = Xml::findSingle( $el, 'type' );
		$match->number = Xml::findSingle( $el, 'number' );


		return $match;
	}


	/**
	 * Populate Legislation members from SimpleXMLElement.
	 *
	 * @param  Legislation $leg obect to be filled
	 * @param  SimpleXMLElement $el property source
	 *
	 * @return Legislation
	 */
	public static function fillLegislation( Model\Legislation $leg, \SimpleXMLElement $el ) {
		$leg->external_id         = Xml::findSingle( $el, '@id' );
		if($leg->getSessionState() === '0'){
			return null;
		}
		$leg->session             = Xml::findSingle( $el, 'number/session' );
		$leg->state               = Xml::findSingle( $el, 'number/code' );
		$leg->type                = Xml::findSingle( $el, 'number/type' );
		$leg->number              = Xml::findSingle( $el, 'number/number' );
		$leg->title               = Xml::findSingle( $el, 'title' );
		$leg->abstract            = Xml::findSingle( $el, 'abstract' );
		$leg->full_text_url       = Xml::findSingle( $el, 'billurl' );
		$leg->sponsor_name        = Xml::findSingle( $el, 'sponsor/name' );
		$leg->sponsor_url         = Xml::findSingle( $el, 'sponsor/URL' );
		$leg->status_date         = Xml::findSingle( $el, 'status/statusdt' );
		$leg->status_val          = Xml::findSingle( $el, 'status/statusval' );
		$leg->status_standardkey  = Xml::findSingle( $el, 'status/standardkey' );
		$leg->status_standard_val = Xml::findSingle( $el, 'status/standardval' );
		$leg->status_val          = Xml::findSingle( $el, 'status/statusval' );
		$leg->status_url          = Xml::findSingle( $el, 'statusurl' );

		//TODO here is fix needed check if session is expired otherwise lets skip it

		$leg->setMatches( self::createProfileMatchModels( $el ) );
		$leg->setBills( self::createBillsMatchModels( $el ) );
		$leg->setEntityText(self::createEntityTextModel($leg->full_text_url,$leg->full_text_url));

		if(!empty($leg->getEntityText())){
			$leg->textUploaded = TRUE;
		}
		return $leg;
	}

	private static function createHearingModels( $el ) {
		$models                  = array();
		$legislation_external_id = Xml::findSingle( $el, '@id' );
		$state                   = Xml::findSingle($el,'number/code'); //adding state because its easier to show it for calendar if there is no leg associated with it

		$hearings = Xml::find( $el, '*/hearing' );
		if ( is_a( $hearings, 'SimpleXMLElement' ) ) {
			$hearings = [ $hearings ];
		}

		if ( is_array( $hearings ) ) {
			foreach ( $hearings as $hearing ) {
				$model                          = new Model\Hearing();
				$model->legislation_external_id = $legislation_external_id;
				$model->state                   = $state;

				self::fillHearing( $model, $hearing );
				$model->setMatches( self::createProfileMatchModels( $el ) );
				$models[] = $model;
			}
		}

		return $models;
	}

	public static function fillHearing( Model\Hearing $hea, \SimpleXMLElement $el ) {
		$rawDate        = Xml::findSingle( $el, 'date' );
		$hea->date      = date( 'Y-m-d', strtotime( $rawDate ) );
		$hea->time      = Xml::findSingle( $el, 'time' );
		$hea->house     = Xml::findSingle( $el, 'house' );
		$hea->committee = Xml::findSingle( $el, 'committee' );
		$hea->place     = Xml::findSingle( $el, 'place' );

		// Inconsitent with other entities, no external ID exists, so fake it:
		$hea->external_id = md5( $hea->date . $hea->time . $hea->house . $hea->committee );
	}


	/**
	 * Creates ActionText models for each one represented in the feed snippet.
	 *
	 * Delegates the mapping of XML fields to model fields.
	 *
	 * @param \SimpleXMLElement $el
	 *
	 * @return \MainStreetAdvocates\Model\ActionText[]
	 */
	private static function createActionTextModels( \SimpleXMLElement $el ) {
		$actionTexts = Xml::find( $el, 'action_texts/action_text' );
		if ( is_a( $actionTexts, 'SimpleXMLElement' ) ) {
			$actionTexts = [ $actionTexts ];
		}

		$models = array();
		if ( is_array( $actionTexts ) ) {
			foreach ( $actionTexts as $actionText ) {
				$model = new Model\ActionText();
				self::fillActionText( $model, $actionText );
				$models[] = $model;
			}
		}

		return $models;
	}

	/**
	 * Updates an ActionText model with data from a feed snippet.
	 *
	 * @param \MainStreetAdvocates\Model\ActionText $actionText
	 * @param \SimpleXMLElement $el
	 */
	private static function fillActionText( Model\ActionText $actionText, \SimpleXMLElement $el ) {
		$actionText->external_id    = Xml::findSingle( $el, '@id', 0 );
		$actionText->original_url   = Xml::findSingle( $el, 'action_text_url' );
		$actionText->statetrack_url = Xml::findSingle( $el, 'action_text_local_url' );
		$actionText->type           = Xml::findSingle( $el, 'action_text_type' );
	}

	/**
	 * Creates ProfileMatch models for each one represented in the feed snippet.
	 *
	 * Delegates the mapping of XML fields to model fields.
	 *
	 * @param \SimpleXMLElement $el
	 *
	 * @return \MainStreetAdvocates\Model\ProfileMatch[]
	 */
	private static function createProfileMatchModels( \SimpleXMLElement $el ) {
		$profiles = Xml::find( $el, 'profiles/profile' );
		if ( is_a( $profiles, 'SimpleXMLElement' ) ) {
			$profiles = [ $profiles ];
		}

		$models = array();

		if ( is_array( $profiles ) ) {
			foreach ( $profiles as $profile ) {

				$model = new Model\ProfileMatch();
				self::fillProfileMatch( $model, $profile );
				$models[] = $model;
			}
		}

		return $models;
	}

	/**
	 * Creates RelatedBills models for each one represented in the feed snippet.
	 *
	 * Delegates the mapping of XML fields to model fields.
	 *
	 * @param \SimpleXMLElement $el
	 *
	 * @return \MainStreetAdvocates\Model\BillMatch[]
	 */

	private static function createBillsMatchModels( \SimpleXMLElement $el ) {
		$bills = Xml::find( $el, 'relatedbills/relatedbill' );

		if ( is_a( $bills, 'SimpleXMLElement' ) ) {
			$bills = [ $bills ];
		}

		$models = array();

		if ( is_array( $bills ) ) {
			foreach ( $bills as $bill ) {
				$model = new Model\RelatedBills();

				self::fillBillMatch( $model, $bill );
				$models[] = $model;
			}
		}

		return $models;
	}


}