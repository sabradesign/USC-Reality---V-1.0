<?php



function reality_load_deal_meta( $post ) {

	

	if ( $post->post_type == 'reality_deals' ) {
		
		global $deal_meta;
		$deal_meta = get_post_meta( $post->ID );
	
	} elseif ( $post->post_type == 'reality_cards' ) {
	
		global $card_meta;
		$card_meta = get_post_meta( $post->ID );
	
	}
	
}
add_action( 'the_post', 'reality_load_deal_meta' );

function the_deal_image_url( $post_id = false, $size = 'full' ) {
	echo get_the_deal_image_url( $post_id, $size );
}

function get_the_deal_image_url( $post_id = false, $size = 'full' ) {

	global $deal_meta, $post;

	if ( !$post_id ) $post_id = $post->ID;

	if ( get_post_type( $post_id ) == 'reality_deals' ) {

		if ( has_post_thumbnail( $post_id ) ) {
		
			$imageID = get_post_thumbnail_id( $post_id );
			$imageURL = wp_get_attachment_image_src( $imageID, $size );
			$thumbnailsrc = $imageURL[0];
		
		} else {
		
			if ( isset( $deal_meta['REALITY_deal_video_link'][0] ) ) {
				$youtubeID = $deal_meta['REALITY_deal_video_link'][0];
			} else {
				 $youtubeID = get_post_meta( $post_id, 'REALITY_deal_video_link', true );
			}
		
			switch( $size ) {
				case 'full':
					$thumbnailsrc = 'http://img.youtube.com/vi/'.$youtubeID.'/maxresdefault.jpg';
					break;
				case 'medium':
					$thumbnailsrc = 'http://img.youtube.com/vi/'.$youtubeID.'/hqdefault.jpg';
					break;
				case 'thumbnail':
					$thumbnailsrc = 'http://img.youtube.com/vi/'.$youtubeID.'/1.jpg';
					break;
				default:
					$thumbnailsrc = 'http://img.youtube.com/vi/'.$youtubeID.'/maxresdefault.jpg';
					break;
			}
		
		}
		
		return $thumbnailsrc;
	
	} else {
		return false;
	}

}

function the_deal_content( $post_id = false ) {
	echo get_the_deal_content( $post_id );
}

function get_the_deal_content( $post_id = false ) {

	global $deal_meta, $post;

	if ( !$post_id ) $post_id = $post->ID;
	$post_title = get_the_title( $post_id );

	if ( get_post_type( $post_id ) == 'reality_deals' ) {
	
		$output = '';
		
		// Check if there is content in the visual editor
		
		if ( get_the_content() != '' ) {
		
			$content = apply_filters( 'the_content', get_the_content() );
		
			$output .= '<div class="visualEditor">'.$content.'</div>';
		
		}
		
		// Check if has youtube ID
		
		if ( isset($deal_meta['REALITY_deal_video_link'][0]) ) {
		
			$output .= '<div class="youtube-player">';
			$output .= '<iframe width="635" height="357" src="http://www.youtube.com/embed/'.$deal_meta['REALITY_deal_video_link'][0].'?rel=0" frameborder="0" allowfullscreen></iframe>';
			$output .= '</div>';
		
		}
		
		// Check if has additional images
		
		if ( !empty( $deal_meta['REALITY_deal_images'] ) ) {
		
		$output .= '<div class="deal-images">';
		$output .= '<h3>Deal Images</h3>';
		$output .= '<ul>';
		
			foreach( $deal_meta['REALITY_deal_images'] as $image ) {
			
				$output .= '<li>';
				
				$imageSrcFull = wp_get_attachment_image_src( $image, 'full' );
				$imageSrcLarge = wp_get_attachment_image_src( $image, 'large' );
				
				$output .= '<a href="'.$imageSrcFull[0].'" title="'.$post_title.'" rel="fancybox"><img src="'.timthumb_photo( $imageSrcLarge[0], 635, '', '', false).'" alt="'.$post_title.'"></a>';
				
				$output .= '</li>';
			
			}
		
		$output .= '</ul></div>';
		
		}
		
		// Check if has files
		
		if ( !empty( $deal_meta['REALITY_deal_files'] ) ) {
		
			$output .= '<div class="deal-files"><ul>';
		
			foreach( $deal_meta['REALITY_deal_files'] as $fileID ) {
			
				$attachment = get_post( $fileID );
				$fileSrc = wp_get_attachment_url( $fileID );
				
				switch( $attachment->post_mime_type ) {
					case 'application/zip':
						$output .= '<li>';
						$output .= '<h3>ZIP</h3>';
						$output .= '<a href="'.$fileSrc.'" title="'.$post_title.'" class="download-file" type="'. $attachment->post_mime_type .'">Click Here to Download File</a>';
						$output .= '</li>';
						break;
					case 'application/pdf':
						$output .= '<li>';
						$output .= '<h3>Document</h3>';
						$output .= '<iframe src="http://docs.google.com/viewer?url='.$fileSrc.'&embedded=true" width="635" height="780" style="border: none;"></iframe>';
						$output .= '</li>';
						break;
					case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
						$output .= '<li>';
						$output .= '<h3>Document</h3>';
						$output .= '<iframe src="http://docs.google.com/viewer?url='.$fileSrc.'&embedded=true" width="635" height="780" style="border: none;"></iframe>';
						$output .= '</li>';
						break;
					case 'application/msword':
						$output .= '<li>';
						$output .= '<h3>Document</h3>';
						$output .= '<iframe src="http://docs.google.com/viewer?url='.$fileSrc.'&embedded=true" width="635" height="780" style="border: none;"></iframe>';
						$output .= '</li>';
						break;
					case 'audio/mpeg':
					case 'audio/ogg':
					case 'audio/wav':
						$output .= '<li>';
						$output .= '<h3>Audio</h3>';
						$output .= '<audio class="" controls="" data-fallback="'.get_stylesheet_directory_uri().'/js/audio-player/AudioPlayerV1.swf">';
						$output .= '<source src="'.$fileSrc.'" type="audio/mpeg">';
						$output .= '<source src="'.$fileSrc.'" type="audio/ogg">';
						$output .= 'Your browser does not support the audio element.';
						$output .= '</audio>';
						$output .= '</li>';
						break;
					default:
						$output .= '<li>';
						$output .= '<a href="'.$fileSrc.'" title="'.$post_title.'" class="download-file" type="'. $attachment->post_mime_type .'">Click Here to Download File</a>';
						$output .= '</li>';
				}
			
			}
			
			$output .= '</ul></div>';
		
		}
		
		return $output;
	
	}

}

function deal_has_files( $post_id = false ) {

	global $deal_meta, $post;

	if ( !$post_id ) $post_id = $post->ID;

	if ( get_post_type( $post_id ) == 'reality_deals' ) {
	
		if ( !empty($deal_meta['REALITY_deal_files']) ) {
		
			return true;
		
		} else {
			
			return false;
		
		}
	
	} else {
		
		return false;
	
	}

}

function the_deal_files( $post_id = false ) {

	global $deal_meta, $post;

	if ( !$post_id ) $post_id = $post->ID;

	if ( get_post_type( $post_id ) == 'reality_deals' ) {
	
		if ( !empty($deal_meta['REALITY_deal_files']) ) {
		
			$output = '<div class="deal-files"><ul>';
		
			foreach( $deal_meta['REALITY_deal_files'] as $file ) {
			
				$attachment = get_post( $file );
				$fileSrc = wp_get_attachment_url( $file );
				
				$output .= '<li><h4>'.$attachment->post_title.'</h4><a href="'.$fileSrc.'" title="Download this deal\'s files!" class="download-file"></a></li>';
			
			}
			
			$output .= '</ul></div>';
			
			echo $output;
		
		}
	
	} else {
	
		return false;
	
	}

}

function the_deal_logline( $post_id = false ) {
	echo get_the_deal_logline( $post_id );
}

function get_the_deal_logline( $post_id = false ) {
	
	global $deal_meta, $post;

	if ( !$post_id ) $post_id = $post->ID;

	if ( get_post_type( $post_id ) == 'reality_deals' ) {
	
		if ( isset( $deal_meta['REALITY_deal_logline'][0] ) ) {
			$deal_logline = $deal_meta['REALITY_deal_logline'][0];
		} elseif ( $deal_logline = get_post_meta( $post_id, 'REALITY_deal_logline', true ) ) {
		
		} else {
		
			return false;
		
		}
		
		return $deal_logline;
	
	}

}

function the_deal_points( $post_id = false ) {
	echo get_the_deal_points( $post_id );
}

function get_the_deal_points( $post_id = false ) {

	global $deal_meta, $post;

	if ( !$post_id ) $post_id = $post->ID;

	if ( get_post_type( $post_id ) == 'reality_deals' ) {

		if ( isset( $deal_meta['REALITY_total_value'][0] ) ) {
			$points = $deal_meta['REALITY_total_value'][0];
		} elseif ( $points = get_post_meta( $post_id, 'REALITY_total_value', true ) ) {
		
		} else {
			$points = 0;
		}
		
		$points = number_format( $points );
		
		return $points;
	
	} else {
		return false;
	}

}

function reality_get_maker_card( $post_id = false ) {
	
	global $post;

	if ( !$post_id ) $post_id = $post->ID;
	
	if ( get_post_type( $post_id ) == 'reality_deals' ) {

		$cards = wp_get_object_terms( $post_id , 'cards-tax');
		
		foreach( $cards as $card ) {
		
			if ( has_term( 'Maker', 'card-type', $card->slug )) $maker_card = $card;
		
		}
		
		return $maker_card;
	
	} else {
		return false;
	}

}

function the_deal_authors( $post_id = false, $how = 'list' ) {

	if ( $authors = get_the_deal_authors( $post_id ) ) {
	
		switch ( $how ) {
			case 'list':
				$output = '';
				$multiAuthor = false;
				$authorsNumber = count($authors);
				if ( $authorsNumber >= 2 ) {
					$multiAuthor = true;
					$count = 1;
				}
				foreach( $authors as $author ) {
				
					if ( $multiAuthor && $count < $authorsNumber && $count < $authorsNumber - 1) {
				
						$output .= bp_core_get_userlink( $author->slug ).', ';
						
					} elseif ( $multiAuthor && $count < $authorsNumber && $count == $authorsNumber - 1) {
					
						$output .= bp_core_get_userlink( $author->slug ).' ';
					
					} elseif ( $multiAuthor && $count == $authorsNumber ) {
						
						$output .= 'and '.bp_core_get_userlink( $author->slug );
						
					} elseif ( !$multiAuthor ) {
					
						$output .= bp_core_get_userlink( $author->slug );
					
					}
					
					isset( $count ) ? $count++ : false ;
				
				}	
			
			
				break;
			case 'avatars':
			
				break;
		
		}
		
		echo $output;
	
	} else {
		
		return false;
	
	}

}

function get_the_deal_authors( $post_id = false ) {
	global $post;
	
	if ( !$post_id ) $post_id = $post->ID;
	
	if ( get_post_type( $post_id ) == 'reality_deals' ) {
	
		$authors = wp_get_object_terms( $post_id, 'authors-tax' );
		
		return $authors;
	
	} else {
	
		return false;
	
	}
	
	
}

function the_deal_cards( $post_id = false, $how = 'cards', $size = 'small' ) {

	if ( $cards = get_the_deal_cards( $post_id ) ) {
	
		switch( $how ) {
			case 'cards':
				
				$output = '<ul class="deal-cards">';
				foreach( $cards as $card ) {
				
					$output .= '<li><a href="'.get_permalink( $card->slug ).'" title="'.get_the_title( $card->slug ).'">';
					$output .= do_shortcode('[reality_card size="'.$size.'" number="'.$card->name.'"]');
					$output .= '</a></li>';
				
				}
				$output .= '</ul>';
				$output .= '<div class="clear"></div>';
				break;
		
		}
		
		echo $output;
	
	} else {
	
		return false;
	
	}

}

function get_the_deal_cards( $post_id = false ) {
	global $post;
	
	if ( !$post_id ) $post_id = $post->ID;
	
	if ( get_post_type( $post_id ) == 'reality_deals' ) {
	
		$cards = wp_get_object_terms( $post_id, 'cards-tax' );
		
		return $cards;
	
	} else {
	
		return false;
	
	}
	
}

function get_the_deal_type( $post_id = false ) {
	global $post;
	
	if ( !$post_id ) $post_id = $post->ID;
	
	if ( get_post_type( $post_id ) == 'reality_deals' ) {
	
		$makerCard = reality_get_maker_card( $post_id );
		
		$type = get_post_meta( $makerCard->slug, 'REALITY_maker_card_submission_type', TRUE );
		
		return $type;
	
	} else {
	
		return false;
	
	}


}

function the_deal_awards( $post_id = false, $link = true ) {

	$awards = get_the_deal_awards( $post_id );
	$output = '<ul class="reality-awards">';
	
	foreach ( $awards as $award ) {
	
		$output .= '<li>';
		
		$awards_tax = get_taxonomy( 'awards-tax' );
		$award_link = get_bloginfo( 'url' ) . '/' . $awards_tax->rewrite['slug'] . '/' . $award->slug;
		
		if ( $link ) $output .= '<a href="'.$award_link.'" title="See other winners of the '.$award->name.' Award.">';
	
		if ( has_post_thumbnail( $award->slug ) ) {
		
			$output .= get_the_post_thumbnail( $award->slug, 'medium' );
		
		} else {
		
			$output .= '<div class="award-default">';
			$output .= $award->name;
			$output .= '</div>';
		}
		
		if ( $link ) $output .= '</a>';
		
		$output .= '</li>';
	
	}
	
	$output .= '</ul>';
	
	echo $output;

}

function deal_has_awards( $post_id = false ) {
	return get_the_deal_awards( $post_id );
}

function get_the_deal_awards( $post_id = false ) {
	global $post;
	
	if ( !$post_id ) $post_id = $post->ID;

	if ( get_post_type( $post_id ) == 'reality_deals' ) {
	
		$awards = wp_get_object_terms( $post_id, 'awards-tax' );
		
		if ( $awards ) {
		
			return $awards;
			
		} else {
			
			return false;
			
		}
	
	} else {
	
		return false;
	
	}

}

function reality_deal_has_notes( $post_id = false ) {

	global $deal_meta, $post;
	
	if ( isset( $deal_meta ) && isset( $deal_meta['REALITY_deal_notes'][0] ) ) {
	
		return $deal_meta['REALITY_deal_notes'][0];
	
	} else {
	
		if ( !$post_id ) $post_id = $post->ID;
	
		if ( $notes = get_post_meta( $post_id, 'REALITY_deal_notes', true ) ) {
			
			return $notes;
			
		} else {
		
			return false;
		
		}
	
	}

}

function get_the_deal_card_layout( $post_id = false, $size ) {
	global $deal_meta, $post;
	
	if ( !$post_id ) $post_id = $post->ID;
	
	if ( !isset($size ) ) $size = 'full';

	if ( get_post_type( $post_id ) == 'reality_deals' ) {
	
		if ( isset( $deal_meta['REALITY_deal_card_layout'][0] ) ) {
		
			$attachmentID = $deal_meta['REALITY_deal_card_layout'][0];
		
		} elseif ( $attachmentID = get_post_meta( $post_id, 'REALITY_deal_card_layout', true ) ) {
		
		
		} else {
		
			return false;
		
		}

		$image = wp_get_attachment_image_src( $attachmentID, $size );
			
		$imageURL = $image[0];
			
		return $imageURL;
	
	} else {
	
		return false;
	
	}

}

function the_deal_justification_video( $post_id = false ) {
	echo get_the_deal_justification_video( $post_id );
}

function get_the_deal_justification_video( $post_id = false ) {
	global $deal_meta, $post;
	
	if ( !$post_id ) $post_id = $post->ID;

	if ( get_post_type( $post_id ) == 'reality_deals' ) {
	
		if ( isset( $deal_meta['REALITY_deal_justification_video_link'][0] ) ) {
		
			$justificationVideo = $deal_meta['REALITY_deal_justification_video_link'][0];
			
		} elseif ( $justificationVideo = get_post_meta( $post_id, 'REALITY_deal_justification_video_link', true ) ) {
			
		} else {
		
			return false;
			
		}
		
		$videoEmbed = '<iframe width="635" height="357" src="http://www.youtube.com/embed/'.$justificationVideo.'?rel=0" frameborder="0" allowfullscreen></iframe>';
		
		return $videoEmbed;
	
	} else {
	
		return false;
	
	}

}

function the_deal_comments( $post_id = false ) {
	global $post, $bp;
	
	if ( !$post_id ) $post_id = $post->ID;

	if ( get_post_type( $post_id ) == 'reality_deals' ) { ?>
	
	<ul id="activity-stream" class="activity-list item-list">
	
	<?php if ( bp_has_activities( 'primary_id='.$post_id ) ) : ?>
	
	<?php while ( bp_activities() ) : bp_the_activity(); ?>
	
	<li class="<?php bp_activity_css_class(); ?>" id="activity-<?php bp_activity_id(); ?>">

	<?php do_action( 'bp_before_activity_entry_comments' ); ?>

	<?php if ( ( is_user_logged_in() && bp_activity_can_comment() ) || bp_activity_get_comment_count() ) : ?>

		<div class="activity-comments no-js deals-single">

			<?php if ( is_user_logged_in() ) : ?>
				<script>
					jQuery(document).ready( function($) {
						$(document).unbind('keydown');
					});
				</script>
				<form action="<?php bp_activity_comment_form_action(); ?>" method="post" id="ac-form-<?php bp_activity_id(); ?>" class="ac-form"<?php bp_activity_comment_form_nojs_display(); ?> style="display: block !important;">
					<div class="ac-reply-avatar"><?php bp_loggedin_user_avatar( 'width=' . BP_AVATAR_THUMB_WIDTH . '&height=' . BP_AVATAR_THUMB_HEIGHT ); ?></div>
					<div class="ac-reply-content">
					<div class="reality-deal-audience-awards">
					<?php $authorTax = get_term_by( 'slug', $bp->loggedin_user->id, 'authors-tax' ); ?>
					<?php if ( !has_term( $authorTax->term_id, 'authors-tax', get_the_ID() ) ) : ?>
						<?php $current_user_votes = get_user_meta( $bp->loggedin_user->id, 'REALITY_audience_vote' ); ?>
						<?php if ( !is_int( array_search( $post_id, $current_user_votes ) ) ) : ?>
						
							<?php $audience_awards_options = get_option( 'reality_audience_award_options' ); ?>
							<?php foreach( $audience_awards_options as $slug => $award ) : ?>
							
								<label>
									<?php echo $award; ?>
									<input type="radio" name="audience_award" value="<?php echo $slug; ?>">
								</label>
							
							<?php endforeach; ?>
							<input type="hidden" name="reality_deal_id" value="<?php echo get_the_ID() ?>">
							<div class="clear"></div>
						
						<?php else : ?>
						
							<div class="message">You have already voted on this deal.</div>
					
						<?php endif; ?>
					<?php else : ?>
						<div class="message">Can't vote on your own deal.</div>
					<?php endif; ?>
						</div>
						<div class="ac-textarea">
							<textarea id="ac-input-<?php bp_activity_id(); ?>" class="ac-input" name="ac_input_<?php bp_activity_id(); ?>"></textarea>
						</div>
						<input type="submit" name="ac_form_submit" value="<?php _e( 'Post', 'buddypress' ); ?>" />
						<input type="hidden" name="comment_form_id" value="<?php bp_activity_id(); ?>" />
						<div class="clear"></div>
					</div>

					<?php do_action( 'bp_activity_entry_comments' ); ?>

					<?php wp_nonce_field( 'new_activity_comment', '_wpnonce_new_activity_comment' ); ?>

				</form>

			<?php endif; ?>

			<?php bp_activity_comments(); ?>

		</div>

	<?php endif; ?>

	<?php do_action( 'bp_after_activity_entry_comments' ); ?>

	</li>
	
	<?php endwhile; ?>
	
	</ul>
	
	<?php endif; ?>
	
	<?php } else {
	
		return false;
	
	}

}

function reality_deal_comments_order_fix( $sql ) {

	global $post;
	
	if ( is_object( $post ) ) {
		if ( $post->post_type == 'reality_deals' ) {	
			
			$sql = str_replace( 'ASC', 'DESC', $sql );
		
		}
	}
	
	return $sql;

}
add_filter( 'bp_activity_comments_user_join_filter', 'reality_deal_comments_order_fix' );

function reality_user_has_voted( $user_id = false, $post_id = false ) {
	global $bp, $post;

	if ( !$user_id ) $user_id = $bp->loggedin_user->id;
	if ( !$post_id && is_object( $post ) ) $post_id = $post->ID;
	
	$userVotes = get_the_author_meta( 'reality_audience_award_votes', $user_id );
	
	if ( ( isset($userVotes) && in_array( $post_id, $userVotes ) ) ) return true;
		else return false;
	

}

function reality_deal_audience_award_process_vote( $comment_id, $params ) {
	
	global $bp;
	
	if ( isset($_POST['audience_award']) && $_POST['audience_award'] != '' ) {
	
		$vote = (string) $_POST['audience_award'];
		$deal = (int) $_POST['reality_deal_id'];
		
		if ( !reality_user_has_voted() ) {
		
		
 			if ( $postAward = (int) get_post_meta( $deal, 'REALITY_audience_awards_' . $vote, true ) ) {
 			
				$postAward += 1;
 			
 			} else {
 			
 				$postAward = 1;
 			
 			}
			
			$updateStatus = update_post_meta( $deal, 'REALITY_audience_awards_' . $vote, $postAward );
			
			add_user_meta( $bp->loggedin_user->id, 'REALITY_audience_vote', $deal );
			
			do_action( 'reality_after_update_audience_awards', $deal, $vote );
		
		}
	
	}
	
}
add_action( 'bp_activity_comment_posted', 'reality_deal_audience_award_process_vote', 10, 2 );

function reality_is_maker_card( $cardNumber ) {

	if ( $card = term_exists( $cardNumber, 'cards-tax' ) ) {
	
		$cardTerm = get_term( $card['term_id'], 'cards-tax' );
	
		if ( has_term( 'Maker', 'card-type', $cardTerm->slug ) ) {
			return true;
		} else {
			return false;
		}
	
	} else {
	
		return false;
	
	}

}

//SAVE DEAL CARD VALUE FIX 

function reality_deal_save_card_value( $post_id ) {

	if ( get_post_type( $post_id ) == 'reality_deals' ) {
	
		$cards = array();
		foreach( $_POST as $key => $value ) {
		
			if ( strstr( $key, 'REALITY_card_' ) ) {
			
				$cards[$key] = $value;
			
			}
		
		}
		
		foreach( $cards as $key => $card ) {
		
			update_post_meta( $post_id, $key, $card );
		
		}
	
	}

}
add_action( 'save_post', 'reality_deal_save_card_value' );

?>