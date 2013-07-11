<?php

get_header(); ?>
<div class="wrapper">
		<div id="content" role="main">
		<div class="deal-archive">
		
		<?php if ( isset($_GET['deals_search']) && $_GET['deals_search'] != '' ) : ?>
		
			<?php 
			
			global $wp_query;
			$search = $_GET['deals_search'];
			
			$deal_search_args = array(
				'post_type'	=>	'reality_deals',
				's'			=>	$search
			); 
			
			$wp_query = new WP_Query( $deal_search_args );
			
			?>
			
		
		
		<?php endif; ?>
		
		<?php if ( have_posts() ) : ?>
			<?php $count = 1; ?>
			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				get_template_part( 'content', 'reality_deals' );

				if ( $count%3 == 0 ) {
					echo '<div class="clear"></div>';
				}

			endwhile;

			echo '<div class="clear"></div>';

			reality_archive_nav( 'deals-nav-below' );
			?>

		<?php else : ?>
			<h1 style="text-align:center;">No deals have been made yet!</h1>
		<?php endif; ?>
		</div>

		</div><!-- #content -->

</div>
<?php get_footer(); ?>