<?php
/**
 * Template Name: Reality Login
 */

get_header(); ?>

	<div id="primary" class="site-content wrapper">
		<div class="login-container">
				<?php $args = array(
					'redirect'	=>	site_url()
				); ?>
				<?php wp_login_form( $args ); ?>
				<a href="<?php echo wp_lostpassword_url(); ?>" title="Retrieve Your Password">Forgot Password?</a>
		</div>
	</div>
	
<?php get_footer(); ?>