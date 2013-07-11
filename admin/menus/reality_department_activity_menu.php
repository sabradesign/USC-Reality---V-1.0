<?php

	if ( isset($_GET['action']) ) {
	
		switch( $_GET['action'] ) {
			case 'calculate_activity':
			
				// SETUP DEPT ACTIVITIES ARRAY
				//$departments = get_option('reality_player_departments');
				global $wp_roles;
				$departments = $wp_roles->roles;
				
				$dept_activities = array();
				$dept_deals = array();
				
				if ( is_array( $departments ) ) {
		
					foreach( $departments as $slug => $dept ) {
			
						if ( $slug != 'administrator' && $slug != 'game-master' ) {
							$dept_activities[$slug] = array();
							$dept_deals[$slug] = array();
						}
			
					}		
				}
			
// 				$filter = array(
// 					'user_id'		=>	$user_id
// 				);
		 
				$deal_template_args = array(
					'page'             => 1,
					'per_page'         => 9999999,
					'show_hidden'      => true
				);
		 
				$deal_activities = new BP_Activity_Template( $deal_template_args );
				
				if ( $deal_activities->has_activities() ) {
				
					while ( $deal_activities->user_activities() ) {
    		
						$deal_activities->the_activity();
						
						if ( $deal_activities->activity->user_id ) {
						
							$author = new WP_User( $deal_activities->activity->user_id );
						
							$user_depts = $author->roles;
							
							if ( isset( $user_depts[0] ) && $user_depts[0] != 'administrator' && $user_depts[0] != 'game-master' ) {
							
								$user_dept = $user_depts[0];
							
							} else {
							
								$user_dept = false;
							
							}
						
							if ( $user_dept != false && !array_search( $deal_activities->activity->id, $dept_activities[$user_dept] ) ) {
						
								$dept_activities[$user_dept][] = $deal_activities->activity->id;
							
							}
						
						} else {
						
							$deal = get_post( $deal_activities->activity->item_id );
							
							if ( $deal && $deal->post_type == 'reality_deals' ) {
							
								$authors = wp_get_object_terms( $deal->ID, 'authors-tax' );
								
								foreach( $authors as $author_tax ) {
								
									$author = new WP_User( $author_tax->slug );
									$user_depts = $author->roles;
									
									if ( isset( $user_depts[0] ) && $user_depts[0] != 'administrator' && $user_depts[0] != 'game-master' ) {
							
										$user_dept = $user_depts[0];
							
									} else {
							
										$user_dept = false;
							
									}
									
									if ( $user_dept != false && !array_search( $deal_activities->activity->id, $dept_activities[$user_dept] ) ) {
						
										$dept_activities[$user_dept][] = $deal_activities->activity->id;
										$dept_deals[$user_dept][] = $deal->ID;
							
									}
								
								}
							
							}
						
						}
			
					}
				
				}
				
				foreach( $dept_activities as $dept => $activities ) {
				
					update_option( 'REALITY_department_activity-'.$dept, $activities );
					update_option( 'REALITY_department_deals-'.$dept, $dept_deals[$dept] );
				
				}
			
				break;
		}
	
	}

?>

<div class="wrap">
<h2>Reality Department Activity</h2>
<p>Monitor Department Specific Activity Here</p>
<p><a href="?page=reality_department_activity&action=calculate_activity" class="button">Recalculate Activity</a></p>

<?php if ( get_option('reality_use_departments') ) : ?>
<div>
	
	<h3>Departments</h3>
	<div class="dept_nav">
		<?php $departments = get_option('reality_player_departments'); ?>
		<?php global $wp_roles; ?>
		<?php $roles = $wp_roles->roles; ?>
		
		<?php if ( is_array( $roles ) ) : ?>
		
			<a href="?page=reality_department_activity&dept=all" class="button<?php if ( !isset($_GET['dept']) || ( isset($_GET['dept']) && $_GET['dept'] == 'all' ) ) echo ' active'; ?>">All</a> 
			
			<?php foreach( $roles as $slug => $dept ) : ?>
				
				<?php if ( in_array( $dept['name'], $departments ) ) : ?>
			
					<a href="?page=reality_department_activity&dept=<?php echo $slug; ?>" class="button<?php if (  isset($_GET['dept']) && $_GET['dept'] == $slug  ) echo ' active'; ?>"><?php echo $dept['name']; ?></a> 
				
				<?php endif; ?>
			
			<?php endforeach; ?>
		
		<?php endif; ?>
	</div>
	
</div>
<?php endif; ?>

<div>
	<h3>Activity Types</h3>
	<!-- Activity Types go here -->
	
	<?php $activity_types = bp_activity_get_types(); ?>
	
	<?php foreach( $activity_types as $type ) : ?>
	
		<!-- 
<a href="?page=reality_department_activity&dept=<?php echo $_GET['dept']; ?>&activity=<?php echo $type?>"><?php echo $type; ?></a> | 
	
 -->
	<?php endforeach; ?>
	
</div>

<div>
	<h3>Activity</h3>

<table class="widefat fixed activities" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></th><th scope="col" id="author" class="manage-column column-author" style="">Author</th><th scope="col" id="comment" class="manage-column column-comment" style="">Activity</th><th scope="col" id="response" class="manage-column column-response" style="">In Response To</th>				</tr>
			</thead>

			<tfoot>
				<tr>
					<th scope="col" class="manage-column column-cb check-column" style=""><label class="screen-reader-text" for="cb-select-all-2">Select All</label><input id="cb-select-all-2" type="checkbox"></th><th scope="col" class="manage-column column-author" style="">Author</th><th scope="col" class="manage-column column-comment" style="">Activity</th><th scope="col" class="manage-column column-response" style="">In Response To</th>				</tr>
			</tfoot>

			<tbody id="the-comment-list">
				
				<?php 
				
					if ( isset( $_GET['dept'] ) && $_GET['dept'] != 'administrator' && $_GET['dept'] != 'game-master' ) {
					
						$in_array = get_option( 'REALITY_department_activity-'.$_GET['dept'] );
						$in = implode( ',', $in_array );
					
					} else {
					
						$in = '';
					
					}
				
				?>
				
				<?php $deal_template_args = array(
					'page'             => 1,
					'per_page'         => 9999999,
					'show_hidden'      => true,
					'in'			   => $in
				);
		 
				$deal_activities = new BP_Activity_Template( $deal_template_args ); ?>
				
				<?php if ( $deal_activities->has_activities() ) : ?>
				
					<?php while ( $deal_activities->user_activities() ) : ?>
					
						<?php $deal_activities->the_activity(); ?>
			
						<tr class="alternate" id="activity-<?php echo $deal_activities->activity->id ?>" data-parent_id="2794" data-root_id="0">
							<th scope="row" class="check-column"><input type="checkbox" name="aid[]" value="<?php echo $deal_activities->activity->id ?>"></th>
							<td class="author column-author"><strong><img src="http://uscreality/wp-content/uploads/avatars/1/af506eb935e9504d42c9b877e94a072e-bpthumb.jpg" class="avatar user-1-avatar avatar-32 photo" width="32" height="32" alt="Avatar of Konstantin Brazhnik">
								<a href="http://uscreality/members/sbr/" title="Konstantin Brazhnik">Konstantin Brazhnik</a></strong>
							</td>
							<td class="comment column-comment">
								<div class="submitted-on">Submitted on <a href="http://uscreality/activity/p/<?php echo $deal_activities->activity->id ?>/"><?php echo date( 'F j, Y', strtotime($deal_activities->activity->date_recorded) ); ?> at <?php echo date( 'g:i a', strtotime($deal_activities->activity->date_recorded) ); ?></a></div>
								<p><a href="http://www.youtube.com/watch?v=rn_YodiJO6k" rel="nofollow">http://www.youtube.com/watch?v=rn_YodiJO6k</a></p>
								<div class="row-actions">
									<span class="reply"><a href="#" class="reply hide-if-no-js">Reply</a> | </span>
									<span class="edit"><a href="http://uscreality/wp-admin/admin.php?page=bp-activity&amp;aid=<?php echo $deal_activities->activity->id ?>&amp;action=edit">Edit</a> | </span>
									<span class="spam"><a href="http://uscreality/wp-admin/admin.php?page=bp-activity&amp;aid=<?php echo $deal_activities->activity->id ?>&amp;action=spam&amp;_wpnonce=e007cb1377">Spam</a> | </span>
									<span class="delete"><a href="http://uscreality/wp-admin/admin.php?page=bp-activity&amp;aid=<?php echo $deal_activities->activity->id ?>&amp;action=delete&amp;_wpnonce=e007cb1377" onclick="javascript:return confirm('Are you sure?'); ">Delete Permanently</a></span>
								</div>
							</td>
							<td class="response column-response">
								<a href="http://uscreality/activity/p/<?php echo $deal_activities->activity->id ?>/">View Activity</a>
							</td>
						 </tr>	
						 		
					<?php endwhile; ?>
				
				<?php endif; ?>
			
			</table>
		
</div>

</div>