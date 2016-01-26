<?php
/**
 * Cocoro functions and definitions
 *
 * @package Cocoro
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'cocoro_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function cocoro_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Cocoro, use a find and replace
	 * to change 'cocoro' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'cocoro', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'cocoro' ),
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	// Setup the WordPress core custom background feature.
	#add_theme_support( 'custom-background', apply_filters( 'cocoro_custom_background_args', array(
	#	'default-color' => 'ffffff',
	#	'default-image' => '',
	#) ) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array( 'comment-list', 'search-form', 'comment-form', ) );
}
endif; // cocoro_setup
add_action( 'after_setup_theme', 'cocoro_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function cocoro_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'cocoro' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'cocoro_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function cocoro_scripts() {
	wp_enqueue_style( 'cocoro-style', get_stylesheet_uri() );

	wp_enqueue_script( 'cocoro-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'cocoro-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
	wp_enqueue_script( 'cocoro-blockUI', get_template_directory_uri() . '/js/jquery.blockUI.js', array(), '', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'cocoro_scripts' );

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/* Disable WordPress Admin Bar for all users but admins. */
  show_admin_bar(false);

function register_my_menu() {
  register_nav_menu('footer-menu',__( 'Footer Menu' ));
}
add_action( 'init', 'register_my_menu' );

/* News Top Content Text Limitation  */
function content($num) {
$theContent = get_the_content();
$output = preg_replace('/<img[^>]+./','', $theContent);
$output = preg_replace( '/<blockquote>.*<\/blockquote>/', '', $output );
$output = preg_replace( '|\[(.+?)\](.+?\[/\\1\])?|s', '', $output );
$limit = $num+1;
$content = explode(' ', $output, $limit);
array_pop($content);
$content = implode(" ",$content)."...";
echo $content;
}
/* News Top Content First Image  */
function catch_that_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];

  if(empty($first_img)){ //Defines a default image
    $first_img = "";
  }
  return $first_img;
}




add_action( 'auth_redirect', 'subscriber_go_to_home' );
function subscriber_go_to_home( $user_id ) {
$user = get_userdata( $user_id );
if ( !$user->has_cap( 'edit_posts' ) ) {
wp_redirect( get_home_url().'/profile/' );
exit();
}
}
add_action( 'after_setup_theme', 'subscriber_hide_admin_bar' );

function subscriber_hide_admin_bar() {
$user = wp_get_current_user();
if ( isset( $user->data ) && !$user->has_cap( 'edit_posts' ) ) {
show_admin_bar( false );
}
}

class royaltyCardAPI {
	const APIKEY = '4ACE516C-B2C1-404D-9DD3-4AC5722E1556';
	const SITEID = '0A8D8262-1E1C-48DD-AEA1-E77E7AE73EF6';
	const QueryLoyaltyAccount = 'https://api.pointone.co.uk/Members/MembersAccounts.svc/QueryLoyaltyAccount/JSON/';
	const RegisterPreIssuedLoyaltyCard = 'https://api.pointone.co.uk/Members/MembersAccounts.svc/RegisterPreIssuedLoyaltyCard/JSON';
	const IncrementLoyaltyPointsBalance = 'https://api.pointone.co.uk/Members/MembersAccounts.svc/IncrementLoyaltyPointsBalance/JSON';
	const validateOrder = 'https://api.pointone.co.uk/OOAppService.svc/Order/JSON';

	function getCardInfo($card){
		$buildAPI = self::QueryLoyaltyAccount.self::APIKEY.'/'.self::SITEID.'/'.$card;
		$request = wp_remote_get($buildAPI);
		$response = wp_remote_retrieve_body( $request );
		$arrResponse = json_decode($response);
		$LoyaltyPointsBalance = $arrResponse->LoyaltyPointsBalance;
		return $LoyaltyPointsBalance;
	}
	function addCard($userInfo){
		if(username_exists($userInfo['USER'])){
			return 'Username already exists, please choose different username.';
		}
		if(email_exists($userInfo['EMAIL'])){
			return 'Email already exists, please use different email address.';
		}
		$jsonValue = array(
			"APIKey" => self::APIKEY,
			"SiteID" => self::SITEID,
			"Address" => $userInfo['ADDRESS'],
			"CardNo" => $userInfo['CARD_NO'],
			"ContactByEmail" => "false",
			"ContactBySMS" => "false",
			"Email" => $userInfo['EMAIL'],
			"FirstName" => $userInfo['FIRST_NAME'],
			"Surname" => $userInfo['LAST_NAME'],
		);
		$arg = array(
			'method' => 'POST',
			'timeout' => 120,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(
				'Content-Type' => 'application/json',
				),
			'body' => json_encode($jsonValue),
		);
		$response = wp_remote_post(self::RegisterPreIssuedLoyaltyCard, $arg);
		if(is_wp_error($response)) {
			return $response->get_error_message();
		}
		$json_header = 'application/json; charset=utf-8';
			if(!isset($response['headers']['content-type']) || $response['headers']['content-type'] != $json_header) {
			return 'Error - Header is not json format, please contact us.';
		}
		$obj = json_decode($response['body']);
			if(!is_a($obj,'stdClass')){
			return 'Error - Data may not be correct format.';
		}
		if($obj->Result == 1){
			return 'SUCCESS';
		}else{
			return $obj->ResultString;
		}
	}
	function incrementPoints($userInfo){
		$jsonValue = array(
			"APIKey" => self::APIKEY,
			"SiteID" => self::SITEID,
			"CardNo" => $userInfo['CARD_NO'],
			"PointsAdjustment" => $userInfo['PLUS_POINT'],
		);
		//var_dump($jsonValue);

		$arg = array(
			'method' => 'POST',
			'timeout' => 120,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(
				'Content-Type' => 'application/json',
				),
			'body' => json_encode($jsonValue),
		);
		$response = wp_remote_post(self::IncrementLoyaltyPointsBalance, $arg);
		if(is_wp_error($response)) {
			return $response->get_error_message();
		}
		$json_header = 'application/json; charset=utf-8';
			if(!isset($response['headers']['content-type']) || $response['headers']['content-type'] != $json_header) {
			return 'Error - Header is not json format, please contact us.';
		}
		$obj = json_decode($response['body']);
			if(!is_a($obj,'stdClass')){
			return 'Error - Data may not be correct format.';
		}
		if($obj->Result == 1){
			return 'SUCCESS';
		}else{
			return $obj->ResultString;
		}
	}

}

add_action('registration_errors', 'validationUserInfo', 10, 3);
function validationUserInfo($error, $user, $email){
	if(empty($_POST['cimy_uef_CARD_NO']) || strlen($_POST['cimy_uef_CARD_NO']) != 16){
		return new WP_Error(444, 'Card number must be 16 digit.');
	}
	$userInfo = array(
		'CARD_NO' => $_POST['cimy_uef_CARD_NO'],
		'FIRST_NAME' => $_POST['cimy_uef_FIRST_NAME'],
		'LAST_NAME' => $_POST['cimy_uef_LAST_NAME'],
		'ADDRESS' => $_POST['cimy_uef_ADDRESS'],
		'EMAIL' => $email,
		'USER' => $user,
	);
	$royaltyCardAPI = new royaltyCardAPI();
	$validationCardNumber = $royaltyCardAPI->addCard($userInfo);
	if($validationCardNumber == 'SUCCESS'){
		return $error;
	} else {
		return new WP_Error(444, $validationCardNumber);
	}
}


add_action( 'woocommerce_thankyou', 'addRoyaltyPoint', 10, 1 );
function addRoyaltyPoint($order_id){

	if(!get_current_user_id()){
		return false;
	}
    $order = new WC_Order( $order_id );
	$subtotal = $order->get_subtotal();
	$pointsAdjustment = ($subtotal - $order->cart_discount);
	$cardNo = get_cimyFieldValue(get_current_user_id(), 'CARD_NO');

	$userInfo = array(
		"CARD_NO" => $cardNo,
		"PLUS_POINT" => number_format($pointsAdjustment,2),
	);

	$royaltyCardAPI = new royaltyCardAPI();
	$rtnIncrementPoints = $royaltyCardAPI->incrementPoints($userInfo);

	if($rtnIncrementPoints == 'SUCCESS'){
		//echo 'aaaa';
	} else {
		return new WP_Error(444, $rtnIncrementPoints);
	}
}


//add_action( 'woocommerce_calculate_totals','apply_shipping_discount' );
function apply_shipping_discount() {
	global $woocommerce;

	if ( is_admin() && ! defined( 'DOING_AJAX' ) )
	return;

	$chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
	$chosen_shipping = $chosen_methods[0];
	$coupon_code = 'sample'; // your coupon code here
	//$woocommerce->cart->remove_coupon($coupon_code);

	if ($chosen_shipping == 'local_pickup') {
		if ( $woocommerce->cart->has_discount( $coupon_code ) ){
			return false;
		}else{
			$woocommerce->cart->add_discount( $coupon_code );
			$cart_updated = apply_filters( 'woocommerce_update_cart_action_cart_updated', true );
		}
	}else{
		$woocommerce->cart->cart_contents_total = $woocommerce->cart->subtotal;
		$woocommerce->cart->remove_coupon($coupon_code);
		//WC()->session->total = 100;
		//$cart_updated = apply_filters( 'woocommerce_update_cart_action_cart_updated', true );
	}
	//echo "<pre>";var_dump(WC()->session->total);echo "</pre>";
	//echo "<pre>";var_dump($woocommerce->cart);echo "</pre>";

}

add_action( 'woocommerce_cart_calculate_fees','woocommerce_custom_discount' );
function woocommerce_custom_discount() {
	global $woocommerce;
	if ( is_admin() && ! defined( 'DOING_AJAX' ) )
	return;

	$exclude_cate = array("soft-drink","party-special-platter","japanese-tableware-gift-sets","drink","sushi-boxes");

	$items = $woocommerce->cart->get_cart();
	$discount = '';
	foreach($items as $item => $values) {
		$_product = $values['data']->post;
		$product_id = $_product->ID;
		$quantity = $values['quantity'];
		$price = get_post_meta($values['product_id'] , '_price', true);
		$bool_discount = true;

		$product_cat = wp_get_post_terms( $product_id, 'product_cat');
		foreach ($product_cat as $value) {
			if( in_array($value->slug, $exclude_cate) ){
				$bool_discount = false;
			}
		}
		if(!empty($product_cat) && $bool_discount){
			$discount = $discount + ($price * $quantity);
		}
	}

	$discount = round($discount/10, 2, PHP_ROUND_HALF_DOWN);
	$woocommerce->cart->discount_cart = number_format($discount,2);
	$woocommerce->cart->cart_contents_total = $woocommerce->cart->cart_contents_total - $woocommerce->cart->discount_cart;
}


function AJ_add_to_cart() {
	global $woocommerce;
	// The $_REQUEST contains all the data sent via ajax
	if ( isset($_REQUEST) ) {
		$p_id = $_REQUEST['p_id'];
		$woocommerce->cart->add_to_cart( $p_id );
		//echo '<script>console.log('.$p_id.');</script>';
		echo getItemsInCart();
		// If you're debugging, it might be useful to see what was sent in the $_REQUEST
		// print_r($_REQUEST);
	}
	// Always die in functions echoing ajax content
	die();
}
add_action( 'wp_ajax_nopriv_AJ_add_to_cart', 'AJ_add_to_cart' );
add_action( 'wp_ajax_AJ_add_to_cart', 'AJ_add_to_cart' );

function getItemsInCart(){
	global $woocommerce;
	$items = $woocommerce->cart->get_cart();
	$str = '';
	foreach($items as $item => $values) {
		$_product = $values['data']->post;
		$str .= "<div><b>".$_product->post_title.'</b> x '.$values['quantity'].'<br>';
		$price = get_post_meta($values['product_id'] , '_price', true);
		$str .= " Price: ".$price."<span data-product_id='".$values['product_id']."' class='delete-item'>[Delete]<span></div>";
	}
	return $str;
}


function AJ_delete_from_cart() {
	global $woocommerce;
	// The $_REQUEST contains all the data sent via ajax
	if ( isset($_REQUEST) ) {

		$p_id = $_REQUEST['p_id'];
		$items = $woocommerce->cart->get_cart();
		foreach ( $items as $cart_item_key => $values ) {
			if($values['product_id'] == $p_id ){
			$test = $woocommerce->cart->remove_cart_item( $cart_item_key );
			}
		}

	echo getItemsInCart();

	// If you're debugging, it might be useful to see what was sent in the $_REQUEST
	// print_r($_REQUEST);
	}
	// Always die in functions echoing ajax content
	die();
}
add_action( 'wp_ajax_nopriv_AJ_delete_from_cart', 'AJ_delete_from_cart' );
add_action( 'wp_ajax_AJ_delete_from_cart', 'AJ_delete_from_cart' );


// Hook after add to cart
add_action( 'woocommerce_add_to_cart' , 'repair_woocommerce_ajax_add_to_cart_guest');

function repair_woocommerce_ajax_add_to_cart_guest( ){
    if ( defined( 'DOING_AJAX' ) ) {
        wc_setcookie( 'woocommerce_items_in_cart', 1 );
        wc_setcookie( 'woocommerce_cart_hash', md5( json_encode( WC()->cart->get_cart() ) ) );
        do_action( 'woocommerce_set_cart_cookies', true );
    }
}


function sortArrayByKey( &$array, $sortKey, $sortType = SORT_ASC ) {
    $tmpArray = array();
    foreach ( $array as $key => $row ) {
        $tmpArray[$key] = $row[$sortKey];
    }
    array_multisort( $tmpArray, $sortType, $array );
    unset( $tmpArray );
}

// Hook in
add_filter( 'woocommerce_checkout_fields' , 'collection_time' );

// Our hooked in function - $fields is passed via the filter!
function collection_time( $fields ) {
	$arrTime = array();
	for ($t=11; $t <22; $t++) { 
		$arrTime[$t.':00'] = $t.':00';
		$arrTime[$t.':15'] = $t.':15';
		$arrTime[$t.':30'] = $t.':30';
		$arrTime[$t.':45'] = $t.':45';
	}
     $fields['billing']['collection_time'] = array(
        'label'     => __('Collection/Delivery time', 'woocommerce'),
    //'placeholder'   => _x('dropdown', 'placeholder', 'woocommerce'),
    'required'  => true,
    'class'     => array('form-row-wide'),
    'clear'     => true,
    'type'      => 'select',
    'options'  => 	$arrTime,
    );

    return $fields;
}

add_action( 'woocommerce_before_checkout_process', 'initiate_order' , 10, 1 );
function initiate_order($order_id){
global $woocommerce;
$items = $woocommerce->cart->get_cart();
$cartTotal = $woocommerce->cart->total;
$discountAmount = $woocommerce->cart->discount_cart;

$itemList = array();
foreach($items as $item => $values) {
	$epos_product_id = get_field('epos_product_id',$values['product_id']);
	$quantity =$values['quantity'];
	array_push($itemList, array("ID" => "{$epos_product_id}","Quantity" => "$quantity "));
}


if($_POST["ship_to_different_address"]==1){
	//shipping address
		$customer_name = $_POST["shipping_first_name"];
		$customer_name .= ' '.$_POST["shipping_last_name"];
		$shipping_address = $_POST["shipping_address_1"];
		$shipping_address .= ' '.$_POST["shipping_address_2"];
		$shipping_address .= ' '.$_POST["shipping_city"];
		$shipping_address .= ' '.$_POST["shipping_state"];
		$shipping_postcode = $_POST["shipping_postcode"];
		$email = $_POST["billing_email"];
		$phone = $_POST["billing_phone"];
		$comment = $_POST["order_comments"];
		$collection_time = $_POST["collection_time"];

	}else{
	//billing address
		$customer_name = $_POST["billing_first_name"];
		$customer_name .= ' '.$_POST["billing_last_name"];
		$shipping_address = $_POST["billing_address_1"];
		$shipping_address .= ' '.$_POST["billing_address_2"];
		$shipping_address .= ' '.$_POST["billing_city"];
		$shipping_address .= ' '.$_POST["billing_state"];
		$shipping_postcode = $_POST["billing_postcode"];
		$email = $_POST["billing_email"];
		$phone = $_POST["billing_phone"];
		$comment = $_POST["order_comments"];
		$collection_time = $_POST["collection_time"];
}
$shipping_fee = $amount2 = floatval( preg_replace( '#[^\d.]#', '', $woocommerce->cart->get_cart_shipping_total() ) );
$today = date("d/m/Y");

	$chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
	$chosen_shipping = $chosen_methods[0];
	$chosen_shipping == 'local_pickup' ? $chosen_shipping_id = 0 : $chosen_shipping_id = 1; //0=pick / 1=deli

//wc_add_notice( $amount2 ,'error' );

	$jsonValue = array(
		"APIKey" => royaltyCardAPI::APIKEY,
		"SiteID" => royaltyCardAPI::SITEID,
		"UserID" => "ab9d1dd6-52ea-4acf-bb29-06f2da959f33",
  		"OrderItems" => $itemList,
  		"OrderType" => $chosen_shipping_id,
		//"RequiredDate" => $today,
		"RequiredDate" => '12/01/2016',
  		"RequiredTime" => $collection_time,
  		"Name" => $customer_name,
 		"DeliveryAddress" => $shipping_address,
  		"DeliveryPostcode" => $shipping_postcode,
  		"TelNo" => $phone,
  		"Email" => $email,
  		"DeliveryInstructions" => $comment,
  		"DeliveryCost" => $shipping_fee,
  		//"PromoCode" => 'sample',
  		"Payment" => array(
  			"PaymentType" => "4"
  			),
  		"OrderTotal" => $cartTotal - $shipping_fee + $discountAmount
	);

	$arg = array(
		'method' => 'POST',
		'timeout' => 120,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'headers' => array(
			'Content-Type' => 'application/json',
			),
		'body' => json_encode($jsonValue, JSON_UNESCAPED_SLASHES),
	);

	$response = wp_remote_post(royaltyCardAPI::validateOrder, $arg);
	if(is_wp_error($response)) {
		//return $response->get_error_message();
		wc_add_notice( $response->get_error_message() ,'error' );
	}
	$json_header = 'application/json; charset=utf-8';
		if(!isset($response['headers']['content-type']) || $response['headers']['content-type'] != $json_header) {
		return 'Error - Header is not json format, please contact us.';
	}
	$obj = json_decode($response['body']);
		if(!is_a($obj,'stdClass')){
		return 'Error - Data may not be correct format.';
	}
	if($obj->Result == 4){
		//wc_add_notice( $woocommerce->cart ,'error' );
		if (!session_id()) {
    		session_start();
		}
		$_SESSION["TransactionID"] = $obj->TransactionID;
		$_SESSION["CollectionTime"] = $collection_time;
		wc_add_notice( json_encode($jsonValue, JSON_UNESCAPED_SLASHES) ,'error' );

	}else{
		
		//wc_add_notice( $discountAmount ,'error' );
		//wc_add_notice( json_encode($jsonValue, JSON_UNESCAPED_SLASHES) ,'error' );
		wc_add_notice( $obj->ResultString ,'error' );
	}
	
    
}

add_action( 'woocommerce_thankyou', 'sendDataToEPOS');
function sendDataToEPOS($order_id){
	if(!session_id()){
		session_start();
	}
	$transaction_id = $_SESSION["TransactionID"];
	$collection_time = $_SESSION["CollectionTime"];

	$order = new WC_Order( $order_id );
	$itemList = array();
	foreach($order->get_items() as $key => $value) {
		$epos_product_id = get_field('epos_product_id',$value["item_meta"]["_product_id"][0]);
		$quantity = $value["item_meta"]["_qty"][0];
		array_push($itemList, array("ID" => "{$epos_product_id}","Quantity" => "$quantity"));
	}
	$discountAmount = $order->cart_discount;
	$cartTotal = $order->get_total();


	//shipping address
		$customer_name = $order->shipping_first_name;
		$customer_name .= ' '.$order->shipping_last_name;
		$shipping_address = $order->shipping_address_1;
		$shipping_address .= ' '.$order->shipping_address_2;
		$shipping_address .= ' '.$order->shipping_city;
		$shipping_address .= ' '.$order->shipping_state;
		$shipping_postcode = $order->shipping_postcode;
		$email = $order->billing_email;
		$phone = $order->billing_phone;
		$comment = $order->order_comments;

	$shipping_fee = $order->get_total_shipping();
	$today = date("d/m/Y");

	$chosen_methods = $order->get_shipping_method();
	$chosen_methods == 'Local Pickup' ? $chosen_shipping_id = 0 : $chosen_shipping_id = 1; //0=pick / 1=deli

//wc_add_notice( $amount2 ,'error' );

	$jsonValue = array(
		"APIKey" => royaltyCardAPI::APIKEY,
		"SiteID" => royaltyCardAPI::SITEID,
		"UserID" => "ab9d1dd6-52ea-4acf-bb29-06f2da959f33",
  		"OrderItems" => $itemList,
  		"OrderType" => $chosen_shipping_id,
		"RequiredDate" => $today,
		//"RequiredDate" => '05/01/2016',
  		"RequiredTime" => $collection_time,
  		"Name" => $customer_name,
 		"DeliveryAddress" => $shipping_address,
  		"DeliveryPostcode" => $shipping_postcode,
  		"TelNo" => $phone,
  		"Email" => $email,
  		"DeliveryInstructions" => $comment,
  		"DeliveryCost" => $shipping_fee,
  		"Payment" => array(
  			"PaymentType" => "4",
  			"TransactionID"=>$transaction_id
  			),
  		"OrderTotal" => $cartTotal - $shipping_fee + $discountAmount
	);

	$arg = array(
		'method' => 'POST',
		'timeout' => 120,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'headers' => array(
			'Content-Type' => 'application/json',
			),
		'body' => json_encode($jsonValue, JSON_UNESCAPED_SLASHES),
	);

	$response = wp_remote_post(royaltyCardAPI::validateOrder, $arg);
	if(is_wp_error($response)) {
		//return $response->get_error_message();
		wc_add_notice( $response->get_error_message() ,'error' );
	}
	$json_header = 'application/json; charset=utf-8';
		if(!isset($response['headers']['content-type']) || $response['headers']['content-type'] != $json_header) {
		return 'Error - Header is not json format, please contact us.';
	}
	$obj = json_decode($response['body']);
		if(!is_a($obj,'stdClass')){
		return 'Error - Data may not be correct format.';
	}
	if($obj->Result == 4){
		//wc_add_notice( $obj->ResultString ,'error' );
		echo $obj->ResultString;
		session_destroy();
	}else{
		echo $obj->ResultString;
		session_destroy();
	}

}



add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

function custom_override_checkout_fields( $fields ) {
$fields['order']['order_comments']['placeholder'] = 'Type the present code here!';
$fields['order']['order_comments']['label'] = 'Present Code';
return $fields;
}


/**
 * login page customise
 */

function custom_login_logo() { ?>
	<style>
		#login {
			width:90%;
			max-width:320px;
    		padding: 5% 0 0;
		}
		.login #login h1 a {
			background-image: url(http://cocororestaurant.co.uk/wp-content/themes/cocoro-ii/img/logo_top.png) !important; 
			width:100%;
			height:40px;
			-webkit-background-size:100%;
			background-size: 100%; 
		}
		.login #login_error, .login .message {
    		border-left: 0;
    		background: none;
    		box-shadow: none;
		}
		#login_error, .login .message {
    		padding: 0;
		}
		.login form {
    		margin-top: 10px;
    		margin-left: 0;
    		padding: 16px 14px 16px;
    		background: #fff;
    		-webkit-box-shadow: none;
    		box-shadow: none;
		}
		.login label {
		    font-size: 12px;
		}
		.login form .input, .login input[type=text] {
    		font-size: 14px;
    		padding: 0 3px;
    		margin: 2px 6px 10px 0;
		}
		.wp-core-ui .button-primary {
		    background: #5f1602 !important;
		    border-color: #932a10 !important; 
		}
		.login #nav {
		    margin: 10px 0 0 !important;
    		text-align: center;
		}
		.login #backtoblog {
			padding:0 !important;
		}
		.login #backtoblog a {
			display:block;
			width:100%;
			line-height:30px;
			background:orange;
			color:#fff;
			text-align:center;
			-moz-border-radius:3px;
			-webkit-border-radius:3px;
			border-radius:3px;
			-khtml-border-radius:3px;
		}
		.login #backtoblog a:hover {
				background:darkorange;
				color:#fff;
		}
		.login .message.register {
			display:none;
		}

	</style>
<?php }
add_action( 'login_enqueue_scripts', 'custom_login_logo' );