<?php

/**
 * BuddyPress - Members Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_dtheme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php do_action( 'bp_before_members_loop' ); ?>

<?php global $reality_query; ?>

<?php if ( bp_has_members( $reality_query ) ) : ?>

	<div id="pag-top" class="pagination">

		<div class="pagination-links" id="member-dir-pag-top">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php do_action( 'bp_before_directory_members_list' ); ?>

	<ul id="members-list" class="item-list" role="main">

	<?php while ( bp_members() ) : bp_the_member(); ?>

		<li>
			<div class="item-avatar">
				<a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar('height=160&width=160'); ?></a>
			</div>

			<div class="item">
				
				<?php 
					if ( !$user_points = get_the_author_meta( 'reality_current_points', bp_get_member_user_id(), true ) ) $user_points = 0;
		
					$user_rank = get_the_author_meta( 'reality_current_rank', bp_get_member_user_id(), true );
		
					$dealArgs = array(
						'post_type'	=>	'reality_deals',
						'post_per_page'	=>	999999,
						'orderby'	=>	'meta_value_num',
						'meta_key'		=>	'REALITY_total_value',
						'tax_query' => array(
							array(
								'taxonomy' => 'authors-tax',
								'field' => 'slug',
								'terms' => bp_get_member_user_id()
							)
						)
					);
					$user_deals = new WP_Query( $dealArgs );
		
					if ( $user_deals->have_posts() ) {
						$user_deals->the_post();
			
						$deals_made = $user_deals->post_count;
						$most_valuable_deal = get_post_meta( $user_deals->post->ID, 'REALITY_total_value', true );
		
					} else {
						$deals_made = 0;
						$most_valuable_deal = 0;
					}
					wp_reset_postdata();
		
					$comments = reality_get_player_comments_count( bp_get_member_user_id() );
				?>
				
				<div class="item-title">
					<a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a><span class="rank"><?php echo $user_rank['current_rank']; ?></span>
				</div>
				
				<div class="item-reality-points">
				
					<div class="points"><?php echo $user_points ?></div>
					<div class="points-name">Points</div>
				
				</div>
				
				<div class="item-latest-update">
					<?php if ( bp_get_member_latest_update() ) : ?>

						<span class="update"> <?php bp_member_latest_update(); ?></span>

					<?php endif; ?>
				</div>
				
				<div class="item-reality-summary">
				
					<div class="top-line">
					<table>
						<tbody>
							<tr>
								<td class="title"><?php _e('Deals Made', 'Reality'); ?>:</td><td class="value"><?php echo $deals_made; ?></td>
								<td class="title"><?php _e('Most Valuable Deal', 'Reality'); ?>:</td><td class="value"><?php echo $most_valuable_deal; ?></td>
							</tr>
						</tbody>
					</table>
					</div>
					
					<div class="bottom-line">
					<table>
						<tbody>
							<tr>
								<td class="title"><?php _e('Comments', 'Reality'); ?>:</td><td class="value"><?php echo $comments; ?></td>
								<td class="title"><?php _e('Collaborators', 'Reality'); ?>:</td><td class="value"><?php echo friends_get_total_friend_count( bp_get_member_user_id() ); ?></td>
							</tr>
						</tbody>
					</table>
					</div>
				
				</div>
				
				<?php do_action( 'bp_directory_members_item' ); ?>

				<?php
				 /***
				  * If you want to show specific profile fields here you can,
				  * but it'll add an extra query for each member in the loop
				  * (only one regardless of the number of fields you show):
				  *
				  * bp_member_profile_data( 'field=the field name' );
				  */
				?>
			</div>

			<div class="clear"></div>
		</li>

	<?php endwhile; ?>

	</ul>

	<?php do_action( 'bp_after_directory_members_list' ); ?>

	<?php bp_member_hidden_fields(); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pagination-links" id="member-dir-pag-bottom">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( "Sorry, no members were found.", 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_members_loop' ); ?>
