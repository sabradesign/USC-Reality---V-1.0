<?php

/**
 * BuddyPress - Users Header
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php do_action( 'bp_before_member_header' ); ?>

<div id="item-header-content">

	<!-- <span class="user-nicename">@<?php /* bp_displayed_user_username(); */ ?></span> -->
	<!-- <span class="activity"><?php /* bp_last_activity( bp_displayed_user_id() ); */ ?></span> -->

	<?php do_action( 'bp_before_member_header_meta' ); ?>
	
	<div class="reality-user-status wrapper">
		<?php if ( !$points = get_the_author_meta( 'reality_current_points', bp_displayed_user_id() ) ) {
			$points = 0;
		} ?>
		
		<?php if ( !$rankInfo = get_user_meta( bp_displayed_user_id(), 'reality_current_rank', true ) ) {
			$rankInfo['current_rank'] = 'Unranked';
		}?>
		
		<?php if ( ! $standings_info = get_the_author_meta( 'REALITY_user_standings', bp_displayed_user_id() ) ) {
			$standings_info['points_rank'] = 'Unranked';
		} else {
			$standings_info = $standings_info;
		} ?>
			
		<div class="reality-user-points one-third"><span class="value-title"><?php _e('Points', 'Reality')?></span><span class="value"><?php echo $points; ?></span></div>
		<div class="reality-user-rank one-third"><span class="value-title"><?php _e('Rank', 'Reality') ?></span><span class="value"><?php echo $rankInfo['current_rank']; ?></span></div>
		<div class="reality-seed-rank one-third last"><span class="value-title"><?php _e('Seed', 'Reality') ?></span><span class="value"><?php echo $standings_info['points_rank']; ?></span></div>
		<div class="clear"></div>
		<div class="reality-level-progress-bar"><div class="progress" style="width:<?php echo $rankInfo['percent_to_next_level']; ?>%;"></div></div>
	
	</div>

</div><!-- #item-header-content -->

<?php do_action( 'bp_after_member_header' ); ?>

<?php do_action( 'template_notices' ); ?>