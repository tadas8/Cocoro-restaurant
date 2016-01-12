<?php
/**
 * Template Name: test
 *
 *
 * @package Cocoro
 */
get_header(); ?>
<pre>
<?php
$order = new WC_Order( 746 );



	$itemList = array();
	foreach($order->get_items() as $key => $value) {
		$epos_product_id = get_field('epos_product_id',$value["item_meta"]["_product_id"][0]);
		$quantity = $value["item_meta"]["_qty"][0];
		array_push($itemList, array("ID" => "{$epos_product_id}","Quantity" => "$quantity"));
	}
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
		//"RequiredDate" => $today,
		"RequiredDate" => '22/12/2015',
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
  		"OrderTotal" => $cartTotal
	);


	var_dump($jsonValue);

?>
</pre>




<?php get_footer(); ?>
