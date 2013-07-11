<?php

function reality_register_actions() {
	$actions = array(
		// Deals filters
		//'reality_deal_submit'    => 'reality_deal_submit',
		'reality_card_preview'	=>	'reality_preview_card',
		'reality_users_search'	=>	'reality_users_search',
		'reality_get_user_preview'	=>	'reality_preview_user',
		'reality_sumbission_get_maker_card'	=>	'reality_submission_get_maker_card',
		
		'reality_leader_board_dept_switch'	=>	'reality_leader_board_dept_switch'
	);

	/**
	 * Register all of these AJAX handlers
	 *
	 * The "wp_ajax_" action is used for logged in users, and "wp_ajax_nopriv_"
	 * executes for users that aren't logged in. This is for backpat with BP <1.6.
	 */
	foreach( $actions as $name => $function ) {
		add_action( 'wp_ajax_'        . $name, $function );
		add_action( 'wp_ajax_nopriv_' . $name, $function );
	}
}
add_action( 'after_setup_theme', 'reality_register_actions', 21 );

function reality_preview_card() {

	$card_number = $_POST['card_number'];
	
	if ( isset( $_POST['no_maker'] ) && $_POST['no_maker'] == true ) {
	
		if ( reality_is_maker_card( $card_number ) ) die(false);
	
	}

	$output = do_shortcode('[reality_card number="'.$card_number.'" size="medium"]');

	echo $output;
	
	die();

}

function reality_users_search() {

	$search = $_POST['search'];
	
	$users = get_users( array( 'search' => $search ) );
	$users_array = array();
	
	foreach( $users as $user ) {
	
		$users_array[] = $user->display_name;
	
	}
	
	echo json_encode( $users_array );

}

function reality_preview_user() {

	$userName = $_POST['user'];
	
	if ( $userTax = term_exists( $userName, 'authors-tax' ) ) {
	
		$user = get_term( $userTax['term_id'], 'authors-tax' );
	
		$avatarArgs = array(
			'height'	=>	100,
			'width'		=>	100,
			'item_id'	=>	$user->slug
		);
		$output = bp_core_fetch_avatar( $avatarArgs );
		
		$output .= '<input type="hidden" name="deal_collaborators[]" value="' . $userName . '">';
	
		echo $output;
		
		die();
	
	}

}

function reality_submission_get_maker_card() {

	$maker_card_id = $_POST['maker_card_id'];
	
	if ( reality_is_maker_card( $maker_card_id ) ) {
	
		$output = do_shortcode('[reality_card number="' . $maker_card_id . '" size="large" side="back"]');
		$output .= do_shortcode('[reality_card number="' . $maker_card_id . '" size="large"]');
	
		echo $output;
		
		die();
	
	} else {
		$output = '';
		
		echo $output;
		
		die();
	}

}

function reality_leader_board_dept_switch() {

	$dept = $_POST['dept'];
	$size = $_POST['size'];
	$type = $_POST['type'];
	$title = $_POST['title'];
	
	echo do_shortcode( '[reality_leaderboard type="'.$type.'" dept="'.$dept.'" size="'.$size.'" title="'.$title.'" dept_nav=0 ajax=1]' );
	
	die();

}

?>