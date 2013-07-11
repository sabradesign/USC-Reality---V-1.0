<?php

function reality_translate_outs_side( $side ) {
	
		switch( $side ) {
			case 'L':
				$output = 'Left';
				break;
			case 'R':
				$output = 'Right';
				break;
			case 'B':
				$output = 'Bottom';
				break;
			default:
				$output = false;
		
		}
		
		return $output;
	
}

function reality_import_cards() {

	//TRANSLATION ARRAY
	$translation = array(
		'ID'						=>	'post_name',
		'Set'						=>	'card-sets',
		'Set member #'				=>	'REALITY_card_back_setinfo',
		'Set Size'					=>	'Set Size',
		'Collectible'				=>	'REALITY_card_back_title',
		'Description'				=>	'REALITY_card_back_description',
		'Img filename'				=>	'REALITY_card_info_image',
		'Gerund/Prefix'				=>	'REALITY_card_front_title_firstline',
		'Face ("playable side")'	=>	'REALITY_card_front_title_secondline',
		'Card Type'					=>	'card-type',
		'Face Category'				=>	'face_category',
		'Outs'						=>	'outs',
		'R, L or S template'		=>	'side',
		'Special Out'				=>	'special',
		'Pts AAA'					=>	'points_high',
		'Pts AA'					=>	'points_medium',
		'Pts A'						=>	'points_low',
		'Face Description (optional except for Maker cards)'	=>	'REALITY_card_front_description',
		'Powerup (optional)'		=>	'REALITY_card_front_powerup'
	);
	$translation_reverse = array_flip( $translation );

	global $reality_importer_messages;
	$reality_importer_messages = array();
	$reality_importer_messages['unfound_images_count'] = 0;
	$reality_importer_messages['unfound_images'] = array();
	$reality_importer_messages['added_cards_count'] = 0;

	if ( isset( $_POST['_wp_reality_import_cards_nonce'] ) && wp_verify_nonce( $_POST['_wp_reality_import_cards_nonce'], 'reality_import_cards' ) ) {
	
		if ( !empty( $_FILES['reality_import_cards_csv'] ) ) {
		
			if ( !empty( $_FILES['reality_import_cards_images'] ) ) $card_images = $_FILES['reality_import_cards_images'];
			
			include_once( 'parsecsv.lib.php' );
		
			$import_file = $_FILES['reality_import_cards_csv'];
			$csv = new parseCSV();
			
			$csv->auto( $import_file['tmp_name'] );
			
			//SETUP ARRAY FOR POST IMPORTS
			
			$cards = array();
			
			foreach( $csv->data as $cardData ) {
			
				if ( $cardData[ $translation_reverse['post_name'] ] == '' ) continue;
			
				$card = array();
				
				//SETUP POST DATA
				$post_data = array(
				
					'post_type'		=>	'reality_cards',
					'post_name'		=>	htmlentities($cardData[ $translation_reverse['post_name'] ]),
					'post_title'	=>	htmlentities($cardData[ $translation_reverse['REALITY_card_front_title_secondline'] ]),
					'post_status'	=>	'publish'
				
				);
				$card['post_data'] = $post_data;
				
				//SETUP POST TAXONOMIES
				
				
				// Setup Card points values
				if ( $cardData[ $translation_reverse['points_medium'] ] != '' && $cardData[ $translation_reverse['points_low'] ] != '' ) {
					
					$card_points = $cardData[ $translation_reverse['points_low'] ] . ' ' . $cardData[ $translation_reverse['points_medium'] ] . ' ' . $cardData[ $translation_reverse['points_high'] ];
					$card_points = (string) $card_points;
					
				} else {
				
					$card_points = (string) $translation_reverse['points_high'];
				
				}
				
				// Setup Card Connections value
				$card_connections = '';
				
				if ( $cardData[ $translation_reverse['outs'] ] != '' ) $card_connections .= (string) $cardData[ $translation_reverse['outs'] ] . ' Out';
				if ( $cardData[ $translation_reverse['side'] ] != '' ) {
					$card_connections .= ' ' . reality_translate_outs_side($cardData[ $translation_reverse['side'] ]);
				} else {
				
					if ( $cardData[ $translation_reverse['outs'] ] == 1 ) {
						$card_connections .= ' Bottom';
					} elseif ( $cardData[ $translation_reverse['outs'] ] == 2 ) {
						$card_connections .= ' Left';
					}
				
				}
				
				if ( $cardData[ $translation_reverse['special'] ] != '' ) $card_connections .= ' Special';
				
				$post_tax = array(
					'card-type'			=>	$cardData[ $translation_reverse['card-type'] ],
					'card-sets'			=>	htmlentities($cardData[ $translation_reverse['card-sets'] ]),
					'card-value'		=>	$card_points,
					'card-connections'	=>  $card_connections
				);
				
				$card['post_tax'] = $post_tax;
				
				//SETUP POST META
				$post_meta = array(
					'REALITY_card_front_title_firstline'	=>	htmlentities($cardData[ $translation_reverse['REALITY_card_front_title_firstline'] ]),
					'REALITY_card_front_title_secondline'	=>	htmlentities($cardData[ $translation_reverse['REALITY_card_front_title_secondline'] ]),
					'REALITY_card_front_description'		=>	htmlentities($cardData[ $translation_reverse['REALITY_card_front_description'] ]),
					'REALITY_card_front_powerup'			=>	htmlentities($cardData[ $translation_reverse['REALITY_card_front_powerup'] ]),
					'REALITY_card_back_title'				=>	htmlentities($cardData[ $translation_reverse['REALITY_card_back_title'] ]),
					'REALITY_card_info_image'				=>	htmlentities($cardData[ $translation_reverse['REALITY_card_info_image'] ]),
					'REALITY_card_back_description'			=>	htmlentities($cardData[ $translation_reverse['REALITY_card_back_description'] ]),
					'REALITY_card_back_setinfo'				=>	htmlentities($cardData[ $translation_reverse['REALITY_card_back_setinfo'] ])
				);
				
				$card['post_meta'] = $post_meta;
			
				$cards[] = $card;
			
			}
			
			foreach( $cards as $card ) {
			
				//CREATE POST
				if ( $deal_id = wp_insert_post( $card['post_data']) ) {
			
					//APPLY TAXONOMIES
					foreach ( $card['post_tax'] as $tax => $term ) {
					
						if ( $term_info = term_exists( $term, $tax ) ) {
						
							wp_set_object_terms( $deal_id, (int) $term_info['term_id'], $tax );
						
						} else { 
							if ( $tax == 'card-sets' ) {
							
								$term_id = wp_insert_term( $term, $tax );
								wp_set_object_terms( $deal_id, $term_id['term_id'], $tax );
							
							} elseif ( $tax == 'card-value' ) {
							
								if ( strpos( $term, ' ' ) ) {
								
									$term_values = str_replace( ' ', ',', $term );
								
									$term_args = array(
										'description' => $term_values
									);
									$term_title = $term;
								
								} else {
								
									$term_args = array(
										'description' => (int) $term
									);
									$term_title = $term . ' Points';
								
								}
								
								$term_id = wp_insert_term( $term_title, $tax, $term_args );
								wp_set_object_terms( $deal_id, $term_id['term_id'], $tax );
							
							}
						}
					
					}
					//ADD IMAGE
					if ( $card['post_meta']['REALITY_card_info_image'] != '' && is_int( $image_id = array_search( $card['post_meta']['REALITY_card_info_image'], $card_images['name'] ) ) ) {
					
						$uploadedfile = array(
							'name'		=>	$card_images['name'][$image_id],
							'type'		=>	$card_images['type'][$image_id],
							'size'		=>	$card_images['size'][$image_id],
							'tmp_name'	=>	$card_images['tmp_name'][$image_id],
							'error'		=>	$card_images['error'][$image_id]
						);
						
						$upload_overrides = array( 'test_form' => false );
						$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
						if ( $movefile ) {
								    
						    $filename = $movefile['file'];
								    
						    $wp_filetype = wp_check_filetype(basename($filename), null );
							$wp_upload_dir = wp_upload_dir();
							$attachment = array(
							     'guid' => $wp_upload_dir['url'] . '/' . basename( $filename ), 
							     'post_mime_type' => $wp_filetype['type'],
							     'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
							    'post_content' => '',
							     'post_status' => 'inherit'
							);
							$attach_id = wp_insert_attachment( $attachment, $filename, $deal_id );
							require_once(ABSPATH . 'wp-admin/includes/image.php');
							$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
							wp_update_attachment_metadata( $attach_id, $attach_data );
							
							$card['post_meta']['REALITY_card_info_image'] = $attach_id;
							    
						}
					
					} else {
						
						if ( $card['post_meta']['REALITY_card_info_image'] != '' ) {
							$card_post = get_post( $deal_id );
							$reality_importer_messages['unfound_images_count']++;
							$reality_importer_messages['unfound_images'][] = $card['post_meta']['REALITY_card_info_image'] . ' on <a href="' . admin_url('post.php') . '?post=' . $card_post->ID . '&action=edit">Card #' . $card_post->post_name . ' (' . $card_post->post_title . ')</a>';
						}
						
						unset($card['post_meta']['REALITY_card_info_image']);
					}
			
					//UPDATE POST META
					foreach( $card['post_meta'] as $meta_key => $meta_value ) {
					
						if ( !update_post_meta( $deal_id, $meta_key, $meta_value ) ) $reality_importer_messages['messages'] = 'Could not update '.$meta_key.' of card #'.$card['post_data']['post_name'].' ('.$card['post_data']['post_title'].')';
					
					}
					
					$reality_importer_messages['added_cards_count']++;
					
				
				} else {
				
					$reality_importer_messages['success'] = false;
					$reality_importer_messages['messages'] = 'Could not create post for card #'.$card['post_data']['post_name'].' ('.$card['post_data']['post_title'].')';
				
				}
			
			}
			
			$reality_importer_messages['success'] = true;
			$reality_importer_messages['messages'] = 'Successfully imported cards!';
		
		} else {
		
			$reality_importer_messages['success'] = false;
			$reality_importer_messages['messages'] = 'You must choose a CSV file to import.';
		
			return false;
		
		}
	
	} else {
	
		$reality_importer_messages['success'] = false;
		$reality_importer_messages['messages'] = 'Could not verify nonce';
		
		return false;
	}

}
add_action( 'admin_init', 'reality_import_cards' );


?>