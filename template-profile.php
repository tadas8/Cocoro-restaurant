<?php
/**
 * Template Name: PROFILE
 *
 *
 * @package Cocoro
 */
$cardNo = get_cimyFieldValue($current_user->ID, "CARD_NO");
$firstName = get_cimyFieldValue($current_user->ID, "FIRST_NAME");
$lastName = get_cimyFieldValue($current_user->ID, "LAST_NAME");
get_header(); ?>

		<main id="main" class="site-main" role="main">
			<header class="entry-header">
				<h1 class="entry-title">User profile page</h1>
			</header>
			<div class="entry-content">
				<?php
				if ( is_user_logged_in() ):
					$royaltyCardAPI = new royaltyCardAPI();
					$balance = $royaltyCardAPI->getCardInfo($cardNo);
					?>
					<p>Royalty point balance : <?php echo $balance; ?></p>

					<?php
					global $current_user;
					get_currentuserinfo();
					?>
					<p>Name: <?php echo $firstName.' '.$lastName; ?></p>
					<p>Username: <?php echo $current_user->user_login; ?></p>
					<p>E-mail: <?php echo $current_user->user_email; ?></p>
				<?php else: ?>
					<p>You are not logged in - Please <a href="<?php echo site_url(); ?>/wp-login.php/">Login</a> or <a href="<?php echo site_url(); ?>/wp-login.php?action=register">Register</a></P>
				<?php endif; ?>
			</div>
		</main><!-- #main -->


<?php get_footer(); ?>
