<?php

add_action( 'init', 'create_post_type', 0 );
function create_post_type() {

	// CREATE CARD POST TYPES
	register_post_type( 'reality_cards',
		array(
			'labels' => array(
				'name' => __( 'Cards' ),
				'singular_name' => __( 'Card' ),
				'add_new_item'	=> __( 'Add New Card' ),
				'edit_item'		=> __( 'Edit Card' ),
				'new_item'	=> __( 'New Card' ),
				'view_item'	=> __( 'View Card' )
			),
			'public' => true,
			'description'	=> __( 'Reality Cards' ),
			'has_archive' => true,
			'rewrite' => array('slug' => 'cards'),
			'supports'	=> array( 'title', 'thumbnail','comments' ),
			'taxonomies'	=> array( 'card_type', 'card_value' )
		)
	);
	
	// CREATE DEAL POST TYPES
	register_post_type( 'reality_deals',
		array(
			'labels' => array(
				'name' => __( 'Deals' ),
				'singular_name' => __( 'Deal' ),
				'add_new_item'	=> __( 'Add New Deal' ),
				'edit_item'		=> __( 'Edit Deal' ),
				'new_item'	=> __( 'New Deal' ),
				'view_item'	=> __( 'View Deal' )
			),
			'public' => true,
			'description'	=> __( 'Reality Deals' ),
			'has_archive' => true,
			'rewrite' => array('slug' => 'deals' ),
			'supports'	=> array( 'title', 'editor', 'thumbnail', 'author' )
		)
	);
	
	// CREATE AWARDS POST TYPES
	register_post_type( 'reality_awards',
		array(
			'labels' => array(
				'name' => __( 'Awards' ),
				'singular_name' => __( 'Award' ),
				'add_new_item'	=> __( 'Add New Award' ),
				'edit_item'		=> __( 'Edit Award' ),
				'new_item'	=> __( 'New Award' ),
				'view_item'	=> __( 'View Award' )
			),
			'public' => true,
			'description'	=> __( 'Reality Awards' ),
			'has_archive' => true,
			'rewrite' => array('slug' => 'awards'),
			'supports'	=> array( 'title' )
		)
	);
	
	// CREATE EXPOSURE POST TYPES
	register_post_type( 'reality_exposure',
		array(
			'labels' => array(
				'name' => __( 'Exposure' ),
				'singular_name' => __( 'Exposure' ),
				'add_new_item'	=> __( 'Add New Exposure' ),
				'edit_item'		=> __( 'Edit Exposure' ),
				'new_item'	=> __( 'New Exposure' ),
				'view_item'	=> __( 'View Exposure' )
			),
			'public' => true,
			'description'	=> __( 'Reality Exposure' ),
			'has_archive' => true,
			'rewrite' => array('slug' => 'Exposure'),
			'supports'	=> array( 'title', 'editor', 'thumbnail', 'excerpt' )
		)
	);
	
	// DISABLE COMMENTS ON ALL PAGES
	remove_post_type_support( 'page', 'comments' );
}

/**
 * Create a card or award parallel taxonomy whenever a new card or award is created.
 *
 * This function generates a term in the reality_awards or reality_cards taxonomy
 * so that deals and users can be tagged with them.  They are linked in the following way:
 * 
 * AWARDS
 * Taxonomy Name		=>	Award CPT Title
 * Taxonomy Slug		=>	Award CPT Post ID
 * Taxonomy Description	=>	Award Excerpt
 *
 * CARDS
 * Taxonomy Name		=>	Card CPT Slug
 * Taxonomy Slug		=>	Card CPT Post ID
 * Taxonomy Description	=>	Card CPT Title
 *
 */

function create_parallel_tax( $post_id, $post ) {
	
	if ( !current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	
	if ( isset($_GET['post_id']) ) {
		$post_id = $_GET['post_id'];
		$post = get_post( $post_id );	
	}
	
	if ( wp_is_post_revision( $post_id ) ) {
		$post_id = wp_is_post_revision( $post_id );
		$post = get_post( $post_id );
	}
	
	if ( wp_is_post_autosave( $post_id ) ) return;
	
	if ( get_post_status( $post_id ) == 'auto-draft' ) return;
	
	if ( $post->post_type == 'reality_cards' ) {
		
		$term_args = array(
			'description'	=>	$post->post_title,
			'slug'	=>	$post_id
		);
		
		$term_info = array();
		
		$name = $post->post_name;
		$term_info['name'] = $name;
		$term_info['term_args'] = $term_args;
		
		$term_info = apply_filters( 'reality_before_create_parallel_cards_tax', $term_info, $name, $term_args );
		
		if ( $term_id = term_exists( $term_info['name'], 'cards-tax' ) ) {
			
			if ( !wp_update_term( $term_id['term_id'], 'cards-tax', $term_info['term_args'] ) ) {
				die('Could not update term!');
			};
			
		} else {
		
			wp_insert_term( $term_info['name'], 'cards-tax', $term_info['term_args'] );
		
		}
		
	} elseif ( $post->post_type == 'reality_awards' ) {
		
		if ( isset($_POST['REALITY_award_value']) && $_POST['REALITY_award_value'] != '' ) {
			$value = (int) $_POST['REALITY_award_value'];
		} else {
			$value = 0;
		}
			
		if ( isset($_POST['REALITY_award_type']) && $_POST['REALITY_award_type'] ) {
			$parent = get_term_by( 'slug', $_POST['REALITY_award_type'], 'awards-tax', 'ARRAY_A' );
		} else {
			$parent = term_exists( 'Point Achievements', 'awards-tax' );
		}

		$award_info = array(
			'description'	=>	$post->post_excerpt,
			'point_value'	=>	$value
		);
		$award_info = apply_filters( 'reality_save_parallel_awards_tax_info', $award_info, $post_id );
		
		$term_args = array(
			'description'	=>	serialize($award_info),
			'slug'	=>	$post->ID,
			'parent'	=>	$parent['term_id']
		);
		
		$term_info = array();
		
		$name = $post->post_title;
		$term_info['name'] = $name;
		$term_info['term_args'] = $term_args;
		
		$term_info = apply_filters( 'reality_before_create_parallel_awards_tax', $term_info, $name, $term_args );
		
		if ( $term_id = term_exists( $term_info['name'], 'awards-tax' ) ) {
			
			wp_update_term( $term_id['term_id'], 'awards-tax', $term_info['term_args'] );
			
		} else {
		
			wp_insert_term( $term_info['name'], 'awards-tax', $term_info['term_args'] );
		
		}
		
	} else {
		return;
	}

}
add_action( 'save_post', 'create_parallel_tax', 20, 2);



/**
 * Delete a card or award parallel taxonomy whenever a card or award is deleted.
 */

function delete_parallel_tax( $post_id ) {
	
	if ( !current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	
	if ( isset($_GET['post']) ) {
		$post_id = $_GET['post'];
	}
	
	$post = get_post( $post_id );
	
	$postType = get_post_type( $post_id );
	$postTitle = get_the_title( $post_id );
	
	if ( $postType == 'reality_cards' ) {
		
		if ( $term_id = term_exists( $post->post_name, 'cards-tax' ) ) {
			wp_delete_term( $term_id['term_id'], 'cards-tax' );
		}
	
	} elseif ( $postType == 'reality_awards' ) {
		
		if ( $term_id = term_exists( $postTitle, 'awards-tax' ) ) {
			wp_delete_term( $term_id['term_id'], 'awards-tax' );
		}
		
	} else {
	
		return;
	
	}

}
add_action( 'before_delete_post', 'delete_parallel_tax' );


/**
 * Change deals CPT view screen to show pertinent information
 */

add_filter( 'manage_edit-reality_deals_columns', 'set_custom_edit_reality_deals_columns' );
add_action( 'manage_reality_deals_posts_custom_column' , 'custom_reality_deals_columns', 10, 2 );

function set_custom_edit_reality_deals_columns($columns) {
    unset( $columns['author'] );
    unset( $columns['comments'] );
    $columns['points_value'] = __( 'Points Value', 'Reality' );
    $columns['standings'] = __( 'Standings', 'Reality' );

    return $columns;
}

function custom_reality_deals_columns( $column, $post_id ) {
    switch ( $column ) {

        case 'points_value' :
            $points_value = get_post_meta( $post_id, 'REALITY_total_value', true);
            echo $points_value;
            break;
        case 'standings' :
            $standingsInfo = '<table class="deal-standings-info">';
			$standingsInfo .= '<tbody>';
		
			if ( $standings = get_post_meta( $post_id, 'REALITY_deal_standings', true ) ) {
		
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
			echo $standingsInfo;
            break;

    }
}



?>