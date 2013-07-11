<?php
/**
 * The sidebar containing the main widget area.
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

<div class="horizontal-bar tablet-phone" style="margin-bottom: 1em;">
				<div class="wrapper">
					<div class="page-tagline"><?php _e('Related Deals', 'Reality') ?></div>
				</div>
			</div>
	
		<aside id="secondary" class="widget-area" role="complementary">
		
		<?php $makerCard = reality_get_maker_card(); ?>
		
		<?php $dealCardTax = get_the_deal_cards(); ?>
		<?php $cards = array(); ?>
		<?php foreach ( $dealCardTax as $card ) {
			$cards[] = $card->slug;
		} ?>
		
		<?php $relatedDealsArgs = array(
			'post_type'	=>	'reality_deals',
			'posts_per_page'	=>	4,
			'post__not_in'	=>	array( get_the_ID() ),
			'orderby'	=>	'rand',
			'tax_query' => array(
				array(
					'taxonomy' => 'cards-tax',
					'field' => 'slug',
					'terms' => $cards
				)
			)
		);
		
		$relatedDeals = new WP_Query( $relatedDealsArgs );
		
		if ( $relatedDeals->have_posts() ) : ?>
		
			<ul class="related-deals">
		
				<?php while ( $relatedDeals->have_posts() ) : $relatedDeals->the_post(); ?>
			
					<li class="related-deal <?php echo $post->post_name; ?>">
						<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
							<div class="deal-image" style="background-image: url(<?php the_deal_image_url( get_the_ID(), 'medium' ); ?>);">
								<div class="aspect-control"></div>
							</div>
							<h4><?php the_title(); ?></h4>
						</a>
					</li>
			
				<?php endwhile; ?>
		
				<?php wp_reset_postdata(); ?>
		
			</ul>
		
		<?php endif; ?>
		
		<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		<?php endif; ?>
		
		</aside><!-- #secondary -->

		