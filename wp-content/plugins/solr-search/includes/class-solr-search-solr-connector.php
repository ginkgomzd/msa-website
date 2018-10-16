<?php

require_once('lib/SolrPHPClient/Apache/Solr/Service.php');

class Solr_Search_Connector {

    private $solr = '';

    private function solr_connect() {
        $options = get_option('solr_search_server', array() );
        echo "<pre>";
            $options;
        echo "</pre>";
        if($options != null) {
            $solr = new Apache_Solr_Service('localhost', '7575', '/solr/collection1');
        }
    }

    public function display_field ( $data = array(), $post = false, $echo = true )
    {

        // Get field info
        if (isset($data['field'])) {
            $field = $data['field'];
        } else {
            $field = $data;
        }

        // Check for prefix on option name
        $option_name = '';
        if (isset($data['prefix'])) {
            $option_name = $data['prefix'];
        }

        // Get saved data
        $data = '';
        if ($post) {

            // Get saved field data
            $option_name .= $field['id'];
            $option = get_post_meta($post->ID, $field['id'], true);

            // Get data to display in field
            if (isset($option)) {
                $data = $option;
            }

        } else {

            // Get saved option
            $option_name .= $field['id'];
            $option = get_option($option_name);

            // Get data to display in field
            if (isset($option)) {
               echo $data = $option;
            }

        }
    }
}