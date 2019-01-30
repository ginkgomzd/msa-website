<?php

/**
 * MainStreet Advocates functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package MainStreet_Advocates
 */

if ( ! function_exists( 'mainstreet_advocates_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function mainstreet_advocates_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on MainStreet Advocates, use a find and replace
		 * to change 'mainstreet-advocates' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'mainstreet-advocates', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'mainstreet-advocates' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'mainstreet_advocates_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'mainstreet_advocates_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function mainstreet_advocates_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'mainstreet_advocates_content_width', 640 );
}

add_action( 'after_setup_theme', 'mainstreet_advocates_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function mainstreet_advocates_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'mainstreet-advocates' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'mainstreet-advocates' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}

add_action( 'widgets_init', 'mainstreet_advocates_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function mainstreet_advocates_scripts() {
	wp_enqueue_style( 'mainstreet-advocates-style', get_stylesheet_uri() );

	wp_enqueue_script( 'mainstreet-advocates-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'mainstreet-advocates-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'mainstreet_advocates_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Addding Custom style and javascripts dependencies
 */

//Load styles
function theme_styles() {
	wp_enqueue_style( 'bootstrap_css', get_template_directory_uri() . '/css/bootstrap.min.css' );
	wp_enqueue_style( 'fontawesome_all_css', 'https://use.fontawesome.com/releases/v5.3.1/css/all.css' );
	wp_enqueue_style( 'main_css', get_template_directory_uri() . '/css/style.css' );
	wp_enqueue_style( 'datatables_css', get_template_directory_uri() . '/DataTables/datatables.min.css' );
	wp_enqueue_style( 'select2', get_template_directory_uri() . '/css/select2.min.css' );
	wp_enqueue_style( 'fancybox', '//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.css' );
	wp_enqueue_style( 'export', 'https://www.amcharts.com/lib/3/plugins/export/export.css' );
}

add_action( 'wp_enqueue_scripts', 'theme_styles' );

//Load jscripts
function theme_js() {

	global $wp_scripts;
	wp_enqueue_script( 'jquery_js', get_template_directory_uri() . '/js/jquery.js' );
	wp_enqueue_script( 'bootstrap_js', get_template_directory_uri() . '/js/bootstrap.min.js' );
	wp_enqueue_script( 'select2', get_template_directory_uri() . '/js/select2.min.js', true );
	wp_enqueue_script( 'datatable_js', get_template_directory_uri() . '/DataTables/datatables.min.js' );
	wp_enqueue_script( 'amchart', get_template_directory_uri() . '/js/ammap.js', true );
	wp_enqueue_script( 'fancybox', get_template_directory_uri() . '/js/jquery.fancybox.min.js', true );
	//wp_enqueue_script( 'usaLow', get_template_directory_uri() . '/js/usaLow.js' ,true);
	wp_enqueue_script( 'light', get_template_directory_uri() . '/js/light.js', true );
	wp_enqueue_script( 'export', get_template_directory_uri() . '/js/export.min.js', true );

}

add_action( 'wp_enqueue_scripts', 'theme_js' );

//// include custom jQuery
//function shapeSpace_include_custom_jquery() {
//
//	wp_deregister_script('jquery');
//	wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.3.1.min.js', array(), null, true);
//
//}
//add_action('wp_enqueue_scripts', 'shapeSpace_include_custom_jquery');

//function my_theme_scripts() {
//    wp_enqueue_script( 'my-great-script', get_template_directory_uri() . '/js/my-great-script.js', array( 'jquery' ), '1.0.0', true );
//}
//add_action( 'wp_enqueue_scripts', 'my_theme_scripts' );

include( 'inc/msa_rest_api.php' );

/**
 * Addding Company info to user data
 */

function custom_user_profile_fields( $user ) {

	global $wpdb;
	$clients       = "SELECT id,client FROM `user_clients`";
	$client_result = $wpdb->get_results( $clients, ARRAY_A );

	$json   = file_get_contents( get_site_url() . '/wp-content/themes/mainstreet-advocates/states.json' );
	$states = json_decode( $json );

	$default       = esc_attr( get_the_author_meta( 'company', $user->ID ) );
	$default_state = esc_attr( get_the_author_meta( 'state', $user->ID ) );

	?>
    <h3>Additional profile information</h3>
    <table class="form-table">
        <tr>
            <th><label for="company">Company Name</label></th>
            <td>
                <select class="regular-text" id="company" name="company">
					<?php if ( strlen( $default ) == 0 ) { ?>
                        <option value="" selected disabled>Select a company</option>
					<?php }
					foreach ( $client_result as $client ) {
						if ( $client["id"] === $default ) { ?>
                            <option value="<?php echo $client["id"] ?>"
                                    selected><?php echo $client["client"] ?></option>
						<?php } else { ?>
                            <option value="<?php echo $client["id"] ?>"><?php echo $client["client"] ?></option>
						<?php }
					} ?>

                </select>
            </td>
        </tr>
        <tr>
            <th><label for="company">State</label></th>
            <td>
                <select class="regular-text" id="state" name="state">
					<?php if ( strlen( $default_state ) > 0 ) { ?>
                        <option value="<?php echo $default_state ?>" selected><?php echo $default_state ?></option>
					<?php } else { ?>
                        <option value="" selected disabled>Select a state</option>
					<?php }
					foreach ( $states as $state ) { ?>
                        <option value="<?php echo $state->abbreviation; ?>"><?php echo $state->abbreviation; ?></option>
					<?php } ?>
                </select>
            </td>
        </tr>
    </table>
	<?php
}

add_action( 'show_user_profile', 'custom_user_profile_fields' );
add_action( 'edit_user_profile', 'custom_user_profile_fields' );
add_action( "user_new_form", "custom_user_profile_fields" );

function save_custom_user_profile_fields( $user_id ) {
	global $wpdb;
	# again do this only if admin
	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	// save my custom field
	// if company meta value changed then we should clean all records and update new ones e.g if user changed company
	// update_user_meta will return false if meta value is same is in database thats why we are doing true check
	if ( update_user_meta( $user_id, 'company', $_POST['company'] ) ) {
		$wpdb->query( "DELETE FROM user_settings WHERE user_id ={$user_id}" );
		$wpdb->query( $wpdb->prepare( "INSERT IGNORE INTO user_settings (user_id,type,category,isfrontactive,ismailactive) SELECT %s,type,category,isfrontactive,ismailactive FROM client_settings  WHERE client_id = %s", $user_id, $_POST['company'] ) );

	}
	if ( isset( $_POST['state'] ) ) {
		update_user_meta( $user_id, 'state', $_POST['state'] );
	}
}

add_action( 'user_register', 'save_custom_user_profile_fields' );
add_action( 'profile_update', 'save_custom_user_profile_fields' );


function am_enqueue_admin_styles() {

	wp_register_style( 'am_admin_bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css' );
	wp_enqueue_style( 'am_admin_bootstrap' );
	wp_enqueue_script( 'jquery_js', get_template_directory_uri() . '/js/jquery.js' );
}

add_action( 'admin_enqueue_scripts', 'am_enqueue_admin_styles' );

/*
add_action('admin_init', 'your_function');
function your_function(){
	add_settings_section(
		'eg_setting_section',
		__( 'Example settings section in reading', 'textdomain' ),
		'wpdocs_setting_section_callback_function',
		'general'
	);
/*add_settings_field(
	'diven',
	'För in mätarställningen här',
	'ac_add_field',
	'Anders-counter',
	'ac_div'
);
};
*/
// custom function  - returns profiles / categories matches function
function getCategoriesByID( $id ) {
	global $wpdb;
	$cat_query  = "SELECT pname FROM `profile_match` where entity_id = $id ";
	$categories = $wpdb->get_results( $cat_query, OBJECT );

	foreach ( $categories as $categorie ) {
		$name[] = $categorie->pname;
	}

	if ( $name !== null ) {
		return implode( ", ", $name );
	}
}

// custom function  - returns profiles / categories matches function
function getBillCategoriesByID( $id, $type, $output = 'string' ) {
	global $wpdb;
	$cat_query  = "SELECT pname FROM `profile_match` where entity_id = $id  AND entity_type = '{$type}'";
	$categories = $wpdb->get_results( $cat_query, OBJECT );

	foreach ( $categories as $categorie ) {
		$name[] = $categorie->pname;
	}

	if ( $name !== null ) {
		if ( $output === 'string' ) {
			return implode( ", ", $name );
		} else {
			return $name;
		}
	}
}

function getCategoriesByClient( $ent_name ) {
	global $wpdb;
	$ent_name = strtolower( $ent_name );
	//  $cat_query="SELECT pname FROM `profile_match` where client like '%$ent_name%' GROUP BY pname";
	$cat_query  = "SELECT pname FROM `profile_match` AS pm LEFT JOIN `user_clients` AS us ON pm.client_id = us.id where client like '%$ent_name%' GROUP BY pname";
	$categories = $wpdb->get_results( $cat_query, OBJECT );

	foreach ( $categories as $categorie ) {
		$name[] = $categorie->pname;
	}

	if ( $name !== null ) {
		return implode( ", ", $name );
	}
}

function getCategoriesByClient2( $ent_name ) {
	global $wpdb;
	$ent_name   = strtolower( $ent_name );
	$cat_query  = "SELECT * FROM `profile_match` where client like '%$ent_name%' GROUP BY pname";
	$categories = $wpdb->get_results( $cat_query, OBJECT );

	return $categories;

}

function userSettingsCategories( $user_id ) {
	global $wpdb;
	$categoies = $wpdb->get_results( "SELECT id,category FROM user_settings WHERE type = 'category' AND isfrontactive = 1 AND user_id = '$user_id'", OBJECT );

	return $categoies;
}

// custom function  - returns profiles / categories matches function
function getCategoriesByUser( $user_id ) {
	global $wpdb;
	$cat_query2 = "SELECT lfront FROM `user_profile` where user_id='$user_id' and lfront IS NOT NULL";

	$categories2 = $wpdb->get_results( $cat_query2, OBJECT );

	foreach ( $categories2 as $categorie2 ) {
		$name2[] = $categorie2->lfront;
	}

	if ( isset( $name2 ) and $name2 !== null ) {
		return implode( ", ", $name2 );
	}
}

// custom function  - returns keywords function
function getKeyword( $ent_id ) {
	global $wpdb;
	$key_query = "SELECT keyword FROM `profile_keyword` where profile_match_id = '$ent_id'";
	$keywords  = $wpdb->get_results( $key_query, OBJECT );

	foreach ( $keywords as $keyword ) {
		$key_name[] = $keyword->keyword;
	}

	if ( isset( $key_name ) && $key_name !== null ) {
		return implode( ", ", $key_name );
	}

}

/**
 * @param $id
 *
 * @return array|null|object|void
 */
function getProfileMatchPnameByID( $id ) {
	global $wpdb;
	$client_query = "SELECT pname FROM `profile_match` WHERE id = {$id}";
	$profilematch = $wpdb->get_row( $client_query, OBJECT );

	return $profilematch->pname;
}

function getClientSettings( $category, $type, $client_id ) {
	global $wpdb;
	$client_query = "SELECT id FROM `client_settings` WHERE client_id = {$client_id} AND type='{$type}' AND category = '{$category}'";
	$profilematch = $wpdb->get_row( $client_query, OBJECT );

	return $profilematch->id;
}

function updateUserSettings( $field, $type, $user_id ) {
	global $wpdb;
	$fields = explode( '_', stripslashes( $field ) );
	$id     = $fields[2];
	$group  = $fields[1];
	//$pname = getProfileMatchPnameByID($id);
	//update new settings
	$query  = "UPDATE `user_settings` SET `{$type}` = NOT `{$type}` WHERE id = '{$id}' AND user_id = {$user_id}";
	$result = $wpdb->query( "UPDATE `user_settings` SET `{$type}` = NOT `{$type}` WHERE id = '{$id}' AND user_id = {$user_id}" );

	$client_id = get_user_meta( get_current_user_id(), 'company', true );
	if ( $group === 'keyword' ) {
		$us_settings = $wpdb->get_results( "SELECT type,pname,profile_match_id FROM user_settings as us LEFT JOIN profile_keyword as pk ON us.category = pk.keyword LEFT JOIN profile_match as pm ON pk.profile_match_id = pm.id WHERE us.id = {$id} AND client_id = {$client_id}", OBJECT );
		// TODO refactor this!
		foreach ( $us_settings as $setting ) {
			if ( $setting->type === 'keyword' ) {
				$us_keywords = $wpdb->get_results( "SELECT * FROM user_settings WHERE category IN ( SELECT keyword FROM profile_match as pm LEFT JOIN profile_keyword as pk ON pm.id = pk.profile_match_id WHERE pm.id = {$setting->profile_match_id}) AND user_id = {$user_id}" );
				foreach ( $us_keywords as $us_keyword ) {
					if ( $us_keyword->isfrontactive === '0' || $us_keyword->ismailactive === '0' ) {
						echo $us_keyword->profile_match_id;
						$updatequery = "UPDATE user_settings as us,(SELECT * FROM profile_match WHERE id = {$setting->profile_match_id}) as pm SET `{$type}` = 0 WHERE category = pm.pname and user_id = {$user_id} and type = 'category'";
						$wpdb->query( $updatequery );
						break;
					}
				}
			}
		}
	}

}

/**
 * @param $field
 * @param $type
 * @param $client_id
 */
function updateprofileForClient( $field, $type, $client_id ) {
	global $wpdb;
	$fields = explode( '_', $field );
	$id     = $fields[2];
	$group  = $fields[1];
	//$pname = getProfileMatchPnameByID($id);
	$query  = "UPDATE `client_settings` SET `{$type}` = NOT `{$type}` WHERE id = '{$id}' AND client_id = {$client_id}";
	$result = $wpdb->query( $query );
}

// custom function  - returns keywords function
function getAllClients() {
	global $wpdb;
	$client_query = "SELECT id,client FROM user_clients";
	$client       = $wpdb->get_results( $client_query, OBJECT );

	return $client;
}

// custom function  - returns keywords function
function getCleint( $ent_id ) {
	global $wpdb;
	$client_query = "SELECT client FROM `profile_match` where entity_id = '$ent_id'";
	$client       = $wpdb->get_row( $client_query, OBJECT );


	return $client->client;
}

// custom function  - returns related documents function
function getRelatedBills( $ent_id ) {
	global $wpdb;
	$bill_query = "SELECT * FROM `related_bill` where legislation_id='$ent_id'";

	$bills = $wpdb->get_results( $bill_query, OBJECT );

	return $bills;

}

class MSABase {
	protected $wpdb;
	public $client_id;
	public $user_id;
	public $user_is_visitor;

	const HEARING = 'hearing';
	const LEGISLATION = 'legislation';
	const REGULATION = 'regulation';

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
	}

	/**
	 * base class for  dry
	 */
	public function getClientUsers( $client_id, array $fields ) {
		$users        = get_users( array(
			'meta_key'   => 'company',
			'meta_value' => $client_id
		) );
		$return_users = array();
		foreach ( $users as $user ) {
			foreach ( $fields as $field ) {
				$us[ $field ] = $user->{$field};
			}
			array_push( $return_users, $us );
		}

		return $return_users;
	}

	/**
	 * Returns list of admin or staff users
	 * @return array (object)
	 */
	protected function getStaffUsers() {
		$users = get_users( array(
			'role__in' => array( 'Administrator', 'Staff' )
		) );

		return $users;
	}

	/**
	 * check if user has setting for seeing bill : state, category
	 *
	 */
	protected function validateBillForUser( $user, $bill ) {

	}
}

class MSABill extends MSABase {
	/**
	 *
	 */
	public $categories = array();
	public $keywords = array();
	public $type;
	public $id;
	public $state;
	public $session;
	public $session_information; // relates to legislation session
	public $hearing_information; // relates to legislation hearing
	public $priority = false;
	public $notes = array();
	public $updated;
	public $approved;
	public $hidden = false;
	public $entity_type;
	public $bookmark_note = false;
	public $related_bills;
	public $legislation_status;
	public $last_updated;

	public function __construct( $entity_type, $fields = null, $client_id = null ) {
		parent::__construct();
		$this->entity_type = $entity_type;
		if ( ! is_null( $client_id ) ) {
			$this->client_id = $client_id;
		}
		if ( ! is_null( $fields ) ) {
			foreach ( $fields as $key => $value ) {
				$this->$key = $value;
			}
		}
		if ( $entity_type == MSABase::LEGISLATION ) {
			$this->getBillCategories();
			$this->getRelatedBills();
			$this->calculateStatus();
		}
	}

	public function getHearingForLegislation() {
		$this->hearing_information = $this->wpdb->get_row( $this->wpdb->prepare(
			"SELECT * FROM hearing
                        WHERE legislation_external_id = %s",
			$this->external_id ),
			OBJECT );
	}

	/**
	 *
	 */
	public function calculateStatus() {
		if ( isset( $this->status_standardkey ) === false ) {
			$this->legislation_status = 1;

			return;
		}
		$key = $this->status_standardkey;
		switch ( (int) $key ) {
			case ( (int) $key < 10000 ):
				$status = 1;
				break;
			case ( (int) $key >= 10000 && (int) $key < 20000 ):
				$status = 2;
				break;
			case ( (int) $key >= 20000 && (int) $key < 30000 ):
				$status = 3;
				break;
			case( (int) $key >= 30000 && (int) $key < 40000 ):
				$status = 4;
				break;
			case ( (int) $key >= 40000 && (int) $key < 50000 ):
				$status = 5;
				break;
			case ( (int) $key >= 50000 && (int) $key < 60000 ):
				$status = 6;
				break;
			case ( (int) $key >= 60000 && (int) $key < 70000 ):
				$status = 7;
				break;
			case ( (int) $key >= 70000 && (int) $key < 80000 ):
				$status = 8;
				break;
			case ( (int) $key >= 80000 && (int) $key < 90000 ):
				$status = 9;
				break;
			case ( (int) $key >= 90000 && (int) $key < 100000 ):
				$status = 10;
				break;
		}
		$this->legislation_status = $status;
	}

	/**
	 * Gets related bills associated with legislation document
	 * @return void
	 */
	public function getRelatedBills() {
		$this->related_bills = $this->wpdb->get_results( $this->wpdb->prepare(
			"SELECT * FROM related_bill
                    WHERE legislation_id = %s",
			$this->id ),
			OBJECT );
	}

	public function getHearingLegislation() {
		$this->legislation = $this->wpdb->get_row( $this->wpdb->prepare(
			"SELECT * FROM legislation
                    WHERE external_id = %s",
			$this->legislation_external_id ),
			OBJECT );
	}

	public function getSessionInformation() {
		$this->session_information = $this->wpdb->get_row( $this->wpdb->prepare(
			"SELECT * FROM session_info
                    WHERE id = %s",
			$this->session_id ),
			OBJECT );

	}

	public function getLatestUpdated() {
		$result = $this->wpdb->get_row( $this->wpdb->prepare(
			"SELECT MAX(curation_date) as last_update FROM unit_tests.last_updated AS lu
                    LEFT JOIN import_table AS it ON lu.import_table_id = it.id
                    WHERE document_id = %s 
                    AND entity_type = %s
                    AND curation_date IS NOT NULL",
			$this->id,
			$this->entity_type ), OBJECT );

		if ( $result ) {
			$this->last_updated = $result->last_update;
		}

	}

	public function getNotesForBill( $is_admin = false ) {
		if ( $is_admin ) {
			$notes = $this->wpdb->get_results( $this->wpdb->prepare(
				"SELECT * FROM bill_notes
                    WHERE entity_type = %s 
                    AND bill_id = %s
                    ORDER BY note_timestamp DESC",
				$this->entity_type,
				$this->id ),
				OBJECT );
		} else {
			$notes = $this->wpdb->get_results( $this->wpdb->prepare(
				"SELECT * FROM bill_notes
                    WHERE client_id = %s
                    AND entity_type = %s 
                    AND bill_id = %s
                    ORDER BY note_timestamp DESC",
				$this->client_id,
				$this->entity_type,
				$this->id ),
				OBJECT );
		}

		// if there is 1 or more notes for bill then set bookmark = true
		if ( ! empty( $notes ) ) {
			$this->bookmark_note = true;
		}
		foreach ( $notes as $note ) {
			array_push( $this->notes, new MSANotes( $note ) );
		}
	}

	// creates array of keywords for particular bill
	public function getKeywordsForCategory( $category_id ) {
		if ( ! empty( $this->categories ) ) {
			$keywords = $this->wpdb->get_results( $this->wpdb->prepare(
				"SELECT keyword FROM profile_keyword
                        WHERE profile_match_id = %s ",
				$category_id ),
				OBJECT );
			if ( ! is_null( $keywords ) ) {
				foreach ( $keywords as $keyword ) {
					if ( ! in_array( $keyword->keyword, $this->keywords ) ) {
						$this->keywords[] = $keyword->keyword;
					}
				}
			}
		}
	}

	public function setPrioritized() {
		if ( $this->priority ) {
			$result = $this->wpdb->delete( 'prioritized_bills', array(
				'bill_id'     => $_POST['id'],
				'user_id'     => get_current_user_id(),
				'entity_type' => $_POST['type'],
				'client_id'   => $this->client_id
			) );
		} else {
			$result = $this->wpdb->insert( 'prioritized_bills', array(
				'bill_id'     => $_POST['id'],
				'user_id'     => get_current_user_id(),
				'entity_type' => $_POST['type'],
				'client_id'   => $this->client_id
			) );
		}

		return $result;
	}


	public function getPrioritzed( $admin = false ) {
		if ( $admin ) {
			$row = $this->wpdb->get_row( $this->wpdb->prepare(
				"SELECT id FROM prioritized_bills
                    WHERE bill_id = %s 
                    AND entity_type = %s",
				$this->id,
				$this->entity_type ),
				OBJECT );
		} else {
			$row = $this->wpdb->get_row( $this->wpdb->prepare(
				"SELECT id FROM prioritized_bills
                    WHERE bill_id = %s 
                    AND client_id = %s
                    AND entity_type = %s",
				$this->id,
				$this->client_id,
				$this->entity_type ),
				OBJECT );
		}
		if ( ! is_null( $row ) ) {
			$this->priority = true;
		}
	}

	public function getHiddenStatus() {
		$_result = $this->wpdb->get_row( $this->wpdb->prepare(
			"SELECT id FROM hidden_bills
                    WHERE bill_id = %s
                    AND entity_type = %s
                    AND client_id = %s",
			$this->id,
			$this->entity_type,
			$this->client_id ),
			OBJECT );
		if ( ! is_null( $_result ) ) {
			$this->hidden = true;
		}
	}

	/**
	 * returns array of categories for bill
	 * @return void
	 */
	public function getBillCategories() {
		if ( is_null( $this->client_id ) ) {
			$categories = $this->wpdb->get_results( $this->wpdb->prepare(
				"SELECT id,pname
                    FROM profile_match 
                    WHERE entity_id = %d
                    AND entity_type = %s 
                    GROUP BY pname",
				$this->id,
				$this->entity_type ),
				OBJECT );
		} else {
			$categories = $this->wpdb->get_results( $this->wpdb->prepare(
				"SELECT id,pname
                    FROM profile_match 
                    WHERE entity_id = %d
                    AND entity_type = %s 
                    AND client_id = %s
                    GROUP BY pname",
				$this->id,
				$this->entity_type,
				$this->client_id ),
				OBJECT );
		}
		foreach ( $categories as $category ) {
			if ( in_array( $category->pname, $this->categories ) === false ) {
				$this->categories[] = $category->pname;
				// build keywords for bill also
				$this->getKeywordsForCategory( $category->id );
			}
		}

	}

}

class MSANotes extends MSABase {
	public $note_timestamp;
	public $external_id;
	public $bill_url;
	public $note_text;
	public $user;
	public $bill_id;
	public $entity_type;
	/**
	 * Requirments:
	 * ·
	 * ·         It will filter the list checking for the bill's profiles (categories).
	 * -    If a user from list has any of the State/Categories/Profiles enabled in the dashboard, it will include the user in the notification email. If not will remove it.
	 */
	/**
	 * Notes constructor.
	 * User should be able to manage notes that is:
	 * -to be able to see all notes that belong to parent client
	 * -to be able to add,delete new note for particular bill
	 * Staff member should be able to manage notes that is to be able to do following:
	 * -
	 */
	public function __construct( $fields ) {
		parent::__construct();
		foreach ( $fields as $key => $value ) {
			$this->$key = $value;
		}
		if ( isset( $this->user_id ) ) {
			$this->user = get_userdata( $this->user_id );
		}
	}

	public function createRecipentsList() {
		$recipent_list = array();
		//It will get the list of users within the same client
		$client_users = $this->getClientUsers( $this->user->client_id, array( 'ID', 'user_email' ) );
		foreach ( $client_users as $client_user ) {
			// It will exclude the user performing the action (user that is adding note)
			if ( $this->user_id !== $client_user['ID'] ) {
				$_user = new MSAUser( $this->user_id );
				array_push( $recipent_list, $client_user['user_email'] );
			}
		}
		//It will always include Staff role mail – “unless he is the user performing the action
		$staff_users = $this->getStaffUsers();
		foreach ( $staff_users as $staff_user ) {
			if ( $this->user_id !== $staff_user->ID ) {
				array_push( $recipent_list, $staff_user->user_email );
			}
		}

		return $recipent_list;
	}

	public function newNoteNotificationStaff() {
		$body = "Added by user: " . $this->user->display_name;
		$body .= "<br>User Client: " . get_user_meta( $this->user_id, 'company', true );
		$body .= "<br>Bill ID:" . $this->bill_id;
		$body .= "<br>Bill URL: <a href='" . $this->bill_url . "'>" . $this->bill_url . "</a>";
		$body .= "<br>Note Created At: " . date_format( $this->note_timestamp, 'Y-m-d H:i:s' );

		$headers = array( 'Content-Type: text/html; charset=UTF-8', 'From: MainStreetAdvocates <info@msa.com>' );
		$subject = "New Note added on System " . date( "Y/m/d" );
		//TODO get list of recepients that is all of staff members - use getstaffmembers,iterate and compare if user that is here in list skip it in that case
		$sent_message = wp_mail( "ljubisa.dobric@live.com", $subject, $body, $headers );
	}

	public function delete() {
		$result = $this->wpdb->delete( 'bill_notes', [ "id" => $this->id ], [ '%s' ] );
		if ( $result !== false ) {
			return true;
		} else {
			return false;
		}
	}

	public function update() {
		$_update = $this->wpdb->update( 'bill_notes', [
			'note_text'      => $this->note_text,
			'note_timestamp' => $this->note_timestamp
		], [ 'id' => $this->id ] );
		if ( $_update !== false ) {
			return true;
		} else {
			return false;
		}
	}
	// TODO
	/*
	 * When making a note on item, staff role will receive an alert via email identifying the following information:
     *    User name that created the note
     *     Client that this user belongs to
     *     The item (bill id) on which the note was made for – DOB: what about type of bill is it legislation,regulation or hearing?!
     *     Hyperlink to that particular item (bill) detailed page.
     *    Day and time the note was created
	 */
	public function save() {
		$result = $this->wpdb->insert( 'bill_notes', [
			'client_id'   => $this->client_id,
			'user_id'     => $this->user_id,
			'bill_id'     => $this->bill_id,
			'bill_url'    => $this->bill_url,
			'note_text'   => $this->note_text,
			'entity_type' => $this->entity_type

		], [ '%s', '%s', '%s', '%s', '%s', '%s' ] );
		if ( $result !== false ) {
			$this->inserted_id = $this->wpdb->insert_id;
			$this->newNoteNotificationStaff();

			return true;
		} else {
			return false;
		}
	}
}

class MSAClient extends MSABase {
	public $solr_active = false;

	public function __construct() {
		parent::__construct();
	}

	/**
	 * We will filter out hidden bills which shoul'd not be shown for user
	 *
	 * @param $type
	 */
	protected function getHiddenBills( $type ) {

	}

	/**
	 * Show list of approved bills for client
	 */
	protected function getApprovedBills( $type ) {

	}

	protected function checkSolr() {
		if ( has_filter( 'solr_get_core' ) ) {
			if ( apply_filters( 'solr_get_core', $this->client_id ) ) {
				$this->solr_active = true;
			}
		}
	}
}

class MSAUser extends MSAClient {
	public $user_states = array();
	public $user_categories = array();
	public $user_keywords = array();

	// We should handle if user is "user" or staff

	public function __construct( $user_id = null ) {
		parent::__construct();
		$this->user_id   = ( is_null( $user_id ) ) ? get_current_user_id() : $user_id;
		$this->client_id = get_user_meta( $this->user_id, 'company', true );
		//  we will initialize settings as they are mandatory for most of actions
		$this->getUserSettings();
		$this->checkSolr();

	}

	/**
     * Iterates user categories states and keywords and updates user settings
	 * @param bool $return_object_settings
	 *
	 * @return array|object|null
	 */
	public function getUserSettings( $return_object_settings = false ) {
		$settings = $this->wpdb->get_results( $this->wpdb->prepare(
			"SELECT id,type,category,isfrontactive,ismailactive 
                    FROM user_settings 
                    WHERE user_id = %s
                    ORDER BY category",
			$this->user_id ),
			OBJECT );
		// sets internal settings
		foreach ( $settings as $setting ) {
			if ( $setting->type === 'state' ) {
				$this->user_states[ $setting->category ] = array(
					"isfrontactive" => $setting->isfrontactive,
					"ismailactive"  => $setting->ismailactive
				);
			} else if ( $setting->type === 'category' ) {
				$this->user_categories[ $setting->category ] = array(
					"isfrontactive" => $setting->isfrontactive,
					"ismailactive"  => $setting->ismailactive
				);
			} else if ( $setting->type === 'keyword' ) {
				$this->user_keywords[ $setting->category ] = array(
					"isfrontactive" => $setting->isfrontactive,
					"ismailactive"  => $setting->ismailactive
				);
			}
		}
		//returns objects that might needed for lists
		if ( $return_object_settings ) {
			return $settings;
		}
	}

	public function reindexSolrCore(){
		$solr_result = apply_filters( 'solr_reindex_core', 3 );

		return $solr_result;
    }

	/**
	 * @param $filter
	 *
	 * @return mixed|void
	 */
	public function typeheadSuggestion($filter){
		$solr_result = apply_filters( 'solr_typehead_suggestion', $filter );

		return $solr_result;
    }

	/**
	 * @param $bill_id
	 * @param $entity_type
	 * @param $status
	 * @param null $client_id
	 *
	 * @return bool
	 */
	public function updateBillPriority($bill_id,$entity_type,$status,$client_id = null){
	    if($status === 'enable'){
		    $result = $this->wpdb->insert( 'prioritized_bills', array( 'bill_id'     => $bill_id,
		                                                         'user_id'     => $this->user_id,
		                                                         'entity_type' => $entity_type,
		                                                         'client_id'   => $this->client_id
		    ) );
		    if($this->solr_active) {
			    $solr_result = apply_filters( 'solr_update_document_priority', $entity_type, $bill_id, true);
		    }
        }else{
		    $result = $this->wpdb->delete( 'prioritized_bills', array( 'bill_id'     => $bill_id,
		                                                         'entity_type' => $entity_type,
		                                                         'client_id'   => $this->client_id
		    ) );
		    if($this->solr_active) {
			    $solr_result = apply_filters( 'solr_update_document_priority', $entity_type, $bill_id, false);
		    }
        }
        if($result){
            return True;
        }else{
            return False;
        }
    }
	/**
	 *
	 */
	public function reverseCalculateStatus($key){
		$key = (int)$key;
		$status = [];
        switch ($key){
            case 1:
                $status = [0,10000];
                break;
            case 2:
                $status = [10000,20000];
                break;
            case 3:
                $status = [20000,30000];
                break;
            case 4:
                $status = [30000,40000];
                break;
            case 5:
                $status = [40000,50000];
                break;
            case 6:
                $status = [50000,60000];
                break;
            case 7:
                $status = [60000,70000];
                break;
            case 8:
                $status = [70000,80000];
                break;
            case 9:
                $status = [80000,90000];
                break;
            case 10:
                $status = [90000,100000];
                break;
            default:
                $status = [0,100000];
                break;
        }
        return $status;
	}
	public function getActiveUserStats() {
		$_out = [];
		foreach ( $this->user_states as $state => $value ) {
			if ( $value['isfrontactive'] == 1 ) {
				$_out[] = $state;
			}
		}

		return $_out;
	}
	public function getSolrID( $resultset ) {
		$push_result = [];
		if ( ! empty( $resultset ) ) {
			foreach ( $resultset as $document ) {
				foreach ( $document as $field => $value ) {
					array_push( $push_result, $value );
				}
			}
		}

		return $push_result;
	}

	public function generateDataTableResult( $totalRecords, $data, $draw ) {
		$end_result = array(
			"draw"            => $draw,
			"recordsTotal"    => $totalRecords,
			"recordsFiltered" => $totalRecords,
			"aaData"          => $data
		);

		return $end_result;
	}

	public function generateLegislation( $output ) {
		$useroutput = [];
		foreach ( $output as $item ) {
			if ( ! isset( $item->isPriority ) ) {
				//echo $item->priority;
				if ( $item->priority !== null ) {
					$item->isPriority = true;
				} else {
					$item->isPriority = false;
				};
			} else if ( $item->isPrirority === null ) {
				$item->isPriority = false;
			}
			$_tmp['id']                  = $item->legislation_id;
			$_tmp['isPriority']          = $item->isPriority;
			$_tmp['bookmark_note']       = $item->bookmark_note;
			$_tmp['state']               = $item->state;
			$_tmp['session']             = $item->session;
			$_tmp['type']                = $item->type;
			$_tmp['number']              = $item->number;
			$_tmp['sponsor_name']        = $item->sponsor_name;
			$_tmp['sponsor_url']         = $item->sponsor_url;
			$_tmp['title']               = $item->title;
			$_tmp['abstract']            = $item->abstract;
			$_tmp['last_update_date']    = $item->last_updated;
			$_tmp['status_val']          = $item->status_val;
			$_tmp['status_standard_val'] = $item->status_standard_val;
			$_tmp['categories']          = ( is_array( $item->categories ) ) ? implode( ', ', $item->categories ) : $item->categories;
			$useroutput[]                = $_tmp;
		}

		return $useroutput;
	}

	public function getLegislationsSolr( $category = null, $federal = '', $search = null, $draw, $start, $request ) {
		$columns      = [
			0  => 'legislation_id',
			3  => 'state',
			4  => 'session',
			5  => 'type',
			6  => 'number',
			7  => 'sponsor_name',
			8  => 'title',
			9  => 'abstract',
			11 => 'status_val',
			12 => 'pname',
			13 => 'status_standard_val'
		];
		$order        = [
			'order_by' => $columns[ $request['order'][0]['column'] ],
			'order'    => $request['order'][0]['dir']
		];
		$exclude_list = [];
		foreach ( $this->user_categories as $user_category => $status ) {
			if ( $status['isfrontactive'] === '0' ) {
				array_push( $exclude_list, $user_category );
			}
		}
		$solr_result = apply_filters( 'solr_get_legislations', $category, $federal, $exclude_list, $search, $start, $order );
		$list        = ( implode( ',', $this->getSolrID( $solr_result ) ) );
		if ( $list !== '' ) {
			$_result = $this->wpdb->get_results(
				"SELECT leg.id as legislation_id,number,session,state,status_val,status_standard_val,type,title,abstract,sponsor_name,sponsor_url,
                COUNT(DISTINCT bn.bill_id) as bookmark_note,GROUP_CONCAT(DISTINCT pm.pname) as categories,
                (SELECT id FROM prioritized_bills WHERE client_id={$this->client_id} and entity_type = 'legislation' and bill_id = leg.id) as priority,
                (SELECT MAX(fetching_date) FROM unit_tests.last_updated AS lu LEFT JOIN import_table AS it ON lu.import_table_id = it.id WHERE document_id = leg.id and client_id = {$this->client_id} and entity_type = 'legislation') as last_updated
                FROM legislation AS leg 
				LEFT JOIN bill_notes AS bn ON leg.id= bn.bill_id and entity_type = 'legislation' and bn.client_id = {$this->client_id}
				LEFT JOIN profile_match AS pm ON leg.id = pm.entity_id  and pm.entity_type = 'legislation' and pm.client_id = {$this->client_id}
				WHERE leg.id IN ({$list})
				GROUP BY leg.id
				ORDER BY {$order['order_by']} {$order['order']};", OBJECT );
		} else {
			$_result = [];
		}

		$data = $this->generateLegislation( $_result );

		return $this->generateDataTableResult( $solr_result->getNumFound(), $data, $draw );
	}

	public function getLegislations( $category = null, $federal = '' ) {
		// validate input request for profile matches compare
		if ( $category !== null ) {
			$_result = $this->wpdb->get_results( $this->wpdb->prepare(
				"SELECT * FROM profile_match 
                       WHERE client_id = %s
                       AND entity_type = %s
                       AND pname = %s",
				$this->client_id,
				MSABase::LEGISLATION,
				$category ),
				OBJECT );
		} else {
			$_result = $this->wpdb->get_results( $this->wpdb->prepare(
				"SELECT * FROM profile_match 
                       WHERE client_id = %s
                       AND entity_type = %s",
				$this->client_id,
				MSABase::LEGISLATION ),
				OBJECT );
		}
		$lista = [];
		// validate user categories
		foreach ( $_result as $profile_match ) {
			if ( array_key_exists( $profile_match->pname, $this->user_categories ) ) {
				if ( $this->user_categories[ $profile_match->pname ]['isfrontactive'] === '1' ) {
					if ( ! isset( $lista[ $profile_match->entity_id ] ) ) {
						$lista[ $profile_match->entity_id ] = "";
					}
				} else if ( $this->user_categories[ $profile_match->pname ]['isfrontactive'] === '0' ) {
					if ( isset( $lista[ $profile_match->entity_id ] ) ) {
						unset( $lista[ $profile_match->entity_id ] );
					}
				}
			}
		}
		$output = [];
		foreach ( $lista as $key => $value ) {
			// lets get bill and then further validate
			//echo "TREBA : " . $key;
			$_bill = $this->getBill( MSABase::LEGISLATION, $key );
			// validate federal input information and filter bills on that
			if ( $federal === 'state' ) {
				if ( $_bill->state === 'US' ) {
					break;
				}
			} else if ( $federal === 'us' ) {
				if ( $_bill->state !== 'US' ) {
					break;
				}
			}
			// get bill and check if country exist for user that that isfrontactive = 1
			if ( in_array( $_bill->state, $this->user_states ) && $this->user_states[ $_bill->state ]['isfrontactive'] === '0' ) {
				break;
			}

			// check session if its active
			if ( ! isset( $_bill->session_id ) ) {
				break;
			} else {
				$_bill->getSessionInformation();
				if ( $_bill->session_information->is_active === '0' ) {
					break;
				}
			}

			$_bill->getHiddenStatus();
			if ( ! $_bill->hidden ) {
				$_bill->getPrioritzed();
				$_bill->getNotesForBill();
				$_bill->getLatestUpdated();
				$output[] = $_bill;
			}

		}
		$useroutput = [];
		foreach ( $output as $item ) {
			$_tmp['id']                  = $item->id;
			$_tmp['isPriority']          = $item->priority;
			$_tmp['bookmark_note']       = $item->bookmark_note;
			$_tmp['state']               = $item->state;
			$_tmp['session']             = $item->session;
			$_tmp['type']                = $item->type;
			$_tmp['number']              = $item->number;
			$_tmp['sponsor_name']        = $item->sponsor_name;
			$_tmp['sponsor_url']         = $item->sponsor_url;
			$_tmp['title']               = $item->title;
			$_tmp['abstract']            = $item->abstract;
			$_tmp['last_update_date']    = $item->last_updated;
			$_tmp['status_val']          = $item->status_val;
			$_tmp['status_standard_val'] = $item->status_standard_val;
			$_tmp['categories']          = implode( ', ', $item->categories );
			$useroutput[]                = $_tmp;
		}

		return $useroutput;
	}

	public function getProfileMatches( $type, $category = null, $is_admin = false ) {
		$query  = "SELECT * FROM profile_match
                  WHERE entity_type = %s";
		$params = [ $type ];
		if ( $category !== null ) {
			$query    .= " AND pname = %s";
			$params[] = $category;
		}
		if ( ! $is_admin ) {
			$query    .= " AND client_id = %s";
			$params[] = $this->client_id;
		}
		$_result = $this->wpdb->get_results( $this->wpdb->prepare( $query, $params ), OBJECT );

		return $_result;
	}

	public function getHearingsSolr( $category = null, $federal = '', $search = null, $draw, $start, $request ) {
		$columns     = [
			0  => 'hearing_id',
			3  => 'date',
			4  => 'time',
			5  => 'house',
			6  => 'committee',
			7  => 'place',
		];
		$order       = [
			'order_by' => $columns[ $request['order'][0]['column'] ],
			'order'    => $request['order'][0]['dir']
		];
		$exclude_list = [];
		foreach ( $this->user_categories as $user_category => $status ) {
			if ( $status['isfrontactive'] === '0' ) {
				array_push( $exclude_list, $user_category );
			}
		}
		$solr_result = apply_filters( 'solr_get_hearings', $category, $federal, $exclude_list, $search, $start, $order );
		$list = ( implode( ',', $this->getSolrID( $solr_result ) ) );
		if ( $list !== '' ) {
			$_result = $this->wpdb->get_results(
				"SELECT her.id as hearing_id,date,time,house,committee,place,COUNT(DISTINCT bn.bill_id) as bookmark_note,(SELECT id FROM prioritized_bills WHERE entity_type = 'hearings' and bill_id = her.id and client_id = {$this->client_id}) as priority FROM hearing AS her 
                LEFT JOIN bill_notes AS bn ON her.id= bn.bill_id and entity_type = 'hearing' and bn.client_id = {$this->client_id}
                WHERE her.id IN ({$list}) 
                GROUP BY her.id;", OBJECT );
		} else {
			$_result = [];
		}

		$data = $this->generateHearingResult( $_result );

		return $this->generateDataTableResult( $solr_result->getNumFound(), $data, $draw );
	}

	protected function generateHearingResult( $result ) {
		$useroutput = [];
		foreach ( $result as $item ) {
			if ( ! isset( $item->isPriority ) ) {
				if ( $item->priority !== null ) {
					$item->isPriority = true;
				} else {
					$item->isPriority = false;
				};
			} else if ( $item->isPrirority === null ) {
				$item->isPriority = false;
			}
			$_tmp['id']            = $item->hearing_id;
			$_tmp['isPriority']    = $item->isPriority;
			$_tmp['bookmark_note'] = $item->bookmark_note;
			$_tmp['date']          = $item->date;
			$_tmp['time']          = $item->time;
			$_tmp['house']         = $item->house;
			$_tmp['committee']     = $item->committee;
			$_tmp['place']         = $item->place;
			$useroutput[]          = $_tmp;
		}

		return $useroutput;
	}

	public function getHearings( $category = null ) {
		// validate input request for profile matches compare
		$_result = $this->getProfileMatches( MSABase::HEARING, $category );
		$lista   = [];
		// validate user categories
		// TODO improve this
		foreach ( $_result as $profile_match ) {
			if ( array_key_exists( $profile_match->pname, $this->user_categories ) ) {
				if ( $this->user_categories[ $profile_match->pname ]['isfrontactive'] === '1' ) {
					if ( ! isset( $lista[ $profile_match->entity_id ] ) ) {
						$lista[ $profile_match->entity_id ] = "";
					}
				} else if ( $this->user_categories[ $profile_match->pname ]['isfrontactive'] === '0' ) {
					if ( isset( $lista[ $profile_match->entity_id ] ) ) {
						unset( $lista[ $profile_match->entity_id ] );
					}
				}
			}
		}
		$output = [];
		foreach ( $lista as $key => $value ) {
			// lets get bill and then further validate
			$_bill = $this->getBill( MSABase::HEARING, $key );
			if ( $_bill === null ) {
				continue;
			}
			// show only non hidden client bills
			$_bill->getHiddenStatus();
			if ( ! $_bill->hidden ) {
				$_bill->getPrioritzed();
				$_bill->getNotesForBill();
				$_bill->getHearingLegislation();
				$output[] = $_bill;
			}

		}
		$useroutput = [];
		foreach ( $output as $item ) {
			$_tmp['id']            = $item->id;
			$_tmp['isPriority']    = $item->priority;
			$_tmp['bookmark_note'] = $item->bookmark_note;
			$_tmp['date']          = $item->date;
			$_tmp['time']          = $item->time;
			$_tmp['house']         = $item->house;
			$_tmp['committee']     = $item->committee;
			$_tmp['place']         = $item->place;
			$_tmp['external_id']   = $item->legislation_external_id;
			$_tmp['state']         = $item->state;
			if ( isset( $item->legislation ) ) {
				$_tmp['leg_title'] = ( $item->legislation->state . ' ' . $item->legislation->type . ' ' . $item->legislation->number );
			}
			$useroutput[] = $_tmp;
		}

		return $useroutput;

	}

	public function generateRegulationResult( $result ) {
		$useroutput = [];
		foreach ( $result as $item ) {
			//handling solr result
			if ( ! isset( $item->isPriority ) ) {
				if ( $item->priority !== null && $item->priority != 0) {
					    $item->isPriority = true;
				} else {
					$item->isPriority = false;
				};
			}
			$_tmp['id']                = $item->regulation_id;
			$_tmp['isPriority']        = $item->isPriority;
			$_tmp['bookmark_note']     = $item->bookmark_note;
			$_tmp['state']             = $item->state;
			$_tmp['agency_name']       = $item->agency_name;
			$_tmp['type']              = $item->type;
			$_tmp['state_action_type'] = $item->state_action_type;
			$_tmp['register_date']     = $item->register_date;
			$_tmp['categories']        = ( is_array( $item->categories ) ) ? implode( ', ', $item->categories ) : $item->categories;
			$useroutput[]              = $_tmp;
		}

		return $useroutput;
	}

	public function getRegulationsSolr( $category = null, $federal = '', $search = null, $draw, $start, $request ) {
		$columns     = [
			0 => 'regulation_id',
			3 => 'state',
			4 => 'agency_name',
			5 => 'type',
			6 => 'state_action_type',
			7 => 'register_date',
		];
		$order       = [
			'order_by' => $columns[ $request['order'][0]['column'] ],
			'order'    => $request['order'][0]['dir']
		];
		$exclude_list = [];
		foreach ( $this->user_categories as $user_category => $status ) {
			if ( $status['isfrontactive'] === '0' ) {
				array_push( $exclude_list, $user_category );
			}
		}
		$solr_result = apply_filters( 'solr_get_regulations', $category, $federal, $exclude_list, $search, $start, $order );
		$list        = ( implode( ',', $this->getSolrID( $solr_result ) ) );
		if ( $list !== '' ) {
			$_result = $this->wpdb->get_results(
				"SELECT reg.id as regulation_id,state,agency_name,type,state_action_type,register_date,COUNT(DISTINCT bn.bill_id) as bookmark_note,GROUP_CONCAT(DISTINCT pm.pname) as categories,(SELECT id FROM prioritized_bills WHERE entity_type = 'regulation' and bill_id = reg.id and client_id = {$this->client_id}) as priority FROM regulation AS reg 
                LEFT JOIN bill_notes AS bn ON reg.id= bn.bill_id and entity_type = 'regulation' and bn.client_id = {$this->client_id}
                LEFT JOIN profile_match AS pm ON reg.id = pm.entity_id  and pm.entity_type = 'regulation' and pm.client_id = {$this->client_id}
                WHERE reg.id IN ({$list})
                GROUP BY reg.id
                ORDER BY {$order['order_by']} {$order['order']};", OBJECT );
		} else {
			$_result = [];
		}

		$data = $this->generateRegulationResult( $_result );

		return $this->generateDataTableResult( $solr_result->getNumFound(), $data, $draw );
	}

	/**
	 *  User should see regulation documents that fulfill following requirement:
	 *  - regulation documents for parent client
	 *  - regulation documents that don't have disabled category inside user_settings that is isfrontactive = 0
	 *  - regulation documents that don't have disabled state for particular user
	 */
	public function getRegulations( $category = null, $federal = '' ) {
		// validate input request for profile matches compare
		if ( $category !== null ) {
			$_result = $this->wpdb->get_results( $this->wpdb->prepare(
				"SELECT * FROM profile_match 
                       WHERE client_id = %s
                       AND entity_type = %s
                       AND pname = %s",
				$this->client_id,
				MSABase::REGULATION,
				$category ),
				OBJECT );
		} else {
			$_result = $this->wpdb->get_results( $this->wpdb->prepare(
				"SELECT * FROM profile_match 
                       WHERE client_id = %s
                       AND entity_type = %s",
				$this->client_id,
				MSABase::REGULATION ),
				OBJECT );
		}

		$lista = [];
		// validate user categories
		foreach ( $_result as $profile_match ) {
			if ( array_key_exists( $profile_match->pname, $this->user_categories ) ) {
				if ( $this->user_categories[ $profile_match->pname ]['isfrontactive'] === '1' ) {
					if ( ! isset( $lista[ $profile_match->entity_id ] ) ) {
						$lista[ $profile_match->entity_id ] = "";
					}
				} else if ( $this->user_categories[ $profile_match->pname ]['isfrontactive'] === '0' ) {
					if ( isset( $lista[ $profile_match->entity_id ] ) ) {
						unset( $lista[ $profile_match->entity_id ] );
					}
				}
			}
		}
		$output = [];
		foreach ( $lista as $key => $value ) {
			// lets get bill and then further validate
			$_bill = $this->getBill( MSABase::REGULATION, $key );
			// validate federal input information and filter bills on that
			if ( $federal === 'state' ) {
				if ( $_bill->state === 'US' ) {
					break;
				}
			} else if ( $federal === 'us' ) {
				if ( $_bill->state !== 'US' ) {
					break;
				}
			}
			// get bill and check if country exist for user that that isfrontactive = 1
			if ( in_array( $_bill->state, $this->user_states ) && $this->user_states[ $_bill->state ]['isfrontactive'] === '0' ) {
				break;
			}

			// show only non hidden client bills
			$_bill->getHiddenStatus();
			if ( ! $_bill->hidden ) {
				$_bill->getPrioritzed();
				$_bill->getNotesForBill();
				//print_r($_bill->notes);
				$_bill->getBillCategories();
				$output[] = $_bill;
			}

		}
		$useroutput = [];
		foreach ( $output as $item ) {
			$_tmp['id']                = $item->id;
			$_tmp['isPriority']        = $item->priority;
			$_tmp['bookmark_note']     = $item->bookmark_note;
			$_tmp['state']             = $item->state;
			$_tmp['agency_name']       = $item->agency_name;
			$_tmp['type']              = $item->type;
			$_tmp['state_action_type'] = $item->state_action_type;
			$_tmp['register_date']     = $item->register_date;
			$_tmp['categories']        = implode( ', ', $item->categories );
			$useroutput[]              = $_tmp;
		}

		return $useroutput;

	}

	public function dashboardManagerSolrMain($document_type = null,$category = null,$priority = null, $document_status = null){
		$exclude_categories = [];
		foreach ( $this->user_categories as $user_category => $status ) {
			if ( $status['isfrontactive'] === '0' ) {
				array_push( $exclude_categories, $user_category );
			}
		}
		$exclude_states = [];
		foreach ( $this->user_states as $user_category => $status ) {
			if ( $status['isfrontactive'] === '0' ) {
				array_push( $exclude_states, $user_category );
			}
		}
        //echo $document_status;
        if($document_status !== null && $document_type === MSABase::LEGISLATION){
	        $document_status = $this->reverseCalculateStatus($document_status);
        }else{
            $document_status = null;
        }

		$solr_result = apply_filters( 'solr_get_dashboard_main',$document_type,$category,$document_status,$priority,$exclude_categories,$exclude_states);
		return $solr_result;
    }

    public function dashboardManagerSolr($category,$type,$state,$priority,$status){
	    $exclude_list = [];
	    foreach ( $this->user_categories as $user_category => $status ) {
		    if ( $status['isfrontactive'] === '0' ) {
			    array_push( $exclude_list, $user_category );
		    }
	    }
	    $solr_result = apply_filters( 'solr_get_dashboard', $type, $category, $state, $exclude_list,$status,$priority);
        return $solr_result;
    }

	public function dashboardManager( $category, $type = 'legislation', $state = 'state', $priority = null, $status = null ) {
		$json          = file_get_contents( get_template_directory_uri() . '/states.json' );
		$states        = json_decode( $json );
		$test          = [];
		$total_records = 0;
		//foreach ( $states as $state ) {
		//    $test[ $state->abbreviation ] = [ "regulation" => 0, "legislation" => 0, "total" => 0 ];
		//}
		//$test[ 'US' ] = [ "regulation" => 0, "legislation" => 0, "total" => 0 ];

		$entity_type = MSABase::LEGISLATION;
		switch ( $type ) {
			case 'legislation':
				$entity_type = MSABase::LEGISLATION;
				break;
			case 'regulation':
				$entity_type = MSABase::REGULATION;
				break;
			case 'hearing':
				$entity_type = MSABase::HEARING;
				break;
		}
		if ( $category !== '' ) {
			$_result = $this->wpdb->get_results( $this->wpdb->prepare(
				"SELECT * FROM profile_match 
                       WHERE client_id = %s
                       AND entity_type = %s
                       AND pname = %s",
				$this->client_id,
				$entity_type,
				$category ),
				OBJECT );
		} else {
			$_result = $this->wpdb->get_results( $this->wpdb->prepare(
				"SELECT * FROM profile_match 
                       WHERE client_id = %s
                       AND entity_type = %s",
				$this->client_id,
				$entity_type ),
				OBJECT );
		}
		$lista = [];
		// validate user categories
		foreach ( $_result as $profile_match ) {
			if ( array_key_exists( $profile_match->pname, $this->user_categories ) ) {
				if ( $this->user_categories[ $profile_match->pname ]['isfrontactive'] === '1' ) {
					if ( ! isset( $lista[ $profile_match->entity_id ] ) ) {
						$lista[ $profile_match->entity_id ] = "";
					}
				} else if ( $this->user_categories[ $profile_match->pname ]['isfrontactive'] === '0' ) {
					if ( isset( $lista[ $profile_match->entity_id ] ) ) {
						unset( $lista[ $profile_match->entity_id ] );
					}
				}
			}
		}
		$output = [];
		foreach ( $lista as $key => $value ) {
			// lets get bill and then further validate
			$_bill = $this->getBill( $entity_type, $key );
			// show only non hidden client bills
			if ( $entity_type === MSABase::LEGISLATION || $entity_type === MSABase::REGULATION ) {
				//filter if its state or federal
				if ( $state !== null && isset( $_bill->state ) ) {
					if ( $state === 'state' && $_bill->state === 'US' ) {
						break;
					} else if ( $state === 'us' && $_bill->state !== 'US' ) {
						break;
					}
				}
				// get bill and check if country exist for user that that isfrontactive = 1
				if ( in_array( $_bill->state, $this->user_states ) && $this->user_states[ $_bill->state ]['isfrontactive'] === '0' ) {
					break;
				}

				if ( $entity_type === MSABase::LEGISLATION ) {
					// check session if its active
					if ( ! isset( $_bill->session_id ) ) {
						break;
					} else {
						$_bill->getSessionInformation();
						if ( $_bill->session_information->is_active === '0' ) {
							break;
						}
					}
				}
			}

			$_bill->getHiddenStatus();
			if ( ! $_bill->hidden ) {
				$_bill->getPrioritzed();
				//check legislation status if its set in filter
				if ( $entity_type === MSABase::LEGISLATION && $status !== null ) {
					if ( $_bill->legislation_status !== $status ) {
						break;
					}
				}
				if ( $entity_type === MSABase::HEARING ) {
					$_bill->getHearingLegislation();
					if ( $_bill->legislation ) {
						$_bill->state = $_bill->legislation->state;
					}
				}
				//check priority if 1 (true) or 0 (false)
				//echo "DA" . $priority;
				//echo $priority;
				if ( $priority !== null ) {
					if ( $priority === '0' && $_bill->priority ) {
						continue;
					} else if ( $priority === '1' && ! $_bill->priority ) {
						continue;
					}
				}
				//check if state exists inside already
				if ( ! array_key_exists( $_bill->state, $test ) ) {
					$test[ $_bill->state ] = [ "regulation" => 0, "legislation" => 0, 'hearing' => 0, "total" => 0 ];
				}
				$test[ $_bill->state ][ $entity_type ] += 1;
				$test[ $_bill->state ]['total']        += 1;
				$total_records                         += 1;
			}

		}
		$output['data'] = [];
		foreach ( $test as $state => $value ) {
			$_tmparray['id']           = "US-" . $state;
			$_tmparray['modalUrl']     = 'test';
			$_tmparray['selectable']   = true;
			$_tmparray['value']        = $value['total'];
			$_tmparray[ $entity_type ] = $value[ $entity_type ];
			$output['data'][]          = $_tmparray;
		}
		/*
					"id": "US-<?php echo $state; ?>",
					"modalUrl": "<?php echo get_site_url() ?>/dashboard-list/?cat=" + parameter + "&st=<?php echo $state; ?>",
					"selectable": true,
					"value": <?php echo $value['total'];?>,
					"legalisation": <?php echo $value['legislation'];?>,
					"regulations":<?php echo $value['regulation'];?>*/

		//$output['data']  = $test;
		$output['total'] = $total_records;

		return $output;
	}

	public function dashboardMain() {
		$url    = get_site_url();
		$json   = file_get_contents( $url . '/wp-content/themes/mainstreet-advocates/states.json' );
		$states = json_decode( $json );
		$test   = [];
		foreach ( $states as $state ) {
			$test[ $state->abbreviation ] = [ "regulation" => 0, "legislation" => 0, "total" => 0 ];
		}


		$_result       = $this->wpdb->get_results( $this->wpdb->prepare(
			"SELECT * FROM profile_match 
                       WHERE client_id = %s
                       AND entity_type = %s",
			$this->client_id,
			MSABase::REGULATION ),
			OBJECT );
		$lista         = [];
		$total_records = 0;
		// validate user categories
		foreach ( $_result as $profile_match ) {
			if ( array_key_exists( $profile_match->pname, $this->user_categories ) ) {
				if ( $this->user_categories[ $profile_match->pname ]['isfrontactive'] === '1' ) {
					if ( ! isset( $lista[ $profile_match->entity_id ] ) ) {
						$lista[ $profile_match->entity_id ] = "";
					}
				} else if ( $this->user_categories[ $profile_match->pname ]['isfrontactive'] === '0' ) {
					if ( isset( $lista[ $profile_match->entity_id ] ) ) {
						unset( $lista[ $profile_match->entity_id ] );
					}
				}
			}
		}
		$output = [];
		foreach ( $lista as $key => $value ) {
			// lets get bill and then further validate
			$_bill = $this->getBill( MSABase::REGULATION, $key );
			// validate federal input information and filter bills on that

			if ( $_bill->state === 'US' ) {
				break;
			}
			// get bill and check if country exist for user that that isfrontactive = 1
			if ( in_array( $_bill->state, $this->user_states ) && $this->user_states[ $_bill->state ]['isfrontactive'] === '0' ) {
				break;
			}
			// show only non hidden client bills
			$_bill->getHiddenStatus();
			if ( ! $_bill->hidden ) {
				$test[ $_bill->state ]['regulation'] += 1;
				$test[ $_bill->state ]['total']      += 1;
				$total_records                       += 1;
			}

		}
		$_result = $this->wpdb->get_results( $this->wpdb->prepare(
			"SELECT * FROM profile_match 
                       WHERE client_id = %s
                       AND entity_type = %s",
			$this->client_id,
			MSABase::LEGISLATION ),
			OBJECT );
		$lista   = [];
		// validate user categories
		foreach ( $_result as $profile_match ) {
			if ( array_key_exists( $profile_match->pname, $this->user_categories ) ) {
				if ( $this->user_categories[ $profile_match->pname ]['isfrontactive'] === '1' ) {
					if ( ! isset( $lista[ $profile_match->entity_id ] ) ) {
						$lista[ $profile_match->entity_id ] = "";
					}
				} else if ( $this->user_categories[ $profile_match->pname ]['isfrontactive'] === '0' ) {
					if ( isset( $lista[ $profile_match->entity_id ] ) ) {
						unset( $lista[ $profile_match->entity_id ] );
					}
				}
			}
		}
		$output = [];
		foreach ( $lista as $key => $value ) {
			// lets get bill and then further validate
			//echo "TREBA : " . $key;
			$_bill = $this->getBill( MSABase::LEGISLATION, $key );
			// validate federal input information and filter bills on that

			if ( $_bill->state === 'US' ) {
				break;
			}

			// get bill and check if country exist for user that that isfrontactive = 1
			if ( in_array( $_bill->state, $this->user_states ) && $this->user_states[ $_bill->state ]['isfrontactive'] === '0' ) {
				break;
			}

			// check session if its active
			if ( ! isset( $_bill->session_id ) ) {
				break;
			} else {
				$_bill->getSessionInformation();
				if ( $_bill->session_information->is_active === '0' ) {
					break;
				}
			}

			$_bill->getHiddenStatus();
			if ( ! $_bill->hidden ) {
				$test[ $_bill->state ]['legislation'] += 1;
				$test[ $_bill->state ]['total']       += 1;
				$total_records                        += 1;
			}

		}

		$output['data']  = $test;
		$output['total'] = $total_records;

		return $output;
	}

	public function editNote( $note_text, $note_id, $type ) {
		$_result = $this->wpdb->get_row( $this->wpdb->prepare(
			"SELECT * FROM bill_notes 
                   WHERE id = %s
                   AND entity_type = %s",
			$note_id,
			$type ),
			OBJECT );
		if ( $_result ) {
			$_note = new MSANotes( $_result );
			if ( $_note->user_id == $this->user_id ) {
				$_note->note_timestamp = current_time( 'mysql' );
				$_note->note_text      = $note_text;
				if ( $_note->update() ) {
					return $_note;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * Deletes user note from system that is associated with bill
	 * Checks if note belongs to this user before deleting
	 *
	 * @param $bill_id
	 * @param $note_id
	 * @param $type
	 *
	 * @return bool
	 */
	public function deleteNote( $bill_id, $note_id, $type ) {
		$_result = $this->wpdb->get_row( $this->wpdb->prepare(
			"SELECT * FROM bill_notes 
                   WHERE id=%s", $note_id ), OBJECT );

		if ( $_result ) {
			$_note = new MSANotes( $_result );
			if ( $_note->bill_id === $bill_id && $_note->entity_type === $type && $_note->user_id == $this->user_id ) {
				return $_note->delete();
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * Creates new note inside database
	 *
	 * @param $note_text string
	 *
	 * @return object MSANotes
	 */
	public function createNewNote( $note_text, $bill_id, $type,$client_id = null ) {
		$bill_url = get_site_url();
		switch ( $type ) {
			case 'regulation':
				$bill_url .= '/regulation-detail-view/?id=' . $bill_id;
				break;
			case 'hearing':
				$bill_url .= '/hearing-detail-view/?id=' . $bill_id;
				break;
			case 'legislation':
				$bill_url .= '/detailed-view/?id=' . $bill_id;
				break;
		}
		if($client_id === null){
		    $client_id = $this->client_id;
        }
		$fields = [
			'client_id'      => $client_id,
			'user_id'        => $this->user_id,
			'entity_type'    => $type,
			'bill_id'        => $bill_id,
			'note_text'      => $note_text,
			'note_timestamp' => new DateTime(),
			'bill_url'       => $bill_url
		];
		$_note  = new MSANotes( $fields );
		$_note->save();

		return $_note;
	}

	public function getPrioritizedBillsList() {
		$_result = $this->wpdb->get_results( $this->wpdb->prepare( "SELECT * FROM prioritized_bills_view 
                                                                          WHERE client_id = %s",
			$this->client_id ),
			OBJECT );

		return $_result;
	}

	public function getBill( $entity_type, $id ) {
		$query = "SELECT * FROM";
		switch ( $entity_type ) {
			case MSABase::REGULATION:
				$query .= " regulation";
				break;
			case MSABase::HEARING:
				$query .= " hearing";
				break;
			case MSABase::LEGISLATION:
				$query .= " legislation";
				break;
		}
		$query .= " WHERE ID = %s";
		$row   = $this->wpdb->get_row( $this->wpdb->prepare(
			$query,
			$id ),
			OBJECT );
		if ( $row !== null ) {
			$new_bill = new MSABill( $entity_type, $row, $this->client_id );

			return $new_bill;
		} else {
			return null;
		}
	}

	public function getLegislationBillDetail( $id ) {
		$_bill = $this->getBill( MSABase::LEGISLATION, $id );
		$_bill->getPrioritzed();
		$_bill->getNotesForBill();
		$_bill->getSessionInformation();
		$_bill->getHearingForLegislation();

		return $_bill;
	}

	public function getHearingBillDetail( $id ) {
		$_bill = $this->getBill( MSABase::HEARING, $id );
		$_bill->getPrioritzed();
		$_bill->getNotesForBill();
		$_bill->getHearingLegislation();

		return $_bill;
	}

	public function getRegulationBillDetail( $bill_id ) {
		$_bill = $this->getBill( MSABase::REGULATION, $bill_id );
		$_bill->getPrioritzed();
		$_bill->getNotesForBill();

		return $_bill;
	}

}

class MSAVisitor extends MSAUser {
	public $user_is_visitor = true;

	public function __construct() {
		if ( is_null( $this->wpdb ) ) {
			global $wpdb;
			$this->wpdb = $wpdb;
		}
	}
}

class MSAAdmin extends MSAUser {

	public function __construct( $user_id = null ) {
		if ( is_null( $this->wpdb ) ) {
			global $wpdb;
			$this->wpdb = $wpdb;
		}
		$this->checkSolr();
		$this->user_id = ( is_null( $user_id ) ) ? get_current_user_id() : $user_id;
		$this->getUserSettings();
	}

	public function getPrioritizedBillsList() {
		$_result = $this->wpdb->get_results( "SELECT * FROM prioritized_bills_view", OBJECT );

		return $_result;
	}

	/**
	 * @param bool $return_object_settings
	 *
	 * @return array|object|null
	 */
	public function getUserSettings( $return_object_settings = false ) {
		$settings = $this->wpdb->get_results(
			"SELECT DISTINCT(category),type 
                   FROM client_settings
                   ORDER BY category",
			OBJECT );


		// sets internal settings
		foreach ( $settings as $setting ) {
			if ( $setting->type === 'state' ) {
				$this->user_states[ $setting->category ] = array(
					"isfrontactive" => 1,
					"ismailactive"  => 1
				);
			} else if ( $setting->type === 'category' ) {
				$this->user_categories[ $setting->category ] = array(
					"isfrontactive" => 1,
					"ismailactive"  => 1
				);
			} else if ( $setting->type === 'keyword' ) {
				$this->user_keywords[ $setting->category ] = array(
					"isfrontactive" => 1,
					"ismailactive"  => 1
				);
			}
		}

		//returns objects that might needed for lists
		if ( $return_object_settings ) {
			return $settings;
		}
	}

	public function hideBill($bill_id,$entity_type,$status,$client_id = null){
		$query = [
			'bill_id'=>$bill_id,
			'entity_type'=>$entity_type
		];
		if($client_id !== null){
			$query['client_id'] = $client_id;
		}
		if($status === 'enable'){
			//TODO Admin should prioritize for all clients
			$result = $this->wpdb->insert( 'hidden_bills', $query );

		}else{
			$result = $this->wpdb->delete( 'hidden_bills', $query );
		}
		if($result){
			return True;
		}else{
			return False;
		}
    }

	public function updateBillPriority($bill_id,$entity_type,$status,$client_id = null){
	    $query = [
	            'bill_id'=>$bill_id,
                'entity_type'=>$entity_type
            ];
		if($client_id !== null){
			$query['client_id'] = $client_id;
		}
	    if($status === 'enable'){
		    //TODO Admin should prioritize for all clients
            $query['user_id'] = $this->user_id;
			$result = $this->wpdb->insert( 'prioritized_bills', $query );
			if($this->solr_active) {
				$solr_result = apply_filters( 'solr_update_document_priority', $entity_type, $bill_id, true);
			}
		}else{
			$result = $this->wpdb->delete( 'prioritized_bills', $query );
			if($this->solr_active) {
				$solr_result = apply_filters( 'solr_update_document_priority', $entity_type, $bill_id, false);
			}
		}
		if($result){
			return True;
		}else{
			return False;
		}
	}
	/**
	 * Ordering
	 *
	 * Construct the ORDER BY clause for server-side processing SQL query
	 *
	 * @param  array $request Data sent to server by DataTables
	 * @param  array $columns Column information array
	 *
	 * @return string SQL order by clause
	 */
	private function order( $request, $columns ) {
		$order = '';
		if ( isset( $request['order'] ) && count( $request['order'] ) ) {
			$orderBy   = array();
			$dtColumns = self::pluck( $columns, 'dt' );
			for ( $i = 0, $ien = count( $request['order'] ); $i < $ien; $i ++ ) {
				// Convert the column index into the column data property
				$columnIdx     = intval( $request['order'][ $i ]['column'] );
				$requestColumn = $request['columns'][ $columnIdx ];
				$columnIdx     = array_search( $requestColumn['data'], $dtColumns );
				$column        = $columns[ $columnIdx ];
				if ( $requestColumn['orderable'] == 'true' ) {
					$dir       = $request['order'][ $i ]['dir'] === 'asc' ?
						'ASC' :
						'DESC';
					$orderBy[] = '`' . $column['db'] . '` ' . $dir;
				}
			}
			if ( count( $orderBy ) ) {
				$order = 'ORDER BY ' . implode( ', ', $orderBy );
			}
		}

		return $order;
	}

	/**
	 * Pull a particular property from each assoc. array in a numeric array,
	 * returning and array of the property values from each item.
	 *
	 * @param  array $a Array to get data from
	 * @param  string $prop Property to read
	 *
	 * @return array        Array of property values
	 */
	private function pluck( $a, $prop ) {
		$out = array();
		for ( $i = 0, $len = count( $a ); $i < $len; $i ++ ) {
			$out[] = $a[ $i ][ $prop ];
		}

		return $out;
	}


	public function getLegislationsSolr( $category = null, $federal = '', $search = null, $draw, $start, $request ) {
		$columns     = [
			0  => 'legislation_id',
			3  => 'state',
			4  => 'session',
			5  => 'type',
			6  => 'number',
			7  => 'sponsor_name',
			8  => 'title',
			9  => 'abstract',
			11 => 'status_val',
			12 => 'pname',
			13 => 'status_standard_val'
		];
		$order       = [
			'order_by' => $columns[ $request['order'][0]['column'] ],
			'order'    => $request['order'][0]['dir']
		];
		$solr_result = apply_filters( 'solr_get_legislations', $category, $federal, null, $search, $start, $order );

		$list = ( implode( ',', $this->getSolrID( $solr_result ) ) );

		if ( $list !== '' ) {
			$_result = $this->wpdb->get_results(
				"SELECT leg.id as legislation_id,number,session,state,status_val,status_standard_val,type,title,abstract,sponsor_name,sponsor_url,
                COUNT(DISTINCT bn.bill_id) as bookmark_note,GROUP_CONCAT(DISTINCT pm.pname) as categories,
                (SELECT MAX(id) FROM prioritized_bills WHERE entity_type = 'legislation' and bill_id = leg.id) as priority,
                (SELECT MAX(fetching_date) FROM unit_tests.last_updated AS lu LEFT JOIN import_table AS it ON lu.import_table_id = it.id WHERE document_id = leg.id and entity_type = 'legislation') as last_updated
                FROM legislation AS leg 
				LEFT JOIN bill_notes AS bn ON leg.id= bn.bill_id and entity_type = 'legislation' 
				LEFT JOIN profile_match AS pm ON leg.id = pm.entity_id  and pm.entity_type = 'legislation'
				WHERE leg.id IN ({$list})
				GROUP BY leg.id
				ORDER BY {$order['order_by']} {$order['order']};", OBJECT );
		} else {
			$_result = [];
		}
		$data = $this->generateLegislation( $_result );

		return $this->generateDataTableResult( $solr_result->getNumFound(), $data, $draw );
	}

	public function getLegislations( $category = null, $federal = '', $draw, $start, $request ) {
		$columns = [
			0  => 'entity_id',
			3  => 'state',
			4  => 'session',
			5  => 'type',
			6  => 'number',
			7  => 'sponsor_name',
			8  => 'title',
			9  => 'abstract',
			11 => 'status_val',
			12 => 'pname',
			13 => 'status_standard_val'
		];
		$order   = [
			'order_by' => $columns[ $request['order'][0]['column'] ],
			'order'    => $request['order'][0]['dir']
		];
		// validate input request for profile matches compare
		$query = "SELECT DISTINCT(entity_id) FROM profile_match AS pm 
                       LEFT JOIN legislation AS leg ON pm.entity_id = leg.id
                       WHERE entity_type = 'legislation'";

		if ( $federal === 'state' ) {
			$query .= " AND state <> 'US'";
		} else if ( $federal === 'us' ) {
			$query .= " AND state = 'US'";
		}

		if ( $category !== null ) {
			$query .= " AND pname = '{$category}'";
		}
		$query .= " ORDER BY {$order['order_by']} {$order['order']} 
                       LIMIT {$start},10;";
		//echo $query;
		$_result = $this->wpdb->get_results( $query, OBJECT );
		/*if ( $category !== null ) {
			$_result = $this->wpdb->get_results( $this->wpdb->prepare(
				"SELECT DISTINCT(entity_id) FROM profile_match
                       WHERE entity_type = %s
                       AND pname = %s",
				MSABase::LEGISLATION,
				$category ),
				OBJECT );
		} else {
			$_result = $this->wpdb->get_results( $this->wpdb->prepare(
				"SELECT DISTINCT(entity_id) FROM profile_match AS pm 
                       LEFT JOIN legislation AS leg ON pm.entity_id = leg.id
                       WHERE entity_type = %s
                       ORDER BY {$order['order_by']} {$order['order']} 
                       LIMIT %d,10",
				MSABase::LEGISLATION,$start),
				OBJECT );
		}*/
		$lista = [];
		// validate user categories
		foreach ( $_result as $profile_match ) {
			array_push( $lista, $profile_match->entity_id );
		}
		$output = [];
		foreach ( $lista as $key => $value ) {
			// lets get bill and then further validate
			$_bill = $this->getBill( MSABase::LEGISLATION, $value );
			// validate federal input information and filter bills on that
			if ( ! is_null( $_bill ) ) {
				// check session if its active
				if ( ! isset( $_bill->session_id ) ) {
					continue;
				} else {
					$_bill->getSessionInformation();
					if ( $_bill->session_information->is_active === '0' ) {
						continue;
					}
				}
				$_bill->getPrioritzed( true );
				$_bill->getNotesForBill();
				$_bill->getLatestUpdated();
				$output[] = $_bill;

			}

		}
		$data = $this->generateLegislation( $output );

		return $this->generateDataTableResult( count( $output ), $data, $draw );
	}

	public function getRegulationsSolr( $category = null, $federal = '', $search = null, $draw, $start, $request ) {
		$columns     = [
			0 => 'regulation_id',
			3 => 'state',
			4 => 'agency_name',
			5 => 'type',
			6 => 'state_action_type',
			7 => 'register_date',
		];
		$order       = [
			'order_by' => $columns[ $request['order'][0]['column'] ],
			'order'    => $request['order'][0]['dir']
		];
		$solr_result = apply_filters( 'solr_get_regulations', $category, $federal, null, $search, $start, $order );
		$list        = ( implode( ',', $this->getSolrID( $solr_result ) ) );
		if ( $list !== '' ) {
			$_result = $this->wpdb->get_results(
				"SELECT reg.id as regulation_id,state,agency_name,type,state_action_type,register_date,COUNT(DISTINCT bn.bill_id) as bookmark_note,GROUP_CONCAT(DISTINCT pm.pname) as categories,(SELECT COUNT(id) FROM prioritized_bills WHERE entity_type = 'regulation' and bill_id = reg.id) as priority FROM regulation AS reg 
                LEFT JOIN bill_notes AS bn ON reg.id= bn.bill_id and entity_type = 'regulation' 
                LEFT JOIN profile_match AS pm ON reg.id = pm.entity_id  and pm.entity_type = 'regulation'
                WHERE reg.id IN ({$list})
                GROUP BY reg.id
                ORDER BY {$order['order_by']} {$order['order']};", OBJECT );
		} else {
			$_result = [];
		}

		$data = $this->generateRegulationResult( $_result );

		return $this->generateDataTableResult( $solr_result->getNumFound(), $data, $draw );
	}

	public function getRegulations( $category = null, $federal = '' ) {
		// validate input request for profile matches compare
		if ( $category !== null ) {
			$_result = $this->wpdb->get_results( $this->wpdb->prepare(
				"SELECT * FROM profile_match 
                       WHERE entity_type = %s
                       AND pname = %s",
				MSABase::REGULATION,
				$category ),
				OBJECT );
		} else {
			$_result = $this->wpdb->get_results( $this->wpdb->prepare(
				"SELECT * FROM profile_match 
                       WHERE entity_type = %s",
				MSABase::REGULATION ),
				OBJECT );
		}

		$lista = [];
		// validate user categories
		foreach ( $_result as $profile_match ) {
			$lista[ $profile_match->entity_id ] = "";
		}

		$output = [];
		foreach ( $lista as $key => $value ) {
			// lets get bill and then further validate
			$_bill = $this->getBill( MSABase::REGULATION, $key );
			// validate federal input information and filter bills on that
			if ( $federal === 'state' ) {
				if ( $_bill->state === 'US' ) {
					break;
				}
			} else if ( $federal === 'us' ) {
				if ( $_bill->state !== 'US' ) {
					break;
				}
			}

			// show only non hidden client bills
			$_bill->getHiddenStatus();
			if ( ! $_bill->hidden ) {
				$_bill->getPrioritzed();
				$_bill->getNotesForBill();
				$_bill->getBillCategories();
				$output[] = $_bill;
			}

		}

		return $this->generateRegulationResult( $output );
	}


	public function getHearingsSolr( $category = null, $federal = '', $search = null, $draw, $start, $request ) {
		$columns     = [
			0  => 'hearing_id',
			3  => 'date',
			4  => 'time',
			5  => 'house',
			6  => 'committee',
			7  => 'place',
		];
		$order       = [
			'order_by' => $columns[ $request['order'][0]['column'] ],
			'order'    => $request['order'][0]['dir']
		];
		$solr_result = apply_filters( 'solr_get_hearings', $category, $federal, null, $search, $start, $order );

		$list = ( implode( ',', $this->getSolrID( $solr_result ) ) );
		//TODO implement solr and datable connection for number of visible items and paginating
		if ( $list !== '' ) {
			$_result = $this->wpdb->get_results(
				"SELECT her.id as hearing_id,date,time,house,committee,place,COUNT(DISTINCT bn.bill_id) as bookmark_note,(SELECT id FROM prioritized_bills WHERE entity_type = 'hearing' and bill_id = her.id) as priority FROM hearing AS her 
                LEFT JOIN bill_notes AS bn ON her.id= bn.bill_id and entity_type = 'hearing'
                LEFT JOIN profile_match AS pm ON her.id = pm.entity_id  and pm.entity_type = 'hearing'
                WHERE her.id IN ({$list})
                GROUP BY her.id
                ORDER BY {$order['order_by']} {$order['order']};", OBJECT );
		} else {
			$_result = [];
		}

		$data = $this->generateHearingResult( $_result );

		return $this->generateDataTableResult( $solr_result->getNumFound(), $data, $draw );
	}

	public function getHearings( $category = null ) {
		// validate input request for profile matches compare
		$_result = $this->getProfileMatches( MSABase::HEARING, $category, true );
		$lista   = [];
		// validate user categories
		foreach ( $_result as $profile_match ) {

			$lista[ $profile_match->entity_id ] = "";

		}
		$output = [];
		foreach ( $lista as $key => $value ) {
			// lets get bill and then further validate
			$_bill = $this->getBill( MSABase::HEARING, $key );
			if ( $_bill == null ) {
				continue;
			}
			// show only non hidden client bills
			$_bill->getHiddenStatus();
			if ( ! $_bill->hidden ) {
				$_bill->getPrioritzed();
				$_bill->getNotesForBill();
				$output[] = $_bill;
			}

		}

		return $this->generateHearingResult( $output );
	}


	public function getRegulationBillDetail( $bill_id ) {
		$_bill = $this->getBill( MSABase::REGULATION, $bill_id );
		// TODO need to solve this
		//$_bill->getPrioritzed();
		$_bill->getNotesForBill( true );

		return $_bill;
	}

	public function getHearingBillDetail( $id ) {
		$_bill = $this->getBill( MSABase::HEARING, $id );
		$_bill->getPrioritzed();
		$_bill->getNotesForBill( true );
		$_bill->getHearingLegislation();

		return $_bill;
	}

	public function getLegislationBillDetail( $id ) {
		$_bill = $this->getBill( MSABase::LEGISLATION, $id );
		$_bill->getPrioritzed();
		$_bill->getNotesForBill( true );
		$_bill->getHearingForLegislation();
		$_bill->getSessionInformation();

		return $_bill;
	}

	/**
     * Returns all sessions row available
	 * @return array|object|null
	 */
	public function getSessionInformationList(){
		$_result= $this->wpdb->get_results("SELECT * FROM session_info",OBJECT);
		return $_result;
    }

	/**
     * Gets session information from session table by ID
	 * @param $id
	 *
	 * @return array|object|void|null
	 */
    public function getSessionInformationEdit($id){
	    $_result = $this->wpdb->get_row( "SELECT * FROM session_info WHERE id = {$id}", OBJECT );
	    return $_result;
    }

	/**
	 * @return array|object|null
	 */
    public function getSessitionListFromLegislation(){
	    $_result = $this->wpdb->get_results("SELECT DISTINCT session FROM legislation",OBJECT);
	    return $_result;
    }
}

//we need to determine if user is : staff, user (client child) or visitor
function MSAvalidateUserRole() {
	$user = null;
	if ( is_user_logged_in() ) {
		$_user       = get_userdata( get_current_user_id() );
		$_user_roles = $_user->roles;
		//TODO replace this with new "staff" role afterwards
		if ( in_array( 'administrator', $_user_roles, true ) ) {
			$user = new MSAAdmin();
		} else {
			$user = new MSAUser();
		}
	} else {
		$user = new MSAVisitor();
	}

	return $user;
}