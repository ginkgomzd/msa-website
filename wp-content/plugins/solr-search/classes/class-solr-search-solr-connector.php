<?php

use Solarium\Client;
use Solarium\Exception\HttpException;

require_once 'DataImport.php';

class Solr_Search_Connector {

	/*
	 *
	 */
	private $solr = '';

	/*
	 *
	 */
	public $core_prefix;

	/**
	 * @var mixed|void
	 */
	public $server_host;

	/**
	 * @var mixed|void
	 */
	public $server_port;

	/**
	 * @var array
	 */
	private $client_config = array();

	/**
	 * @var
	 */
	private $exclude_categories;

	/**
	 * @var
	 */
	public $client;

	/**
	 * Solr_Search_Connector constructor.
	 */
	public function __construct() {
		$this->core_prefix = get_option( 'wpt_solr_search_core_prefix', 'core_' );
		$this->server_host = get_option( 'wpt_solr_search_server_host', 'localhost' );
		$this->server_port = get_option( 'wpt_solr_search_server_port', 8983 );
	}

	/**
	 * Configures Solr endpoint for particular customer / staff
	 * based on plugin settings
	 *
	 * @param $client_id
	 *
	 * @return array
	 */
	private function setEndPoint( $client_id ) {
		$path = '';
		if ( $client_id === null ) {
			$path = '/solr/core';
		} else {
			$path = '/solr/' . $this->core_prefix . $client_id;
		}
		$client_config = array(
			'endpoint' => array(
				'localhost' => array(
					'host' => $this->server_host,
					'port' => $this->server_port,
					'path' => $path,
				)
			)
		);

		return $client_config;
	}

	/**
	 *
	 * @param null $client_id
	 */
	public function createCore($client_id = null){
		$config = array(
			'endpoint' => array(
				'localhost' => array(
					'host' => $this->server_host,
					'port' => $this->server_port,
					'path' => '/solr/',
					'timeout'=> 30000
				)
			)
		);
		$this->client = new Client($config);
		$coreAdminQuery = $this->client->createCoreAdmin()->setTimeAllowed(30000);
		$createAction = $coreAdminQuery->createCreate();
		$corename = $this->core_prefix . $client_id;
		$createAction->setCore($corename);
		$createAction->setShard("1");
		$createAction->setConfigSet("template");
		$coreAdminQuery->setAction($createAction);
		try {
			$result = $this->client->coreAdmin( $coreAdminQuery );
			if ($result){
				return true;
			}
		}catch ( HttpException $httpexec){
			return false;
		}
	}

	/**
	 * @param null $client_id
	 *
	 * @return bool
	 */
	public function pingCore( $client_id = null ) {
		$this->client = new Client( $this->setEndPoint( $client_id ) );
		try {
			$ping   = $this->client->createPing();
			$result = $this->client->ping( $ping );
			if ( $result ) {
				return true;
			} else {
				return false;
			}
		} catch ( HttpException $e ) {
			return false;
		}
	}

	/**
	 * Builds string for categories that are disabled on user settings
	 *
	 * @param $user_categories array of categories that should be ignored
	 *
	 * @return string
	 */
	public function buildQueryCategory( $user_categories ) {
		$this->exclude_categories = ( '-pname:("' . implode( '" OR "', $user_categories ) . '")' );
	}

	/**
	 *
	 * @param null $core
	 * @return bool
	 */
	public function reindexCore( $core = null ) {
		$this->client->registerQueryType( DataImport::QUERY_DATAIMPORT, new DataImport );
		$query = $this->client->createQuery( DataImport::QUERY_DATAIMPORT );
		$query->setTimeAllowed(30000);
		$query->setClean( true );
		$query->setCommit( true );
		$query->setOptimize( true );
		$query->setCustomParameters( [ 'core_client_id' => $core ] );
		$query->setCommand( DataImport::COMMAND_FULL_IMPORT );
		$request = $this->client->createRequest( $query );
		$result  = $this->client->executeRequest( $request );
		$keepRequesting = true;
		while($keepRequesting){
			sleep(1);
			$result = $this->client->executeRequest($request);
			if(strpos($result->getBody(),'Indexing completed.') !== false){
				$keepRequesting = false;
			}
		}
		if($result->getStatusMessage() === 'OK'){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * @param null $exclude_states
	 *
	 * @return mixed
	 */
	public function upcomingHearings($exclude_states = null){
		$query = $this->client->createSelect();
		$_query = 'entity_type:"hearing"';
		if ( $this->exclude_categories !== null ) {
			$_query .= ' AND ' . $this->exclude_categories;
		}
		if ( $exclude_states !== null ) {
			$_query .= ' AND ' . ( '-state:("' . implode( '" OR "', $exclude_states ) . '")' );
		}
		$query->setQuery( $_query );
		$query->setFields( [  'hearing_id' ] );


		$query->createFilterQuery( 'date' )->setQuery( 'date:[NOW TO *]' );
		$resultset = $this->client->execute( $query );
		return $resultset->getNumFound();
	}
	/**
	 * @param $document_type
	 * @param null $category
	 * @param string $federal
	 * @param null $search
	 * @param $current_page
	 * @param array $order
	 *
	 * @return mixed
	 */
	public function querySolr( $document_type, $category = null, $federal = '', $search = null, $current_page, $order = [], $status = null ,$length = 10,$exclude_states = null) {
		$query = $this->client->createSelect();

		$_query = 'entity_type:"' . $document_type . '" ';

		if ( $search !== null ) {
			$_query .= 'AND _text_:"*' . $search . '*"';
		}
		if ( $this->exclude_categories !== null ) {
			$_query .= ' AND ' . $this->exclude_categories;
		}
		if ( $exclude_states !== null ) {
			$_query .= ' AND ' . ( '-state:("' . implode( '" OR "', $exclude_states ) . '")' );
		}

		$query->setQuery( $_query );

		$query->setFields( [ $document_type . '_id' ] );

		if ( $category !== null ) {
			// create a filterquery
			$category = ( '("' . implode( '" OR "', explode( ',', $category ) ) . '")' );
			$query->createFilterQuery( 'pname' )->setQuery( 'pname:' . $category );
		}


		if ( $federal !== '' ) {
			$federal = ( '("' . implode( '" OR "', explode( ',', $federal ) ) . '")' );
			$query->createFilterQuery( 'state' )->setQuery( 'state:' . $federal . '' );
		}

		if ( $status !== null ) {
			/*
			 *The brackets around a query determine its inclusiveness.
			 *Square brackets [ & ] denote an inclusive range query that matches values including the upper and lower bound.
			 *Curly brackets { & } denote an exclusive range query that matches values between the upper and lower bounds, but excluding the upper and lower bounds themselves.
			 *You can mix these types so one end of the range is inclusive and the other is exclusive. Here’s an example: count:{1 TO 10]
			 */
			$status = ( '[' . implode( ' TO ', $status ) . '}' );
			$query->createFilterQuery( 'status_standardkey' )->setQuery( 'status_standardkey:' . $status . '' );
		}
		//pagination and sorting
		$query->setRows( $length );
		$query->setStart( $current_page );

		if ( empty( $order ) ) {
			$query->addSort( $document_type . '_id', $query::SORT_ASC );
		} else {
			if ( $order['order'] === 'desc' ) {
				$sort = $query::SORT_DESC;
			} else {
				$sort = $query::SORT_ASC;
			}
			$query->addSort( $order['order_by'], $sort );
		}
		//print_r($query);
		$resultset = $this->client->execute( $query );
		return $resultset;
	}

	public function queryAutocomplete( $filter ) {
		$query = $this->client->createSuggester();
		$query->setQuery( $filter );
		$query->setDictionary( 'mySuggester' );
		$query->setBuild( true );
		$query->setCount( 10 );


		$resultset = $this->client->suggester( $query );

		$data = [];
		foreach ( $resultset as $dictionary => $terms ) {
			foreach ( $terms as $term => $termResult ) {
				foreach ( $termResult as $result ) {
					$data[] = $result['term'];
				}
			}
		}

		return $data;
	}

	/**
	 * Generates array list of results per each state and number of type(bills) that belong to that state
	 *
	 * @param null $document_type
	 * @param null $category
	 * @param null $status
	 * @param null $exclude_states
	 *
	 * @return array
	 */
	public function querySolrDashboardMain( $document_type = null, $category = null, $priority = null, $status = null, $exclude_states = null ) {
		$query = $this->client->createSelect();

		if ( $document_type === null ) {
			$_query = '*:*';
		} else {
			$_query = 'entity_type:"' . $document_type . '" ';
		}

		if ( $this->exclude_categories !== null ) {
			$_query .= ' AND ' . $this->exclude_categories;
		}

		if ( $priority !== null ) {
			if ( $priority == true ) {
				$query->createFilterQuery( 'priority' )->setQuery( 'priority:true' );
			} else {
				$query->createFilterQuery( 'priority' )->setQuery( 'priority:false' );
			}
		}

		if ( $exclude_states !== null ) {
			$_query .= ' AND ' . ( '-state:("' . implode( '" OR "', $exclude_states ) . '")' );
		}

		if ( $category !== null ) {
			// create a filter query
			$_category = ( '("' . implode( '" OR "', explode( ',', $category ) ) . '")' );
			$query->createFilterQuery( 'pname' )->setQuery( 'pname:' . $_category );
		}


		if ( $status !== null ) {
			/*
			 *The brackets around a query determine its inclusiveness.
			 *Square brackets [ & ] denote an inclusive range query that matches values including the upper and lower bound.
			 *Curly brackets { & } denote an exclusive range query that matches values between the upper and lower bounds, but excluding the upper and lower bounds themselves.
			 *You can mix these types so one end of the range is inclusive and the other is exclusive. Here’s an example: count:{1 TO 10]
			 */

			$url_status = $this->reverseCalculateStatus( $status );
			$status = ( '[' . implode( ' TO ', $status ) . '}' );
			$query->createFilterQuery( 'status_standardkey' )->setQuery( 'status_standardkey:' . $status . '' );
		} else {
			$url_status = null;
		}

		$query->setQuery( $_query );
		//print_r($query);
		// get the facetset component
		$facetSet = $query->getFacetSet();

		if ( $document_type === null ) {
			$facet = $facetSet->createFacetPivot( 'states' );
			$facet->addFields( 'entity_type,state' );
		} else {
			$facetSet->createFacetField( 'states' )->setField( 'state' );
		}

		// Set the number of results to return
		$query->setRows( 0 );

		$resultset = $this->client->execute( $query );
		$facetResult    = $resultset->getFacetSet()->getFacet( 'states' );
		$_data          = [];
		$_data['total'] = $resultset->getNumFound();
		$_resultdata    = [];
		if ( $document_type === null ) {
			$_tmpdata = [];
			foreach ( $facetResult as $pivot ) {
				foreach ( $pivot->getPivot() as $nextPivot ) {
					if ( ! key_exists( $nextPivot->getValue(), $_tmpdata ) ) {
						$_tmpdata[ $nextPivot->getValue() ] = [
							"legislation" => 0,
							"hearing"     => 0,
							"regulation"  => 0,
							"total"       => 0
						];
					}
					$_tmpdata[ $nextPivot->getValue() ][ $pivot->getValue() ] += $nextPivot->getCount();
					$_tmpdata[ $nextPivot->getValue() ]["total"]              += $nextPivot->getCount();
				}
			}

			foreach ( $_tmpdata as $state => $value ) {
				$_tmparray['id']          = "US-" . $state;
				$_tmparray['selectable']  = false;
				$_tmparray['value']       = $value['total'];
				$_tmparray['legislation'] = $value['legislation'];
				$_tmparray['regulation']  = $value['regulation'];
				$_tmparray['hearing']     = $value['hearing'];
				$_resultdata[]            = $_tmparray;
			}
		} else {

			foreach ( $facetResult as $state => $value ) {
				if ( $value !== 0 ) {
					$_tmparray['id']             = "US-" . $state;
					$_tmparray['modalUrl']       = get_site_url() . '/legislation-list?document_type=' . $document_type . '&document_category=' . $category . '&document_state=' . $state . '&document_status=' . $url_status;
					$_tmparray['selectable']     = true;
					$_tmparray['value']          = $value;
					$_tmparray[ $document_type ] = $value;
					$_resultdata[]               = $_tmparray;
				}
			}
		}
		$_data['data'] = $_resultdata;

		return $_data;
	}

	public function updateDocumentPriority( $document_type, $document_id, $priority = true ) {
		$query = $this->client->createSelect();

		$query->setQuery( $document_type . '_id:"' . $document_id . '"' );
		$query->setFields( [ 'id' ] );
		$query->setRows( 1 );
		$result = $this->client->select( $query );
		$id     = $result->getDocuments()[0]->id;
		if ( $id !== null ) {

			// get an update query instance
			$update = $this->client->createUpdate();

			$doc = $update->createDocument();
			$doc->setKey( 'id', $id );
			$doc->setField( 'priority', $priority );
			$doc->setFieldModifier( 'priority', 'set' );
			//add document and commit
			$update->addDocument( $doc )->addCommit();

			// this executes the query and returns the result
			$result = $this->client->update( $update );
			if ( $result->getStatus() === 0 ) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}

	}

	/**
	 *
	 */
	public function reverseCalculateStatus( $status ) {
		if ( $status[0] >= 90000 && $status[1] <= 100000 ) {
			$_clstatus = 10;
		} elseif ( $status[0] >= 80000 && $status[1] <= 90000 ) {
			$_clstatus = 9;
		} elseif ( $status[0] >= 70000 && $status[1] <= 80000 ) {
			$_clstatus = 8;
		} elseif ( $status[0] >= 60000 && $status[1] <= 70000 ) {
			$_clstatus = 7;
		} elseif ( $status[0] >= 50000 && $status[1] <= 60000 ) {
			$_clstatus = 6;
		} elseif ( $status[0] >= 40000 && $status[1] <= 50000 ) {
			$_clstatus = 5;
		} elseif ( $status[0] >= 30000 && $status[1] <= 40000 ) {
			$_clstatus = 4;
		} elseif ( $status[0] >= 20000 && $status[1] <= 30000 ) {
			$_clstatus = 3;
		} elseif ( $status[0] >= 10000 && $status[1] <= 20000 ) {
			$_clstatus = 2;
		} elseif ( $status[0] >= 0 && $status[1] <= 10000 ) {
			$_clstatus = 1;
		}

		return $_clstatus;
	}

	/*
	 *
	 */
	public function display_field( $data = array(), $post = false, $echo = true ) {

		// Get field info
		if ( isset( $data['field'] ) ) {
			$field = $data['field'];
		} else {
			$field = $data;
		}

		// Check for prefix on option name
		$option_name = '';
		if ( isset( $data['prefix'] ) ) {
			$option_name = $data['prefix'];
		}

		// Get saved data
		$data = '';
		if ( $post ) {

			// Get saved field data
			$option_name .= $field['id'];
			$option      = get_post_meta( $post->ID, $field['id'], true );

			// Get data to display in field
			if ( isset( $option ) ) {
				$data = $option;
			}

		} else {

			// Get saved option
			$option_name .= $field['id'];
			$option      = get_option( $option_name );

			// Get data to display in field
			if ( isset( $option ) ) {
				echo $data = $option;
			}

		}
	}
}