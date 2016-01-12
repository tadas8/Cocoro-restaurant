<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' );

$shop_holiday = get_field("shop_holiday",434);
$tue_thu_sun_open = get_field("tue_thu_sun_open",434);
$tue_thu_sun_close = get_field("tue_thu_sun_close",434);
$fri_sat_open = get_field("fri_sat_open",434);
$fri_sat_close = get_field("fri_sat_close",434);
$day = date("D");
$now = strtotime(date("G:i"));
//var_dump($day);
//var_dump(date("G:i"));
//var_dump(date("Y/m/d"));
$arr_shop_holiday = explode( ';', $shop_holiday);
if(in_array(date("Y/m/d"), $arr_shop_holiday)){
	$open = true;
}

switch ($day) {
	case 'Mon':
		$open = true;
		break;
	case 'Sun':
	case 'Tue':
	case 'Wed':
	case 'Thu':
		if(strtotime($tue_thu_sun_open) < $now && $now < strtotime($tue_thu_sun_close)){
			$open = true;
		}else{
			$open = true;
		}
		break;

	case 'Fri':
	case 'Sat':
		if(strtotime($fri_sat_open) < $now && $now < strtotime($fri_sat_close)){
			$open = true;
		}else{
			$open = true;
		}
		break;

	default:
		$open = true;
		break;
}

//var_dump($open);



?>

	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

		<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

			<h1 class="shop-title"><?php woocommerce_page_title(); ?></h1>

			<?php if(!$open): ?>
				<div class="online-order-warning">Online order is available<br />12:00 - 20:45 on Tue,Wed,Thu,Sun<br />12:00 - 21:15 on Fri,Sat</div>
			<?php endif; ?>

		<?php endif; ?>

		<?php
			/**
			 * woocommerce_archive_description hook
			 *
			 * @hooked woocommerce_taxonomy_archive_description - 10
			 * @hooked woocommerce_product_archive_description - 10
			 */
			do_action( 'woocommerce_archive_description' );
		?>

		<?php if ( have_posts() ) : ?>



<div class="row">
<?php

$args = array( 'taxonomy' => 'product_cat' );
$terms = get_terms('product_cat', $args);
//var_dump($terms);

if (count($terms) > 0) {
echo '<ul class="product_cat_items 3u">';
foreach ($terms as $term) {
echo '<li class="product_cat_item" data-side_taxonomy="'.$term->term_taxonomy_id.'">' . $term->name . '</li> ';
}
echo '</ul>';
}
?>


<div class="cat-product-wrap 6u">
<?php
//var_dump($terms);
$first = true;
foreach ($terms as $term):
$thumbnail_id = get_woocommerce_term_meta( $term->term_taxonomy_id, 'thumbnail_id', true );
$image = wp_get_attachment_url( $thumbnail_id );
?>

<ul id="product_list_<?php echo $term->term_taxonomy_id; ?>" style="display:<?php echo $first ? 'block':'none'; ?>;">
<li><img style="width:100%;" src="<?php echo $image; ?>"></li>
<li><h2><?php echo $term->name; ?></h2></li>
<?php
	$args = array( 'post_type' => 'product', 'posts_per_page' => -1, 'product_cat' => $term->slug, 'orderby' => 'meta_value', 'orderby' => '_sku', 'order' => 'ASC');
	$loop = new WP_Query( $args );
	//echo '<pre>';var_dump($loop);echo '</pre>';
	$j=0;
	$tmpData = array();
	while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
		<?php
		$tmpData[$j]["title"] = get_the_title();
		$tmpData[$j]["price"] = $product->get_price_html();
		$tmpData[$j]["content"] = get_the_content();
		$tmpData[$j]["product_id"] = $loop->post->ID;
		$tmpData[$j]["sku"] = $product->get_sku();
		$j++;
		?>
	<?php endwhile; ?>
	<?php wp_reset_query(); ?>
	<?php
	sortArrayByKey( $tmpData, 'sku' );
	$h=0;
	foreach ($tmpData as $key => $value): ?>
		<li class="product">
			<div class="item-detail">
				<h3><?php echo $value["title"]; ?> - <span class="price"><?php echo $value["price"]; ?></span></h3>
				<p><?php echo $value["content"]; ?></p>
			</div>
			<?php if($open): ?>
				<div rel="nofollow" data-product_id="<?php echo $value["product_id"]; ?>" data-product_sku="<?php echo $value["sku"]; ?>" data-quantity="1" class="ATC product_type_simple"><div class="button">Add to cart</div></div>
			<?php endif;
			//var_dump($product);
			//woocommerce_template_loop_add_to_cart( $loop->post, $product ); ?>
		</li>
	<?
	$h++;
	endforeach;
	?>
</ul><!--/.products-->
<?php
$first = false;
endforeach; ?>
</div>

<div class="items-in-cart 3u">
	<div id="in-cart">
		<?php echo getItemsInCart(); ?>
	</div>
	<div class="item-detail">
		<h3>Chopsticks</h3>
		<span class="price">£0.05</span>
	</div>
	<?php if($open): ?>
		<div rel="nofollow" data-product_id="481" data-product_sku="chopsticks" data-quantity="1" class="ATC product_type_simple"><div class="button">Add to pair</div></div>

		<div class="checkout">
			<a href="<?php echo site_url(); ?>/cart/">Go to checkout</a>
		</div>
   <?php endif; ?>


    <div class="note">
    	<img src="<?php bloginfo('template_directory'); ?>/img/img-delivery-area.jpg" title="Delivery available in London N6, N2, N19, N8 and N10" style="margin-top:20px;" />
        <a href="<?php echo site_url(); ?>/restaurants/highgate/"><img src="<?php bloginfo('template_directory'); ?>/img/img-pickup.jpg" title="10% off Pick Up In-Store Highgate" style="margin-top:5px;" /></a>
        <div class="vegetarians">
        	<h3><strong>(v)</strong> = Suitable for vegetarians</h3>
            <p>Please feel free to ask us more information about our dishes and notify us of any allergies.</p>
        </div>
    </div>
</div>

</div>
<div style="clear:both;"></div>



<script>
jQuery(document).on('click','.product_cat_item',function(){
	var side_taxonomy = jQuery(this).data('side_taxonomy');
	jQuery(".cat-product-wrap > ul").each(function(){
		jQuery(this).hide();
	});
	var idShown = '#product_list_'+side_taxonomy;
	jQuery(idShown).show();
});



jQuery(document).on('click','.ATC',function(e){
e.preventDefault();
addToCart(jQuery(this).data('product_id'));
return false;
});

function addToCart(p_id) {
var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
//console.log(p_id);
jQuery('#in-cart').block({ 
    message: '', 
    //css: { 'background-color': 'rgba(255,255,255,0.70)' } 
}); 
//console.log(p_id);
jQuery.ajax({
	url: ajaxurl,
	data: {
		'action':'AJ_add_to_cart',
		'p_id' : p_id,
	},
	success:function(data) {
		// This outputs the result of the ajax request
		console.log(data);

		jQuery('#in-cart').empty();
		jQuery('#in-cart').append(data);
		jQuery('#in-cart').unblock();
	},
	error: function(errorThrown){
		console.log(errorThrown);
	}
});

}

jQuery(document).on('click','.delete-item',function(e){
e.preventDefault();
deleteFromCart(jQuery(this).data('product_id'));
return false;
});
function deleteFromCart(p_id) {
var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
jQuery.ajax({
url: ajaxurl,
data: {
'action':'AJ_delete_from_cart',
'p_id' : p_id,
},
success:function(data) {
// This outputs the result of the ajax request
jQuery('#in-cart').empty();
jQuery('#in-cart').append(data);
},
error: function(errorThrown){
console.log(errorThrown);
}
});

}
</script>











			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php wc_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		//do_action( 'woocommerce_sidebar' );
	?>

<?php get_footer( 'shop' ); ?>
