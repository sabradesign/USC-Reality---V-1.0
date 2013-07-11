<?php
	
	if ( isset($_POST['reality_ranks_updated']) && $_POST['reality_ranks_updated'] == 'Y') {
	
		if ( isset($_POST['ranks_value']) ) {
		
			$ranks = $_POST['ranks_value'];
			$save_ranks = array();
			
			foreach( $ranks as $key => $rank ) {
				if ( $rank['rank_points'] == '' ) $rank['rank_points'] = (int) 0;
				
				if ( $rank['rank_name'] == '' ) {
					unset($ranks[$key]);
				} else {
					$rank['rank_slug'] = sanitize_title_with_dashes($rank['rank_name']);
					$ranks[$key]['rank_slug'] = $rank['rank_slug'];
					$save_ranks[$rank['rank_points']]['rank_name'] = $rank['rank_name'];
					$save_ranks[$rank['rank_points']]['rank_slug'] = $rank['rank_slug'];
				}
				
				
				
			}
			
			delete_option( 'reality_rank_values' );
			
			ksort( $save_ranks );
			
			if ( update_option( 'reality_rank_values', $save_ranks ) ) {
				
				reality_update_all_user_points();
				$message = 'Rank values saved successfully!';
				
			} else {
			
				$message = 'Error saving rank values...';
			
			}
		
		}
	
	} else {
	
		$retrieved_ranks = get_option( 'reality_rank_values' );
		$ranks = array();
		
		ksort( $retrieved_ranks );
		
		$count = 0;
		
		foreach( $retrieved_ranks as $key => $rank ) {
			$ranks[$count]['rank_name'] = $rank['rank_name'];
			$ranks[$count]['rank_slug'] = $rank['rank_slug'];
			$ranks[$count]['rank_points'] = $key;
			
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

<?php if ( isset($message) && $message != '' ) : ?>

	<div class="message"><?php echo $message; ?></div>

<?php endif; ?>

<form id="reality_ranks_options_form" method="post">
	<table>
		<thead>
			<tr>
				<th>Rank Name</th>
				<th>Rank Slug</th>
				<th>Points Needed</th>
				<th>Delete Activity</th>
			</tr>
		</thead>
		<tbody>
	
		<?php $count = 0; ?>
		<?php if ( !empty( $ranks ) ) : ?>
		<?php foreach( $ranks as $key => $rank ) : ?>
		
			<tr class="reality_points_options_row">
				<td>
					<input type="text" class="rank_name" name="ranks_value[<?php echo $count; ?>][rank_name]" value="<?php echo stripslashes($rank['rank_name']); ?>">
				</td>
				<td>
					<input type="text" class="rank_slug" name="ranks_value[<?php echo $count; ?>][rank_slug]" value="<?php echo $rank['rank_slug']; ?>" disabled>
				</td>
				<td>
					<input type="text" class="rank_points" name="ranks_value[<?php echo $count; ?>][rank_points]" value="<?php echo $rank['rank_points']; ?>"<?php if (!$admin) echo ' DISABLED'; ?>>
				</td>
				<td>
					<?php if ( $admin ) : ?>
						<a href="#" title="Delete this activity." onClick="if ( confirm('Delete row?') ) { jQuery(this).parent().parent().remove(); }" class="delete_row">Delete</a>
					<?php endif; ?>
				</td>
			</tr>
			
			<?php $count++; ?>
		
		<?php endforeach; ?>
		<?php endif; ?>
	
		<tr class="reality_points_options_row">
			<td>
				<input type="text" class="rank_name" name="ranks_value[<?php echo $count; ?>][rank_name]" value="">
			</td>
			<td>
					<input type="text" class="rank_slug" name="ranks_value[<?php echo $count; ?>][rank_slug]" value="" disabled>
				</td>
			<td>
				<input type="text" class="rank_points" name="ranks_value[<?php echo $count; ?>][rank_points]" value="">
			</td>
			<td>
				<a href="#" title="Delete this Rank." class="delete_row" onClick="if ( confirm('Delete row?') ) { jQuery(this).parent().parent().remove(); }">Delete</a>
			</td>
		</tr>
		</tbody>
	</table>
	<input type="hidden" name="reality_ranks_updated" value="Y">
	<input type="submit" value="Submit">
	<?php if ( $admin ) : ?>
		<div class="add_rows">
			<a href="#" class="add_row">Add Row</a>
		</div>
	<?php endif; ?>
</form>
</div>