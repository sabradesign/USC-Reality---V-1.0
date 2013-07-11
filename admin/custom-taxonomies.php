<?php

function reality_taxonomies() {
	
	/**
	 * CREATE CARD TAXONOMY
	 * 
 	 * The CARD taxonomy is a tag-like taxonomy that are automatically generated whenever
 	 * new card custom post types are created allowing cards to be affiliated with deals
 	 * as easily as tags are associated with blog posts in Wordpress.
	 */
	$card_labels = array(
    	'name'                         => _x( 'Cards', 'taxonomy general name' ),
    	'singular_name'                => _x( 'Card', 'taxonomy singular name' ),
    	'search_items'                 => __( 'Search Cards' ),
    	'popular_items'                => __( 'Popular Cards' ),
    	'all_items'                    => __( 'All Cards' ),
    	'parent_item'                  => null,
    	'parent_item_colon'            => null,
    	'edit_item'                    => __( 'Edit Card' ), 
    	'update_item'                  => __( 'Update Card' ),
    	'add_new_item'                 => __( 'Add New Card' ),
    	'new_item_name'                => __( 'New Card Name' ),
    	'separate_items_with_commas'   => __( 'Separate cards with commas' ),
    	'add_or_remove_items'          => __( 'Add or remove cards' ),
    	'choose_from_most_used'        => __( 'Choose from the most used cards' ),
    	'not_found'                    => __( 'No cards found.' ),
    	'menu_name'                    => __( 'Cards' )
  	); 

 	 $card_args = array(
 	   'hierarchical'            => false,
 	   'labels'                  => $card_labels,
 	   'show_ui'                 => true,
 	   'show_admin_column'       => true,
 	   'update_count_callback'   => '_update_post_term_count',
 	   'query_var'               => true,
 	   'rewrite'                 => array( 'slug' => 'cards-tax' )
 	 );
	
	register_taxonomy( 'cards-tax', 'reality_deals', $card_args );
	
	/**
	 * CREATE CARD TYPE TAXONOMY
	 * 
 	 * The CARD TYPE taxonomy is a category-like taxonomy that orders the cards
 	 * based on their game-play attributes. Ex. the "maker" category are cards that
 	 * all deals must have and determine the medium of the final product
	 */
	  $cardtype_labels = array(
	    'name'                => _x( 'Card Types', 'taxonomy general name' ),
	    'singular_name'       => _x( 'Card Type', 'taxonomy singular name' ),
	    'search_items'        => __( 'Search Card Types' ),
	    'all_items'           => __( 'All Card Types' ),
	    'parent_item'         => __( 'Parent Card Type' ),
	    'parent_item_colon'   => __( 'Parent Card Type:' ),
	    'edit_item'           => __( 'Edit Card Type' ), 
	    'update_item'         => __( 'Update Card Type' ),
	    'add_new_item'        => __( 'Add New Card Type' ),
	    'new_item_name'       => __( 'New Card Type Name' ),
	    'menu_name'           => __( 'Card Types' )
	  ); 	
	
	  $cardtype_args = array(
	    'hierarchical'        => false,
	    'labels'              => $cardtype_labels,
	    'show_ui'             => true,
	    'show_admin_column'   => true,
	    'query_var'           => true,
	    'rewrite'             => array( 'slug' => 'card-type' )
	  );
	
	  register_taxonomy( 'card-type', 'reality_cards', $cardtype_args );
	  
	/**
	 * CREATE CARD VALUE TAXONOMY
	 * 
 	 * The CARD VALUE taxonomy is a category-like taxonomy that stores the cards'
 	 * possible values within the description field. These values are stored as a
 	 * comma separated list that is parsed into an array for card value extraction.
	 */
	  $cardvalue_labels = array(
	    'name'                => _x( 'Card Values', 'taxonomy general name' ),
	    'singular_name'       => _x( 'Card Value', 'taxonomy singular name' ),
	    'search_items'        => __( 'Search Card Values' ),
	    'all_items'           => __( 'All Card Values' ),
	    'parent_item'         => __( 'Parent Card Value' ),
	    'parent_item_colon'   => __( 'Parent Card Value:' ),
	    'edit_item'           => __( 'Edit Card Value' ), 
	    'update_item'         => __( 'Update Card Value' ),
	    'add_new_item'        => __( 'Add New Card Value' ),
	    'new_item_name'       => __( 'New Card Value Name' ),
	    'menu_name'           => __( 'Card Values' )
	  ); 	
	
	  $cardvalue_args = array(
	    'hierarchical'        => false,
	    'labels'              => $cardvalue_labels,
	    'show_ui'             => true,
	    'show_admin_column'   => true,
	    'query_var'           => true,
	    'rewrite'             => array( 'slug' => 'card-value' )
	  );
	
	  register_taxonomy( 'card-value', 'reality_cards', $cardvalue_args );
	  
	/**
	 * CREATE CARD CONNECTIONS TAXONOMY
	 * 
 	 * The CARD CONNECTIONS taxonomy is a category-like taxonomy that stores the number
 	 * of connections that a card can handle
	 */
	  $cardconnections_labels = array(
	    'name'                => _x( 'Card Connections', 'taxonomy general name' ),
	    'singular_name'       => _x( 'Card Connection', 'taxonomy singular name' ),
	    'search_items'        => __( 'Search Card Connections' ),
	    'all_items'           => __( 'All Card Connections' ),
	    'parent_item'         => __( 'Parent Card Connections' ),
	    'parent_item_colon'   => __( 'Parent Card Connections:' ),
	    'edit_item'           => __( 'Edit Card Connections' ), 
	    'update_item'         => __( 'Update Card Connections' ),
	    'add_new_item'        => __( 'Add New Card Connections' ),
	    'new_item_name'       => __( 'New Card Connections Name' ),
	    'menu_name'           => __( 'Card Connections' )
	  ); 	
	
	  $cardconnections_args = array(
	    'hierarchical'        => false,
	    'labels'              => $cardconnections_labels,
	    'show_ui'             => false,
	    'show_admin_column'   => true,
	    'query_var'           => true,
	    'rewrite'             => array( 'slug' => 'card-connections' )
	  );
	
	  register_taxonomy( 'card-connections', 'reality_cards', $cardconnections_args );  
	  
	/**
	 * CREATE CARD SET TAXONOMY
	 * 
 	 * The CARD SET taxonomy is a tag-like taxonomy
	 */
	 
	  $cardsets_labels = array(
	    'name'                => _x( 'Card Sets', 'taxonomy general name' ),
	    'singular_name'       => _x( 'Card Set', 'taxonomy singular name' ),
	    'search_items'        => __( 'Search Card Sets' ),
	    'all_items'           => __( 'All Card Set' ),
	    'parent_item'         => __( 'Parent Card Set' ),
	    'parent_item_colon'   => __( 'Parent Card Set:' ),
	    'edit_item'           => __( 'Edit Card Set' ), 
	    'update_item'         => __( 'Update Card Set' ),
	    'add_new_item'        => __( 'Add New Card Set' ),
	    'new_item_name'       => __( 'New Card Set Name' ),
	    'menu_name'           => __( 'Card Sets' )
	  ); 	
	
	  $cardsets_args = array(
	    'hierarchical'        => false,
	    'labels'              => $cardsets_labels,
	    'show_ui'             => true,
	    'show_admin_column'   => true,
	    'query_var'           => true,
	    'rewrite'             => array( 'slug' => 'card-sets' )
	  );
	
	  register_taxonomy( 'card-sets', 'reality_cards', $cardsets_args );  
	
	/**
	 * CREATE AUTHORS TAXONOMY
	 * 
 	 * The AUTHORS taxonomy is a tag-like taxonomy that are automatically generated whenever
 	 * new users created allowing multiple authors to be affiliated with deals
 	 * as easily as tags are associated with blog posts in Wordpress.
	 */
	$author_labels = array(
    	'name'                         => _x( 'Authors', 'taxonomy general name' ),
    	'singular_name'                => _x( 'Author', 'taxonomy singular name' ),
    	'search_items'                 => __( 'Search Authors' ),
    	'popular_items'                => __( 'Popular Authors' ),
    	'all_items'                    => __( 'All Authors' ),
    	'parent_item'                  => null,
    	'parent_item_colon'            => null,
    	'edit_item'                    => __( 'Edit Author' ), 
    	'update_item'                  => __( 'Update Author' ),
    	'add_new_item'                 => __( 'Add New Author' ),
    	'new_item_name'                => __( 'New Author Name' ),
    	'separate_items_with_commas'   => __( 'Separate authors with commas' ),
    	'add_or_remove_items'          => __( 'Add or remove authors' ),
    	'choose_from_most_used'        => __( 'Choose from the most used authors' ),
    	'not_found'                    => __( 'No authors found.' ),
    	'menu_name'                    => __( 'Authors' )
  	); 

 	 $author_args = array(
 	   'hierarchical'            => false,
 	   'labels'                  => $author_labels,
 	   'show_ui'                 => true,
 	   'show_admin_column'       => true,
 	   'update_count_callback'   => '_update_post_term_count',
 	   'query_var'               => true,
 	   'rewrite'                 => array( 'slug' => 'authors-tax' )
 	 );
	
	register_taxonomy( 'authors-tax', 'reality_deals', $author_args );
	
	/**
	 * CREATE AWARDS TAXONOMY
	 * 
 	 * The AWARDS taxonomy is a tag-like taxonomy that are automatically generated whenever
 	 * new award custom post types are created allowing awards to be affiliated with users
 	 * as easily as tags are associated with blog posts in Wordpress.
	 */
	$award_labels = array(
    	'name'                         => _x( 'Awards', 'taxonomy general name' ),
    	'singular_name'                => _x( 'Award', 'taxonomy singular name' ),
    	'search_items'                 => __( 'Search Awards' ),
    	'popular_items'                => __( 'Popular Awards' ),
    	'all_items'                    => __( 'All Awards' ),
    	'parent_item'                  => null,
    	'parent_item_colon'            => null,
    	'edit_item'                    => __( 'Edit Award' ), 
    	'update_item'                  => __( 'Update Award' ),
    	'add_new_item'                 => __( 'Add New Award' ),
    	'new_item_name'                => __( 'New Award Name' ),
    	'separate_items_with_commas'   => __( 'Separate awards with commas' ),
    	'add_or_remove_items'          => __( 'Add or remove awards' ),
    	'choose_from_most_used'        => __( 'Choose from the most used awards' ),
    	'not_found'                    => __( 'No awards found.' ),
    	'menu_name'                    => __( 'Awards' )
  	); 

 	 $award_args = array(
 	   'hierarchical'            => true,
 	   'labels'                  => $award_labels,
 	   'show_ui'                 => false,
 	   'show_admin_column'       => true,
 	   'update_count_callback'   => '_update_post_term_count',
 	   'query_var'               => true,
 	   'rewrite'                 => array( 'slug' => 'awards-tax' )
 	 );
	
	register_taxonomy( 'awards-tax', array('reality_deals','reality_awards'), $award_args );
	
}
add_action( 'init', 'reality_taxonomies', 1 );
add_action( 'admin_init', 'reality_taxonomies', 1 );

/**
 * Create an author taxonomy whenever a new user is create.
 *
 * This function generates a new user in the reality_authors taxonomy
 * so that deals can be tagged with multiple users.  The user is uniquely
 * linked to the taxonomy by storing the user's ID as the taxonomy slug, both
 * of which are unique index variable.
 */
 
function create_parallel_user_tax( $user_ID ) {
	
	if ( isset( $_POST['first_name'] ) && $_POST['first_name'] != '' && isset( $_POST['last_name'] ) && $_POST['last_name'] != '' ) {
		$name = $_POST['first_name'] . ' ' . $_POST['last_name'];
	} else {
		$name = $_POST['user_login'];
	}
	
	if ( isset( $_POST['user_login'] ) ) {
		$nicename = $_POST['user_login'];
	} else {
		$nicename = bp_core_get_username( $user_ID );
	}
	
	$additional_user_info = array(
		'nicename'	=>	$nicename
	);
	
	$additional_user_info = apply_filters( 'reality_user_taxonomy_additional_info_update', $additional_user_info, $user_ID );
	
	$term_args = array(
			'description'	=>	serialize($additional_user_info),
			'slug'	=>	$user_ID
		);
		
	$term_info['name'] = $name;
	$term_info['term_args'] = $term_args;
	
	$term_info = apply_filters( 'reality_before_create_parallel_user_tax', $term_info, $name, $term_args );
		
	wp_insert_term( $term_info['name'], 'authors-tax', $term_info['term_args'] );
	
	// SET PRELIMINARY USER DATA
	
	update_user_meta( $user_ID, 'reality_current_points', 0 );
	$current_points = 0;
	
	$ranks = get_option( 'reality_rank_values' );
	if ( !isset( $ranks[0] ) ) {
		$ranks[0]['rank_name'] = 'Unranked';
		$ranks[0]['rank_slug'] = 'unranked';
	}
	
	foreach( $ranks as $key => $rank ) {
		
		if ( $current_points < $key ) {
			$current_rank = $ranks[$previous]['rank_name'];
			$current_rank_slug = $ranks[$previous]['rank_slug'];
			$points_spread = $key - $previous;
			$points_to_next_level = $key - $current_points;
			$percent_to_next_level = (float) (1 - ($points_to_next_level / $points_spread))*100;
			$points_towards_next_level = $points_spread - $points_to_next_level;
			break;
		}
		
		$previous = $key;
	}
	
	$rank_info = array(
		'current_rank'			=>	$current_rank,
		'current_rank_slug'		=>	$current_rank_slug,
		'points_to_next_level'	=>	$points_to_next_level,
		'points_spread'			=>	$points_spread,
		'percent_to_next_level'	=>	$percent_to_next_level,
		'points_towards_next_level'	=>	$points_towards_next_level
	);
	
	update_user_meta( $user_ID, 'reality_current_rank', $rank_info );

}
add_action( 'user_register', 'create_parallel_user_tax' );

function update_parallel_user_tax( $user_ID, $old_user_data ) {

	if ( isset( $_POST['_wp_http_referer'] ) && strchr($_POST['_wp_http_referer'], '/members/') ) return;

	if ( isset( $_POST['first_name'] ) && $_POST['first_name'] != '' && isset( $_POST['last_name'] ) && $_POST['last_name'] != '' ) {
		$name = $_POST['first_name'] . ' ' . $_POST['last_name'];
	} else {
		$name = $_POST['user_login'];
	}
	
	if ( isset( $_POST['user_login'] ) ) {
		$nicename = $_POST['user_login'];
	} else {
		$nicename = bp_core_get_username( $user_ID );
	}
	
	$additional_user_info = array(
		'nicename'	=>	$nicename
	);
	
	$additional_user_info = apply_filters( 'reality_user_taxonomy_additional_info_update', $additional_user_info, $user_ID );
	
	$term_args = array(
			'description'	=>	serialize($additional_user_info),
			'slug'	=>	$user_ID
		);
		
	$term_info['name'] = $name;
	$term_info['term_args'] = $term_args;
	
	$term_info = apply_filters( 'reality_before_create_parallel_user_tax', $term_info, $name, $term_args );
	
	if ( $term = get_term_by( 'slug', $term_info['term_args']['slug'], 'authors-tax' ) ) {
	
		$term_info['term_args']['name'] = $name;
		wp_update_term( $term->term_id, 'authors-tax', $term_info['term_args'] );
	
	} else {
	
		wp_insert_term( $term_info['name'], 'authors-tax', $term_info['term_args'] );
	
	}

}
add_action( 'profile_update', 'update_parallel_user_tax', 10, 2 );

/**
 * Delete an author taxonomy whenever a new user is create.
 *
 * This function deletes the parallel reality_authors taxonomy term
 * affiliated with this user.
 */
 
 function delete_parallel_user_tax( $user_ID ) {

	$user = get_userdata( $user_ID );
	$name = $user->user_firstname . ' ' . $user->user_lastname;
		
	if ( $term = term_exists( $name, 'authors-tax' ) ) {
		wp_delete_term( $term['term_id'], 'authors-tax' );
	}

}
add_action( 'delete_user', 'delete_parallel_user_tax' );
 
?>