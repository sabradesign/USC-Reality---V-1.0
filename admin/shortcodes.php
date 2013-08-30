<?php

/**
 * SHOW CARD SHORTCODE
 *
 * This shortcode will generate any existing card and display it.
 *
 * USAGE
 * [reality_card number="11006" side="front" size="large"]
 * 
 * number = the number of the card you wish to display
 * side = display the front or the back side?
 * size = what size to display.  If no size is set, the card will fill the parent div
 *			it is in.
 */

function generate_reality_card( $atts, $content = null ) {
	extract( shortcode_atts( array(
			'number' => '',
			'side'	 =>	'front',
			'size'	 => 'large'
		), $atts ) );
	
	$args = array(
	  'post_type' => 'reality_cards',
	  'post_status' => 'publish',
	  'numberposts' => 1
	);
	
	if ( $number == '' && isset($_GET['post']) ){
		if ( get_post_type( $_GET['post'] ) == 'reality_cards' ) {
			
			$card_post = get_post( $_GET['post'] );
			
			$have_card = true;
			
		} else {
			$have_card = false;
		}
	} elseif( $number == '' && !isset($_GET['post']) ) {
		$args['name'] = '11006';
		$number = '11006';
		$card_posts = get_posts($args);
		foreach( $card_posts as $post ) {
			$card_post = $post;
			break;
		}
		
		$have_card = false;
	} else {
		$args['name'] = $number;
		$card_posts = get_posts($args);
		
		if ( !empty( $card_posts ) ) {
		
			$card_post = $card_posts[0];
		
			$have_card = true;
		
		} else {
			
			$have_card = false;
			
		}
	}
	
	if ( $have_card || is_admin() ) {
	
	if ( isset( $card_post ) ) {
	
		$number = $card_post->post_name;
		$card_post_meta = get_post_meta( $card_post->ID );
		
		$type = wp_get_object_terms( $card_post->ID, 'card-type' );
		$value_tax = wp_get_object_terms( $card_post->ID, 'card-value' );
		$connections = wp_get_object_terms( $card_post->ID, 'card-connections' );
		$set_taxs = wp_get_object_terms( $card_post->ID, 'card-sets' );
		if ( !empty( $set_taxs ) ) {
			foreach( $set_taxs as $tax ) {
				$set_tax = $tax;
				break;
			}
		}
	
		foreach( $value_tax as $value ) {
			$values = explode(',', $value->description);
			rsort( $values );
			break;
		}
	}
	
	$output = '<div id="card-';
		isset( $number ) ? $output .= $number : false;
		$output .= '" class="reality-card '.$side.' '.$size.'">';
	$output .= '<div class="aspect-control"></div>';
	$output .= '<div class="reality-card-background"><div class="';
		isset( $type[0] ) ? $output .= $type[0]->slug : false;
		$output .= '"></div></div>';
	$output .= '<div class="reality-card-container ';
		isset( $type[0] ) ? $output .= $type[0]->slug : false;
		$output .= '">';
	
		if ( $side == 'front' ) {
		
			$output .= '<div class="reality-card-connections"><div class="';
				isset( $connections[0] ) ? $output .= $connections[0]->slug : false;
				$output .= '"></div></div>';
				
			$output .= '<div class="REALITY_card_front_description">';
				isset($card_post_meta['REALITY_card_front_description'][0]) ? $output .= $card_post_meta['REALITY_card_front_description'][0] : false;
				$output .= '</div>';
			
			$output .= '<div class="REALITY_card_front_title_firstline">';
				isset($card_post_meta['REALITY_card_front_title_firstline'][0]) ? $output.= $card_post_meta['REALITY_card_front_title_firstline'][0] : false;
				$output .= '</div>';
			
			$output .= '<div class="REALITY_card_front_title_secondline">';
				isset($card_post_meta['REALITY_card_front_title_secondline'][0]) ? $output .= $card_post_meta['REALITY_card_front_title_secondline'][0] : false;
				$output .= '</div>';
			
			$output .= '<div class="REALITY_card_front_powerup">';
			isset($card_post_meta['REALITY_card_front_powerup'][0]) ? $output .= $card_post_meta['REALITY_card_front_powerup'][0] : false;
				$output .= '</div>';
				$output .= '<div class="reality-card-front-values">';
			
			if ( isset( $values ) ) {
				foreach( $values as $value ) {
					$output .= '<div class="value">'.$value.'</div>';
				}
			}
			
			$output .= '</div>';
			$output .= '<div class="REALITY_card_front_id">#';
				isset( $number ) ? $output .= $number : false;
				$output .= '</div>';
			
		} elseif ( $side == 'back') {
		
			if ( isset( $card_post_meta['REALITY_card_info_image'][0] ) ) {
				$image = wp_get_attachment_image_src( $card_post_meta['REALITY_card_info_image'][0], array(200,200) );
			}
		
			$output .= '<div class="reality-card-back-ID">';
				isset( $number ) ? $output .= $number : false;
				$output .= '</div>';
			
			$output .= '<div class="REALITY_card_back_title">';
				isset( $card_post_meta['REALITY_card_back_title'][0] ) ? $output .= $card_post_meta['REALITY_card_back_title'][0] : false;
				$output .= '</div>';
			
			$output .= '<img src="';
				isset( $image ) ? $output .= timthumb_photo( $image[0], 155, 155, '', false) : false;
				$output .= '" alt="';
				isset( $card_post_meta['REALITY_card_back_title'][0] ) ? $output .= $card_post_meta['REALITY_card_back_title'][0] : false;
				$output .= '" />';
				
			$output .= '<div class="REALITY_card_back_description">';
				isset( $card_post_meta['REALITY_card_back_description'][0] ) ? $output .= $card_post_meta['REALITY_card_back_description'][0] : false;
				$output .= '</div>';
			if ( isset( $set_tax ) ) {
				$output .= '<div class="REALITY_card_back_setinfo">'.$set_tax->name.' '.$card_post_meta['REALITY_card_back_setinfo'][0].' of '.$set_tax->count.'</div>';
			}
		}
	
	$output .= '</div>';
	$output .= '</div>';
	
	} else {
	
		$output = '<div class="reality-card '.$size.'"><div class="aspect-control"></div><div class="no-card">No Card Found</div></div>';
	
	}
	
	return $output;

}
add_shortcode( 'reality_card', 'generate_reality_card' );

/**
 * LEADERBOARD SHORTCODE
 *
 * This shortcode will generate leaderboards for display
 *
 * USAGE
 * [reality_leaderboard type="total_points" size="10"]
 *
 * type = the type of leader board you wish to display
 * size = the number of spots you would like to display
 * award = if "type" = "audience," this variable deteremines which award to show.  Get
 *		the appropriate values from the "slug" column of the "Awards" tab of the "Reality
 *		Settings" menu.
 * title = if this field is set, it will override the default title
 *
 */
 function reality_leaderboard_shortcode_function($atts, $content = null){
 	global $bp, $wpdb, $reality;
 	
 	$current_season = $reality->current_season;
 	if ( is_array( $current_season ) ) {
 		foreach( $current_season as $slug => $name ) {
 			$season_slug = $slug;
 		}
 		
 		
 	} else {
 		$season_slug = '';
 	}
 	
 	$current_user = new WP_User( $bp->loggedin_user->id );
 	
 	if ( in_array( 'administrator', $current_user->roles ) || in_array( 'administrator', $current_user->roles ) ) {
 		$dept = false;
 		$dept_nav = true;
 	} else {
 		foreach( $current_user->roles as $role ) {
 			$dept = $role;
 		}
 		$dept_nav = false;
 	}
 	
 	extract( shortcode_atts( array(
			'type' => 'total_points',
			'size'	=>	-1,
			'award'	=>	'',
			'season'=> $season_slug,
			'week'	=>	$reality->current_week,
			'title'	=>	'',
			'dept'	=>	$dept,
			'dept_nav'	=>	$dept_nav,
			'ajax'	=>	0
		), $atts ) );
 	
 	
 	$output = '';
 	
 	if ( !$ajax ) {
 	 	$output .= '<div class="leaderboard-container" data-size="'.$size.'" data-type="'.$type.'" data-season="'.$season.'" data-title="'.$title.'">';
 	 }
 	 
 	 if ( isset( $reality->available_depts ) ) {
		$departments = $reality->available_depts;
	} else {
		$departments = get_option( 'reality_player_departments' );
	}
 	 
 	 if ( $dept ) {
 	 	
 	 	$dept_name = $departments[$dept].' ';
 	 } else {
 	 	$dept_name = '';
 	 }
 	
 	if ( $dept_nav && !$ajax && !empty( $departments ) ) {
 	
 		$output .= '<div class="dept_nav">';
 		
 		global $wp_roles;
 		$avail_roles = $wp_roles->roles;
 		
 		$output .=  '<a href="?dept=0" class="dept-nav-button button';
 		
 		if ( !$dept ) $output .= ' active';
 		
 		$output .= '" data-dept="0">All</a> ';
 		
 		foreach( $avail_roles as $slug => $role ) {
 		
 			if ( in_array( $role['name'], $departments) ) {
 				$output .=  '<a href="?dept='.$slug.'" class="dept-nav-button button';
 				
 				if ( $dept == $slug ) $output .= ' active';	
 				
 				$output .= '" data-dept="'.$slug.'">'.$role['name'].'</a> ';
 			}
 		
 		}
 		
 		$output .= '</div>';
 	
 	}
 	
 	if ( !$ajax ) {
 	
 		$output .= '<div class="table-container">';
 	}
 
 	switch( $type ) {
 		case 'total_points':
 			$output .= '<table class="leader-board '.$type.'">';
 			if ( $title == '' ) $title = $dept_name.'Point Leaders';
 			$output .= '<thead><tr><th colspan=3>'.$title.'</th></tr></thead>';
 			$output .= '<tbody>';
 			
 			if ( $size != -1 ) $limit = ' LIMIT 0, '.$size;
 				else $limit = '';
 				
 				
 			$or_syntax = '';
 				
 			if ( $dept ) {
 			
 				$or_syntax .= ' AND ( ';
 			
 				global $wp_roles;
 				$roles = $wp_roles->roles;
 				
 				if ( array_key_exists( $dept, $roles ) ) {
 				
 					$dept_users = get_users( array( 'role' => $dept ) );
 					
 					if ( $dept_users ) {
 					
 						foreach( $dept_users as $i => $user ) {
 						
 							$or_syntax .= 'user_id='.$user->ID.' ';
 							
 							if ( $i != count($dept_users) - 1 ) {
 							
 								$or_syntax .= 'OR ';
 							
 							}
 						
 						}
 					
 					
 					}
 				
 					
 				
 				}
 				
 				$or_syntax .= ') ';
 			
 			}
 			
			$order = $wpdb->get_results("SELECT DISTINCT user_id, meta_value FROM $wpdb->usermeta WHERE meta_key='reality_current_points'".$or_syntax." ORDER BY meta_value DESC".$limit, "ARRAY_N");
			$players = array();
			foreach($order as $pid)
			    $players[(int) $pid[0]] = $pid[1]; 

			if (!empty($players))
			{
				$count = 1;
				arsort($players);
			    foreach ($players as $player => $points)
			    {
			    	$playerUser = new WP_User($player);
			    	$output .= '<tr>';
			    	$output .= '<td>'.$count.'</td>';
			        $player_info = get_userdata($playerUser->ID);
			        
			        if ( $player_info->user_firstname != '' && $player_info->user_lastname != '' ) {
			        	$name = $player_info->user_firstname.' '.$player_info->user_lastname;
			        } else {
			        	$name = $player_info->user_nicename;
			        }
			        
			        $output .= '<td><a href="'.get_bloginfo('url').'/members/'.$player_info->user_nicename.'" title="View '.$name.'\'s Profile">'.$name.'</a></td>';
			        //$output .= '<td>'.get_the_author_meta('reality_current_points',$player_info->ID).'</td>';
			        $output .= '<td>'.$points.'</td>';
			        $output .= '</tr>';
			        
			        $count++;
			    }
			} else {
			    $output .= '<tr><td colspan=2>No players with scores found</td></tr>';
			}
			
			$output .= '</tbody>';
 			$output .= '</table>';
 			break;
 		case 'biggest_deals':
 			$output .= '<table class="leader-board '.$type.'">';
 			if ( $title == '' ) $title = $dept_name.'Deal Leaderboard';
 			$output .= '<thead><tr><th colspan=3>'.$title.'</th></tr></thead>';
 			$output .= '<tbody>';
 			
 			if ( $dept ) {
 			
 				$dept_deals = get_option('REALITY_department_deals-'.$dept);
 				$in = $dept_deals;
 			
 			} else {
 			
 				$in = '';
 			
 			}
 			
 			$args = array(
 				'post_type'	=>	'reality_deals',
 				'orderby'	=>	'meta_value_num',
 				'order'		=>	'DESC',
 				'meta_key'	=>	'REALITY_total_value',
 				'posts_per_page'	=>	$size,
 				'post__in'	=>	$in
 			);
 			$deals = new WP_Query($args);
 			
 			if ( $deals->have_posts() ) {
 				$count = 1;
 				while ( $deals->have_posts() ) {
 					$deals->the_post();
 					
 					$output .= '<tr>';
 					$output .= '<td>'.$count.'</td>';
 					$output .= '<td><a href="'.get_permalink().'" title="Watch '.get_the_title().'">'.get_the_title().'</a></td>';
 					$output .= '<td>'.get_post_meta(get_the_ID(), 'REALITY_total_value', TRUE).'</td>';
 					$output .= '</tr>';
 					$count++;
 				}
 				
 				wp_reset_postdata();
 				
 			} else {
 				$output .= '<tr><td colspan=3>Could not find any deals.</td></tr>';
 			}
 			
 			$output .= '</tbody>';
 			$output .= '</table>';
 			break;
 		case 'most_collaborators':
 			break;
 		case 'most_deals':
 			$output .= '<table class="leader-board '.$type.'">';
 			if ( $title == '' ) $title = $dept_name.'Most Deals';
 			
 			$output .= '<thead><tr><th colspan=3>'.$title.'</th></thead>';
 			$output .= '<tbody>';
 		
 			if ( $dept ) {
 			
 				$in = array();
 			
 				$dept_users = get_users( array( 'role' => $dept ) );
 				
 				foreach( $dept_users as $user ) {
 				
 					$tax = get_term_by( 'slug', $user->ID, 'authors-tax' );
 					$in[] = (int) $tax->term_id;
 				
 				}
 			
 			} else {
 			
 				$in = '';
 			
 			}
 			
 			if ( $size = -1 ) $size = '';
 		
 			$args = array(
 				'orderby'	=>	'count',
 				'order'		=>	'DESC',
 				'hide_empty'	=>	false,
 				'number'	=>	$size,
 				'include'	=>	$in
 			);
 			$players = get_terms( 'authors-tax', $args);
 			
 			if ( $players ) {
 				$count = 1;
 				foreach( $players as $player ) {
 				
 					$player_info = unserialize($player->description);
 				
 					$output .= '<tr>';
 					$output .= '<td>'.$count.'</td>';
 					$output .= '<td><a href="'.get_bloginfo('url').'/members/'.$player_info['nicename'].'" title="View'.$player->name.'\'s Profile">'.$player->name.'</a></td>';
 					$output .= '<td>'.$player->count.'</td>';
 					$output .= '</tr>';
 					$count++;
 				}
 			
 			} else {
 				$output .= '<tr><td colspan=3>Could not find any players.</td></tr>';
 			}
 			
 			$output .= '</tbody>';
 			$output .= '</table>';
 			break;
 		case 'audience':
 			$awardOptions = get_option( 'reality_audience_award_options' );
 			$output .= '<table class="leader-board '.$type.'">';
 			if ( $award != '' || !in_array( $award, $awardOptions ) ) {
 				
 				if ( $title == '' ) $title = $dept_name.''.$awardOptions[$award].' Award';
 				
 				
 				$output .= '<thead><tr><th colspan=3>'.$title.'</th></thead>';
 				$output .= '<tbody>';
 				
 				$awardMetaValue = 'REALITY_audience_awards_'.$award;
 				
 				if ( $dept ) {
			
					$in = $reality->current_dept_deals;
			
				} else {
			
					$in = '';
			
				}
 				
 				$args = array(
 					'orderby'			=>	'meta_value_num',
 					'meta_key'			=>	$awardMetaValue,
 					'posts_per_page'	=>	$size,
 					'post_type'			=>	'reality_deals',
 					'order'				=>	'DESC',
 					'post__in'			=>	$in
 				);
 				$deals = new WP_Query($args);
 			
 				if ( $deals->have_posts() ) {
 					$count = 1;
 					while ( $deals->have_posts() ) {
 						$deals->the_post();
 						
 						$output .= '<tr>';
 						$output .= '<td>'.$count.'</td>';
 						$output .= '<td><a href="'.get_permalink().'" title="Watch '.get_the_title().'">'.get_the_title().'</a></td>';
 						$output .= '<td>'.get_post_meta(get_the_ID(), 'REALITY_audience_awards_'.$award, TRUE).'</td>';
 						$output .= '</tr>';
 						$count++;
 					}
 					
 					wp_reset_postdata();
 				
 				} else {
 					$output .= '<tr><td colspan=3>Could not find any deals with "'.$awardOptions[$award].'" votes.</td></tr>';
 				}
 				
 				$output .= '</tbody>';
 				$output .= '</table>';
 			
 			}
 			break;
 		case 'weekly':
 				$output .= '<table class="leader-board '.$type.'">';
 				
 				// Validate season and week.  Revert to current if doesn't exist
				$seasons = get_option( 'reality_game_instances' );
				if ( !isset( $seasons[$season] ) ) $season = $season_slug;
				if ( !isset( $seasons[$season]['weeks'][$reality->current_week] ) ) $week = $reality->current_week;
				if ( $title == '' ) $title = 'Week '.$week.' Leader Board';
 		
 				$meta_key = 'reality_weekly_points_'.$season.'_week_'.$week;
 		
 				$output .= '<thead><tr><th colspan=3>' . $title . '</th></tr></thead>';
				$output .= '<tbody>';
			
				if ( $size != -1 ) $limit = ' LIMIT 0, '.$size;
					else $limit = '';
			
				$order = $wpdb->get_results("SELECT DISTINCT user_id, meta_value FROM $wpdb->usermeta WHERE meta_key='" . $meta_key . "' ORDER BY meta_value DESC".$limit, "ARRAY_N");
				$players = array();
				foreach($order as $pid)
					$players[(int) $pid[0]] = $pid[1]; 

				if (!empty($players))
				{
					$count = 1;
					arsort($players);
					foreach ($players as $player => $points)
					{
						
						
						$playerUser = new WP_User($player);
						
						if ( $dept && !in_array( $dept, $playerUser->roles ) ) continue;
						
						$output .= '<tr>';
						$output .= '<td>'.$count.'</td>';
						$player_info = get_userdata($playerUser->ID);
					
						if ( $player_info->user_firstname != '' && $player_info->user_lastname != '' ) {
							$name = $player_info->user_firstname.' '.$player_info->user_lastname;
						} else {
							$name = $player_info->user_nicename;
						}
					
						$output .= '<td><a href="'.get_bloginfo('url').'/members/'.$player_info->user_nicename.'" title="View'.$name.'\'s Profile">'.$name.'</a></td>';
						//$output .= '<td>'.get_the_author_meta('reality_current_points',$player_info->ID).'</td>';
						$output .= '<td>'.$points.'</td>';
						$output .= '</tr>';
					
						$count++;
					}
				} else {
					$output .= '<tr><td colspan=2>No players with scores found</td></tr>';
				}
			
				$output .= '</tbody>';
				$output .= '</table>';
 		
 			break;
 			case 'weekly_tabs':
 				// Validate season and week.  Revert to current if doesn't exist
				$seasons = get_option( 'reality_game_instances' );
				if ( $seasons ) {
					if ( !isset( $seasons[$season] ) ) $season = $season_slug;
				
					foreach( $seasons[$season]['weeks'] as $key => $week ) {
				
						if ( $week['startdate'] > time() ) {
							unset( $seasons[$season]['weeks'][$key] );
						}
				
					}
				
					// Create Tab Navigation
				
					$output .= '<div class="leader-board weekly-leaderboard-tabbed">';
				
					$output .= '<ul>';
					foreach( $seasons[$season]['weeks'] as $key => $week ) {
				
						$output .= '<li><a href="#week-'.$key.'">Week '.$key.'</a></li>';
				
					}
					$output .= '</ul>';
					// Tab Content
				
					foreach( $seasons[$season]['weeks'] as $key => $week ) {
				
						$output .= '<div id="week-'.$key.'">';
						$title = 'Week '.$key.' Leader Board';
		
						$meta_key = 'reality_weekly_points_'.$season.'_week_'.$key;
		
						$output .= '<table class="leader-board '.$type.'">';
						$output .= '<thead><tr><th colspan=3>' . $title . '</th></tr></thead>';
						$output .= '<tbody>';
			
						if ( $size != -1 ) $limit = ' LIMIT 0, '.$size;
							else $limit = '';
							
							$or_syntax = '';
 				
						if ( $dept ) {
		
							$or_syntax .= ' AND ( ';
		
							global $wp_roles;
							$roles = $wp_roles->roles;
			
							if ( array_key_exists( $dept, $roles ) ) {
			
								$dept_users = get_users( array( 'role' => $dept ) );
				
								if ( $dept_users ) {
				
									foreach( $dept_users as $i => $user ) {
					
										$or_syntax .= 'user_id='.$user->ID.' ';
						
										if ( $i != count($dept_users) - 1 ) {
						
											$or_syntax .= 'OR ';
						
										}
					
									}
				
				
								}
			
				
			
							}
			
							$or_syntax .= ') ';
		
						}
			
						$order = $wpdb->get_results("SELECT DISTINCT user_id, meta_value FROM $wpdb->usermeta WHERE meta_key='" . $meta_key . "'".$or_syntax." ORDER BY meta_value DESC".$limit, "ARRAY_N");
						$players = array();
						foreach($order as $pid)
							$players[(int) $pid[0]] = $pid[1]; 

						if (!empty($players))
						{
							$count = 1;
							arsort($players);
							foreach ($players as $player => $points)
							{
								$playerUser = new WP_User($player);
								$output .= '<tr>';
								$output .= '<td>'.$count.'</td>';
								$player_info = get_userdata($playerUser->ID);
					
								if ( $player_info->user_firstname != '' && $player_info->user_lastname != '' ) {
									$name = $player_info->user_firstname.' '.$player_info->user_lastname;
								} else {
									$name = $player_info->user_nicename;
								}
					
								$output .= '<td><a href="'.get_bloginfo('url').'/members/'.$player_info->user_nicename.'" title="View'.$name.'\'s Profile">'.$name.'</a></td>';
								//$output .= '<td>'.get_the_author_meta('reality_current_points',$player_info->ID).'</td>';
								$output .= '<td>'.$points.'</td>';
								$output .= '</tr>';
					
								$count++;
							}
						} else {
							$output .= '<tr><td colspan=2>No players with scores found</td></tr>';
						}
			
						$output .= '</tbody>';
						$output .= '</table>';
					
						$output .= '</div>';
					}
				
					$output .= '</div>';
				} else {
				
					// Output weekly leaderboard for the 12 most recent weeks
					
					$output .= '<div>Setup seasons to view weekly leaderboards.</div>';
				
				}
 		
 			break;
 		default:
 			$output .= '<p>Leader Board does not exists</p>';
 			break;
 	}
 	
 	if ( !$ajax ) {
 	
 		$output .= '</div></div>';
 	
 	}
 	
 	return $output;
 
 }
 add_shortcode( 'reality_leaderboard', 'reality_leaderboard_shortcode_function');


function reality_player_past_scores( $atts, $content = null ) {
	global $bp;
	
	extract( shortcode_atts( array(
			'user_id'	=> $bp->displayed_user->id,
			'title'		=> 'Past Seasons'
		), $atts ) );
		
	$instances = get_option( 'reality_game_instances' );	
	$pastPoints = get_the_author_meta( 'reality_past_points', $user_id );
	
	$has_past_scores = false;
	
	if ( isset( $pastPoints ) && is_array( $pastPoints) ) {
		foreach ( $pastPoints as $score ) {
			if ( $score != 0 && $score != '' ) {
				$has_past_scores = true;
				break;
			}
		}
	}
	
	if ( $has_past_scores ) {
	
	$output = '<table class="user-quick-status">';
	$output .= '<thead><tr><th>'.$title.'</th><th>Score</th></tr></thead>';
	$output .= '<tbody>';
	
	foreach( $pastPoints as $slug => $score ) {
	
		if ( $score != 0 && $score != '' ) {
	
			$output .= '<tr><td class="value-title">'.$instances[$slug]['name'].'</td><td class="value">'.$score.'</td></tr>';
	
		}
	
	}
	
	$output .= '</tbody></table>';
	
	return $output;
	
	} else {
	
		return false;
	
	}
		
}
add_shortcode( 'reality_past_scores', 'reality_player_past_scores');
?>