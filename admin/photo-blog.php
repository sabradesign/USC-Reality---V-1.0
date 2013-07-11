<?php

function reality_activity_action_post_update() {

	// Do not proceed if user is not logged in, not viewing activity, or not posting
	if ( !is_user_logged_in() || !bp_is_activity_component() || !bp_is_current_action( 'post' ) )
		return false;

	// Check the nonce
	check_admin_referer( 'post_update', '_wpnonce_post_update' );

	global $bp;
	$user_id = bp_loggedin_user_id();

	// Get activity info
	$content = apply_filters( 'bp_activity_post_update_content', $_POST['whats-new']             );
	
	if ( isset( $_POST['whats-new-post-object'] ) ) {
		$object  = apply_filters( 'bp_activity_post_update_object',  $_POST['whats-new-post-object'] );
	} else {
		$object = null;
	}
	
	if ( isset( $_POST['whats-new-post-in'] ) ) {
		$item_id = apply_filters( 'bp_activity_post_update_item_id', $_POST['whats-new-post-in']     );
	} else {
		$item_id = null;
	}
	
	if ( !empty( $_FILES['new-photo'] ) ) $new_photo = $_FILES['new-photo'];

	// No activity content so provide feedback and redirect
	if ( empty( $content ) ) {
		bp_core_add_message( __( 'Please enter some content to post.', 'buddypress' ), 'error' );
		bp_core_redirect( wp_get_referer() );
	}

	// No existing item_id
	if ( empty( $item_id ) && empty( $new_photo ) ) {
		
		$activity_id = bp_activity_post_update( array( 'content' => $content ) );
	
	} else if ( empty( $item_id ) && !empty( $new_photo ) ) {
	
		
	
		// Record this on the user's profile
		$from_user_link   = bp_core_get_userlink( $user_id );
		$activity_action  = sprintf( __( '%s posted a photo!', 'Reality' ), $from_user_link );
		$activity_content = $content;
		$primary_link     = bp_core_get_userlink( $user_id, false, true );
	
		
	
		// Now write the values
		$activity_id = bp_activity_add( array(
			'user_id'      => $user_id,
			'action'       => apply_filters( 'bp_activity_new_update_action', $activity_action ),
			'content'      => apply_filters( 'bp_activity_new_update_content', $activity_content ),
			'primary_link' => apply_filters( 'bp_activity_new_update_primary_link', $primary_link ),
			'component'    => $bp->activity->id,
			'type'         => 'photo_blog_update'
		) );
	
		// Add this update to the "latest update" usermeta so it can be fetched anywhere.
		bp_update_user_meta( bp_loggedin_user_id(), 'bp_latest_update', array( 'id' => $activity_id, 'content' => $content ) );
		
		$uploadedfile = $_FILES['new-photo'];						
		$upload_overrides = array( 'test_form' => false );
		if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
		$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
		if ( $movefile ) {
			
		    $filename = $movefile['file'];
								    
		    $wp_filetype = wp_check_filetype(basename($filename), null );
			$wp_upload_dir = wp_upload_dir();
			$attachment = array(
			     'guid' => $wp_upload_dir['url'] . '/' . basename( $filename ), 
			     'post_mime_type' => $wp_filetype['type'],
			     'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
			     'post_content' => '',
			     'post_status' => 'inherit'
			);
			$attach_id = wp_insert_attachment( $attachment, $filename );
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
			wp_update_attachment_metadata( $attach_id, $attach_data );
				
			bp_activity_update_meta( $activity_id, 'reality_photo', $attach_id );
							    
		}
		
		
		
	// Post to groups object
	} else if ( 'groups' == $object && bp_is_active( 'groups' ) ) {
		if ( (int) $item_id ) {
			$activity_id = groups_post_update( array( 'content' => $content, 'group_id' => $item_id ) );
		}

	// Special circumstance so let filters handle it
	} else {
		$activity_id = apply_filters( 'bp_activity_custom_update', $object, $item_id, $content );
	}

	// Provide user feedback
	if ( !empty( $activity_id ) )
		bp_core_add_message( __( 'Update Posted!', 'buddypress' ) );
	else
		bp_core_add_message( __( 'There was an error when posting your update, please try again.', 'buddypress' ), 'error' );

	// Redirect
	bp_core_redirect( wp_get_referer() );
}
add_action( 'bp_actions', 'reality_activity_action_post_update' );

?>