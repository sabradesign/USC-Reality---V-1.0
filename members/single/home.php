<?php

/**
 * BuddyPress - Users Home
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

get_header( 'buddypress' ); ?>

	<div id="content">
		<div class="">

			<?php do_action( 'bp_before_member_home_content' ); ?>

			<div id="item-header" role="complementary">

				<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>

			</div><!-- #item-header -->
			
			<?php if ( bp_is_user_messages() ) : ?>
				<div class="horizontal-bar">
					<div class="wrapper">
						<div class="page-tagline">
							Messages
						</div>
					</div>
				</div>
				<div class="wrapper main_content">
					<?php locate_template( array( 'members/single/messages.php'  ), true ); ?>
				</div>
			<?php elseif ( bp_is_user_settings() ) : ?>
				<div class="horizontal-bar">
					<div class="wrapper">
						<div class="page-tagline">
							Settings
						</div>
					</div>
				</div>
				<div class="wrapper main_content">
					<?php locate_template( array( 'members/single/settings.php'  ), true ); ?>
				</div>
			<?php elseif ( bp_is_user_profile() ) : ?>
				<div class="horizontal-bar">
					<div class="wrapper">
						<div class="page-tagline">
							Edit Profile
						</div>
					</div>
				</div>
				<div class="wrapper main_content">
					<?php locate_template( array( 'members/single/profile.php'   ), true ); ?>
				</div>
			<?php else : ?>
			
			<div class="horizontal-bar">
				<div class="wrapper">
					<div class="page-tagline">
						<div class="two-third"><?php _e('Demografik','Reality') ?><?php if ( bp_is_my_profile() ) echo ' <a href="'.bp_core_get_userlink( bp_displayed_user_id(), false, true).'/profile/edit/">(Edit)</a>'?></div>
						<div class="one-third last browser-only"><?php _e('Collaborators','Reality'); ?></div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
			<div class="reality-user-profile wrapper">
				<div class="two-third">		
					<?php locate_template( array( 'members/single/profile.php'   ), true ); ?>
				</div>
				<div class="one-third last">
					
					<?php if ( friends_check_user_has_friends( bp_displayed_user_id() ) ) : ?>
					<ul class="friends-array">
						<?php $friends = friends_get_friend_user_ids( bp_displayed_user_id() ); ?>
						<?php foreach( $friends as $friend ) : ?>
				
							<?php $avatarArgs = array(
								'item_id'	=>	$friend,
								'height'	=>	60,
								'width'		=>	60
							); 
							$userdata = get_userdata( $friend ); ?>
							<li>
								<a href="<?php echo bp_core_get_userlink($friend, false, true); ?>" title="View <?php echo $userdata->user_firstname ?> <?php echo $userdata->user_lastname ?>'s Profile "><?php echo bp_core_fetch_avatar( $avatarArgs ); ?></a>
							</li>
			
						<?php endforeach; ?>
					</ul>
					<?php else : ?>
		
						<h3>This user has not collaborated with anybody yet.</h3>
		
					<?php endif; ?>
					
	
				</div>

			</div>

			<?php if ( $player_achievements = get_user_meta( bp_displayed_user_id(), 'REALITY_user_awards' ) ) : ?>

			<?php 
			$player_achievements = get_user_meta( bp_displayed_user_id(), 'REALITY_user_awards' );

			?>

			<div class="horizontal-bar">
				<div class="wrapper">
					<div class="page-tagline"><?php _e('Awards','Reality') ?> </div>
				</div>
			</div>

			<div class="reality-user-awards wrapper">

				<ul class="reality-awards">
	
					<?php foreach( $player_achievements as $award ) : ?>
			
						<?php $awardPost = get_post( $award ); ?>
						<li>
							<?php $awards_tax = get_taxonomy( 'awards-tax' ); ?>
							<?php $award_tax = get_term_by( 'slug', $award, 'awards-tax' ); ?>
							<?php $awardLink = get_bloginfo( 'url' ) . '/' . $awards_tax->rewrite['slug'] . '/' . $award; ?>
						
							<a href="<?php echo $awardLink; ?>" title="See other recipients of the <?php echo $awardPost->post_title; ?> Award">
						
							<?php if ( has_post_thumbnail( $awardPost->ID ) ) : ?>
								<?php the_post_thumbnail( $awardPost->ID, 'medium' ); ?>
							<?php else : ?>
								<div class="award-default">
									<?php echo $awardPost->post_title ?>
								</div>
							<?php endif; ?>
							
							</a>
						</li>
	
					<?php endforeach; ?>
	
				</ul>

			</div>

			<?php endif; ?>

			
			<div class="horizontal-bar">
				<div class="wrapper">
					<div class="page-tagline"><?php _e('Activity','Reality') ?></div>
				</div>
			</div>
			
			<div class="wrapper">

			<section class="reality-profile-content">

			<div id="item-body">

				<?php do_action( 'bp_before_member_body' );

				if ( bp_is_user_activity() || !bp_current_component() ) :
					locate_template( array( 'members/single/activity.php'  ), true );

				 elseif ( bp_is_user_blogs() ) :
					locate_template( array( 'members/single/blogs.php'     ), true );

				elseif ( bp_is_user_friends() ) :
					locate_template( array( 'members/single/friends.php'   ), true );

				elseif ( bp_is_user_groups() ) :
					locate_template( array( 'members/single/groups.php'    ), true );

				elseif ( bp_is_user_messages() ) :
					locate_template( array( 'members/single/messages.php'  ), true );

				elseif ( bp_is_user_profile() ) :
					locate_template( array( 'members/single/profile.php'   ), true );

				elseif ( bp_is_user_forums() ) :
					locate_template( array( 'members/single/forums.php'    ), true );

				elseif ( bp_is_user_settings() ) :
					locate_template( array( 'members/single/settings.php'  ), true );

				// If nothing sticks, load a generic template
				else :
					locate_template( array( 'members/single/plugins.php'   ), true );

				endif;

				do_action( 'bp_after_member_body' ); ?>

			</div><!-- #item-body -->

			<?php do_action( 'bp_after_member_home_content' ); ?>
			
			</section>
			
			
			
			<?php get_sidebar( 'members_sidebar' ); ?>
			
			</div> <!-- end .wrapper -->
			<?php endif; ?> <!-- end if( messages || etc.) -->
		</div><!-- .padder -->
	</div><!-- #content -->


<?php get_footer( 'buddypress' ); ?>
