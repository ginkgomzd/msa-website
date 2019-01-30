<?php

namespace MainStreetAdvocates;

class SimpleXMLHelper {

  /**
   * Extends the behaviour of SimpleXMLElement::xpath().
   * Tries to un-box results whenever possible.
   * @param  SimpleXMLElement $xml
   * @param  String $path xpath query
   * @param  mixded $empty default value when result is empty.
   * @return mixed
   * FALSE if not found or error encountered in xpath().
   * a SimpleXMLElement when has children.
   * an array of values when multiple leaf-nodes are found.
   * a string if possible.
   * value of $empty, if empty.
   */
  public static function find(\SimpleXMLElement $xml, $path, $empty=NULL) {
    $attr = basename($path);
    $isAttribute = (strpos($attr, '@') !== FALSE);

    $found = $xml->xpath($path);

    if ($found === FALSE || empty($found)) {
      return FALSE;
    }

    if (count($found) === 1) {
      $found = array_pop($found);

      if ($found->count() === 0) {
        $found = trim($found);
      }

      if ($isAttribute && $found->count() === 1) {
        return trim($found);
      }
    } else {
      $found = array_map(function($item){
        if ($item->count() === 0) {
          $item = trim($item); // convert to string
        }
        return $item;
      }, $found);
    }

    if (empty($found) || $found === '') {
      return $empty;
    }

    return $found;
  }

/**
 * Returns FALSE if not found or if multiple results are found.
 * Defaults to empty string if found and empty.
 * See docs for find().
 *
 * @param  [type] $xml   [description]
 * @param  [type] $path  [description]
 * @param  [type] $empty [description]
 * @return [type]        [description]
 */
  public static function findSingle(\SimpleXMLElement $xml, $path, $empty='') {
    $found = self::find($xml, $path, $empty);
    if (is_array($found)) {
      return FALSE;
    }
    return $found;
  }
}
 ?>
