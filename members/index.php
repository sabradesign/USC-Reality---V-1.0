<?php

/**
 * BuddyPress - Members Directory
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

get_header( 'buddypress' ); ?>

	<?php do_action( 'bp_before_directory_members_page' ); ?>

	<div id="content" class="wrapper">
		<aside id="secondary" class="widget-area" role="complementary">
		<?php if ( is_active_sidebar( 'reality_members' ) ) : ?>
				
					<ul>
						<?php dynamic_sidebar( 'reality_members' ); ?>
					</ul>
		<?php else : ?>
		
			<h1><?php _e('Check out other members here!') ?></h1>
			
			<?php if ( current_user_can( 'administrator' ) ) : ?>
		
				<h2><a href="<?php echo admin_url( 'widgets.php' ); ?>" title="Add Widgets"><?php _e('Add widgets to sidebar titled "Members Sidebar" here.'); ?></a></h2>

			<?php endif; ?>
		
		<?php endif; ?>
		</aside><!-- #secondary -->
		<section class="">

		<?php do_action( 'bp_before_directory_members' ); ?>

		<form action="" method="post" id="members-directory-form" class="dir-form">

			<?php do_action( 'bp_before_directory_members_content' ); ?>

			<div id="members-dir-search" class="dir-search" role="search">

				<?php bp_directory_members_search_form(); ?>

			</div><!-- #members-dir-search -->

			<div class="item-list-tabs" id="subnav" role="navigation">
				<ul>

					<?php do_action( 'bp_members_directory_member_sub_types' ); ?>

					<li id="members-order-select" class="last filter">

						<label for="members-order-by"><?php _e( 'Order By:', 'buddypress' ); ?></label>
						<select id="members-order-by">
							<option value="active"><?php _e( 'Last Active', 'buddypress' ); ?></option>

							<?php if ( bp_is_active( 'xprofile' ) ) : ?>

								<option value="alphabetical"><?php _e( 'Alphabetical', 'buddypress' ); ?></option>

							<?php endif; ?>

							<?php do_action( 'bp_members_directory_order_options' ); ?>

						</select>
					</li>
				</ul>
			</div>

			<div id="members-dir-list" class="members dir-list">

				<?php locate_template( array( 'members/members-loop.php' ), true ); ?>

			</div><!-- #members-dir-list -->

			<?php do_action( 'bp_directory_members_content' ); ?>

			<?php wp_nonce_field( 'directory_members', '_wpnonce-member-filter' ); ?>

			<?php do_action( 'bp_after_directory_members_content' ); ?>

		</form><!-- #members-directory-form -->

		<?php do_action( 'bp_after_directory_members' ); ?>

		</section><!-- .padder -->
		
	</div><!-- #content -->

	<?php do_action( 'bp_after_directory_members_page' ); ?>


<?php get_footer( 'buddypress' ); ?>
