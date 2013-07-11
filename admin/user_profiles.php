<?php

add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );

function extra_user_profile_fields( $user ) { ?>
<h3><?php _e("Reality User Information", "blank"); ?></h3>

<table class="form-table">
	
	
	<tr>
		<th><label for="reality_current_points"><?php _e("Current Points"); ?></label></th>
		<td>
			<?php if ( isset($_GET['user_id']) ) {
			 $user_id = $_GET['user_id'];
			 } else {
			 	global $bp;
			 	$user_id = $bp->loggedin_user->id;
			 } ?>
			<input type="text" name="reality_current_points" id="reality_current_points" value="<?php echo get_the_author_meta( 'reality_current_points', $user_id ); ?>" class="regular-text" disabled /><br />
			<span class="description"><?php _e("The total number of points this user currently has. Only change in the case of an override."); ?></span>
		</td>
	</tr>
	<?php if ( $pastScores = get_the_author_meta( 'reality_past_points', $user_id ) ) : ?>
	<?php $instances = get_option( 'reality_game_instances' ); ?>
	<tr>
		<th><label for="reality_past_points"><?php _e("Past Scores"); ?></label></th>
		<td>
			<table>
				<thead>
					<th>Gameplay Instance</th>
					<th>Score</th>
				</thead>
				<tbody>
					<?php foreach( $pastScores as $slug => $score ) : ?>
					
						<tr>
							<td><?php echo $instances[$slug]['name']; ?></td>
							<td><?php echo $score; ?></td>
						</tr>
					
					<?php endforeach; ?>
				</tbody>
			</table>
		</td>
	</tr>
	<?php endif; ?>
	<tr>
		<th><label for="reality_rank"><?php _e("Rank"); ?></label></th>
		<td>
			<?php $rank_info = get_the_author_meta( 'reality_current_rank', $user_id ); ?>
			<input type="text" name="reality_rank" id="reality_rank" value="<?php echo $rank_info['current_rank'] ?>" class="regular-text" disabled/><br />
			<span class="description"><?php _e("The user's current rank."); ?></span>
		</td>
	</tr>
	<tr>
		<th><label for="reality_player_achievements"><?php _e("Player Achievements", "Reality"); ?></label></th>
		<td>
			<?php
			$playerAchievementsTaxID = term_exists( 'Player Achievements', 'awards-tax' );
			$achievement_args = array( 'parent'	=>	$playerAchievementsTaxID['term_id'], 'hide_empty' => false );
			$playerAchievements = get_terms( array( 'awards-tax' ), $achievement_args );
			$currentPlayerAchievements = get_user_meta( $user_id, 'REALITY_user_awards' );
			
			
			
			foreach ( $playerAchievements as $achievement ) {
				
				$award = get_post( $achievement->slug );
				$hasAward = array_search( $award->ID, $currentPlayerAchievements );
			
				if ( !$value = get_post_meta( $award->ID, 'REALITY_award_value', true ) ) {
					$value = 0;
				}
			
				$output = '<div><label>';
				if ( is_int( $hasAward ) ) {
					$output .= '<input type="checkbox" name="REALITY_user_awards[]" value="'.$award->ID.'" checked>';
				} else {
					$output .= '<input type="checkbox" name="REALITY_user_awards[]" value="'.$award->ID.'">';
				} 
				
				$output .= ' '.$award->post_title.' ('.$value.' Points)</label></div>';
				
				echo $output;
			
			}
			?>
			<br /><span class="description"><?php _e("The awards this user currently has."); ?></span>
		</td>
	</tr>
	<tr>
		<th><label for="reality_point_achievements"><?php _e("Point Achievements", "Reality"); ?></label></th>
		<td>
		
			<?php
			
			$pointAchievementstaxID = term_exists( 'Point Achievements', 'awards-tax' );
			$achievement_args = array( 'parent'	=>	$pointAchievementstaxID['term_id'], 'hide_empty' => false );
			$pointAchievements = get_terms( array( 'awards-tax' ), $achievement_args );

			foreach ( $pointAchievements as $achievement ) {
				
				$award = get_post( $achievement->slug );
				if ( is_object( $award ) ) {
					
					$hasAward = array_search( $award->ID, $currentPlayerAchievements );
			
					if ( !$value = get_post_meta( $award->ID, 'REALITY_award_value', true ) ) {
						$value = 0;
					}
			
					$output = '<div><label>';
					if ( is_int( $hasAward ) ) {
						$output .= '<input type="checkbox" name="REALITY_user_awards[]" value="'.$award->ID.'" checked>';
					} else {
						$output .= '<input type="checkbox" name="REALITY_user_awards[]" value="'.$award->ID.'">';
					} 
				
					$output .= ' '.$award->post_title.' ('.$value.' Points)</label></div>';
				
					echo $output;
				}
			
			}
			
			?>
		
		</td>
	</tr>
</table>
<?php }

add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

function save_extra_user_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }

	delete_user_meta( $user_id, 'REALITY_user_awards' );

	if ( !empty( $_POST['REALITY_user_awards'] ) ) {

		foreach( $_POST['REALITY_user_awards'] as $award ) {
			add_user_meta( $user_id, 'REALITY_user_awards', $award );
		}

	}

}

function reality_display_user_points() { 
	
	global $bp;
	
	reality_calculate_user_standings();
	
	$points = get_the_author_meta( 'reality_current_points', $bp->displayed_user->id );
	$rank_info = get_the_author_meta( 'reality_current_rank', $bp->displayed_user->id );
	$standings_info = get_the_author_meta( 'REALITY_user_standings', $bp->displayed_user->id);
	?>

	<div class="reality-user-points-rank">
		<div class="user-current-points-rank">Current Points Rank: <?php echo $standings_info['points_rank']; ?></div>
		<span class="reality-points">Current Points: <?php echo $points; ?> </span> <span class="reality-rank"><?php echo $rank_info['current_rank']; ?> (<?php echo $rank_info['points_towards_next_level']; ?> / <?php echo $rank_info['points_spread'] ?> to next level)</span>
		<div class="reality-level-up-bar" style="width:300px;background:black;height:30px;"><div class="reality-level-up-progress" style="height:100%;width:<?php echo $rank_info['percent_to_next_level'] ?>%;background:green;"></div></div>
	</div>

<?php }
//add_action('bp_before_member_header_meta', 'reality_display_user_points');

function reality_member_activity_show_hidden( $success, $activities_template_local, $template_args ){
	
	global $bp, $activities_template;
	
	$filter = $template_args['filter'];
	
	if ( isset($filter['user_id']) && $filter['user_id'] != 0 ) {
		
			$user_ids = explode(',', $filter['user_id']);
			
			//die('Looking for activies in '.reality_get_player_activities($user_ids));
		
			$filter['user_id'] = false;
			
			if ( isset($_POST['filter']) && $_POST['filter'] != -1 ) $filter['action'] = $_POST['filter'];
			if ( isset($_COOKIE['bp-activity-filter']) && $_COOKIE['bp-activity-filter'] != -1 ) $filter['action'] = $_COOKIE['bp-activity-filter'];
		
			$template_args['filter'] =	$filter;
			$template_args['in'] = reality_get_player_activities($user_ids);
			$template_args['show_hidden'] =	true;
		
			$activities_template = new BP_Activity_Template( $template_args );
	
	} else {
	
		if ( isset($_POST['filter']) && $_POST['filter'] != -1 ) $filter['action'] = $_POST['filter'];
		if ( isset($_COOKIE['bp-activity-filter']) && $_COOKIE['bp-activity-filter'] != -1 ) $filter['action'] = $_COOKIE['bp-activity-filter'];
		
		$template_args['filter'] =	$filter;
		$template_args['display_comments'] = true;
		$activities_template = new BP_Activity_Template( $template_args );
	
	}
	
	return $activities_template->has_activities();

}
add_filter('bp_has_activities', 'reality_member_activity_show_hidden', 1, 3);


function reality_activity_comment_edit( $action ) {
	
	global $bp;

	$reality_action = sprintf( __( '%s posted a new comment', 'reality' ), bp_core_get_userlink( $bp->loggedin_user->id ) );

	return $reality_action;

}
add_filter( 'bp_activity_comment_action', 'reality_activity_comment_edit' );

function reality_get_player_activities( $user_ids ) {

	$output = '';

	foreach( $user_ids as $user_id ) {
	
		$user_activities = get_the_author_meta( 'reality_player_activities', $user_id );
		$user_activities = unserialize( $user_activities );
		
		if ( isset( $user_activities ) && is_array( $user_activities) ) {
			foreach( $user_activities as $user_activity ) {
				$activities_list = implode(',',$user_activity);
				$output .= $activities_list.',';
				
			}
		}
	}
	
	return $output;

}

/**
 * Change users view screen to show pertinent information
 */

add_filter( 'manage_users_columns', 'set_custom_reality_user_columns' );
add_action( 'manage_users_custom_column' , 'custom_reality_users_columns', 10, 3 );

function set_custom_reality_user_columns($columns) {
    unset( $columns['email'] );
    unset( $columns['role'] );
    unset( $columns['posts'] );
    if ( get_option('reality_use_departments') ) {
    	$columns['reality_department'] = __( 'Department', 'Reality' );
    }
    $columns['current_score'] = __( 'Current Score', 'Reality' );
    $columns['deals_made'] = __( 'Deals Made', 'Reality' );
    $columns['reality_awards'] = __( 'Awards', 'Reality' );
    
    return $columns;
}

function custom_reality_users_columns( $empty="", $column, $user_id ) {
    switch ( $column ) {

        case 'current_score' :
            $current_score = get_the_author_meta( 'reality_current_points', $user_id);
            return $current_score;
            break;
        case 'deals_made' :
			if ( $deals = get_term_by( 'slug', $user_id, 'authors-tax' ) ) return $deals->count;
				else return 0;
            break;
        case 'reality_awards' :
        	$player_achievements = get_user_meta( $user_id, 'REALITY_user_awards' );
        	$output = '';
        	if ( !empty( $player_achievements ) ) {
        		$output .= '<strong>Player Awards</strong><br />';
        		foreach( $player_achievements as $player_achievement ) {
        			$award = get_term_by( 'slug', $player_achievement, 'awards-tax' );
        			if ( is_object( $award ) ) {
        				$output .= $award->name.', ';
        			}
        		}
        	
        	}
        	return $output;
        	break;
        case 'reality_department' :
        	global $wp_roles;
        	$roles = $wp_roles->roles;
        	$user = new WP_User( $user_id );
        	$output = '';
        	
        	foreach ( $user->roles as $role ) {
        		$output .= $roles[$role]['name'];
        	}
        	return $output;
        	break;

    }
}
?>