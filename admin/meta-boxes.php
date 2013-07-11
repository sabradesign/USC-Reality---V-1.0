<?php

/**
 * Register meta boxes
 *
 * @return void
 */
function reality_register_meta_boxes()
{

global $meta_boxes, $card_fields, $submission_meta_boxes, $post;

$submission_meta_boxes = array();

/*
 * DEAL METABOXES
 */

$prefix = 'REALITY_';

$card_fields = array();
$card_desc = 'something';

if ( isset( $_GET['post'] ) ) {
	$post_ID = (int) $_GET['post'];
	$post_type = get_post_type( $post_ID );
}

// $card_fields[] = array(
// 			'name'    => 'Cards',
// 			'id'      => "{$prefix}deal_cards",
// 			'type'    => 'taxonomy',
// 			'options' => array(
// 				// Taxonomy name
// 				'taxonomy' => 'cards-tax',
// 				// How to show taxonomy: 'checkbox_list' (default) or 'checkbox_tree', 'select_tree' or 'select'. Optional
// 				'type' => 'checkbox_list',
// 				// Additional arguments for get_terms() function. Optional
// 				'args' => array()
// 			),
// 		);
if ( isset($post_type ) ) {
if ( $post_type == 'reality_deals' ) {
	
	// GET YOUTUBE VIDEO
	
	if ( $youtubeID = get_post_meta( $post_ID, 'REALITY_deal_video_link', true ) ) {
	
		$youtube_video = '<iframe width="560" height="315" src="http://www.youtube.com/embed/'.$youtubeID.'" frameborder="0" allowfullscreen></iframe>';
	
	}
	
	// GET DEAL CARDS
	
	$cards = wp_get_object_terms( $post_ID, 'cards-tax' );
	
	if ( !empty( $cards ) ) {
		if(!is_wp_error( $cards )){
			$count = (int) 0;
			foreach( $cards as $card ) {
			
				$card_values = wp_get_object_terms( (int) $card->slug, 'card-value' );
				$card_value_array = array();
				
				foreach( $card_values as $card_value ) {
				
					$values = explode(',', $card_value->description);
					
					foreach( $values as $value ) {
					
						$value = (int) $value;
						$card_value_array[$value] = $value.' points';
					
					}
					
					$card_value_array[0] = 0;
					
					ksort( $card_value_array );
				
				}
				
				$id = "{$prefix}card_{$card->name}";
				$count++;
				
				$card_fields[] = array(
					'name'	=>	"<h2>{$card->description} ({$card->name})</h2>",
					'id'	=>	$id,
					'type'	=>	'radio',
					'options'	=>	$card_value_array
				);
				
			}
		
		}
	}
	
	
	
	$standingsInfo = '<table class="deal-standings-info">';
	$standingsInfo .= '<thead><tr><th>Metric</th><th>Rank</th></tr></thead>';
	$standingsInfo .= '<tbody>';
	
	if ( $standings = get_post_meta( $post_ID, 'REALITY_deal_standings', true ) ) {
		
		foreach( $standings as $standing => $rank ) {
		
			$standingsInfo .= '<tr>';
			$standingsInfo .= '<td>'.$standing.'</td>';
			$standingsInfo .= '<td>'.$rank.'</td>';
			$standingsInfo .= '</tr>';
		
		}
	} else {
		$standingsInfo .= '<tr><td colspan=2>No Standings Info Found</td></tr>';
	}
	
	$standingsInfo .= '</tbody>';
	$standingsInfo .= '</table>';

}
}

$deal_awards_id = term_exists('Deal Awards','awards-tax');
$award_args = array(
	'parent'		=>	$deal_awards_id['term_id'],
	'hide_empty'	=>	0,
	'get'			=>	'all'
);
$awardTerms = get_terms( 'awards-tax', array( 'hide_empty' => false ) );
$awardsArray = array();

foreach( $awardTerms as $awardTerm ){
	$awardsArray[ $awardTerm->parent ][] = $awardTerm;
}

$awards = array();

if ( !empty( $awardsArray ) && isset( $awardsArray[$deal_awards_id['term_id']] ) )
	$awards = $awardsArray[$deal_awards_id['term_id']];

$deal_award_values = '';

if ( is_array( $awards ) ) {
	if ( !empty( $awards ) ) {
		foreach( $awards as $award ) {
			$value = get_post_meta( $award->slug, 'REALITY_award_value', true );
			$deal_award_values .= '<input type="hidden" id="award-'.$award->slug.'" value="'.$value.'">';
		}
	} else {
		$deal_award_values .= '<h4>No Awards have been set yet.  <a href="' . admin_url( 'edit.php?post_type=reality_awards' ) . '" title="Create New Awards">Create some here</a></h4>';
	}
}

$card_awards = array();
$card_awards[] = array(
	'name'	=>	'<h4>Deal Awards</h4>',
	'id'	=>	"{$prefix}deal_awards",
	'type'	=>	'taxonomy',
	'options' => array(
		// Taxonomy name
		'taxonomy' => 'awards-tax',
		// How to show taxonomy: 'checkbox_list' (default) or 'checkbox_tree', 'select_tree' or 'select'. Optional
		'type' => 'checkbox_tree',
		// Additional arguments for get_terms() function. Optional
		'args' => $award_args
	),
	'after'	=>	$deal_award_values,
	'before'	=>	'<p>Award points are added after multipliers and bonuses</p>'
);

$card_fields_2 = array();

$card_fields_2[] = array(
	'name'	=>	'Deal Pre-Multiplier Bonus',
	'id'	=>	"{$prefix}premultiplier_bonus",
	'type'	=>	'number',
	'desc'	=>	'Any bonuses applied before the multiplier.'
);

$card_fields_2[] = array(
	'name'	=>	'Deal Multiplier',
	'id'	=>	"{$prefix}multiplier",
	'type'	=>	'number',
	'desc'	=>	'A multiplier on the deal value.'
);

$card_fields_2[] = array(
	'name'	=>	'Deal Post-Multiplier Bonus',
	'id'	=>	"{$prefix}postmultiplier_bonus",
	'type'	=>	'number',
	'desc'	=>	'Any bonuses applied after the multiplier.'
);

$card_fields_2[] = array(
	'name'	=>	'Deal Value',
	'id'	=>	"{$prefix}total_value",
	'type'	=>	'number',
	'desc'	=>	'The total value of the deal.'
);

$meta_boxes = array();

if ( !isset( $youtube_video) ) $youtube_video = '';

$meta_boxes[] = array(
	'id' => 'reality_deal-info',
	'title' => 'Deal Information',
	'pages' => array( 'reality_deals' ),
	'context' => 'advanced',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'	=>	'Deal Logline',
			'id'	=>	"{$prefix}deal_logline",
			'type'	=>	'textarea',
			'desc'	=>	'The deal logline',
			'before'	=>	$youtube_video
		),
		array(
			'name'	=>	'Deal YouTube Link',
			'id'	=>	"{$prefix}deal_video_link",
			'type'	=>	'text',
			'desc'	=>	'The id of the deal YouTube Video'
		),
		array(
			'name'	=>	'Deal Files',
			'id'	=>	"{$prefix}deal_files",
			'type'	=>	'file'
		),
		array(
			'name'	=>	'Deal Images',
			'id'	=>	"{$prefix}deal_images",
			'type'	=>	'plupload_image',
			'max_file_uploads'	=>	99
		),
		array(
			'name'	=>	'Deal Notes',
			'id'	=>	"{$prefix}deal_notes",
			'type'	=>	'textarea',
			'desc'	=>	'Any deal notes'
		),
		array(
			'name'	=>	'Deal Justification YouTube Link',
			'id'	=>	"{$prefix}deal_justification_video_link",
			'type'	=>	'text',
			'desc'	=>	'The url of the deal justification YouTube Video'
		),
		array(
			'name'             => 'Card Layout',
			'id'               => "{$prefix}deal_card_layout",
			'type'             => 'plupload_image',
			'max_file_uploads' => 1,
		)
	)
);

$meta_boxes[] = array(
	'id' => "{$prefix}deal-cards",
	'title' => 'Cards',
	'pages' => array( 'reality_deals' ),
	'context' => 'advanced',
	'priority' => 'low',
	'fields' => $card_fields
);

$meta_boxes[] = array(
	'id' => "{$prefix}deal-awards",
	'title' => 'Awards ( <a href="' . admin_url( 'edit.php?post_type=reality_awards' ) . '" title="Create New Awards">Edit</a> )',
	'pages' => array( 'reality_deals' ),
	'context' => 'advanced',
	'priority' => 'low',
	'fields' => $card_awards
);

$meta_boxes[] = array(
	'id' => "{$prefix}deal-points",
	'title' => 'Total Points and Bonuses',
	'pages' => array( 'reality_deals' ),
	'context' => 'advanced',
	'priority' => 'low',
	'fields' => $card_fields_2
);

/*
 * CARD METABOXES
 */
 
$meta_boxes[] = array(
	'id'	=>	"{$prefix}card_info",
	'title'	=>	'Card Details',
	'pages'	=>	array( 'reality_cards' ),
	'context'	=>	'advanced',
	'priority'	=>	'high',
	'fields'	=> array(
		array(
			'name'	=>	'Card Type',
			'id'	=>	"{$prefix}card_type",
			'type'	=>	'taxonomy',
			'options' => array(
				// Taxonomy name
				'taxonomy' => 'card-type',
				// How to show taxonomy: 'checkbox_list' (default) or 'checkbox_tree', 'select_tree' or 'select'. Optional
				'type' => 'select',
				// Additional arguments for get_terms() function. Optional
				'args' => array()
			)
		),
		array(
			'name'	=>	'Submission Type',
			'id'	=>	"{$prefix}maker_card_submission_type",
			'type'	=>	'select',
			'std'	=>	'Select Upload Type',
			'desc'	=>	'Only applicable to Maker cards.',
			'options'	=>	array(
				''			=>	'Choose one...',
				'youtube'	=>	'YouTube Video',
				'fileupload'	=>	'File Upload',
				'unity'		=>	'Unity Game'
			)
		),
		array(
			'name'	=>	'Card Values',
			'id'	=>	"{$prefix}card_values",
			'type'	=>	'taxonomy',
			'options' => array(
				// Taxonomy name
				'taxonomy' => 'card-value',
				// How to show taxonomy: 'checkbox_list' (default) or 'checkbox_tree', 'select_tree' or 'select'. Optional
				'type' => 'select',
				// Additional arguments for get_terms() function. Optional
				'args' => array()
			)
		),
		array(
			'name'	=>	'Card Connections',
			'id'	=>	"{$prefix}card_connections",
			'type'	=>	'taxonomy',
			'options' => array(
				// Taxonomy name
				'taxonomy' => 'card-connections',
				// How to show taxonomy: 'checkbox_list' (default) or 'checkbox_tree', 'select_tree' or 'select'. Optional
				'type' => 'select',
				// Additional arguments for get_terms() function. Optional
				'args' => array()
			)
		)
	)
);

$meta_boxes[] = array(
	'id'	=>	"{$prefix}card_front_details",
	'title'	=>	'Card Design - Front',
	'pages'	=>	array( 'reality_cards' ),
	'context'	=>	'advanced',
	'priority'	=>	'high',
	'fields'	=> array(
		array(
			'name'	=>	'Card Description - Front',
			'id'	=>	"{$prefix}card_front_description",
			'type'	=>	'textarea',
			'before'	=>	do_shortcode('[reality_card]')
		),
		array(
			'name'	=>	'Card Title - Front - First Line',
			'id'	=>	"{$prefix}card_front_title_firstline",
			'type'	=>	'text'
		),
		array(
			'name'	=>	'Card Title - Front - Second Line',
			'id'	=>	"{$prefix}card_front_title_secondline",
			'type'	=>	'text'
		),
		array(
			'name'	=>	'Card Power-Up',
			'id'	=>	"{$prefix}card_front_powerup",
			'type'	=>	'textarea',
			'desc'	=>	'(optional)',
			'after'	=>	'<div class="clear"></div>'
		)
	)
);

$meta_boxes[] = array(
	'id'	=>	"{$prefix}card_back_details",
	'title'	=>	'Card Design - Back',
	'pages'	=>	array( 'reality_cards' ),
	'context'	=>	'advanced',
	'priority'	=>	'high',
	'fields'	=> array(
		array(
			'name'	=>	'Card Title - Back',
			'id'	=>	"{$prefix}card_back_title",
			'type'	=>	'text',
			'before'	=>	do_shortcode('[reality_card side="back"]')
		),
		array(
			'name'             => 'Featured Image',
			'id'               => "{$prefix}card_info_image",
			'type'             => 'plupload_image',
			'max_file_uploads' => 1,
		),
		array(
			'name'	=>	'Card Description - Back',
			'id'	=>	"{$prefix}card_back_description",
			'type'	=>	'textarea'
		),
		array(
			'name'	=>	'Card Set Info',
			'id'	=>	"{$prefix}card_back_setinfo",
			'type'	=>	'text',
			'after'	=>	'<div class="clear"></div>'
		)
	)
);

// AWARDS META BOXES

$rank_values = get_option('reality_rank_values');
$rank_options = array();
if ( $rank_values ) {
	foreach( $rank_values as $key => $rank ) {
	
		$rank_options[$rank['rank_slug']] = stripslashes($rank['rank_name']) . ' ('.$key.' Points)';
	
	}
}

$meta_boxes[] = array(
	'id'	=>	"{$prefix}award_details",
	'title'	=>	'Award Details',
	'pages'	=>	array( 'reality_awards' ),
	'context'	=>	'advanced',
	'priority'	=>	'high',
	'fields'	=> array(
		array(
			'name'	=>	'Award Description',
			'id'	=>	"excerpt",
			'type'	=>	'textarea'
		),
		array(
			'name'	=>	'Award Type',
			'id'	=>	"{$prefix}award_type",
			'type'	=>	'taxonomy',
			'options' => array(
				// Taxonomy name
				'taxonomy' => 'awards-tax',
				// How to show taxonomy: 'checkbox_list' (default) or 'checkbox_tree', 'select_tree' or 'select'. Optional
				'type' => 'select',
				// Additional arguments for get_terms() function. Optional
				'args' => array(
					'parent'	=>	0
				)
			)
		),
		array(
			'name'             => 'Award Image',
			'id'               => "{$prefix}award_image",
			'type'             => 'plupload_image',
			'max_file_uploads' => 1,
		),
		array(
			'name'	=>	'Award Point Value',
			'id'	=>	"{$prefix}award_value",
			'type'	=>	'number',
			'min'	=>	'0',
			'step'	=>	'100'
		),
		array(
			'name'	=>	'Point Achievement Threshold',
			'id'	=>	"{$prefix}achievement_threshold",
			'type'	=>	'select',
			'options'	=>	$rank_options
		)
	)
);

//SUBMISSION PAGE META BOXES

$submission_meta_boxes[] = array(
	'id'	=>	"{$prefix}submission_form_fields",
	'title'	=>	'Submission Form Fields',
	'pages'	=>	array( 'page' ),
	'context'	=>	'advanced',
	'priority'	=>	'high',
	'fields'	=> array(
		array(
			'name'	=>	'Deal Submitted Successfully Content',
			'id'	=>	"{$prefix}success_message",
			'type' => 'wysiwyg',
			'std'  => '<h2>You Have Successfully Submitted a Deal!</h2>',

			// Editor settings, see wp_editor() function: look4wp.com/wp_editor
			'options' => array(
				'textarea_rows' => 4,
				'teeny'         => true,
				'media_buttons' => false,
			)
		)
	)

);


// Make sure there's no errors when the plugin is deactivated or during upgrade
if ( !class_exists( 'RW_Meta_Box' ) )
		return;

	global $meta_boxes;
	foreach ( $meta_boxes as $meta_box )
	{
		new RW_Meta_Box( $meta_box );
	}
	
	//ADD META BOXES FOR SUBMISSION PAGE TEMPLATE
	
	if ( isset( $_GET['post'] ) )
		$post_id = $_GET['post'];
	elseif ( isset( $_POST['post_ID'] ) )
		$post_id = $_POST['post_ID'];
	else
		$post_id = false;

	$post_id = (int) $post_id;
	
	// Check for page template
	$checked_templates = array( 'submission_form.php' );

	$template = get_post_meta( $post_id, '_wp_page_template', true );
	if ( in_array( $template, $checked_templates ) ) {
	
		foreach ( $submission_meta_boxes as $meta_box ) {
		
			new RW_Meta_Box( $meta_box );
		
		}
	
	}
}
// Hook to 'admin_init' to make sure the meta box class is loaded before
// (in case using the meta box class in another plugin)
// This is also helpful for some conditionals like checking page template, categories, etc.
add_action( 'admin_init', 'reality_register_meta_boxes', 10 );

?>