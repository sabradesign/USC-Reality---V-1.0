<?php

function reality_filter_activity_by_department( $has_activities, $activities_template, $template_args ) {

	global $reality, $activities_template, $bp;
	
	$user_id = $bp->loggedin_user->id;
	$user = new WP_User( $user_id );
	$department = $user->roles;
	
// 	if ( isset( $reality->current_dept ) ) {
// 	
// 		$template_args['in'] = implode( ',', $reality->current_dept_activity );
// 	
// 	}
	
	if ( !in_array( 'administrator', $department ) && !in_array( 'game-master', $department ) && !isset($bp->displayed_user->id) ) {
	
		foreach( $department as $dept ) {
		
		if ( isset( $template_args['in'] ) ) {
		
			$prev_in = explode( ',', $template_args['in'] );
			$new_in = get_option('REALITY_department_activity-'.$dept );
			
			if ( is_array( $new_in ) ) {
			
				$in = array_merge( $prev_in, $new_in );
				$in = array_unique( $in );
			
			} else {
			
				$in = $prev_in;
			
			}
		
		} else {
		
			$in = get_option('REALITY_department_activity-'.$dept );
		
		}
	
		$template_args['in'] = implode( ',', $in );
	
		$activities_template = new BP_Activity_Template( $template_args );
		
		}
	
	}
	
	return $activities_template->has_activities();

}
add_filter( 'bp_has_activities', 'reality_filter_activity_by_department', 10, 3 );

function reality_update_dept_activities( $user_id, $activity_id ) {

	$user = new WP_User( $user_id );
	$roles = $user->roles;
	
	foreach( $roles as $role ) {
		$dept = $role;
	}
	
	$dept_activities = get_option( 'REALITY_department_activity-'.$dept );
	
	
	if ( is_array( $dept_activities ) ) {
		
		if ( !array_search( $activity_id, $dept_activities ) ) {
			$dept_activities[] = $activity_id;
		}
		
	} else {
	
		$dept_activities = array( $activity_id );
	
	}
	
	update_option( 'REALITY_department_activity-'.$dept, $dept_activities );
	
	$activities = bp_activity_get_specific( array( 'activity_ids' => $activity_id, 'max'	=>	1 ) );
	$activity = $activities['activities'][0];
	
	if ( $activity->action == 'reality_deal_submit' ) {
	
		$dept_deals = get_option( 'REALITY_department_deals-'.$dept );
	
		
		$deal_id = $activity->item_id;
	
		if ( is_array( $dept_deals ) ) {
		
			// Get Deal ID
			if ( !array_search( $deal_id, $dept_deals ) ) {
				$dept_deals[] = $deal_id;
			}
		
		} else {
	
			$dept_deals = array( $deal_id );
	
		}
	
		update_option( 'REALITY_department_activity-'.$dept, $dept_activities );
	
	}

}
add_action( 'reality_after_update_user_activities', 'reality_update_dept_activities', 10, 2 );
?>