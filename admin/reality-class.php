<?php

//DEAL WITH GAMEPLAY INSTANCES
function reality_check_if_running() {

	global $reality;
	$reality = new REALITY();
	$reality->is_running = false;

	$game_instances = get_option( 'reality_game_instances' );
	$reality_is_running = false;
	$now = time();
	
	if ( !empty( $game_instances ) ) {
	
		foreach( $game_instances as $instance ) {
	
			if ( $now > $instance['startdate'] && $now < $instance['enddate'] ) {
			
				$reality_is_running = array( $instance['slug'] => $instance['name'] );
				$reality->current_season = $reality_is_running;
				
				// Get current week
				
				foreach( $game_instances[$instance['slug']]['weeks'] as $key => $week ) {
				
					if ( $now > $week['startdate'] && $now < $week['enddate'] ) {
					
						$reality->current_week = $key;
						continue;
					
					}
				
				}
				
				continue;
			
			}
		
		}
	
	} else {
		
		$reality_is_running = true;
		$reality->is_running = true;
		$reality->current_season = true;
		
		// Calculate current Week
		$reality_start_date = get_option( 'reality_game_installed_date' );
		$current_week = ceil( ( time() - $reality_start_date ) / ( 60 * 60 * 24 * 7 ) );
		$reality->current_week = $current_week;
		
	}
	
	update_option( 'reality_is_currently_running', $reality_is_running );

}
add_action( 'init', 'reality_check_if_running', 1 );
add_action( 'admin_init', 'reality_check_if_running', 1 );

function reality_is_running() {
	
	if ( get_option( 'reality_is_currently_running' ) ) {
		return true;
	} else {
		return false;
	}
	
}

// SET REALITY DEPARTMENT

function reality_set_department() {

	if ( is_user_logged_in() ) {
	
		global $bp, $reality;
		$user_id = $bp->loggedin_user->id;
		$user = new WP_User( $user_id );
		$department = reset($user->roles);
		
		if ( get_option('reality_use_departments') ) {
		
			$reality->use_depts = true;
			$reality->available_depts = get_option('reality_player_departments');
		
		}
		
		if ( isset( $department ) ) {
		
			$reality->current_dept = $department;
		
		}
		
		if ( $department && $dept_activity = get_option('REALITY_department_activity-'.$department) ) {
			$reality->current_dept_activity = $dept_activity;
			$reality->current_dept_deals = get_option('REALITY_department_deals-'.$department);
		}
	
	}

}
add_action( 'init', 'reality_set_department' );
add_action( 'admin_init', 'reality_set_department' );

// ADD REALITY BODY CLASSES

add_filter('body_class','reality_add_body_classes');
function reality_add_body_classes($classes) {
	
	global $reality;
	
	if ( $reality->current_dept ) {
		$classes[] = 'department-'.$reality->current_dept;
	}
	
	return $classes;
}

class REALITY {

	public $is_running = false;
	public $current_season = false;
	public $current_week = null;
	
	public $use_depts = false;
	public $available_depts = null;
	public $current_dept = null;
	public $current_dept_activity = null;

}

?>