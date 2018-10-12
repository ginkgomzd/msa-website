<?php
namespace MainStreetAdvocates;

use MainStreetAdvocates\SimpleXMLHelper as Xml;

class FeedXmlModelMaps {

  /**
   * Determines the type of feed in the passed path and returns an appropriate
   * Model classname.
   * @param $fileContent xml string
   * @return ::class()
   * @throws \Exception
   * For unrecognized feed type.
   */
  public static function getFeedType($fileContents) {
    // Only regulation feeds are known to start with an actions tag.
    if (strpos($fileContents, '<actions>') === 0) {
      return Model\Regulation::class;
    }
    // Legislation and hearing feeds are very similar, but only the former has
    // sponsor tags.
    elseif (strpos($fileContents, '<sponsor>')) {
      return Model\Legislation::class;
    }
    // If it is not a legislation feed and it has one or more hearings tags,
    // then it is a hearing feed. Note that a self-closing hearing tag
    // (<hearings/>) is valid.
    elseif (strpos($fileContents, '<hearings')) {
      return Model\Hearing::class;
    }
    else {
      throw new \Exception('Feed type unknown.');
    }
  }

  public static function getRootElement($feedType, $simpleXML) {
    switch ($feedType) {
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
   * @param  array $feed array of SimpleXMLElement's
   * @return void
   */
  public static function createModels($feed, $modelClass) {
    $models = array();
    foreach ($feed as $item) {
      $model = new $modelClass();
      switch ($modelClass) {
        case Model\Legislation::class:
          $model = self::fillLegislation($model, $item);
          break;
        case Model\Regulation::class;
          $model = self::fillRegulation($model, $item);
          break;
        case Model\Hearing::class;
          $model = self::createHearingModels($item);
          break;
      }
      if (is_array($model)) {
        // merge arrays to support nested models, e.g. Hearings:
        $models = array_merge($models, $model);
      }
      else {
        $models[] = $model;
      }

    }
    return $models;
  }

  /**
   * Populate Regulation members from SimpleXMLElement.
   * @param  Regulation  $reg obect to be filled
   * @param  SimpleXMLElement $el  property source
   * @return Regulation
   */
  public static function fillRegulation(Model\Regulation $reg, \SimpleXMLElement $el) {
    $reg->external_id = Xml::findSingle($el, '@id');
    $reg->tracking_key = Xml::findSingle($el, '@tracking_key');
    $reg->state = Xml::findSingle($el, 'state');
    $reg->agency_name = Xml::findSingle($el, 'agency_name');
    $reg->type = Xml::findSingle($el, 'type');
    $reg->state_action_type = Xml::findSingle($el, 'state_action_type');

    $full_text = Xml::findSingle($el, 'full_texts/full_text');
    if ($full_text) {
      $reg->full_text_id = Xml::findSingle($full_text, '@id');
      $reg->full_text_url = Xml::findSingle($full_text, 'full_text_url');
      $reg->full_text_local_url = Xml::findSingle($full_text, 'full_text_local_url');
      $reg->full_text_type = Xml::findSingle($full_text, 'full_text_type');
    }

    $reg->description = Xml::findSingle($el, 'description');

    $reg->code_citation = Xml::findSingle($el, 'code_citations/code_citation');
    $reg->register_date = Xml::findSingle($el, 'register/@date');
    $reg->register_citation = Xml::findSingle($el, 'register/citation');
    $reg->register_url = Xml::findSingle($el, 'register/url');

    $reg->setTexts(self::createActionTextModels($el));
    $reg->setMatches(self::createProfileMatchModels($el));

    return $reg;
  }

  private static function fillProfileMatch(Model\ProfileMatch $match, \SimpleXMLElement $el) {
    $match->external_id = Xml::findSingle($el, '@id');

    $pname = Xml::findSingle($el, 'pname');
    //Regs delimit pname fields with '>', else use '|'
    $delimiter = (strpos($pname, '|') === FALSE) ? '>' : '|';
    $pname_fields = explode($delimiter, $pname);

    $match->pname = array_pop($pname_fields);
    $match->client = array_shift($pname_fields);

    $keywords = Xml::find($el, '*/keyword');
    if (is_array($keywords)) {
      $keywords =
        array_map(function($keyword){
          $model = new Model\ProfileKeyword();
          $model->keyword = $keyword;
          return $model;
        }, $keywords);

      $match->setKeywords($keywords);
    }

    return $match;
  }

    /**
   * Populate Legislation members from SimpleXMLElement.
   * @param  Legislation  $leg  obect to be filled
   * @param  SimpleXMLElement $el  property source
   * @return Legislation
   */
  public static function fillLegislation(Model\Legislation $leg, \SimpleXMLElement $el) {
    $leg->external_id = Xml::findSingle($el, '@id');
    $leg->session = Xml::findSingle($el, 'number/session');
    $leg->state = Xml::findSingle($el, 'number/code');
    $leg->type = Xml::findSingle($el, 'number/type');
    $leg->number = Xml::findSingle($el, 'number/number');
    $leg->title = Xml::findSingle($el, 'title');
    $leg->abstract = Xml::findSingle($el, 'abstract');
    $leg->full_text_url = Xml::findSingle($el, 'billurl');
    $leg->sponsor_name = Xml::findSingle($el, 'sponsor/name');
    $leg->sponsor_url = Xml::findSingle($el, 'sponsor/URL');
    $leg->status_date = Xml::findSingle($el, 'status/statusdt');
    $leg->status_val = Xml::findSingle($el, 'status/statusval');
    $leg->status_standardkey = Xml::findSingle($el, 'status/standardkey');
    $leg->status_standard_val = Xml::findSingle($el, 'status/standardval'); 
    $leg->status_val = Xml::findSingle($el, 'status/statusval');
    $leg->status_url = Xml::findSingle($el, 'statusurl');

    $leg->setMatches(self::createProfileMatchModels($el));

    return $leg;
  }

  private static function createHearingModels($el) {
    $models = array();
    $legislation_external_id = Xml::findSingle($el, '@id');

    $hearings = Xml::find($el, '*/hearing');
    if (is_a($hearings, 'SimpleXMLElement')) {
      $hearings = [$hearings];
    }

    if (is_array($hearings)) {
      foreach($hearings as $hearing) {
        $model = new Model\Hearing();
        $model->legislation_external_id = $legislation_external_id;
        self::fillHearing($model, $hearing);
        $model->setMatches(self::createProfileMatchModels($el));
        $models[] = $model;
      }
    }
    return $models;
  }

  public static function fillHearing(Model\Hearing $hea, \SimpleXMLElement $el) {
    $rawDate = Xml::findSingle($el, 'date');
    $hea->date = date('Y-m-d', strtotime($rawDate));
    $hea->time = Xml::findSingle($el, 'time');
    $hea->house = Xml::findSingle($el, 'house');
    $hea->committee = Xml::findSingle($el, 'committee');
    $hea->place = Xml::findSingle($el, 'place');

    // Inconsitent with other entities, no external ID exists, so fake it:
    $hea->external_id = md5($hea->date . $hea->time . $hea->house . $hea->committee);
  }

  /**
   * Creates ActionText models for each one represented in the feed snippet.
   *
   * Delegates the mapping of XML fields to model fields.
   *
   * @param \SimpleXMLElement $el
   * @return \MainStreetAdvocates\Model\ActionText[]
   */
  private static function createActionTextModels(\SimpleXMLElement $el) {
    $actionTexts = Xml::find($el, 'action_texts/action_text');
    if (is_a($actionTexts, 'SimpleXMLElement')) {
      $actionTexts = [$actionTexts];
    }

    $models = array();
    if (is_array($actionTexts)) {
      foreach ($actionTexts as $actionText) {
        $model = new Model\ActionText();
        self::fillActionText($model, $actionText);
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
  private static function fillActionText(Model\ActionText $actionText, \SimpleXMLElement $el) {
    $actionText->external_id = Xml::findSingle($el, '@id', 0);
    $actionText->original_url = Xml::findSingle($el, 'action_text_url');
    $actionText->statetrack_url = Xml::findSingle($el, 'action_text_local_url');
    $actionText->type = Xml::findSingle($el, 'action_text_type');
  }

  /**
   * Creates ProfileMatch models for each one represented in the feed snippet.
   *
   * Delegates the mapping of XML fields to model fields.
   *
   * @param \SimpleXMLElement $el
   * @return \MainStreetAdvocates\Model\ProfileMatch[]
   */
  private static function createProfileMatchModels(\SimpleXMLElement $el) {
    $profiles = Xml::find($el, 'profiles/profile');
    if (is_a($profiles, 'SimpleXMLElement')) {
      $profiles = [$profiles];
    }

    $models = array();
    if (is_array($profiles)) {
      foreach($profiles as $profile) {
        $model = new Model\ProfileMatch();
        self::fillProfileMatch($model, $profile);
        $models[] = $model;
      }
    }

    return $models;
  }
}
