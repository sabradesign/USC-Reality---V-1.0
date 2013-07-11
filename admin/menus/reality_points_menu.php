<?php
	
	if ( isset($_POST['reality_points_updated']) && $_POST['reality_points_updated'] == 'Y') {
	
		if ( isset($_POST['points_value']) ) {
		
			$points = $_POST['points_value'];
			$save_points = array();
			
			foreach( $points as $key => $point ) {
				if ( $point['activity_value'] == '' ) $point['activity_value'] = (int) 0;
				
				if ( $point['activity_name'] == '' || $point['activity_slug'] == '' ) {
					unset($points[$key]);
				} else {
					$save_points[$point['activity_slug']]['name'] = $point['activity_name'];
					$save_points[$point['activity_slug']]['value'] = (int) $point['activity_value'];
				}
				
				
				
			}
			
			delete_option( 'reality_activity_point_values' );
			
			if ( update_option( 'reality_activity_point_values', $save_points ) ) {
				
				$message = 'Point values saved successfully!';
				
				$retrieved_points = $save_points;
				
			} else {
			
				$message = 'Error saving points values...';
			
			}
		
		}
	
	} else {
	
		$retrieved_points = get_option( 'reality_activity_point_values' );
		$retrieved_points = maybe_unserialize( $retrieved_points );
		$points = array();
		
		$count = 0;
		
		foreach( $retrieved_points as $key => $point ) {
			$points[$count]['activity_name'] = $point['name'];
			$points[$count]['activity_slug'] = $key;
			$points[$count]['activity_value'] = $point['value'];
			
			$count++;
		}
	
	}
	
	if ( current_user_can('administrator') ) {
		$admin = true;
	} else {
		$admin = false;
	}

?>

<div class="wrap">
<h2>Reality Points Menu</h2>
<p>Add or edit points values on this page</p>

<?php global $bp; ?>
<?php $avail_activities = $bp->activity->actions->activity; ?>

<?php if ( isset($message) && $message != '' ) : ?>

	<div class="message"><?php echo $message; ?></div>

<?php endif; ?>

<form id="reality_points_options_form" method="post">
	<table>
		<thead>
			<tr>
				<th>Activity Name</th>
				<th>Activity Slug</th>
				<th>Activity Value</th>
			</tr>
		</thead>
		<tbody>
	
		<?php $count = 0; ?>
		<?php //foreach( $points as $key => $point ) : ?>
		<?php foreach( $avail_activities as $key => $value ) : ?>
		
			<tr class="reality_points_options_row">
				<td>
					<input type="text" class="activity_name" name="points_value[<?php echo $count; ?>][activity_name]" value="<?php echo $value['value']; ?>">
				</td>
				<td>
					<input type="text" class="activity_slug" value="<?php echo $value['key']; ?>" DISABLED>
					<input type="hidden" class="activity_slug" name="points_value[<?php echo $count; ?>][activity_slug]" value="<?php echo $value['key']; ?>">
				</td>
				<td>
					<?php isset( $retrieved_points[$value['key']]['value'] ) ? $point_value = $retrieved_points[$value['key']]['value'] : $point_value = '0'; ?>
					<input type="text" class="activity_value" name="points_value[<?php echo $count; ?>][activity_value]" value="<?php echo $point_value; ?>">
				</td>
			</tr>
			
			<?php $count++; ?>
		
		<?php endforeach; ?>
		
		</tbody>
	</table>
	<input type="hidden" name="reality_points_updated" value="Y">
	<input type="submit" value="Submit">
	<?php if ( $admin ) : ?>
		<div class="add_rows">
			<a href="#" class="add_row">Add Row</a>
		</div>
	<?php endif; ?>
</form>
</div>