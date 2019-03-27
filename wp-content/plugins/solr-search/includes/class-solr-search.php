<?php

if ( ! defined( 'ABSPATH' ) ) exit;


class Solr_Search {

	/**
	 * The single instance of Solr_Search.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Solr Connector
	 * @var object
	 * @access public
	 * @since 1.0.0
	 */
	public $solr_client = null;

	/**
	 * Settings class object
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for Javascripts.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct ( $file = '', $version = '1.0.0' ) {
		$this->_version = $version;
		$this->_token = 'solr_search';

		// Load plugin environment variables
		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Load frontend JS & CSS
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

		// Load admin JS & CSS
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		// Load API for generic admin functions
		if ( is_admin() ) {
			$this->admin = new Solr_Search_Admin_API();
		}


		// Handle localisation
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );

		//adding global plugin filter
		add_filter('solr_get_core',array($this,'getCoreStatus'),10,1);
		//Exposing filters for other plugins
		add_filter('solr_get_legislations',array( $this, 'getLegislations' ),10,9);
		add_filter('solr_get_hearings',array( $this, 'getHearings' ),10,8);
		add_filter('solr_get_regulations',array($this,'getRegulations'),10,8);

		add_filter('solr_get_dashboard_main',array($this,'getDashboardMain'),10,6);
		add_filter('solr_update_document_priority',array($this,'updateDocumentPriority'),10,3);
		add_filter('solr_typehead_suggestion',array($this,'typeheadSuggestion'),10,1);
		add_filter('solr_reindex_core',array($this,'solrReindexCore'),10,1);
		add_filter('solr_create_new_core',array($this,'solrCreateCore'),10,1);
		add_filter('solr_get_upcoming_hearings',array($this,'getUpcomingHearings'),10,2);
	} // End __construct ()

	public function solrCreateCore($client_id = null){
		return $this->solr_client->createCore($client_id);
	}
	/**
	 * @param null $client_id
	 *
	 * @return mixed
	 */
	public function getCoreStatus($client_id = null){
		return $this->solr_client->pingCore($client_id);
	}

	/**
	 * Calls client reindex call method, to fully reindex core
	 * @param null $core
	 */
	public function solrReindexCore($core = null){
		return $this->solr_client->reindexCore($core);
	}
	/**
	 * @param $filter
	 *
	 * @return mixed
	 */
	public function typeheadSuggestion($filter)
	{
		return $this->solr_client->queryAutocomplete($filter);
	}

	/**
	 * @param $document_type
	 * @param $document_id
	 * @param $document_priority
	 *
	 * @return mixed
	 */
	public function updateDocumentPriority($document_type,$document_id,$document_priority){
		return $this->solr_client->updateDocumentPriority($document_type,$document_id,$document_priority);
	}

	public function getUpcomingHearings($exclude_categories,$exclude_states){
		if ($exclude_categories !== null) {
			$this->solr_client->buildQueryCategory( $exclude_categories );
		}
		return $this->solr_client->upcomingHearings($exclude_states);
	}
	/**
	 * @param $document_type
	 * @param null $category
	 * @param null $status
	 * @param null $exclude_categories
	 * @param null $exclude_states
	 *
	 * @return mixed
	 */
	public function getDashboardMain($document_type,$category = null,$document_status = null, $priority = null,$exclude_categories = null,$exclude_states = null){
		if ($exclude_categories !== null) {
			$this->solr_client->buildQueryCategory( $exclude_categories );
		}

		return $this->solr_client->querySolrDashboardMain($document_type,$category,$priority,$document_status,$exclude_states);
	}


	/**
	 * @param null $category
	 * @param null $user_ignore_categories
	 * @param null $search
	 *
	 * @return mixed
	 */
	public function getHearings($category = null, $federal = '', $user_ignore_categories  = null,$search = null,$current_page = 1,$order = [],$length,$exclude_states = null){

		if ($user_ignore_categories !== null) {
			$this->solr_client->buildQueryCategory( $user_ignore_categories );
		}
		return $this->solr_client->querySolr('hearing',$category,$federal,$search,$current_page, $order,null,$length,$exclude_states);
	}

	/**
	 * @param null $category
	 * @param string $federal
	 * @param null $user_ignore_categories
	 * @param null $search
	 *
	 * @return mixed
	 */
	public function getRegulations($category = null, $federal = '', $user_ignore_categories  = null,$search = null,$current_page = 1,$order = [],$length,$exclude_states = null){

		if ($user_ignore_categories !== null) {
			$this->solr_client->buildQueryCategory( $user_ignore_categories );
		}
		return $this->solr_client->querySolr('regulation',$category,$federal,$search,$current_page, $order,null,$length,$exclude_states);
	}

	/**
	 * @param null $category
	 * @param string $federal
	 * @param null $user_ignore_categories
	 * @param null $search
	 * @param int $current_page
	 * @param array $order
	 *
	 * @return mixed
	 */
	public function getLegislations($category = null , $federal = '',$user_ignore_categories = null,$search = null,$current_page = 1,$order = [],$status = null,$length,$exclude_states = null){

		if ($user_ignore_categories !== null) {
			$this->solr_client->buildQueryCategory( $user_ignore_categories );
		}

		return $this->solr_client->querySolr('legislation',$category,$federal,$search,$current_page, $order,$status,$length,$exclude_states);
	}

	/**
	 * Wrapper function to register a new post type
	 * @param  string $post_type   Post type name
	 * @param  string $plural      Post type item plural name
	 * @param  string $single      Post type item single name
	 * @param  string $description Description of post type
	 * @return object              Post type class object
	 */
	public function register_post_type ( $post_type = '', $plural = '', $single = '', $description = '', $options = array() ) {

		if ( ! $post_type || ! $plural || ! $single ) return;

		$post_type = new Solr_Search_Post_Type( $post_type, $plural, $single, $description, $options );

		return $post_type;
	}

	/**
	 * Wrapper function to register a new taxonomy
	 * @param  string $taxonomy   Taxonomy name
	 * @param  string $plural     Taxonomy single name
	 * @param  string $single     Taxonomy plural name
	 * @param  array  $post_types Post types to which this taxonomy applies
	 * @return object             Taxonomy class object
	 */
	public function register_taxonomy ( $taxonomy = '', $plural = '', $single = '', $post_types = array(), $taxonomy_args = array() ) {

		if ( ! $taxonomy || ! $plural || ! $single ) return;

		$taxonomy = new Solr_Search_Taxonomy( $taxonomy, $plural, $single, $post_types, $taxonomy_args );

		return $taxonomy;
	}

	/**
	 * Load frontend CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return void
	 */
	public function enqueue_styles () {
		wp_register_style( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'css/frontend.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-frontend' );
	} // End enqueue_styles ()

	/**
	 * Load frontend Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function enqueue_scripts () {
		wp_register_script( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'js/frontend' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-frontend' );
	} // End enqueue_scripts ()

	/**
	 * Load admin CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_styles ( $hook = '' ) {
		wp_register_style( $this->_token . '-admin', esc_url( $this->assets_url ) . 'css/admin.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-admin' );
	} // End admin_enqueue_styles ()

	/**
	 * Load admin Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_scripts ( $hook = '' ) {
		wp_register_script( $this->_token . '-admin', esc_url( $this->assets_url ) . 'js/admin' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-admin' );
	} // End admin_enqueue_scripts ()

	/**
	 * Load plugin localisation
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_localisation () {
		load_plugin_textdomain( 'solr-search', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_localisation ()

	/**
	 * Load plugin textdomain
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain () {
	    $domain = 'solr-search';

	    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_plugin_textdomain ()

	/**
	 * Main Solr_Search Instance
	 *
	 * Ensures only one instance of Solr_Search is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Solr_Search()
	 * @return Main Solr_Search instance
	 */
	public static function instance ( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}
		return self::$_instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install () {
		$this->_log_version_number();
	} // End install ()

	/**
	 * Log the plugin version number.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number () {
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()

}