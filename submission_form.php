<?php
/**
 * Template Name: Reality Submission Form
 */

get_header(); ?>

<?php
	global $bp, $deal_submission_status;
	if ( isset($_POST['maker_card_id']) ) {
		$maker_card_id = $_POST['maker_card_id']; ?>
		
		<?php if ( reality_is_maker_card( $maker_card_id ) ) : ?>
		
		<script>
		
			jQuery(document).ready( function($) {
					$('#submission-form-tabs').tabs( "option", "disabled", [] );
					
					<?php if ( isset($deal_submission_status['errors'][1]) ) : ?>
					
						$('#submission-form-tabs').tabs( "option", "active", 1 );
					
					<?php elseif ( isset($deal_submission_status['errors'][2]) ) : ?>
					
						$('#submission-form-tabs').tabs( "option", "active", 2 );
					
					<?php elseif ( isset($deal_submission_status['errors'][3]) ) : ?>
					
						$('#submission-form-tabs').tabs( "option", "active", 3 );
					
					<?php elseif ( isset($deal_submission_status['errors'][4]) ) : ?>
					
						$('#submission-form-tabs').tabs( "option", "active", 4 );
					
					<?php elseif ( isset($deal_submission_status['errors'][5]) ) : ?>
					
						$('#submission-form-tabs').tabs( "option", "active", 5 );
					
					<?php else : ?>
					
						$('#submission-form-tabs').tabs( "option", "active", 1 );
					
					<?php endif; ?>
			});
		
		</script>
		
		<?php else : ?>
		
			<?php $maker_card_error = 'Either this card does not exist or it is not a maker card.'; ?>
			<?php $maker_card_id = false; ?>
		
		<?php endif; ?>
		
	<?php } ?>

	<div id="primary" class="site-content wrapper">
		<div id="content" role="main">
		
		<?php while( have_posts() ) : the_post(); ?>
		
		
		<?php if ( empty( $deal_submission_status['success'] ) ) : ?>
			<?php if ( !empty( $deal_submission_status['errors'] ) ) : ?>
				<div class="messages">
					<?php $errors = $deal_submission_status['errors']; ?>
					<?php foreach ( $errors as $error ) : ?>
					
						<div class="message">
							<?php echo $error; ?>
						</div>
					
					<?php endforeach; ?>
				
				</div>
			<?php endif; ?>

			<form method="post" id="deal-submission-form" enctype="multipart/form-data">
			
				<div id="submission-form-tabs">
  					<ul>
   						<li class="tab-maker-card"><a href="#maker-card"><?php _e('Maker Card', 'Reality' ); ?></a></li>
    					<li class="tab-deal-title"><a href="#deal-title"><?php _e('Title', 'Reality' ); ?></a></li>
    					<li class="tab-log-line"><a href="#log-line"><?php _e('Log Line', 'Reality' ); ?></a></li>
    					<li class="tab-video-url"><a href="#evidence"><?php _e( 'Evidence', 'Reality' ); ?></a></li>
    					<li class="tab-collaborators"><a href="#collaborators"><?php _e( 'Collaborators', 'Reality' ); ?></a></li>
    					<li class="tab-cards-and-notes"><a href="#cards-and-notes"><?php _e( 'Cards & Notes', 'Reality' ); ?></a></li>
  					</ul>
 					<div class="clear"></div>
  					<div id="maker-card">
						<label class="required"><?php _e('Maker Card', 'Reality'); ?></label>
						<div class="message">
						<?php if ( isset($maker_card_error) ) : ?>
							<?php echo $maker_card_error; ?>
						<?php endif; ?>
						</div>
						<div class="input-container">
							<input type="text" name="maker_card_id" id="maker_card_id" placeholder="ex. 11008"<?php if ( $maker_card_id ) echo ' value="'.$maker_card_id.'" disabled'; ?>>
						</div> 
					</div>
				  	<div id="deal-title">
    					<label class="required"><?php _e('Deal Title', 'Reality'); ?></label>
    					<div class="input-container">
    						<?php if ( isset( $_POST['deal_title'] ) && $_POST['deal_title'] != '' ) {
    								$deal_title = $_POST['deal_title']; 
    							} else {
    								$deal_title = '';
    							} ?>
							<input type="text" name="deal_title" id="deal_title" placeholder="Deal Title" value="<?php echo $deal_title ?>">
    					</div>
    				</div>
  					<div id="log-line">
    					<label class="required"><?php _e('Deal Logline', 'Reality'); ?></label>
    					<div class="input-container">
    						<?php if ( isset( $_POST['deal_logline'] ) && $_POST['deal_logline'] != '' ) { $deal_logline = $_POST['deal_logline']; } else { $deal_logline = ''; } ?>
							<textarea name="deal_logline" id="deal_logline" class="max-char" maxChar="300"><?php echo $deal_logline; ?></textarea>
    						<div class="input-hint">
  								<span class="blue"><span class="char-count">0</span> <?php _e('of', 'Reality')?> <span class="char-max">300</span> <?php _e('max characters', 'Reality') ?>.</span>
  							</div>
    					</div>
    				</div>
  					<div id="evidence">
  						<table>
  							<tr>
  								<td>
  									<label class="required"><?php _e('Thumbnail Image', 'Reality'); ?></label>
  									<p>This will be the thumbnail that people see on the site.</p>
  								</td>
  								<td>
  									<div class="input-container">
  										<input type="file" name="deal_featured_image" id="deal_featured_image" required accept="image/*">
  									</div>
  								</td>
  							</tr>
  							<tr>
  								<td>
  									<label><?php _e('YouTube ID', 'Reality'); ?></label>
  									<p class="input-hint">Please input the YouTube video id.
  										<br>
  										The ID is the string of characters that comes after the "http://www.youtube.com/watch?v=."
  										<br>
  										<br>
  										After you enter the ID, click away from the input field and the video will show up as a preview.
  									</p>
  								</td>
  								<td>
  									<div class="input-container">
  										<?php ( isset( $_POST['deal_youtube_id'] ) && $_POST['deal_youtube_id'] != '' ) ? $deal_youtube_id = $_POST['deal_youtube_id'] : $deal_youtube_id = ''; ?>
										<input type="text" name="deal_youtube_id" id="deal_youtube_id" placeholder="ex. yCl_8522FF0" value="<?php echo $deal_youtube_id; ?>">
  									</div>
  								</td>
  							</tr>
  							<tr>
  								<td>					
  									<label><?php _e('Other Deal Images', 'Reality'); ?></label>
  									<p class="input-hint">If this deal requires any additional images, please upload them here.</p>
  								</td>
  								<td>
  									<div class="input-container">
  										<input type="file" name="deal_images[]" id="deal_images" multiple accept="image/*">
  									</div>
  								</td>
  							</tr>
  							<tr>
  								<td>  						
  									<label><?php _e('File Upload', 'Reality'); ?></label>
  									<p class="input-hint">If you are submitting any additional files for this deal, upload them here.</p>
  								</td>
  								<td>
  									<div class="input-container">
  										<input type="file" name="deal_files[]" id="deal_files" multiple>
  									</div>
  								</td>
  							</tr>
  						</table>
  					</div>
  					<div id="collaborators">
  						<label><?php _e('Collaborators', 'Reality'); ?></label>
  						<div id="deal_collaborators_container" class="input-container">
  							<input type="text" name="deal_collaborators_selector" id="deal_collaborators">
  						</div>
  						
  						<ul id="deal-collaborators-preview">
  							<li>
  								<?php bp_loggedin_user_avatar( 'width=100&height=100' ); ?>
  								<div class="user_name"><?php echo $bp->loggedin_user->fullname ?></div>
  								<input type="hidden" name="deal_collaborators[]" value="<?php echo $bp->loggedin_user->fullname ?>">
  							</li>
  							
  							<?php if ( !empty( $_POST['deal_collaborators'] ) ) : ?>
  						
  							<?php foreach( $_POST['deal_collaborators'] as $collaborator ) : ?>
  								<?php if ( $collaborator != $bp->loggedin_user->fullname ) : ?>
  								<li>
  									<?php 
  									$userTax = term_exists( $collaborator, 'authors-tax' ); 
  									$user = get_term( $userTax['term_id'], 'authors-tax' );
	
									$avatarArgs = array(
										'height'	=>	100,
										'width'		=>	100,
										'item_id'	=>	$user->slug
									);
									echo bp_core_fetch_avatar( $avatarArgs );
									?>
									<div class="user_name"><?php echo $collaborator; ?></div>
									<div class="delete_user" onclick="jQuery(this).parent().remove();"></div>
  									<input type="hidden" name="deal_collaborators[]" value="<?php echo $collaborator; ?>">
  								</li>
  								<?php endif; ?>
  							<?php endforeach; ?>
  						
  						<?php endif; ?>
  							
  						</ul>
  					</div>
  					<div id="cards-and-notes">
  						<label><?php _e('List All Cards Used In This Deal', 'Reality'); ?></label>
  						<div id="deal_cards_container" class="input-container">
  							<input type="text" name="deal_cards_select" id="deal_cards" onkeypress="return isNumberKey(event)">
  						</div>
  						
  						<ul id="deal-card-preview" class="deal-cards">
  						<?php if ( $maker_card_id ) : ?>
  							<li>
  								<?php echo do_shortcode('[reality_card number="'.$maker_card_id.'" size="medium"]'); ?>
  								<input type="hidden" name="deal_cards[]" value="<?php echo $maker_card_id; ?>">
  							</li>
  						<?php endif; ?>
  						<?php if ( !empty( $_POST['deal_cards'] ) ) : ?>
  						
  							<?php foreach( $_POST['deal_cards'] as $card ) : ?>
  								<?php if ( $card != $maker_card_id ) : ?>
  									<li>
  										<?php echo do_shortcode('[reality_card number="'.$card.'" size="medium"]'); ?>
  										<div class="delete_card" onclick="jQuery(this).parent().remove();"></div>
  										<input type="hidden" name="deal_cards[]" value="<?php echo $card; ?>">
  									</li>
  								<?php endif; ?>
  							<?php endforeach; ?>
  						
  						<?php endif; ?>
  						</ul>
  						
  						<label><?php _e('Notes', 'Reality'); ?></label>
  						<div id="deal_notes_container" class="input-container">
  							<textarea name="deal_notes" id="deal_notes" class="max-char" maxChar="300"><?php if (isset( $_POST['deal_notes'] ) ) echo $_POST['deal_notes']; ?></textarea>
  							<div class="input-hint">
  								<?php _e( 'Add notes about your project here. (Optional)', 'Reality'); ?> <span class="orange"><span class="char-count">0</span> <?php _e('of', 'Reality')?> <span class="char-max">300</span> <?php _e('max characters', 'Reality') ?>.</span>
  							</div>
  						</div>
  					</div>
				</div> <!-- End Tabs -->
			
			<div class="hints">
				
					<?php the_content(); ?>
				
			</div>
			
			<input type="hidden" name="action" value="reality_submit_deal">
			<?php wp_nonce_field( 'reality_submit_deal', '_wpnonce_reality_submit_deal' ); ?>
			
			</form>
			
		<?php else : ?>
		
			<div class="submission-success-message">
			
				<?php $successMessage = get_post_meta( get_the_ID(), 'REALITY_success_message', true); ?>
				<?php $successMessage = apply_filters( 'the_content', $successMessage ); ?>
				
				<?php echo $successMessage; ?>
			
			</div>
		
		<?php endif; ?>

		<?php endwhile; ?>

		</div><!-- #content -->
	</div><!-- #primary -->
	
	<?php if ( empty( $deal_submission_status['success'] ) ) : ?>
	<div class="horizontal-bar tabs-nav">
		<span class="prev-tab" style="float:left;"><a href="#prev-tab" title="Previous Step">&larr; Previous Step</a></span>
		<span class="next-tab" style="float:right;"><a href="#next-tab" title="Next Step">Next Step &rarr;</a></span>
		<div class="clear"></div>
	</div>
	<?php endif; ?>

<?php get_footer(); ?>