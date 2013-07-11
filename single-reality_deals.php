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

	<div id="primary" class="site-content">
		<div id="content" role="main">
		
			<div id="deal-header">
				<div class="wrapper">
					<div class="two-third">
						<div class="deal-thumbnail">
							<a href="<?php the_deal_image_url() ?>" title="<?php the_title(); ?>" rel="fancybox">
								<img src="<?php timthumb_photo( get_the_deal_image_url( false, 'large' ), 175, 175 ) ?>" alt="<?php the_title(); ?>">
							</a>
						</div>
						<div class="deal-title-authors">
							<h1><?php the_title(); ?></h1>
							<div class="authors">
								<h3>By <?php the_deal_authors() ?></h3>
							</div>
						</div>
						<div class="clear"></div>
					
					</div>
					<div class="one-third last">
						<div class="deal-points">
							<div class="aspect-control"></div>
							<div class="value"><?php the_deal_points() ?></div>
							<div class="value-points">Points</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>

			<div class="horizontal-bar">
				<div class="wrapper">
					<div class="two-third"><div class="page-tagline"><?php _e('Evidence', 'Reality') ?></div></div><div class="one-third last browser-only">Deal Info</div><div class="clear"></div>
				</div>
			</div>
			<div class="wrapper evidence-content">
				<div class="one-third last deal-logline" style="float:right;">
					<?php if ( deal_has_files() ) : ?>
						<h4>This deal contains a downloadable attachment:</h4>
						<?php the_deal_files(); ?>
					<?php endif; ?>
					
					<div class="logline">
						<?php the_deal_logline(); ?>
					</div>
					<?php if ( $notes = reality_deal_has_notes() ) : ?>
						<div class="notes">
							<?php echo $notes; ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="two-third deal-content">
					
					<?php the_deal_content(); ?>
				
				</div>
				
				<div class="clear"></div>
			</div>

			<!-- DEAL CARDS -->

			<div class="horizontal-bar">
				<div class="wrapper">
					<div class="page-tagline"><div class="two-third"><?php _e('Cards Used', 'Reality') ?></div><div class="one-third last browser-only"><?php _e('Audience Awards', 'Reality') ?></div><div class="clear"></div></div>
				</div>
			</div>
			<div class="wrapper cards-audience-awards">
				<div class="two-third cards-used">
					<?php the_deal_cards(); ?>
				</div>
				<div class="one-third last audience-awards">
					<?php //$audienceAwards = get_post_meta( get_the_ID(), 'REALITY_audience_awards', true ); ?>
					<?php //$audienceAwards = unserialize( $audienceAwards ); ?>
					<?php $audienceAwardOptions = get_option( 'reality_audience_award_options' ); ?>
					<?php global $deal_meta; ?>
					
					<table>
						<tbody>
							<?php foreach( $audienceAwardOptions as $slug => $award ) : ?>
								<tr<?php if ( !isset($deal_meta['REALITY_audience_awards_'.$slug][0]) || $deal_meta['REALITY_audience_awards_'.$slug][0] == 0 ) echo ' class="empty"'; ?> >
									<td class="award-name"><?php echo $award; ?></td>
									<td class="award-votes"><?php if ( isset( $deal_meta['REALITY_audience_awards_'.$slug][0] ) ) { echo $deal_meta['REALITY_audience_awards_'.$slug][0]; } else { echo 0; } ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					
				</div>
				<div class="clear"></div>
			</div>

			<!-- DEAL AWARDS -->
			
			<?php if ( deal_has_awards() ) : ?>
			
			<div class="horizontal-bar">
				<div class="wrapper">
					<div class="page-tagline"><?php _e('Reality Awards', 'Reality') ?></div>
				</div>
			</div>
			<div class="wrapper deal-awards">
					<?php the_deal_awards() ?>
			</div>
			
			<?php endif; ?>
			
			<!-- DEAL JUSTIFICATION -->

			<div class="horizontal-bar">
				<div class="wrapper">
					<div class="page-tagline"><?php _e('Justification', 'Reality') ?></div>
				</div>
			</div>
			<div class="wrapper justification">
				<div class="one-third card-layout">
					<a href="<?php echo get_the_deal_card_layout( get_the_ID(), 'full' ) ?>" rel="fancybox" title="<?php the_title() ?>'s Card Layout"><img src="<?php echo get_the_deal_card_layout( get_the_ID(), 'reality_deal_card_layout' ) ?>" title="<?php the_title() ?> Cards Layout"></a>
				</div>
				<div class="two-third last justification-video">
					
					<?php the_deal_justification_video() ?>
					
				</div>
				<div class="clear"></div>
			</div>

			<!-- DEAL COMMENTS -->

			<div class="horizontal-bar">
				<div class="wrapper">
					<div class="page-tagline"><div class="two-third"><?php _e('Comments', 'Reality') ?></div><div class="one-third last browser-only"><?php _e('Related Deals', 'Reality') ?></div><div class="clear"></div></div>
				</div>
			</div>
			<div class="wrapper comments-sidebar">
				<section class="deal-comments">
					<div class="activity">
						<?php the_deal_comments(); ?>
					</div>
				</section>
				<?php get_sidebar('reality_deals'); ?>
			</div>

<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>