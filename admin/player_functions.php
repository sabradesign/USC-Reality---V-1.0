<?php

 /* Populate or Update player info
 */

function reality_update_player( $user_id = false ) {

	if ( !user_id )
		return;
		
	$user = new WP_User( $user_id );
	$user_meta = get_user_meta( $user_id );
	
	// Set user points
	if ( !isset( $user_meta[''] ) ){
		reality_update_user_points( $user_id );
	}
	
	// Set user rank info
	if ( !isset( $user_meta['reality_current_rank'] ) ){
		reality_update_user_rank( $user_id );
	}
	
	// If game seasons are set
	if ( get_option( 'reality_game_instances' ) ) {
	
	} else {
	// If no game seasons
	
	}
	
}

?>