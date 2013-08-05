<?php $deal_args = array(
						'post_type'	=>	'reality_deals',
						'post__in'	=>	array( bp_get_activity_item_id() )
					); 
					
					$deal = new WP_Query( $deal_args );
					
					if ( $deal->have_posts() ) : ?>
					
						<?php while ( $deal->have_posts() ) : $deal->the_post(); ?>

<div class="reality-deal-activity">

	<div class="deal-thumb">
		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<img src="<?php timthumb_photo(get_the_deal_image_url( false, 'large' ), 650, 366 ) ?>" alt="<?php the_title(); ?>">
		</a>
	</div>
	
	<div class="deal-info">
	
		<div class="deal-title">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
		</div>
		<div class="deal-value">
			<span class="deal-value-number"><?php the_deal_points(); ?></span>
			<span class="deal-value-points"><?php _e( 'Points', 'Reality' ); ?></span>
		</div>
		<div class="deal-logline">
			<?php the_deal_logline(); ?>
		</div>
	
	</div>

</div>
<div class="deal-authors">
						<h3>Collaborators</h3>
						<ul class="collaborators">
							<?php $authors = wp_get_object_terms( bp_get_activity_item_id() , 'authors-tax'); ?>
							<?php foreach( $authors as $author ) : ?>
								<?php $avatarArgs = array(
									'item_id'	=>	$author->slug,
									'height'	=>	60,
									'width'		=>	60
								); 
								$userdata = get_userdata( $author->slug ); ?>
								<li>
									<a href="<?php echo bp_core_get_userlink($author->slug, false, true); ?>" title="View <?php echo $userdata->user_firstname ?> <?php echo $userdata->user_lastname ?>'s Profile "><?php echo bp_core_fetch_avatar( $avatarArgs ); ?></a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
					
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					<?php endif; ?>