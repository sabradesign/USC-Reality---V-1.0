<?php

get_header(); ?>
<div class="wrapper">
	<div id="content" role="main">
		
		<?php if ( $award = term_exists( single_cat_title('', false), 'awards-tax' ) ) : ?>
			
			<?php 
				$awardTax = get_term( $award['term_id'], 'awards-tax' );
				$awardParent = get_term( $awardTax->parent, 'awards-tax' );
			?>
				
			<?php if ( $awardParent->name == "Deal Awards" ) : ?>
		
				<?php if ( have_posts() ) : ?>
					<div class="deal-archive">
					<?php $count = 1; ?>
						
						<?php while ( have_posts() ) : the_post();

							get_template_part( 'content', 'reality_deals' );

							if ( $count%3 == 0 ) {
								echo '<div class="clear"></div>';
							}

						endwhile;

						twentytwelve_content_nav( 'nav-below' ); ?>
			
					</div>
				<?php else : ?>
					<?php get_template_part( 'content', 'none' ); ?>
				<?php endif; ?>
		
			<?php else : ?>
			
				<?php $award = get_post( $awardTax->slug ); ?>
				
				<?php global $reality_query; ?>
				<?php $reality_query = 'meta_key=REALITY_user_awards&meta_value='.$award->ID; ?>
						
				<div id="members-dir-list" class="members dir-list">

					<?php locate_template( array( 'awards/members-loop.php' ), true ); ?>
	
				</div><!-- #members-dir-list -->
					
			<?php endif; ?>
		
		<?php endif; ?>

		</div><!-- #content -->

</div>
<?php get_footer(); ?>