<?php

get_header(); ?>
<div class="wrapper">

<?php get_sidebar( 'quick_actions' ); ?>

	<section id="primary" class="site-content">
		<div id="content" role="main" style="text-align: center;">
			<?php
			
			$card_args = array(
				'post_type'	=>	'reality_cards',
				'orderby'		=>	'rand',
				'posts_per_page'	=>	1
			);
			
			$card = new WP_Query($card_args);
			
			if ( $card->have_posts() ) : $card->the_post() ; ?>
			
				<?php echo do_shortcode('[reality_card number="'.$post->post_name.'" size="large" side="back"]'); ?>
				<?php echo do_shortcode('[reality_card number="'.$post->post_name.'" size="large"]'); ?>			
							
			<?php endif; ?>
			
			<?php wp_reset_postdata(); ?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_sidebar( 'buddypress' ); ?>

</div>
<?php get_footer(); ?>