<?php

/*
 * rest api test
 */
add_action( 'wp_enqueue_scripts', 'rest_site_scripts' );
function rest_site_scripts() {
	// Enqueue our JS file
	wp_enqueue_script( 'rest_appjs',
		get_template_directory_uri() . '/js/app.js',
		array( 'jquery' ), filemtime( get_template_directory() . '/js/app.js'), true
	);

	// Provide a global object to our JS file containing our REST API endpoint, and API nonce
	// Nonce must be 'wp_rest' !
	wp_localize_script( 'rest_appjs', 'rest_object',
		array(
			'api_nonce' => wp_create_nonce( 'wp_rest' ),
			'api_url'   => site_url('/wp-json/msa/v1/')
		)
	);
}
add_action( 'rest_api_init', 'rest_validate_email_endpoint' );
function rest_validate_email_endpoint() {
	// Declare our namespace
	/*$namespace = 'msa/v1';
	register_rest_route($namespace,'/legislation_list/',array(
		'methods'   => WP_REST_Server::READABLE,
		'callback'  => 'rest_legapi_handler',
		'args'=>array(
			'cat' => ['required' => true]
		),  'permission_callback' => function () {
			return is_user_logged_in();
		}
	));
	// Register the route
	register_rest_route( $namespace, '/email/', array(
		'methods'   => 'POST',
		'callback'  => 'rest_validate_email_handler',
		'args'      => array(
			'email'  => array( 'required' => true ), // This is where we could do the validation callback
		)
	) );*/
}

// The callback handler for the endpoint
function rest_validate_email_handler( $request ) {
	// We don't need to specifically check the nonce like with admin-ajax. It is handled by the API.
	$params = $request->get_params();

	// Check if email is valid
	if ( is_email( $params['email']) ) {
		return new WP_REST_Response( array('message' => 'Valid email.'), 200 );
	}

	// Previous check didn't pass, email is invalid.
	return new WP_REST_Response( array('message' => 'Not a valid email.'), 200 );
}

function rest_legapi_handler($request){
	print_r(get_current_user());
	$user = MSAvalidateUserRole();
	//print_r(json_encode($user));
	if ( $request['cat'] === 'legislation' ) {
		$state = '';
		$category = null;
		/*
		if(isset($request['federal'])) {
			$state = $request['federal'];
		}
		if(isset($request['category'])){
			$category = $request['category'];
		}*/
		$end_result = $user->getLegislations();
	}
	print_r($end_result);
	$end_result = array(
		"sEcho"                => 1,
		"iTotalRecords"        => count( $end_result ),
		"iTotalDisplayRecords" => count( $end_result ),
		"aaData"               => $end_result
	);
	return new WP_REST_Response($request,200);
}
/*
function my_awesome_func( $data ) {
	return "TEST";
}
add_action( 'rest_api_init', function () {
	register_rest_route( 'msa/v1', '/author', array(
		'methods' => 'GET',
		'callback' => 'my_awesome_func'
	) );
}
);
*/
