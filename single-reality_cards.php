<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<?php global $post; ?>
	<?php $card_number = $post->post_name; ?>


		<?php
			
			$card_tax = term_exists( $card_number, 'cards-tax' );
		
			$deal_args = array(
				'post_type'			=>	'reality_deals',
				'posts_per_page'	=>	999999,
				'tax_query'			=>	array(
					array(
						'taxonomy'	=>	'cards-tax',
						'field'		=>	'id',
						'terms'		=>	$card_tax['term_id']
					)
				)
			);
		
			$deals = new WP_Query( $deal_args );
			
			if ( $deals->have_posts() ) : ?>
			<div class="horizontal-bar">
	<div class="wrapper">
		<?php $deals_count = $deals->found_posts; ?>
		<div class="page-tagline"><?php echo sprintf( _n('Used in %d deal.', 'Used in %d deals.', $deals_count, 'Reality'), $deals_count ); ?></div>
	</div>
</div>
			<div class="wrapper card-deals">
				<?php $count = 1; ?>
				<div class="deal-archive">
				<?php while ( $deals->have_posts() ) : $deals->the_post(); ?>
		
					<?php
					
					get_template_part( 'content', 'reality_deals' );

					if ( $count%3 == 0 ) {
						echo '<div class="clear"></div>';
					}
					
					$count++;
				
					?>
		
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
				</div>
			</div>
			<?php endif; ?>


</div>
<div class="horizontal-bar">
	<div class="wrapper">
		<div class="page-tagline"><?php _e('Discuss This Card', 'Reality') ?></div>
	</div>
</div>
<div class="wrapper card-comments">
	<?php get_sidebar( 'quick_actions' ); ?>
	
	<section id="primary" class="site-content">
		<div id="content" role="main">



				<?php comments_template( '', true ); ?>



		</div><!-- #content -->
	</section><!-- #primary -->

	<?php get_sidebar( 'buddypress' ); ?>
	<div class="clear"></div>
</div>


			<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>