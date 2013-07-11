<?php

/**
 * Register the activity stream actions for updates
 *
 * @global object $bp BuddyPress global settings
 * @since BuddyPress (1.6)
 */
function reality_activity_register_activity_actions() {
	global $bp;

	// Core Activities
	bp_activity_set_action( $bp->activity->id, 'reality_rate_deal', __( 'Rated a deal', 'Reality' ) );
	bp_activity_set_action( $bp->activity->id, 'photo_blog_update', __( 'Shared a photo', 'Reality' ) );
	bp_activity_set_action( $bp->activity->id, 'reality_card_comment', __( 'Commented on a card', 'Reality' ) );

	do_action( 'bp_activity_register_reality_actions' );
	
}
add_action( 'bp_register_activity_actions', 'reality_activity_register_activity_actions' );

/**
 * POINT VALUE FOR CERTAIN ACTIVITIES
 *
 * If an activity has a point value specified for it, set the activity's
 * 'reality_activity_point_value' meta value the specified point value so that the user
 * point calculating function (see below) can accurately calculate a player's score.
 * 
 */
function reality_activity_points( $activity ) {

	global $bp;

	if ( $activity->user_id != 0 ) {

		$activity_points_value = get_option('reality_activity_point_values');
		$activity_points_value = $activity_points_value;

		if ( isset( $activity_points_value[$activity->type] ) ) {
			
			$activity_value = $activity_points_value[$activity->type]['value'];
			$activity_value = apply_filters( 'reality_activity_points_modifier', $activity_value, $activity->id, $activity->type );
			
			bp_activity_update_meta( $activity->id, 'reality_activity_point_value', $activity_value );
			
		}
		
		$user_activities = get_the_author_meta('reality_player_activities', $activity->user_id);
		$user_activities = unserialize( $user_activities );
		
		if ( !isset( $user_activities[$activity->type][$activity->id] ) ) {
			$user_activities[$activity->type][$activity->id] = $activity->id;
			update_user_meta( $activity->user_id, 'reality_player_activities', serialize($user_activities) );
			do_action( 'reality_after_update_user_activities', $activity->user_id, $activity->id );
		}
		
		do_action( 'reality_after_new_user_activity', $activity->user_id, $activity );
		
		//reality_update_user_points( $activity->user_id );
	}
}
add_action( 'bp_activity_after_save', 'reality_activity_points' );

function reality_force_update_user_points( $user_id ) {

	$user_activities = array();

	$filter = array(
 		'user_id'		=>	$user_id
 	);
 		 
 	$deal_template_args = array(
 		'page'             => 1,
		'per_page'         => 99999,
		'filter'           => $filter,
		'show_hidden'      => true
 	);
 		 
 	$deal_activities = new BP_Activity_Template( $deal_template_args );

	if ( $deal_activities->has_activities() ) {
    	
    	while ( $deal_activities->user_activities() ) {
    		
    		$deal_activities->the_activity();
    		
    		$user_activities[$deal_activities->activity->type][(int) $deal_activities->activity->id] = (int) $deal_activities->activity->id;
    		
    	}
    	
 	} else {
 	
 	}
 	
 	$dealArgs = array(
 		'post_type'	=>	'reality_deals',
 		'tax_query' => array(
			array(
				'taxonomy' => 'authors-tax',
				'field' => 'slug',
				'terms' => $user_id
			)
		)
 	);
 	$userDeals = new WP_Query($dealArgs);
 	
 	if ( $userDeals->have_posts() ) {
 	
 		while ( $userDeals->have_posts() ) {
 		
 			$userDeals->the_post();
 			$filter = array(
 				'object'		=> 'reality_deals',
 				'primary_id'	=> get_the_ID(),
 				'action'		=> 'reality_deal_submit'
 			);
 		 
 			$deal_template_args = array(
 				'page'             => 1,
				'per_page'         => 1,
				'filter'           => $filter,
				'show_hidden'      => true
 			);
 		 
 			$deal_activities = new BP_Activity_Template( $deal_template_args );
 			
 			if ( $deal_activities->has_activities() ) {
 			
 				$deal_activities->the_activity();
 			
 				$user_activities['reality_deal_submit'][$deal_activities->activity->id] = $deal_activities->activity->id;
 			
 			}
 			
 		
 		}
 	
 	}
 	
 	update_user_meta( $user_id, 'reality_player_activities', serialize($user_activities) );
 	
 	do_action( 'reality_after_force_update_user_points', $user_id );

}

/**
 * Update User Points Functions
 *
 * This function recalculates a user's points by pulling in all of their activity and
 * adding up all the point values attached to those activities.  Activities with no point
 * values are ignored.
 * 
 */
add_action( 'edit_user_profile_update', 'reality_update_user_points', 99999 );
add_action( 'reality_after_new_user_activity', 'reality_update_user_points' );
add_action( 'reality_after_force_update_user_points', 'reality_update_user_points');
function reality_update_user_points( $user_id ) {

		global $reality;

		$user_previous_points = get_the_author_meta( 'reality_current_points', $user_id );
 		$user_activities = (string) reality_get_player_activities( array($user_id) );
 		//$is_reality_currently_running = get_option( 'reality_is_currently_running' );
 		$is_reality_currently_running = $reality->current_season;
 		$instances = get_option( 'reality_game_instances' );
 		
 		$weekly_points = array();
 		
 		if ( is_array( $instances ) ) {
 		
 			if ( is_array( $is_reality_currently_running ) ) {
 		
 				foreach( $is_reality_currently_running as $slug => $name ) {
 					$current_slug = $slug;
 					$current_name = $name;
 				}
 			
 			}
 			
 			//Get rid of future instances
 			
 			foreach( $instances as $slug => $instance ) {
 				$now = time();
 				if ( $instance['startdate'] > $now ) unset($instances[$slug]);
 			
 			}
 		
 		} else {
 			$instances = false;
 		}
 		 
 		$deal_template_args = array(
				'per_page'         => false,
				'display_comments'	=>	true,
				'include'		   => $user_activities
 		);
 		
 		
 		 
 		$deal_activities = new BP_Activity_Template( $deal_template_args );

	if ( $deal_activities->has_activities() ) {
    	
    	if ( is_array( $instances ) ) {
    	
    		$points = array();
    	
    		foreach ( $instances as $slug => $instance ) {
    			$points[ $slug ] = (int) 0;
    		}
    		
    	} else {
    	
    		$points = (int) 0;
    	
    	}
    	
    	while ( $deal_activities->user_activities() ) {
    		
    		$deal_activities->the_activity();
    		
    		$activity_value = (int) bp_activity_get_meta( $deal_activities->activity->id, 'reality_activity_point_value' );
    		
    		if ( $instances ) {
    			$date = strtotime( $deal_activities->activity->date_recorded );
    		
    			foreach( $instances as $slug => $instance ) {
    			
    				if ( $date > $instance['startdate'] && $date < $instance['enddate'] ) {
    				
    					$points[ $slug ] += $activity_value;
    					
    					foreach( $instance['weeks'] as $key => $week ) {
    					
    						if ( $date > $week['startdate'] && $date < $week['enddate'] ) {
    							
    							isset( $weekly_points[$slug][$key] ) ? $weekly_points[$slug][$key] += $activity_value : $weekly_points[$slug][$key] = $activity_value;
    						
    						}
    					
    					}
    					
    					break;
    				
    				}
    			
    			}
    		
    		} else {
    		
    			$points += $activity_value;
    		
    		}
    		
    	}
    	
 	} else {
 		$points = 0;
 	}
 	
 	if ( is_array( $instances ) ) {
 		
 		if ( $is_reality_currently_running ) {
	 		$currentPoints = $points[ $current_slug ];
 		} else {
 			$currentPoints = 0;
 		}
 		
 		$currentPoints = apply_filters('reality_calculated_user_points', $currentPoints, $user_id);
 		
 		update_user_meta( $user_id, 'reality_current_points', $currentPoints );
 		unset( $points[ $current_slug ] );
 		
 		// Set scores from past instances
 		update_user_meta( $user_id, 'reality_past_points', $points );
 		
 		// Update weekly scores
 		
 		foreach( $weekly_points as $season => $weeks ) {
 		
 			foreach( $weeks as $week => $points ) {
 			
 				update_user_meta( $user_id, 'reality_weekly_points_'.$season.'_week_'.$week, $points );
 			
 			}
 		
 		}
 		
 	} else {
 	
 		$currentPoints = apply_filters('reality_calculated_user_points', $points, $user_id);
 	
 		update_user_meta( $user_id, 'reality_current_points', $currentPoints );
 	
 	}
 	
 	do_action( 'reality_after_update_user_points', $user_id, $currentPoints, $user_previous_points );
 	
 	return $points;

}

function reality_update_user_score_on_activity_delete( $args ) {

	reality_update_user_points( $args['user_id'] );

}
add_action( 'bp_activity_delete', 'reality_update_user_score_on_activity_delete', 10, 1 );

function reality_update_all_user_points(){
	
	global $reality;
	
	if ( $reality->use_depts ) {
	
		if ( is_array( $reality->available_depts ) ) {
	
			foreach( $reality->available_depts as $slug => $dept ) {
	
				$args = array(
					'role'	=>	$slug
				);
			
				$users = new WP_User_Query($args);
	
				if ( !empty( $users->results ) ) {
					foreach( $users->results as $user ) {
		
						reality_update_user_points( $user->ID );
		
					}
				}
		
			}
		
		}
	
	} else {
	
		$args = array(
			'role'	=>	'player'
		);
		
		$users = new WP_User_Query($args);
	
		if ( !empty( $users->results ) ) {
			foreach( $users->results as $user ) {
		
				reality_update_user_points( $user->ID );
		
			}
		}
	
	
	}
	
	$args = array(
		'role'	=>	'administrator'
	);
	$users = new WP_User_Query($args);
	
	if ( !empty( $users->results ) ) {
		foreach( $users->results as $user ) {
		
			reality_update_user_points( $user->id );
		
		}
	}
	
	do_action( 'reality_after_update_all_user_points' );
}

/**
 * CALCULATE CURRENT USER RANK
 *
 * Calculate and store user's rank
 * 
 */
function reality_update_user_rank( $user_id, $current_points = false, $previous_points = false ) {

	$user_previous_rank = get_the_author_meta( 'reality_current_rank', $user_id );
	if ( !is_array( $user_previous_rank ) ) {
		$user_pervious_rank['current_rank'] = false;
	}
	
	if ( !isset( $user_previous_rank ) ) $user_previous_rank['current_rank'] = false;

	if ( !$current_points ) {
		$current_points = (int) get_user_meta( $user_id, 'reality_current_points', true);
	}
	$current_points = (int) $current_points;
	
	$ranks = get_option( 'reality_rank_values' );
	$ranks = $ranks;
	
	$previous = 0;
	
	foreach( $ranks as $key => $rank ) {
		
		if ( $current_points < $key ) {
			$current_rank = $ranks[$previous]['rank_name'];
			$current_rank_slug = $ranks[$previous]['rank_slug'];
			$points_spread = $key - $previous;
			$points_to_next_level = $key - $current_points;
			$percent_to_next_level = (float) (1 - ($points_to_next_level / $points_spread))*100;
			$points_towards_next_level = $points_spread - $points_to_next_level;
			break;
		}
		
		$previous = $key;
	}
	
	if ( $current_rank == '' ) $current_rank = $ranks[$previous]['rank_name'];
	
	$rank_info = array(
		'current_rank'			=>	$current_rank,
		'current_rank_slug'		=>	$current_rank_slug,
		'points_to_next_level'	=>	$points_to_next_level,
		'points_spread'			=>	$points_spread,
		'percent_to_next_level'	=>	$percent_to_next_level,
		'points_towards_next_level'	=>	$points_towards_next_level
	);
	
	update_user_meta( $user_id, 'reality_current_rank', $rank_info );
	
	if ( $rank_info['current_rank'] != $user_previous_rank['current_rank'] && (int) $current_points > (int) $previous_points ) {
		//User has leveled up!
		do_action( 'reality_user_leveled_up', $user_id, $rank_info, $user_previous_rank, $current_points, $previous_points );
		do_action( 'reality_user_leveled_up_to_' . $current_rank_slug, $user_id, $rank_info, $user_previous_rank, $current_points, $previous_points );
	}
	
	do_action( 'reality_after_update_user_rank', $user_id, $rank_info, $user_previous_rank, $current_points, $previous_points );
	
	return $current_rank;

}
add_action( 'reality_after_update_user_points', 'reality_update_user_rank', 10, 3 );

/**
 * IF USER HAS MOVED UP IN RANK, CHECK AWARDS FOR ACHIEVEMENTS!
 *
 * 
 */
 
 function reality_give_user_point_achievement_award( $user_id, $new_rank, $previous_rank, $current_points, $previous_points ) {
 
 	global $bp;
 
 	$achievementsParent = term_exists( 'Point Achievements', 'awards-tax' );
 	$achievementAwardArgs = array(
 		'parent'	=>	$achievementsParent['term_id'],
 		'hide_empty'	=>	false
 	);
 	$achievementAwards = get_terms( 'awards-tax', $achievementAwardArgs );
 	
 	$user_awards = get_user_meta( $user_id, 'REALITY_user_awards' );
 	
 	if ( !empty( $achievementAwards ) ) {
 	
 		foreach( $achievementAwards as $award ) {
 		
 			$awardPost = get_post( $award->slug );
 			
 			if ( is_object( $awardPost ) ) {
 			
 				$threshold = get_post_meta( $awardPost->ID, 'REALITY_achievement_threshold', true );
 			
 				if ( $threshold == $new_rank['current_rank_slug'] && !array_search( $awardPost->ID, $user_awards ) ) {
 				
 					add_user_meta( $user_id, 'REALITY_user_awards', $awardPost->ID);
 				
 				}
 				
 			}
 		
 		}
 	
 	}
 
 }
 add_action( 'reality_user_leveled_up', 'reality_give_user_point_achievement_award', 10, 5 );

/**
 * IF USER HAS MOVED UP IN RANK, MAKE AN ACTIVITY!
 *
 * Makes an invisible activity if the user has moved up in rank.  The invisible activity
 * is only visible on the user's profile page.
 * 
 */
function reality_level_up_activity( $user_id, $new_rank, $previous_rank, $current_points, $previous_points ) {
	
		$user = get_userdata( $user_id );
		
		if ( $user->user_firstname != '' && $user->user_lastname != '' ) {
			$name = $user->user_firstname.' '.$user->user_lastname;
		} else {
			$name = $user->user_nicename;
		}
	
		$action = '<a href="'.bp_core_get_user_domain( $user_id ).'" title="View '.$name.'\' Profile">'.$name.'</a> has leveled up to '.$new_rank['current_rank'].'!';
		$component = 'reality_rank';
		$type = 'reality_level_up';
		$primary_link = bp_core_get_user_domain( $user_id );
		$user_id = $user_id;
		$hide = true;
	
		$levelup_activity_args = array(
			'action'            => $action,    // The activity action - e.g. "Jon Doe posted an update"
			'component'         => $component, // The name/ID of the component e.g. groups, profile, mycomponent
			'type'              => $type, // The activity type e.g. activity_update, profile_updated
			'primary_link'      => $primary_link,    // Optional: The primary URL for this item in RSS feeds (defaults to activity permalink)
			'user_id'           => $user_id, // Optional: The user to record the activity for, can be false if this activity is not for a user.
			'hide_sitewide'     => $hide, // Should this be hidden on the sitewide activity stream?
		);
				
		$levelup_activity_args = apply_filters( 'reality_level_up_activity_args', $levelup_activity_args, $user_id, $new_rank, $previous_rank );
				
		if ( !$activity_ID = bp_activity_add( $levelup_activity_args ) ) {
	 	 	die('Could not create activity for leveling up :(');
	 	 }
	
	return $new_rank;

}
add_action( 'reality_user_leveled_up', 'reality_level_up_activity', 10, 5 );

/**
 * Create an activity with a point value when a pending deal is approved and published.
 *
 */
 function reality_publish_deal_create_activity( $post_ID ) {
 
 	if ( $original = wp_is_post_revision( $post_ID ) ) $post_ID = $original;
 
 	isset( $_POST['post_type'] ) ? $post_type = $_POST['post_type'] : $post_type = get_post_type( $post_ID );
 
 	isset( $_POST['post_status'] ) ? $post_status = $_POST['post_status'] : $post_status = get_post_status( $post_ID );
 	
 	if ( $post_type == 'reality_deals' && $post_status == 'publish' ) {
 		$deal_post = get_post($post_ID);
 		if ( isset( $_POST['post_author_override'] ) ) {
 			$post_author = $_POST['post_author_override'];
 		} else {
	 		
	 		$post_author = (int) $deal_post->post->post_author;
	 	 }
	 	 
	 	 $deal_authors = wp_get_object_terms( $post_ID, 'authors-tax' );
	 	 
	 	 //die( 'I found '.count($deal_authors).' authors for this deal.' );
	 	 	
	 	 $collaborators = '';
	 	 $collaboratorArray = array();
	 	 foreach( $deal_authors as $deal_author ) {
	 	 	$user_info = get_userdata( $deal_author->slug );
	 	 	$collaborators .= '<a class="collaborators-list" href="'.get_bloginfo('url').'/members/'.$user_info->user_nicename.'" title="'.$deal_author->name.'">'.bp_core_fetch_avatar( 'item_id='.$deal_author->slug ).'</a>';
	 	 	$collaboratorArray[$deal_author->slug] = $deal_author->name;
	 	 }
 
 		/*
 		 * FORMAT DEAL INFORMATION FOR ACTIVITY INSERTION
 		 */		
	 	$component = 'reality_deals';
	 	$type = 'reality_deal_submit';
	 	$primary_link = get_permalink( $post_ID );
 		$item_id = $post_ID;
 		
 		$maker_card = reality_get_maker_card( $post_ID );
 		$action = $maker_card->description;
 		
 		if ( isset($_POST['title']) ) $title = $_POST['title'];
 			else $title = get_the_title( $post_ID );
 		
 		if ( isset($_POST['REALITY_total_value'] ) ) $deal_value = (int) $_POST['REALITY_total_value'];
	 		else $deal_value = (int) get_post_meta($post_ID, 'REALITY_total_value', true);
 		
 		if ( isset($_POST['excerpt']) ) $excerpt = $_POST['excerpt'];
 			else $excerpt = $deal_post->post_excerpt;
	 	 		
 		$content = '<span class="reality-deal-activity">';
 		$content .= '<span class="deal-thumb">';
 		if ( $youtubeID = get_post_meta( $post_ID, 'REALITY_deal_video_link', true ) ) {
 			$content .= '<a href="http://www.youtube.com/embed/'.$youtubeID.'" rel="fancybox" class="video" title="'.$title.'"><img src="http://img.youtube.com/vi/'.$youtubeID.'/hqdefault.jpg" alt="'.$title.'"/></a>';
 		} else {
 			$content .= '<a href="'.$primary_link.'" title="'.$title.'">'.get_the_post_thumbnail( $post_ID, 'full' ).'</a>';
		}
				
 		$content .= '</span>';
 		$content .= '<span class="deal-info">';
 		$content .= '<span class="deal-title"><a href="'.$primary_link.'" title="'.$title.'">'.$title.'</a></span>';
 		$content .= '<span class="deal-value"><span class="deal-value-number">'.$deal_value.'</span><span class="deal-value-points">Points</span></span>';
 		$content .= '<span class="deal-logline">'.$excerpt.'</span>';
 		$content .= '</span>';
 		$content .= '</span>';
 		
 		$time = get_the_time( 'Y-m-d H:i:s', $post_ID );
 
 		/*
 		 * If an activity already exists for this deal, update it.
 		 */
 		 
 		$filter = array(
 			'object'		=> 'reality_deals',
 			'primary_id'	=> $post_ID,
 			'action'		=> $type
 		);
 		 
 		$deal_template_args = array(
 				'page'             => 1,
				'per_page'         => 99999,
				'filter'           => $filter,
				'show_hidden'      => true
 		);
 		 
 		$deal_activities = new BP_Activity_Template( $deal_template_args );
 		 
 		if ( $deal_activities->has_activities() ) {
 		
 			while ( $deal_activities->user_activities() ) {
 			
 				$deal_activities->the_activity();
 				
 				$deal_activity = new BP_Activity_Activity( $deal_activities->activity->id );
 				
 				$deal_activity->action = $action;
 				$deal_activity->content = $content;
 				$deal_activity->date_recorded = $time;
 					
 				if( $deal_activity->save() ) {
 					
	 	 			bp_activity_update_meta( $deal_activity->id, 'reality_activity_point_value', $deal_value );
	 	 			
	 	 			foreach( $collaboratorArray as $collaboratorID => $collaboratorName ) {
	 	 			
	 	 				if ( !$playerActivities = get_the_author_meta( 'reality_player_activities', (int) $collaboratorID ) ) {
	 	 					$playerActivities = array();
	 	 				} else {
	 	 					$playerActivities = unserialize($playerActivities);
	 	 				}
	 	 				
	 	 				if ( !isset($playerActivities['reality_deal_submit'][$deal_activity->id]) ) {
	 	 					
	 	 					$playerActivities['reality_deal_submit'][$deal_activity->id] = $deal_activity->id;
	 	 					
	 	 					update_user_meta( (int) $collaboratorID, 'reality_player_activities', serialize($playerActivities) );
	 	 					do_action( 'reality_after_update_user_activities', $collaboratorID, $deal_activity->id );
	 	 				}
	 	 			
	 	 				reality_update_user_points( (int) $collaboratorID );
	 	 				
	 	 				// MAKE ALL USERS IN DEAL FRIENDS
	 	 				
	 	 				foreach( $collaboratorArray as $connectionID => $connectionName ) {
	 	 				
	 	 					if ( $collaboratorID != $connectionID && !friends_check_friendship( $collaboratorID, $connectionID ) ) {
	 	 					
	 	 						friends_add_friend( $collaboratorID, $connectionID, true );
	 	 					
	 	 					}
	 	 				
	 	 				}
	 	 			
	 	 			}
	 	 			
	 	 		} else {
	 	 			die( 'Couldn\'t update activity.' );
	 	 		}
 			
 				
 			
 			}
 			
 			//die('Activities edited = '.$activity_ids );
	
	 	 } else {
	
	 	/*
	 	 * Otherwise, create a new activity for all attached authors.  Make all activities except
	 	 * for the submitter's hidden so that we don't get multiple activities in the global
	 	 * activity feed.
	 	 */
	 	 	
	 	 		$user_id = false;
	 	 		$hide = false;
	 	 	
	 	 		$deal_activity_args = array(
					'action'            => $action,    // The activity action - e.g. "Jon Doe posted an update"
					'content'           => $content,    // Optional: The content of the activity item e.g. "BuddyPress is awesome guys!"
					'component'         => $component, // The name/ID of the component e.g. groups, profile, mycomponent
					'type'              => $type, // The activity type e.g. activity_update, profile_updated
					'primary_link'      => $primary_link,    // Optional: The primary URL for this item in RSS feeds (defaults to activity permalink)
					'user_id'           => $user_id, // Optional: The user to record the activity for, can be false if this activity is not for a user.
					'item_id'           => $item_id, // Optional: The ID of the specific item being recorded, e.g. a blog_id
					'secondary_item_id'	=> $item_id,
					'hide_sitewide'     => $hide, // Should this be hidden on the sitewide activity stream?
					'recorded_time'		=> $time
				);
				
				$deal_activity_args = apply_filters( 'reality_deal_approval_activity_args', $deal_activity_args, $post_author );
				
	 	 		/*
	 	 		 * If the activity is created successfully, attach the deal value to it.
	 	 		 */
	 	 		
	 	 		if ( $activity_ID = bp_activity_add( $deal_activity_args ) ) {
	 	 		
	 	 			bp_activity_update_meta( $activity_ID, 'reality_activity_point_value', $deal_value );
	 	 			
	 	 			foreach( $collaboratorArray as $collaboratorID => $collaboratorName ) {
	 	 			
	 	 				if ( !$playerActivities = get_the_author_meta( 'reality_player_activities', (int) $collaboratorID ) ) {
	 	 					$playerActivities = array();
	 	 				} else {
 	 						$playerActivities = unserialize($playerActivities);
 	 					}
	 	 				
	 	 				if ( !isset($playerActivities['reality_deal_submit'][$activity_ID]) ) {
	 	 					
	 	 					$playerActivities['reality_deal_submit'][$activity_ID] = $activity_ID;
	 	 					
	 	 					update_user_meta( (int) $collaboratorID, 'reality_player_activities', serialize($playerActivities) );
	 	 				}
	 	 			
	 	 				reality_update_user_points( $collaboratorID );
	 	 				
	 	 				// MAKE ALL USERS IN DEAL FRIENDS
	 	 				
	 	 				foreach( $collaboratorArray as $connectionID => $connectionName ) {
	 	 				
	 	 					if ( $collaboratorID != $connectionID && !friends_check_friendship( $collaboratorID, $connectionID ) ) {
	 	 					
	 	 						friends_add_friend( $collaboratorID, $connectionID, true );
	 	 					
	 	 					}
	 	 				
	 	 				}
	 	 			
	 	 			}
	 	 			
	 	 		
	 	 		}
	 	 
 		 }
 		 
 		do_action('reality_after_add_deal_activity', $post_ID );
 		 
 	 }
 }
 add_action('save_post', 'reality_publish_deal_create_activity');
 
function reality_calculate_deal_standings( $post_id, $post ){
	
	if ( !$post_id ) {
		if ( isset($_POST['post_id']) ) $post_id = $_POST['post_id'];
			elseif ( isset( $_GET['post_id'] ) ) $post_id = $_GET['post_id'];
	}
	
	if ( isset($post) ) $post_type = $post->post_type;
		else $post_type = get_post_type( $post_id );
	
	if ( $post_type == 'reality_deals' ) {
		$args = array(
 			'post_type'	=>	'reality_deals',
 			'orderby'	=>	'meta_value_num',
 			'order'		=>	'DESC',
 			'meta_key'	=>	'REALITY_total_value',
 			'nopaging'	=>	true
 		);
		$deals = new WP_Query($args);
		
		if ( $deals->have_posts() ) {
		
			while ( $deals->have_posts() ) {
			
				$deals->the_post();
				
				$rank = $deals->current_post + 1;
				
				$deal_standings = array(
					'points_rank'	=>	$rank
				);
				
				$deal_standings = apply_filters( 'reality_save_deal_standings', $deal_standings, get_the_ID() );
				
				update_post_meta( get_the_ID(), 'REALITY_deal_standings', $deal_standings );
			
			}
		
		}
	}
	
}
add_action( 'save_post', 'reality_calculate_deal_standings', 999, 2 );

function reality_calculate_user_standings() {
	global $wpdb;
	
	$order = $wpdb->get_results("SELECT DISTINCT user_id FROM $wpdb->usermeta WHERE meta_key='reality_current_points' ORDER BY meta_value+0 DESC", "ARRAY_N");
	$count = 1;
	foreach($order as $pid) {
	    $player = new WP_User($pid[0]);
	    
	    $standings_info = array(
	    	'points_rank'	=>	$count
	    );
	    $standings_info = apply_filters( 'reality_update_user_standings_info', $standings_info, $player );
	    
	    update_user_meta( $player->ID, 'REALITY_user_standings', $standings_info );
	    
	    $count++;
	}

}
add_action( 'reality_after_update_all_user_points', 'reality_calculate_user_standings' );
add_action( 'reality_after_force_update_user_points', 'reality_calculate_user_standings', 20 );
add_action( 'reality_after_update_user_points', 'reality_calculate_user_standings', 20 );

function reality_add_user_awards_to_points( $points, $user_id ) {

	if ( $awards = get_user_meta( $user_id, 'REALITY_user_awards' ) ) {
	
		foreach( $awards as $award ) {
		
			if ( $value = get_post_meta( $award, 'REALITY_award_value', true ) ) {
			
				$points += (int) $value;
			
			}
		
		}
	
	}
	
	return $points;

}
add_filter( 'reality_calculated_user_points', 'reality_add_user_awards_to_points', 10, 2 );
?>