<?php 

// Set Length of one week
$week_length = 60 * 60 * 24 * 7;
$day_length = 60 * 60 * 24;

global $reality;

if ( isset( $_POST['reality_main_settings_update'] ) && $_POST['reality_main_settings_update'] == 'Y' ) {
	
	$settings = $_POST['reality_main_settings'];

	foreach( $settings as $settingName => $value ) {
	
		switch( $settingName ) {
			case 'reality_audience_award_options':
				$audience_awards = array();
				foreach( $value as $award ) {
					if ( $award != '' ) {
						$slug = sanitize_title_with_dashes($award);
						$audience_awards[$slug] = $award;
					}
				}
				update_option( $settingName, $audience_awards );
				break;
			case 'reality_game_instances':
				$first_value = reset($value);
				if( !empty( $value ) && $first_value['name'] != '' ) {
					$reality_instances = array();
					$sorting_array = array();
					foreach( $value as $key => $instance ) {
				
						if ( $instance['name'] != '' && $instance['startdate'] != '' && $instance['enddate'] != '' ) {
				
							$instance['startdate'] = strtotime( $instance['startdate'] );
							$instance['enddate'] = strtotime( $instance['enddate'] );
							$instance['slug'] = sanitize_title_with_dashes($instance['name']);
							$sorting_array[ $instance['slug'] ] = $instance['startdate'];
						
							$now = time();
							if ( $now > $instance['startdate'] && $now < $instance['enddate'] ) {
								if ( !isset( $current_instance ) ) {
									$current_instance = $instance;
								} else {
									$message[] = 'The following two game play instances overlap: '.$current_instance['name'].' and '.$instance['name'];
								}
							}
						
							if ( isset( $settings['reality_weekly_leaderboard_reset'] ) ) {
						
								// SET FIRST WEEK
								$first_week['startdate'] = $instance['startdate'];
								$first_full_week_start = strtotime( 'First '.$settings['reality_weekly_leaderboard_reset'], $instance['startdate'] );
								if ( $instance['startdate'] != $first_full_week_start ) {
									$first_week['enddate'] = $first_full_week_start - 1;
								} else {
									$first_week['enddate'] = $instance['startdate'] + $week_length - 1;
								}
							
								// SET LAST WEEK
								$last_week['enddate'] = $instance['enddate'] - 1;
								$last_week_start = strtotime( 'Last '.$settings['reality_weekly_leaderboard_reset'], $instance['enddate'] );
							
								if ( $instance['enddate'] - $last_week_start > 0 ) {
									$last_week['startdate'] = $last_week_start;
								} else {
									$last_week['startdate'] = $instance['enddate'];
								}
							
								$full_weeks_gameplay_length = $last_week['startdate'] - $first_week['enddate'] - 1;
							
								$weeks = array();
								$weeks[1] = $first_week;
								$count = 2;
							
								while ( $full_weeks_gameplay_length > 0 ) {
									$week['startdate'] = $last_week['startdate'] - $full_weeks_gameplay_length;
									$week['enddate'] = $week['startdate'] + $week_length - 1;
									$weeks[$count] = $week;
							
									$full_weeks_gameplay_length -= $week_length;
									$count++;
								}
							
								$weeks[$count] = $last_week;
							
								$instance['weeks'] = $weeks;
						
							}
							
							$reality_instances[$instance['slug']] = $instance;

						}

					}
				
					foreach( $reality_instances as $key => $instance ) {
						$startdate[$key] = $instance['startdate'];
					}
					
					//if ( count($reality_instances) > 1 ) {
					
						array_multisort( $startdate, SORT_DESC, $reality_instances );
					
					//}
				
					update_option( $settingName, $reality_instances );
				
					if ( isset( $current_instance ) ) {
						update_option( 'reality_is_currently_running', array( $current_instance['slug'] => $current_instance['name'] ) );
						$settings['reality_is_currently_running'] = array( $current_instance['slug'] => $current_instance['name'] );
					} else {
						update_option( 'reality_is_currently_running', false );
					}
				} else {
				
					delete_option( $settingName );
					update_option( 'reality_is_currently_running', true );
				
				}
				break;
			case 'reality_player_departments' :
				$departments = $value;
				$dept_output = array();
				
				foreach( $departments as $dept ) {
					if ( $dept != '' ) {
						$slug = sanitize_title_with_dashes($dept);
						$dept_output[$slug] = $dept;
					}
				}
				
				$settings['reality_player_departments'] = $dept_output;
				update_option( $settingName, $dept_output );
				
				break;
			default:
				update_option( $settingName, $value );
		}
	
	}
	
	if ( !isset($settings['reality_use_departments']) ) {
		update_option( 'reality_use_departments', false );
		$settings['reality_use_departments'] = false;
	} else {
		$settings['reality_use_departments'] = true;
	}
	
	reality_set_department();
	reality_update_all_user_points();
	
	$settings['reality_audience_award_options'] = $audience_awards;
	
	if ( isset( $reality_instances ) ) {
		$settings['reality_game_instances'] = $reality_instances;
	} else {
		$settings['reality_game_instances'] = '';
	}
	
	reality_check_if_running();

} else {

	$settings['reality_audience_award_options'] = get_option( 'reality_audience_award_options' );
	$settings['reality_404_content'] = get_option( 'reality_404_content' );
	$settings['reality_404_content'] = str_replace( '\\', '', $settings['reality_404_content'] );

	$settings['reality_game_instances'] = get_option( 'reality_game_instances' );
	$settings['reality_is_currently_running'] = get_option( 'reality_is_currently_running' );
	$settings['reality_submit_form_author_autosuggest'] = get_option( 'reality_submit_form_author_autosuggest' );
	$settings['reality_submit_form_card_autosuggest'] = get_option( 'reality_submit_form_card_autosuggest' );
	
	$settings['reality_weekly_leaderboard_reset'] = get_option( 'reality_weekly_leaderboard_reset' );
	
	$settings['reality_card_navigation'] = get_option( 'reality_card_navigation' );
	
	$settings['reality_use_departments'] = get_option( 'reality_use_departments' );
	$settings['reality_player_departments'] = get_option( 'reality_player_departments' );
}

?>

<?php if ( !empty( $messages ) ) : ?>
	<div class="messages">
	
		<?php foreach( $messages as $message ) : ?>
		
			<div class="message"><?php echo $message; ?></div>
		
		<?php endforeach; ?>
	
	</div>
<?php endif; ?>

<form method="post" id="reality-main-menu">

<div id="main-menu-tabs">

	<ul>
		<li><a href="#general-settings"><?php _e( 'General Settings', 'Reality' ); ?></a></li>
		<li><a href="#gameplay-seasons"><?php _e( 'Setup Seasons', 'Reality' ); ?></a></li>
		<li><a href="#deal-settings"><?php _e( 'Deal Settings', 'Reality' ); ?></a></li>
		<li><a href="#404-content"><?php _e( '404 Page Content', 'Reality' ); ?></a></li>
	</ul>
	
	<div id="general-settings">
		<h3><?php _e('General Reality Settings', 'Reality'); ?></h3>
		
		<h4><?php _e( 'Departments', 'Reality' ); ?></h4>
		<table>
			<tbody>
				<tr>
					<td class="setting-name">
						<h5><strong><?php _e( 'User Departments?', 'Reality' ); ?></strong></h5>
						<p><?php _e( 'Would you like to have different departments for the players?.', 'Reality' ); ?></p>
					</td>
					<td class="setting-input">
						<?php $settings['reality_use_departments'] ? $use_departments = 1 : $use_departments = 0 ; ?>
						<input type="checkbox" name="reality_main_settings[reality_use_departments]" id="reality_use_departments" value="1"<?php if ( $use_departments ) echo ' checked'; ?>>
					</td>
				</tr>
				<tr id="add-departments">
					<td class="setting-name">
						<h5><strong><?php _e('Departments', 'Reality' ); ?></strong></h5>
					</td>
					<td class="setting-input">
						<?php isset( $settings['reality_player_departments'] ) ? $departments = $settings['reality_player_departments'] : $departments = 0; ?>
						<?php if ( is_array( $departments ) ) : ?>
							<?php foreach( $departments as $slug => $department ) : ?>
							<div class="department">
								<input type="text" name="reality_main_settings[reality_player_departments][]" class="reality_player_departments" value="<?php echo $department; ?>">
								<input type="text" disabled class="reality_player_departments" value="<?php echo $slug; ?>"> | <a href="#" class="delete-department" title="Delete Department">Delete</a><br>
							</div>
							<?php endforeach; ?>
						<?php endif; ?>
						<input type="text" name="reality_main_settings[reality_player_departments][]" class="reality_player_departments" placeholder="New department name..."><br>
						<input type="text" name="reality_main_settings[reality_player_departments][]" class="reality_player_departments hidden" placeholder="New department name...">
						<a href="#" class="add-department" title="Add New Department">Add</a>
					</td>
				</tr>
			</tbody>
		</table>
		
		<h4><?php _e( 'Submission Form Settings', 'Reality' ); ?></h4>
		<table>
			<tbody>
				<tr>
					<td class="setting-name">
						<h5><strong><?php _e( 'Name Autosuggest Characters', 'Reality' ); ?></strong></h5>
						<p><?php _e( 'The number of characters a user must type in before the autosuggest kicks in for the authors form.', 'Reality' ); ?></p>
					</td>
					<td class="setting-input">
						<?php isset( $settings['reality_submit_form_author_autosuggest'] ) ? $author_auto = $settings['reality_submit_form_author_autosuggest'] : $author_auto = 2 ; ?>
						<input type="text" name="reality_main_settings[reality_submit_form_author_autosuggest]" id="reality_submit_form_author_autosuggest" value="<?php echo $author_auto; ?>">
					</td>
				</tr>
				<tr>
					<td class="setting-name">
						<h5><strong><?php _e(' Card Autosuggest Characters', 'Reality' ); ?></strong></h5>
						<p><?php _e( 'The number of characters a user must type in before the autosuggest kicks in for the add card form.', 'Reality' ); ?></p>
					</td>
					<td class="setting-input">
						<?php isset( $settings['reality_submit_form_card_autosuggest'] ) ? $card_auto = $settings['reality_submit_form_card_autosuggest'] : $card_auto = 3; ?>
						<input type="text" name="reality_main_settings[reality_submit_form_card_autosuggest]" id="reality_submit_form_card_autosuggest" value="<?php echo $card_auto; ?>">
					</td>
				</tr>
			</tbody>
		</table>
		
		<h4>Other Options</h4>
		<table>
			<tbody>
				<tr>
					<td class="setting-name">
						<h5><strong><?php _e( 'Display Card Navigation?', 'Reality' ); ?></strong></h5>
					</td>
					<td class="setting-input">
						<?php isset( $settings['reality_card_navigation'] ) ? $card_nav = $settings['reality_card_navigation'] : $card_nav = 0 ; ?>
						<label>
							<input type="radio" name="reality_main_settings[reality_card_navigation]" id="reality_card_navigation" value="1"<?php if ( $card_nav ) echo ' checked'; ?>> Yes
						</label>
						<label>
							<input type="radio" name="reality_main_settings[reality_card_navigation]" id="reality_card_navigation" value="0"<?php if ( !$card_nav ) echo ' checked'; ?>> No
						</label>
					</td>
				</tr>
			</tbody>
		</table>
		
		<input type="submit" value="Update Options">
	</div>
	<div id="gameplay-seasons">
		<h3><?php _e( 'Setup Game Seasons', 'Reality' ); ?></h3>
		<p><strong><?php _e( 'Seasons must not overlap.', 'Reality' ); ?></strong></p>
		<table>
			<thead>
				<th><?php _e( 'Season Name', 'Reality' ); ?></th>
				<th><?php _e( 'Season Slug', 'Reality' ); ?></th>
				<th><?php _e( 'Season Start Date', 'Reality' ); ?></th>
				<th><?php _e( 'Season End Date', 'Reality' ); ?></th>
				<th><?php _e( 'Season Info', 'Reality' ); ?></th>
				<th><?php _e( 'Delete Season', 'Reality' ); ?></th>
			</thead>
			
			<tbody>
				<?php $gameInstances = $settings['reality_game_instances']; ?>
				<?php $count = 1; ?>
				<?php if ( !empty( $gameInstances ) ) : ?>
				
					<?php if ( $settings['reality_is_currently_running'] ) : ?>
						<?php foreach( $settings['reality_is_currently_running'] as $slug => $name ) : ?>
						<?php $instance = $gameInstances[$slug]; ?>
						<tr>
							<th colspan=4><?php _e( 'Current Season', 'Reality' ); ?></th>
						</tr>
						<tr>
							<td>
								<input type="text" name="reality_main_settings[reality_game_instances][current][name]" id="reality_game_instances_name" placeholder="Season Name" value="<?php echo $instance['name'] ?>">
							</td>
							<td>
								<input type="text" id="reality_game_instances_slug" name="reality_main_settings[reality_game_instances][current][slug]" placeholder="Auto Generated" value="<?php echo $instance['slug'] ?>" disabled>
							</td>
							<td>
								<input type="text" class="startdate" name="reality_main_settings[reality_game_instances][current][startdate]" value="<?php echo date( 'm/d/Y', $instance['startdate']) ?>" placeholder="Start Date" >
							</td>
							<td>
								<input type="text" class="enddate" name="reality_main_settings[reality_game_instances][current][enddate]" value="<?php echo date( 'm/d/Y', $instance['enddate']) ?>" placeholder="End Date" >
							</td>
							<td>
								<?php if ( isset ( $instance['weeks'] ) ) : ?>
									<?php $weeks = $instance['weeks']; ?>
									<p><?php echo count($weeks); ?> <?php _e( 'gameplay weeks', 'Reality' ); ?>.</p>
								<?php endif; ?>
								<p><?php _e('Current Week', 'Reality'); ?>: <?php echo $reality->current_week; ?></p>
							</td>
							<td style="text-align:center;">
								<a href="#" onClick="jQuery(this).parent().parent().remove();">Delete Season</a>
							</td>
						</tr>
					
						<?php unset( $gameInstances[$slug] ); ?>
						<?php endforeach; ?>
					<?php endif; ?>
				
					<?php $futureCount = 0; ?>
					<?php $pastCount = 0; ?>
				
					<?php foreach( $gameInstances as $instance ) : ?>
				
						<?php if ( time() < $instance['startdate'] && !$futureCount ) : ?>
							<tr>
								<th colspan=4>Future Seasons</th>
							</tr>
							<?php $futureCount++; ?>
						<?php endif; ?>
						
						<?php if ( time() > $instance['enddate'] && !$pastCount ) : ?>
							<tr>
								<th colspan=4>Past Seasons</th>
							</tr>
							<?php $pastCount++; ?>
						<?php endif; ?>
				
						<tr>
							<td>
								<input type="text" name="reality_main_settings[reality_game_instances][<?php echo $count; ?>][name]" id="reality_game_instances_name" placeholder="Season Name" value="<?php echo $instance['name'] ?>">
							</td>
							<td>
								<input type="text" id="reality_game_instances_slug" name="reality_main_settings[reality_game_instances][<?php echo $count; ?>][slug]" placeholder="Auto Generated" value="<?php echo $instance['slug'] ?>" disabled>
							</td>
							<td>
								<input type="text" class="startdate" name="reality_main_settings[reality_game_instances][<?php echo $count; ?>][startdate]" value="<?php echo date( 'm/d/Y', $instance['startdate']) ?>" placeholder="Start Date" >
							</td>
							<td>
								<input type="text" class="enddate" name="reality_main_settings[reality_game_instances][<?php echo $count; ?>][enddate]" value="<?php echo date( 'm/d/Y', $instance['enddate']) ?>" placeholder="End Date" >
							</td>
							<td>
								<?php if ( isset ( $instance['weeks'] ) ) : ?>
									<?php $weeks = $instance['weeks']; ?>
									<p><?php echo count($weeks); ?> gameplay weeks.</p>
								<?php endif; ?>
							</td>
							<td>
								<a href="#" onClick="jQuery(this).parent().parent().remove();">Delete Season</a>
								
							</td>
						</tr>
					
						<?php $count++; ?>
				
					<?php endforeach; ?>
				
				<?php endif; ?>
				<tr>
					<th colspan=4>Add New Season</th>
				</tr>
				<tr>
					<td>
						<input type="text" name="reality_main_settings[reality_game_instances][<?php echo $count; ?>][name]" id="reality_game_instances_name" placeholder="Season Name" >
					</td>
					<td>
						<input type="text" id="reality_game_instances_slug" placeholder="Auto Generated" disabled>
					</td>
					<td>
						<input type="text" class="startdate" name="reality_main_settings[reality_game_instances][<?php echo $count; ?>][startdate]" placeholder="Start Date" >
					</td>
					<td>
						<input type="text" class="enddate" name="reality_main_settings[reality_game_instances][<?php echo $count; ?>][enddate]" placeholder="End Date" >
					</td>
					<td>
					</td>
				</tr>
			
			</tbody>
		</table>
		
		<h3><?php _e( 'Weekly Leader Board Reset', 'Reality' ); ?></h3>
		<p><?php _e( 'Which day of the week do weekly leader boards reset on?', 'Reality' ); ?></p>
		
		<select name="reality_main_settings[reality_weekly_leaderboard_reset]" id="reality_weekly_leaderboard_reset">
			<option value="Monday"<?php if ( $settings['reality_weekly_leaderboard_reset'] == 'Monday' ) { echo ' selected'; } ?>>Monday</option>
			<option value="Tuesday"<?php if ( $settings['reality_weekly_leaderboard_reset'] == 'Tuesday' ) { echo ' selected'; } ?>>Tuesday</option>
			<option value="Wednesday"<?php if ( $settings['reality_weekly_leaderboard_reset'] == 'Wednesday' ) { echo ' selected'; } ?>>Wednesday</option>
			<option value="Thursday"<?php if ( $settings['reality_weekly_leaderboard_reset'] == 'Thursday' ) { echo ' selected'; } ?>>Thursday</option>
			<option value="Friday"<?php if ( $settings['reality_weekly_leaderboard_reset'] == 'Friday' ) { echo ' selected'; } ?>>Friday</option>
			<option value="Saturday"<?php if ( $settings['reality_weekly_leaderboard_reset'] == 'Saturday' ) { echo ' selected'; } ?>>Saturday</option>
			<option value="Sunday"<?php if ( $settings['reality_weekly_leaderboard_reset'] == 'Sunday' ) { echo ' selected'; } ?>>Sunday</option>
		</select>
		
		<input type="submit" value="Update Options">
		
	</div>
	
	<!-- DEAL SETTINGS -->
	
	<div id="deal-settings">
		<h3>Deal Settings</h3>
		<p>Adjust these settings to control deal related functionality.</p>
		
		<h4>Audience Award Options</h4>
		
		<div id="audience-award-options">
	
			<?php $count = 0; ?>
			<?php $audienceAwards = $settings['reality_audience_award_options']; ?>
			<table>
				<thead>
					<tr>
						<th>Award Name</th><th>Award Slug</th>
					</tr>
				</thead>
				<tbody>
			<?php if ( !empty( $audienceAwards ) ) : ?>
				<?php foreach( $audienceAwards as $key => $audienceAward ) : ?>
					<tr>
						<td><input type="text" class="audience_award_option" name="reality_main_settings[reality_audience_award_options][<?php echo $count; ?>]" value="<?php echo $audienceAward; ?>"></td>
						<td><input type="text" value="<?php echo $key; ?>" disabled></td>
					</tr>
					<?php $count++; ?>
				<?php endforeach; ?>
			<?php endif; ?>
					<tr>
						<td><input type="text" placeholder="Add new award..." class="audience_award_option" name="reality_main_settings[reality_audience_award_options][<?php echo $count; ?>]" value=""></td>
						<td><input type="text" placeholder="Automatically Generated" disabled></td>
					</tr>
				</tbody>
			</table>
			
		
		</div>
		
		<input type="submit" value="Update Options">
		
	</div>
	
	<div id="404-content">
		<h3>404 Page Content</h3>
		<?php $editorSettings = array(
			'textarea_name'	=>	'reality_main_settings[reality_404_content]'
		); ?>
		<?php wp_editor( $settings['reality_404_content'], 'reality_404_content_editor', $editorSettings ); ?>
		
		<input type="submit" value="Update Options">
	</div>
	
	<input type="hidden" name="reality_main_settings_update" value="Y" >

</div>