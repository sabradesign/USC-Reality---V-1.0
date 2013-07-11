<?php

/**
 * BuddyPress - Users Activity
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>
<!-- 
<div class="item-list-tabs activity-type-tabs" role="navigation" style="display:block;">
	<ul>
		
		<?php //bp_get_options_nav(); ?>
		
	</ul>
</div>
 -->

<div class="item-list-tabs no-ajax" id="subnav" role="navigation">
	<ul>
		<?php isset( $_COOKIE['bp-activity-filter'] ) ? $filter = $_COOKIE['bp-activity-filter'] : $filter = ''; ?>
					<li<?php if ( $filter == -1 ) echo ' class="active selected current"'; ?>><a href="#" title="View All Activity" class="-1">All</a></li>
					<li<?php if ( $filter == 'activity_update' ) echo ' class="active selected current"'; ?>><a href="#" title="View Latest Updates" class="activity_update">Updates</a></li>
					<li<?php if ( $filter == 'reality_deal_submit' ) echo ' class="active selected current"'; ?>><a href="#" title="View Latest Deals" class="reality_deal_submit">Deals</a></li>
					<li<?php if ( $filter == 'new_blog_comment,activity_comment,reality_card_comment' ) echo ' class="active selected current"'; ?>><a href="#" title="View Latest Comments" class="new_blog_comment">Comments</a></li>
					<li<?php if ( $filter == 'photo_blog_update' ) echo ' class="active selected current"'; ?>><a href="#" title="View Latest Photosblog Posts" class="photo_blog_update">Photoblog</a></li>
					
					<li id="activity-filter-select" class="last">
						<label for="activity-filter-by"><?php _e( 'Show:', 'buddypress' ); ?></label>
						<select id="activity-filter-by">
							<option value="-1"><?php _e( 'Everything', 'buddypress' ); ?></option>
							<option value="activity_update"><?php _e( 'Updates', 'buddypress' ); ?></option>
							<option value="photo_blog_update"><?php _e( 'Photo Blog', 'Reality' ); ?></option>
							<option value="reality_deal_submit"><?php _e( 'Deals', 'buddypress' ); ?></option>

							<?php if ( bp_is_active( 'blogs' ) ) : ?>

								<option value="new_blog_comment,activity_comment,reality_card_comment"><?php _e( 'Photoblog Comments', 'buddypress' ); ?></option>
								<option value="new_blog_post"><?php _e( 'Photoblog', 'buddypress' ); ?></option>

							<?php endif; ?>

							<?php do_action( 'bp_activity_filter_options' ); ?>

						</select>
					</li>
	</ul>
</div><!-- .item-list-tabs -->

<?php do_action( 'bp_before_member_activity_post_form' ); ?>

<?php
if ( is_user_logged_in() && bp_is_my_profile() && ( !bp_current_action() || bp_is_current_action( 'just-me' ) ) )
	locate_template( array( 'activity/post-form.php'), true );

do_action( 'bp_after_member_activity_post_form' );
do_action( 'bp_before_member_activity_content' ); ?>

<div class="activity" role="main">

	<?php locate_template( array( 'activity/activity-loop.php' ), true ); ?>

</div><!-- .activity -->

<?php do_action( 'bp_after_member_activity_content' ); ?>
