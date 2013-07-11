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
<aside id="secondary" class="widget-area quick-actions" role="complementary">
	<?php global $bp; ?>
	<?php if ( $bp->loggedin_user->id != bp_displayed_user_id() ) : ?>
	<ul>
		<li><a href="<?php echo bp_get_send_public_message_link() ?>" title="Send <?php bp_displayed_user_fullname() ?> a Public Message">Public Message</a></li>
		<li><a href="<?php echo bp_get_send_private_message_link() ?>" title="Send <?php bp_displayed_user_fullname() ?> a Private Message">Private Message</a></li>
	</ul>
	<?php endif; ?>
	
	<?php 
		global $bp;
		$user_points = get_the_author_meta( 'reality_current_points', $bp->displayed_user->id, true );
		
		$dealArgs = array(
			'post_type'	=>	'reality_deals',
			'post_per_page'	=>	999999,
			'orderby'	=>	'meta_value_num',
			'meta_key'		=>	'REALITY_total_value',
			'tax_query' => array(
				array(
					'taxonomy' => 'authors-tax',
					'field' => 'slug',
					'terms' => $bp->displayed_user->id
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
	
		$comments = reality_get_player_comments_count( $bp->displayed_user->id );
	
		?>
	
	<table class="user-quick-status">
		<tbody>
			<tr>
				<td class="value-title">Total Points</td><td class="value"><?php echo $user_points; ?></td>
			</tr>
			<tr>
				<td class="value-title">Deals Made</td><td class="value"><?php echo $deals_made; ?></td>
			</tr>
			<tr>
				<td class="value-title">Most Valuable Deal</td><td class="value"><?php echo $most_valuable_deal; ?></td>
			</tr>
			<tr>
				<td class="value-title">Comments Posted</td><td class="value"><?php echo $comments ?></td>
			</tr>
			<tr>	
				<td class="value-title">Collaborators</td><td class="value"><?php echo friends_get_total_friend_count( $bp->displayed_user->id ); ?></td>
			</tr>
		</tbody>
	</table>
	
	
	<?php global $reality; ?>
	<?php $seasons = get_option( 'reality_game_instances' ); ?>
	<?php $user_meta = get_user_meta( $bp->displayed_user->id ); ?>
	<?php if ( is_array( $reality->current_season ) ) : ?>
	
		<h3>Weekly Points</h3>
	
		<?php $current_season = $reality->current_season; ?>
		<?php foreach( $current_season as $slug => $name ) {
		
			$weeks = $seasons[$slug]['weeks'];
			break;
			
		} ?>
		
		<table class="user-quick-status">
			<tbody>
				
				<?php foreach( $weeks as $key => $week ) : ?>
					<?php if ( $week['startdate'] < time() ) : ?>
					<tr>
						<td class="value-title">Week <?php echo $key; ?></td>
						<td class="value">
							<?php $meta_key = 'reality_weekly_points_'.$slug.'_week_'.$key; ?>
							<?php if ( isset( $user_meta[$meta_key] ) ) { echo $user_meta[$meta_key][0]; } else { echo '0'; } ?>
						</td>
					</tr>
					<?php endif; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	
	<?php endif; ?>
	
	<?php echo do_shortcode('[reality_past_scores]'); ?>
	
</aside><!-- #secondary -->