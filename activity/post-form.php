<?php

/**
 * BuddyPress - Activity Post Form
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<form action="<?php bp_activity_post_form_action(); ?>" method="post" id="whats-new-form" name="whats-new-form" role="complementary" enctype="multipart/form-data">

	<?php do_action( 'bp_before_activity_post_form' ); ?>

	<h5><?php if ( bp_is_group() )
			printf( __( "What's new in %s, %s?", 'buddypress' ), bp_get_group_name(), bp_get_user_firstname() );
		else
			printf( __( "What's new, %s?", 'buddypress' ), bp_get_user_firstname() );
	?></h5>

	<div id="whats-new-content">
		<div id="whats-new-textarea">
			<textarea name="whats-new" id="whats-new" cols="50" rows="10"><?php if ( isset( $_GET['r'] ) ) : ?>@<?php echo esc_attr( $_GET['r'] ); ?> <?php endif; ?></textarea>
		</div>
		
		<div id="whats-new-avatar">
			<a href="<?php echo bp_loggedin_user_domain(); ?>" class="user-avatar">
				<?php bp_loggedin_user_avatar( 'width=' . bp_core_avatar_thumb_width() . '&height=' . bp_core_avatar_thumb_height() ); ?>
				
			</a>
			<h5>Posting as: <a href="<?php echo bp_loggedin_user_domain(); ?>"><?php bp_loggedin_user_fullname()  ?></a></h5>
		</div>

		<div id="whats-new-options">
			
			<div id="whats-new-submit">
				<a id="aw-whats-new-submit" class="submit-button" onClick="jQuery('form#whats-new-form').submit();">Post Update</a>
				<input type="submit" name="aw-whats-new-submit" id="aw-whats-new-submit" style="display: none;" value="<?php _e( 'Post Update', 'buddypress' ); ?>" />
			</div>
			<div id="whats-new-photo">
				<a href="#" class="new-photo-button"></a>
				<input type="file" name="new-photo" id="new-photo" accept="image/*">
			</div>

			<?php do_action( 'bp_activity_post_form_options' ); ?>

		</div><!-- #whats-new-options -->
	</div><!-- #whats-new-content -->

	<?php wp_nonce_field( 'post_update', '_wpnonce_post_update' ); ?>
	<?php do_action( 'bp_after_activity_post_form' ); ?>

</form><!-- #whats-new-form -->
