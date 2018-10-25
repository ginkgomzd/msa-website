<?php

if ( ! defined( 'ABSPATH' ) ) exit;

require_once('lib/SolrPHPClient/Apache/Solr/Service.php');

class Solr_Search_Connector {

    public $solr = NULL;
    public $solrMessage = '';
    public $offset = 0;
    public $limit = 20;
    public $queryResult = '';

    public function __construct() {
        $this->solrSeverConnection();
    }

    public function solrSeverConnection() {
        $server_username = get_option('wpt_solr_search_server_username');
        $server_password = get_option('wpt_solr_search_server_pass');
        $server_host = get_option('wpt_solr_search_server_host');
        $server_port = get_option('wpt_solr_search_server_port');
        $server_path = get_option('wpt_solr_search_server_path');
        $server_core =  get_option('wpt_solr_search_server_core');

        if( ($server_host && $server_port && $server_path) != null ) {
            $this->solr = new Apache_Solr_Service($server_host, $server_port, $server_path);

            if ( !$this->solr->ping() ) {
                $this->connectionMessage('Solr service not responding.');
            } else {
                $this->connectionMessage('Connected to Solr server.');
            }
        }
    }

    public function setQueries( $queries ) {

        foreach ($queries as $query) {
            $response = $this->solr->search($query, $this->offset, $this->limit);

            if ($response->getHttpStatus() == 200) {

                if ($response->response->numFound > 0) {

                    foreach ($response->response->docs as $doc) {

                        echo "$doc->id <br /> $doc->title <br/>";
                        $this->queryResult = $doc->id;
                    }
                    echo '<br />';
                }
            } else {
                echo $response->getHttpStatusMessage();
            }
        }
    }

    public function getQueriesResults() {

    }

    public function connectionMessage( $msg ) {
        $this->solrMessage = $msg;
    }

    public function getConnectionMessage() {

        return '<p>' . $this->solrMessage . '</p>';
    }
}