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

add_action( 'wp_enqueue_scripts', 'theme_styles');

//Load jscripts
function theme_js() {
    
	global $wp_scripts;
	wp_enqueue_script( 'jquery_js', get_template_directory_uri() . '/js/jquery.js');
    wp_enqueue_script( 'bootstrap_js', get_template_directory_uri() . '/js/bootstrap.min.js' );
    wp_enqueue_script( 'select2', get_template_directory_uri() . '/js/select2.min.js',true );
    wp_enqueue_script( 'datatable_js', get_template_directory_uri() . '/DataTables/datatables.min.js' );
    wp_enqueue_script( 'amchart', get_template_directory_uri() . '/js/ammap.js',true );
    wp_enqueue_script( 'fancybox', get_template_directory_uri() . '/js/jquery.fancybox.min.js' ,true);
    wp_enqueue_script( 'usaLow', get_template_directory_uri() . '/js/usaLow.js' ,true);
    wp_enqueue_script( 'light', get_template_directory_uri() . '/js/light.js' ,true);
    wp_enqueue_script( 'export', get_template_directory_uri() . '/js/export.min.js' ,true);

}

add_action( 'wp_enqueue_scripts', 'theme_js');

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

/**
 * Addding Company info to user data
 */

function custom_user_profile_fields($user){

    global $wpdb;
    $clients="SELECT client FROM `profile_match` group by client";
    $client_result = $wpdb->get_results($clients,ARRAY_A );
    
    $json = file_get_contents(get_site_url().'/wp-content/themes/mainstreet-advocates/states.json');
    $states=json_decode($json);
  
    $default=esc_attr( get_the_author_meta( 'company', $user->ID ) );
    $default_state=esc_attr( get_the_author_meta( 'state', $user->ID ) );

        
  ?>
    <h3>Additional profile information</h3>
    <table class="form-table">
        <tr>
            <th><label for="company">Company Name</label></th>
            <td>
               <select class="regular-text" id="company" name="company">
                           <?php if(strlen($default)>0){  ?>
                     <option value="<?php echo $default?>" selected><?php echo $default ?></option>
                            <?php }  else {?>
                    <option value="" selected disabled>Select a company</option>
                            <?php  } foreach ($client_result as $client_name) {  ?>
                    <option value="<?php echo $client_name[client] ?>" ><?php echo $client_name[client] ?></option>
                            <?php } ?>
               </select>
            </td>
        </tr> 
        <tr>
            <th><label for="company">State</label></th>
            <td>
               <select class="regular-text" id="state" name="state">
                           <?php if(strlen($default_state)>0){  ?>
                     <option value="<?php echo $default_state?>" selected><?php echo $default_state ?></option>
                            <?php }  else {?>
                    <option value="" selected disabled>Select a state</option>
                            <?php  } foreach ($states as $state) {  ?>
                    <option value="<?php echo $state->abbreviation; ?>" ><?php echo $state->abbreviation; ?></option>
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

function save_custom_user_profile_fields($user_id){
    # again do this only if admin
    if(!current_user_can('manage_options'))
        return false;

    # save my custom field
    update_usermeta($user_id, 'company', $_POST['company']);
    update_usermeta($user_id, 'state', $_POST['state']);
}
add_action('user_register', 'save_custom_user_profile_fields');
add_action('profile_update', 'save_custom_user_profile_fields');



function am_enqueue_admin_styles(){
   
    wp_register_style( 'am_admin_bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css' );
    wp_enqueue_style( 'am_admin_bootstrap');

}

add_action( 'admin_enqueue_scripts', 'am_enqueue_admin_styles' );



// custom function  - returns profiles / categories matches function
function getCategoriesByID($id) {
    global $wpdb;
    $cat_query="SELECT pname FROM `profile_match` where entity_id = $id ";
    $categories = $wpdb->get_results($cat_query,OBJECT);

    foreach($categories as $categorie){
        $name[] = $categorie->pname;
    }
    
    if($name !==null){
        return implode(", ",$name);
    }
}

function getCategoriesByClient($ent_name) {
    global $wpdb;
    $ent_name= strtolower($ent_name);
    $cat_query="SELECT pname FROM `profile_match` where client like '%$ent_name%' GROUP BY pname";
    $categories = $wpdb->get_results($cat_query,OBJECT);

    foreach($categories as $categorie){
        $name[] = $categorie->pname;
    }
    
    if($name !==null){
        return implode(", ",$name);
    }
}

function getCategoriesByClient2($ent_name) {
    global $wpdb;
    $ent_name= strtolower($ent_name);
    $cat_query="SELECT * FROM `profile_match` where client like '%$ent_name%' GROUP BY pname";
    $categories = $wpdb->get_results($cat_query,OBJECT);

    return $categories;
    
}

// custom function  - returns profiles / categories matches function
function getCategoriesByUser($user_id) {
    global $wpdb;
    $cat_query2="SELECT lfront FROM `user_profile` where user_id='$user_id' and lfront IS NOT NULL";
    $categories2 = $wpdb->get_results($cat_query2,OBJECT);

    foreach($categories2 as $categorie2){
        $name2[] = $categorie2->lfront;
    }
    
    if($name2 !==null){
        return implode(", ",$name2);
    }
}

// custom function  - returns keywords function
function getKeyword($ent_id) {
    global $wpdb;
    $key_query="SELECT keyword FROM `profile_keyword` where profile_match_id = '$ent_id'";
    $keywords = $wpdb->get_results($key_query,OBJECT);

    foreach($keywords as $keyword){
        $key_name[] = $keyword->keyword;
    }

    if($key_name !==null){
        return implode(", ",$key_name);
    }

}

// custom function  - returns keywords function
function getAllClients() {
    global $wpdb;
    $client_query="SELECT client FROM `profile_match` group by client";
    $client = $wpdb->get_results($client_query,OBJECT);
    
    return $client;
    }

// custom function  - returns keywords function
function getCleint($ent_id) {
    global $wpdb;
    $client_query="SELECT client FROM `profile_match` where entity_id = '$ent_id'";
    $client = $wpdb->get_row($client_query,OBJECT);

   
     return $client->client;
    }

// custom function  - returns related documents function
function getRelatedBills($ent_id) {
    global $wpdb;
    $bill_query="SELECT * FROM `related_bill` where legislation_id='$ent_id'";
   
    $bills = $wpdb->get_results($bill_query,OBJECT);

    return $bills;
    
}
