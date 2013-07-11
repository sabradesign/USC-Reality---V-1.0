<?php

add_action('admin_menu', 'reality_settings_menus');

function reality_settings_menus() {
	add_menu_page('Reality Settings', 'Reality Settings', 'manage_options', 'reality_settings_menus', 'reality_settings_menu', get_stylesheet_directory_uri().'/images/reality_icon_32x32.png', '3');    
    add_submenu_page( 'reality_settings_menus', 'Reality Scoring', 'Scoring', 'manage_options', 'reality_settings_scoring', 'reality_points_menu');
    add_submenu_page( 'reality_settings_menus', 'Reality Ranks / Levels', 'Ranks / Levels', 'manage_options', 'reality_settings_ranks', 'reality_ranks_menu');
    add_submenu_page( 'reality_settings_menus', 'Reality Card Importer', 'Reality Card Importer', 'manage_options', 'reality_card_importer', 'reality_card_importer_menu');
    //add_submenu_page( 'reality_settings_menus', 'Reality Department Activity', 'Reality Department Activity', 'manage_options', 'reality_department_activity', 'reality_department_activity_menu');
    
}

function reality_ranks_menu(){
  	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	include('menus/reality_ranks_menu.php');

}

function reality_points_menu(){
  	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	include('menus/reality_points_menu.php');

}

function reality_settings_menu() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	echo '<div class="wrap">';
    echo '<h2>Reality Settings</h2>';
    include('menus/reality_main_menu.php');
	echo '</div>';
}

function reality_card_importer_menu() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
	include('menus/reality_card_importer.php');
	
}

function reality_department_activity_menu() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
	
	
		include('menus/reality_department_activity_menu.php');
	
}



// Create the function to output the contents of our Dashboard Widget

function reality_dashboard_overview_widget_function() {
	// Display whatever it is you want to show
	
	global $reality;
	
	$output = '';
	$output .= '<div class="table table_content">';
	
	//$is_currently_active = get_option( 'reality_is_currently_running' );
	$seasons = get_option( 'reality_game_instances' );
	$is_currently_active = $reality->current_season;
	
	if ( $is_currently_active ) {
		
		if ( is_array( $is_currently_active ) ) {
		
			foreach( $is_currently_active as $slug => $instance ) {
				$weeks_to_go = count($seasons[$slug]['weeks'] ) - $reality->current_week;
				$output .= 'Reality is currently RUNNING the '.$instance.' season and is on week ' . $reality->current_week . ' with ' . $weeks_to_go . ' weeks to go.';
			}
		
		} else {
			$output .= 'Reality is currently RUNNING and is on week ' . $reality->current_week . '.';
			$installed_on = get_option('reality_game_installed_date');
			$output .= '<p>Reality installed on: ' . date( 'D, M d, Y', $installed_on ) . '</p>';
		}
		
	} else {
		$output .= 'Reality is currently NOT RUNNING.  Set a gameplay season to start.';
	}
	
	$output .= '<p class="sub">Content</p>';
	$output .= '<table><tbody>';
	
	$users_count = count_users();
	
	if ( $reality->use_depts ) {
		
		$players = 0;
	
		foreach( $reality->available_depts as $slug => $dept ) {
			isset( $users_count['avail_roles'][$slug] ) ? $players += $users_count['avail_roles'][$slug] : $players += 0;
		}
	
	} else {
		isset( $users_count['avail_roles']['player'] ) ? $players = $users_count['avail_roles']['player'] : $players = 0;
	}
	$output .= '<tr class="first"><td class="first b b_players"><a href="'.admin_url('users.php').'">'.$players.'</a></td><td class="t players">Players are currently registered.</td></tr>';
	
	$deals_count = wp_count_posts('reality_deals');
	$output .= '<tr><td class="first b b_deals"><a href="'.admin_url('edit.php?post_type=reality_deals').'">'.$deals_count->publish.'</a></td><td class="t deals">Deals have been made.</td></tr>';
	
	if ( $reality->use_depts ) {
		$output .= '<tr><td class="first b b_deals_pending">'.count( $reality->available_depts ).'</td><td class="t deals_pending">Departments are playing.</td></tr>';
	}
	
	$output .= '<tr><td class="first b b_deals_pending"><a href="'.admin_url('edit.php?post_status=pending&post_type=reality_deals').'">'.$deals_count->pending.'</a></td><td class="t deals_pending">Pending Deals.</td></tr>';
	
	$cards_count = wp_count_posts('reality_cards');
	$output .= '<tr><td class="first b b_cards"><a href="'.admin_url('edit.php?post_type=reality_cards').'">'.$cards_count->publish.'</a></td><td class="t cards">Cards have been created.</td></tr>';
	
	
	$output .= '</tbody></table>';
	$output .= '</div>';
	
	echo $output;
}

function reality_dashboard_award_prizes_widget_function() {
	echo "This widget will have a form to award prizes to deals.";
}

function reality_dashboard_add_users_widget_function() {
	
	global $reality, $reality_messages;
	
	?>
	
		<?php if ( !empty( $reality_messages ) ) : ?>
		
			<?php if ( isset( $reality_messages['errors'] ) ) : ?>
			<?php foreach( $reality_messages['errors'] as $error ) : ?>
			
				<div class="message error">
					<?php echo( $error ); ?>
				</div>
			
			<?php endforeach; ?>
			<?php endif; ?>
			
			<?php if ( isset( $reality_messages['success'] ) ) : ?>
				<?php foreach( $reality_messages['success'] as $success ) : ?>
			
					<div class="message success">
						<?php echo( $success ); ?>
					</div>
			
				<?php endforeach; ?>
			<?php endif; ?>
		
		<?php endif; ?>
	
		<form method="POST" id="reality-add-user">
			<table>
			<tr>
				<td><label for="player-email">Player Email</label></td>
				<td><input type="email" required name="player-email" id="player-email"></td>
			</tr>
			
			<tr>
				<td><label for="player-first-name">Player First Name</label></td>
				<td><input type="text" required name="first_name" id="player-first-name"></td>
			</tr>
			
			<tr>
				<td><label for="player-last-name">Player Last Name</label></td>
				<td><input type="text" required name="last_name" id="player-last-name"></td>
			</tr>
			
			<?php if ( $reality->use_depts ) : ?>
			
			<tr>
				<td><label for="player-department">Player Last Name</label></td>
				<td><select required name="player-department" id="player-department">
				
					<option>Select a Department...</option>
				
					<?php foreach ( $reality->available_depts as $slug => $dept ) : ?>
					
						<option value="<?php echo $slug; ?>"><?php echo $dept; ?></option>
					
					<?php endforeach; ?>
				
				</select></td>
			</tr>
			
			<?php endif; ?>
			
			<tr>
			<td colspan=2>
			<input type="hidden" name="reality-action" value="add_player">
			<?php wp_nonce_field( 'reality_add_player', '_wp_nonce_add_player' ) ?>
			
			<input type="submit" value="Add Player">
			</td>
			</tr>
			
			
			</table>
		</form>
	
	<?php
}

add_action( 'admin_init', 'reality_process_new_user_from_dashboard_widget' );
function reality_process_new_user_from_dashboard_widget() {

	global $reality, $reality_messages;
	$reality_messages = array();

	if ( isset( $_POST['reality-action'] ) && $_POST['reality-action'] == 'add_player' && wp_verify_nonce( $_POST['_wp_nonce_add_player'], 'reality_add_player' ) ) {

		$user_name = $_POST['first_name'].$_POST['last_name'];
		$user_name = sanitize_title_with_dashes( $user_name );
		
		$user_email = $_POST['player-email'];
	
		$user_id = username_exists( $user_name );
		if ( !$user_id and email_exists($user_email) == false ) {
		
			$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
			
			$userdata = array(
				'user_pass'			=>	$random_password,
				'user_login'		=>	$user_name,
				'user_nicename'		=>	$user_name,
				'user_email'		=>	$user_email,
				'display_name'		=>	$_POST['first_name'].' '.$_POST['last_name'],
				'first_name'		=>	$_POST['first_name'],
				'last_name'			=>	$_POST['last_name'],
				'nickname'			=>	$_POST['first_name'],
				'user_registered'	=>	date( 'Y-m-d H:i:s', time() )
			);
			
			if ( isset($_POST['player-department'] ) ) {
				$userdata['role'] = $_POST['player-department'];		
			} else {
				$userdata['role'] = 'player';
			}
			
			$user_meta = array();
			
// 			if ( $user_id = bp_core_signup_user( $user_name, $random_password, $user_email, $user_meta ) ) {
// 			
// 				$userdata['ID'] = $user_id;
// 				
// 				wp_insert_user( $userdata );
// 			
// 			}
			
			if ( $user_id = wp_insert_user( $userdata ) ) {
			
				$reality_messages['success'][] = 'Successfully added the player '.$_POST['first_name'].' '.$_POST['last_name'].'.';
				// Send Email with Password
			
				$to = $_POST['player-email'];
				$subject = 'Welcome to Reality';
				$headers = 'From: Reality <'.get_bloginfo('admin_email').'>' . "\r\n";
				
				$message = '<p>Use the following credentails to login at '.get_bloginfo('wpurl').'</p>';
				$message .= '<p>Username: '.$user_name.'<br>';
				$message .= 'Password: '.$random_password.'</p>';
				$message .= '<p>Please change your password upon logging in.</p>';
			
				add_filter( 'wp_mail_content_type', 'set_html_content_type' );
				wp_mail( $to, $subject, $message, $headers );
				remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
			
			}
			
		} else {
			$random_password = __('User already exists.  Password inherited.');
			$reality_messages['errors'][] = $random_password;
		}
		
		
	
	}

}

function set_html_content_type()
{
	return 'text/html';
}

function reality_dashboard_award_prizes_widget_submit_function() {

}

function reality_dashboard_leaderboard_widget_function() {
	
	$output = do_shortcode( '[reality_leaderboard type="weekly_tabs"]' );
	$output .= '<br><br>';
	$output .= do_shortcode( '[reality_leaderboard]' );
	
	
	echo $output;
}

// Create the function use in the action hook

function reality_dashboard_widgets() {
	// Remove default Wordpress Widgets
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_browser_nag', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
	
	wp_add_dashboard_widget('reality_dashboard_overview', 'Reality Overview', 'reality_dashboard_overview_widget_function');	
	wp_add_dashboard_widget('reality_dashboard_add_users', 'Reality Add Users', 'reality_dashboard_add_users_widget_function');	
	wp_add_dashboard_widget('reality_dashboard_leaderboard', 'Reality Points Leaderboard', 'reality_dashboard_leaderboard_widget_function');	
		
} 

// Hook into the 'wp_dashboard_setup' action to register our other functions

add_action('wp_dashboard_setup', 'reality_dashboard_widgets' );

// Register Navigation Menus
function reality_footer_navigation() {
	$locations = array(
		'logged_out_menu' => __( 'Logged Out Navigation', 'Reality' ),
		'footer_menu' => __( 'Custom Footer Menu', 'Reality' ),
		'mobile_footer' => __( 'Footer Menu on mobile devices', 'Reality' ),
	);

	register_nav_menus( $locations );
}

// Hook into the 'init' action
add_action( 'init', 'reality_footer_navigation' );

// ADD CARD NUMBER TO CARD COLUMNS
function reality_add_card_columns($columns) {
    unset($columns['comments']);
    unset($columns['date']);
    
    $card_columns = array(
		'card_preview'	=>	__('Card Preview', 'Reality'),
    	'card_number'	=>	__('Card Number', 'Reality')
    );
    
    $first_element = each($columns);
    
    unset($columns[$first_element['key']]);
   	reset($columns);
    
    $bulk_checkbox = array( $first_element['key'] => $first_element['value'] );
    
    return array_merge($bulk_checkbox, $card_columns, $columns);
}
add_filter('manage_reality_cards_posts_columns' , 'reality_add_card_columns');

function reality_add_card_columns_renderer( $column, $post_id ) {
	global $post;

	switch ( $column ) {

        case 'card_preview' :
        	$output = do_shortcode('[reality_card number="'.$post->post_name.'" size="small"]');
        	$output .= do_shortcode('[reality_card number="'.$post->post_name.'" size="small" side="back"]');
            echo $output;
            break;

        case 'card_number' :
        	echo $post->post_name;
            break;

    }

}
add_action( 'manage_reality_cards_posts_custom_column' , 'reality_add_card_columns_renderer', 10, 2 );

?>