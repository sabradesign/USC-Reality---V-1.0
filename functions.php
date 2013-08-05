<?php

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/admin/class-tgm-plugin-activation.php';
require_once( 'admin/theme_init.php' );

require_once( 'admin/reality-class.php' );

//MAKE MESSAGE AUTO-COMPLETE SHOW ALL REALITY MEMBERS, RATHER THAN JUST FRIENDS
define('BP_MESSAGES_AUTOCOMPLETE_ALL', TRUE);

add_action( 'tgmpa_register', 'uscreality_register_required_plugins' );
function uscreality_register_required_plugins() {
	$plugins = array(

		// This is an example of how to include a plugin pre-packaged with a theme
		array(
			'name'     				=> 'Meta Box Plugin', // The plugin name
			'slug'     				=> 'meta-box', // The plugin slug (typically the folder name)
			'source'   				=> get_stylesheet_directory() . '/admin/plugins/meta-box.zip', // The plugin source
			'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
			'force_activation' 		=> true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
		),

		// This is an example of how to include a plugin from the WordPress Plugin Repository
		array(
			'name' 		=> 'BuddyPress',
			'slug' 		=> 'buddypress',
			'required' 	=> true,
			'force_activation' 		=> true, 
			'force_deactivation' 	=> false
		),
		array(
			'name' 		=> 'WP-Less',
			'slug' 		=> 'wp-less',
			'required' 	=> true,
			'force_activation' 		=> true, 
			'force_deactivation' 	=> false
		),
		array(
			'name'		=>	'User Switching',
			'slug'		=>	'user-switching',
			'required'	=>	true,
			'force_activation' 		=> true, 
			'force_deactivation' 	=> false
		)

	);


	$theme_text_domain = 'uscreality';
	$config = array(
		'domain'       		=> $theme_text_domain,         	// Text domain - likely want to be the same as your theme.
		'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
		'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
		'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'is_automatic'    	=> true,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                       			=> __( 'Install Required Plugins', $theme_text_domain ),
			'menu_title'                       			=> __( 'Install Plugins', $theme_text_domain ),
			'installing'                       			=> __( 'Installing Plugin: %s', $theme_text_domain ), // %1$s = plugin name
			'oops'                             			=> __( 'Something went wrong with the plugin API.', $theme_text_domain ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
			'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
			'return'                           			=> __( 'Return to Required Plugins Installer', $theme_text_domain ),
			'plugin_activated'                 			=> __( 'Plugin activated successfully.', $theme_text_domain ),
			'complete' 									=> __( 'All plugins installed and activated successfully. %s', $theme_text_domain ), // %1$s = dashboard link
			'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);

	tgmpa( $plugins, $config );

}

add_filter( 'bp_activity_enable_afilter_support', '__return_true' );

//LOAD FRONT-END JS

function reality_enqueue_scripts() {
	
	if ( !is_admin())  {
		
		wp_enqueue_script( 'modernizr', get_stylesheet_directory_uri() . '/js/modernizr.js', array( 'jquery' ) );
		wp_enqueue_script( 'fancybox', get_stylesheet_directory_uri() . '/js/fancybox/jquery.fancybox-1.3.4.js', array( 'jquery' ) );
		wp_enqueue_script( 'fancybox-mousewheel', get_stylesheet_directory_uri() . '/js/fancybox/jquery.mousewheel-3.0.4.pack.js', array( 'jquery', 'fancybox' ) );
		wp_enqueue_script( 'flexslider', get_stylesheet_directory_uri() . '/js/flexslider/jquery.flexslider-min.js', array( 'jquery' ) );
		//wp_enqueue_script( 'lessJS', get_stylesheet_directory_uri() . '/js/less-1.3.3.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'dtheme-ajax-js', get_stylesheet_directory_uri() . '/js/global.js', array( 'jquery' ), bp_get_version() );
		wp_enqueue_script( 'reality', get_stylesheet_directory_uri() . '/js/reality.js', array( 'jquery', 'fancybox', 'flexslider', 'modernizr' ) );


		//$translation_array = array( 'env' => 'development', 'async' => false, 'fileAsync' => false, 'poll' => 5000, 'dumpLineNumbers' => "comments", 'relativeUrls' => false );
		//wp_localize_script( 'lessJS', 'less', $translation_array );
	}
	
	if ( is_page_template('submission_form.php' ) ) {
	
			//wp_enqueue_style( 'rwmb-plupload-image', RWMB_CSS_URL . 'plupload-image.css', array( 'wp-admin' ), RWMB_VER );
			wp_enqueue_script( 'rwmb-plupload-image', RWMB_JS_URL . 'plupload-image.js', array( 'jquery-ui-sortable', 'wp-ajax-response', 'plupload-all' ), RWMB_VER, true );
			wp_localize_script( 'rwmb-plupload-image', 'RWMB', array( 'url' => RWMB_URL ) );
			wp_localize_script( 'rwmb-plupload-image', 'rwmb_plupload_defaults', array(
				'runtimes'            => 'html5,silverlight,flash,html4',
				'file_data_name'      => 'async-upload',
				'multiple_queues'     => true,
				'max_file_size'       => wp_max_upload_size() . 'b',
				'url'                 => admin_url( 'admin-ajax.php' ),
				'flash_swf_url'       => includes_url( 'js/plupload/plupload.flash.swf' ),
				'silverlight_xap_url' => includes_url( 'js/plupload/plupload.silverlight.xap' ),
				'filters'             => array(
					array(
						'title'      => _x( 'Allowed Image Files', 'image upload', 'rwmb' ),
						'extensions' => 'jpg,jpeg,gif,png',
					),
				),
				'multipart'        => true,
				'urlstream_upload' => true,
			) );
			
			//wp_enqueue_script( 'jquery-ui-menu', get_stylesheet_directory_uri() . '/js/jquery-ui/jquery.ui.menu.js', array( 'jquery-ui-core' ) );
			wp_enqueue_script( 'jquery-ui-autocomplete' );
	}
	
	if ( is_singular('reality_deals') ) {
	
		wp_enqueue_script ('jquery-ui-widget', get_stylesheet_directory_uri() . '/js/audio-player/jquery.ui.widget.min.js', array( 'jquery' ) );
		wp_enqueue_script ('audio-player', get_stylesheet_directory_uri() . '/js/audio-player/AudioPlayerV1.js', array( 'jquery', 'jquery-ui-widget' ) );
	
	}
}
add_action( 'wp_enqueue_scripts', 'reality_enqueue_scripts', 9);

function reality_enqueue_styles() {

	if ( !is_admin() ) {

		wp_enqueue_style( 'reset', get_stylesheet_directory_uri() . '/css/reset.css' );
		wp_enqueue_style( 'fancybox', get_stylesheet_directory_uri() . '/js/flexslider/flexslider.css' );
		wp_enqueue_style( 'flexslider', get_stylesheet_directory_uri() . '/js/fancybox/jquery.fancybox-1.3.4.css' );
		wp_enqueue_style( 'less-css', get_stylesheet_directory_uri() . '/css/styles.less', array( 'bp-default-responsive' ) );
		wp_enqueue_style( 'reality-cards-css', get_stylesheet_directory_uri() . '/css/reality-cards.css', array( 'bp-default-responsive') );
		wp_enqueue_style( 'custom-less', get_stylesheet_directory_uri() . '/custom.less', array(  'bp-default-responsive', 'reality-cards-css' ) );

	}
	
	if ( is_page_template('submission_form.php' ) ) {

			wp_enqueue_style( 'jquery-ui-menu', get_stylesheet_directory_uri() . '/js/jquery-ui/jquery.ui.menu.css' );
	}
}
add_action( 'init', 'reality_enqueue_styles');

//BUDDYPRESS ALTERATIONS

function reality_add_default_avatar( $url )
{
	return 'http://reality.usc.edu/assets/avatars/89/90d34f5df6df0d7c40edc87e792b7a4f-bpfull.jpg';
}
add_filter( 'bp_core_mysteryman_src', 'reality_add_default_avatar' );

function reality_remove_buddypress_actions() {
	remove_action( 'bp_member_header_actions',    'bp_send_public_message_button',  20 );
}
add_action( 'after_setup_theme', 'reality_remove_buddypress_actions' );

//WIDGET SHORTCODES SHOULD WORK TOO

add_filter('widget_text', 'do_shortcode');

//LOAD ADMIN JS

function reality_enqueue_admin_scripts() {

	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'reality-admin-js', get_stylesheet_directory_uri() . '/admin/js/admin.js', array( 'jquery' ) );

	wp_enqueue_style( 'jquery-ui', 'http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css' );
	wp_enqueue_style( 'reality-admin-css', get_stylesheet_directory_uri() . '/admin/admin.less');
	wp_enqueue_style( 'reality-cards-css', get_stylesheet_directory_uri() . '/css/reality-cards.css');
	
	if (class_exists('WPLessPlugin')){
	
		$less = WPLessPlugin::getInstance();

		// do stuff with its API like:
		$less->processStylesheets();
	
	}
	
}
add_action( 'admin_init', 'reality_enqueue_admin_scripts' );


// ADD CUSTOM POST TYPES

include_once 'admin/custom-post-types.php';

// ADD CUSTOM TAXONOMIES

include_once 'admin/custom-taxonomies.php';

// ADD META BOXES

include_once 'admin/meta-boxes.php';

// INCLUDE THE POINTS SYSTEM

include_once 'admin/points_system.php';

// ADD USER PROFILE INFO

include_once 'admin/admin_menus.php';

// ADD USER PROFILE INFO

include_once 'admin/user_profiles.php';

// ADD USER PROFILE INFO

include_once 'admin/shortcodes.php';

// ADD USER PROFILE INFO

include_once 'admin/realityImporter.php';

// ADD REALITY SIDEBARS

include_once 'admin/sidebars.php';

// ADD DEALS TEMPLATE

include_once 'admin/deals-template.php';

// ADD PHOTO-BLOG FUNCTIONS

include_once 'admin/photo-blog.php';

// ADD AJAX FUNCTIONS

include_once 'admin/reality-ajax.php';

// ADD DEPARTMENT FUNCTIONS

include_once 'admin/departments_functions.php';

// ADD THUMBNAIL IMAGE SIZES

function reality_thumbnail_images() {

	add_image_size( 'reality_deal_card_layout', 312, 357, true );
	
}
add_action( 'init', 'reality_thumbnail_images' );

// TIMTHUMB PHOTO GENERATOR
function timthumb_photo( $img_src, $width = '', $height = '', $other_attr = '', $echo = true ) {
	$output = get_bloginfo( 'stylesheet_directory' ).'/timthumb.php?src='.$img_src.'&w='.$width.'&h='.$height;

	if ( $other_attr != '' ) $output .= '&'.$other_attr;

	if ( $echo ) {
		echo $output;
	} else {
		return $output;
	}
}

// HANDLE DEAL SUBMISSION FORM

function reality_submit_deal() {

	if ( isset($_POST['action']) && $_POST['action'] == 'reality_submit_deal' ) {

		global $bp, $deal_submission_status;
	
		if ( wp_verify_nonce( $_POST['_wpnonce_reality_submit_deal'], 'reality_submit_deal' ) ) {
		
			$deal_messages = array();

			// 1. MAKE THE NEW REALITY_DEALS POST
			// Pull In all necessary variables and format them.
			if ( $_POST['deal_title'] != '' ) {
				$deal_title = $_POST['deal_title'];
			} else {
				$deal_title = '';
				$deal_messages['errors'][1] = "You must set a deal title.";
			}
			
			if ( $_POST['deal_logline'] != '' ) {
				$deal_logline = $_POST['deal_logline'];
			} else {
				$deal_messages['errors'][2] = "You must set a deal log-line.";
			}
	
			if ( $_POST['deal_youtube_id'] != '' ) $deal_youtube_id = $_POST['deal_youtube_id'];
	
			if ( !empty( $_FILES['deal_featured_image'] ) && $_FILES['deal_featured_image']['name'] != '' ) {
				$deal_thumbnail = $_FILES['deal_featured_image'];
			} else {
				$deal_messages['errors'][3] = "You must set a deal featured image.";
			}
	
			if ( !empty( $_FILES['deal_images'] ) ) $deal_images = $_FILES['deal_images'];
	
			if ( isset( $_FILES['deal_files'] ) ) $deal_files = $_FILES['deal_files'];
	
			if ( $_POST['deal_collaborators'] != '' ) {
				$deal_collaborators = $_POST['deal_collaborators'];
				//$deal_collaborators = str_replace( ', ', ',', $deal_collaborators );
				//$deal_collaborators = explode( ',', $deal_collaborators );
				$deal_collaborators_slugs = array();
	
				// Convert author names to taxonomy id's
				foreach( $deal_collaborators as $collaborator ) {
					if ( $collaborator_ID = term_exists( $collaborator, 'authors-tax' ) ) $deal_collaborators_ids[] = (int) $collaborator_ID['term_id'];
				}
		
				$deal_collaborators_ids = array_map('intval', $deal_collaborators_ids);
    			$deal_collaborators_ids = array_unique( $deal_collaborators_ids );
			} else {
				$deal_messages['errors'][4] = "A deal must have at least one author.";
			}
	
			// If submitting user did not add themselves to the list, add them now.
			if ( !in_array( $bp->loggedin_user->id, $deal_collaborators_ids ) ) $deal_collaborators_ids[] = (int) $bp->loggedin_user->id;
	
			if ( !empty($_POST['deal_cards']) ) {
				$deal_cards = $_POST['deal_cards'];
				//$deal_cards = str_replace( ' ', '', $deal_cards );
				//$deal_cards = explode( ',', $deal_cards );
				$deal_card_ids = array();
				foreach( $deal_cards as $card ) {
					if ( $card_id = term_exists( $card, 'cards-tax' ) ) $deal_card_ids[] = (int) $card_id['term_id'];
				}
			} else {
				$deal_messages['errors'][5] = "You must add cards.";
			}
	
			// Make sure the maker card is in here.
			$maker_card = $_POST['maker_card_id'];
			if ( $maker_card != '' ) {
				$card_id = term_exists( $maker_card, 'cards-tax' );
				if ( !in_array( $card_id['term_id'], $deal_card_ids ) ) {
					$deal_card_ids[] = (int) $card_id['term_id'];
				}
			}
			$deal_card_ids = array_map('intval', $deal_card_ids);
    		$deal_card_ids = array_unique( $deal_card_ids );
	
			if ( $_POST['deal_notes'] != '' ) $deal_notes = $_POST['deal_notes'];
	
			// Create the new deal and set to pending
	
			$newDeal = array(
			  'comment_status' => 'open',
			  'ping_status'    => 'open',
  			  'post_author'    => $bp->loggedin_user->id,
			  'post_status'    => 'pending',
			  'post_title'     => $deal_title,
			  'post_type'      => 'reality_deals'
			);  
			
			if ( empty( $deal_messages['errors'] ) ) {
			
				if ( !$dealID = wp_insert_post( $newDeal ) ) {
					$deal_messages['errors'][] = 'Could not create deal...';
				} else {
					$deal_messages['errors'][] = 'Successfully created deal!';
					
					// ADD TAXONOMIES
		
					$authors = wp_set_object_terms( $dealID, $deal_collaborators_ids, 'authors-tax' );
		
					$cards = wp_set_object_terms( $dealID, $deal_card_ids, 'cards-tax' );
	
					// SET POST META
	
					if ( isset($deal_notes) ) update_post_meta( $dealID, 'REALITY_deal_notes', $deal_notes );
					if ( isset($deal_youtube_id) ) update_post_meta( $dealID, 'REALITY_deal_video_link', $deal_youtube_id );
					if ( isset($deal_logline) ) update_post_meta( $dealID, 'REALITY_deal_logline', $deal_logline );
	
					// ATTACH DEAL IMAGES AND FILES
					if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
					
					// ATTACH THUMBNAIL IMAGE
					
					$uploadedfile = $deal_thumbnail;
								
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
						$attach_id = wp_insert_attachment( $attachment, $filename, $dealID );
						require_once(ABSPATH . 'wp-admin/includes/image.php');
						$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
						wp_update_attachment_metadata( $attach_id, $attach_data );
						
						add_post_meta( $dealID, '_thumbnail_id', $attach_id, false );
					   
					}
					
					
					// Attach Images
					if ( !empty($deal_images) ) {
					
						foreach( $deal_images['error'] as $key => $error ) {
						
							if ($error == UPLOAD_ERR_OK) {
							
								$uploadedfile = array(
									'name'	=> $deal_images['name'][$key],
									'type'	=>	$deal_images['type'][$key],
									'size'	=>	$deal_images['size'][$key],
									'tmp_name'	=>	$deal_images['tmp_name'][$key],
									'error'		=>	$deal_images['error'][$key]
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
									$attach_id = wp_insert_attachment( $attachment, $filename, $dealID );
									require_once(ABSPATH . 'wp-admin/includes/image.php');
									$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
									wp_update_attachment_metadata( $attach_id, $attach_data );
									
									add_post_meta( $dealID, 'REALITY_deal_images', $attach_id, false );
							    
								}
							
							}
						
						}
					
					}
					
					// Attach Files
					if ( !empty($deal_files) ) {
					
						foreach( $deal_files['error'] as $key => $error ) {
						
							if ($error == UPLOAD_ERR_OK) {
							
								$uploadedfile = array(
									'name'	=> $deal_files['name'][$key],
									'type'	=>	$deal_files['type'][$key],
									'size'	=>	$deal_files['size'][$key],
									'tmp_name'	=>	$deal_files['tmp_name'][$key],
									'error'		=>	$deal_files['error'][$key]
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
									$attach_id = wp_insert_attachment( $attachment, $filename, $dealID );
									require_once(ABSPATH . 'wp-admin/includes/image.php');
									$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
									wp_update_attachment_metadata( $attach_id, $attach_data );
									
									add_post_meta( $dealID, 'REALITY_deal_files', $attach_id, false );
							    
								}
							
							}
						
						}
					
					}
					
					$deal_messages['success'] = true;
					
				}
	
			}
	
				
				$deal_submission_status = $deal_messages;
				return $deal_messages;
	
		} else {
	
			$deal_messages['errors'][] = 'Could not verify nonces...';
			$deal_submission_status = $deal_messages;
		return $deal_messages;
	
		}
	
	}

}
add_action( 'init', 'reality_submit_deal', 999 );

function reality_user_redirect() {

	global $bp, $post;

	if ( $bp->current_component != '' || ( is_object( $post ) && $post->post_status == 'private' ) ) {
	
		if ( !is_user_logged_in() ) {
		
		
			$click_through = $_SERVER['REQUEST_URI'];
			$redirect = wp_login_url( $click_through );
		
			wp_redirect( $redirect );
			
			exit;
		
		}
	
	}

}
add_action( 'get_header', 'reality_user_redirect', 1 );

function reality_admin_redirect() {

	global $pagenow;
	
	if( is_admin() && $pagenow != 'admin-ajax.php' && !current_user_can( 'administrator' ) ) {
  		wp_redirect( home_url() );
  		exit();
 	}

}
add_action( 'init', 'reality_admin_redirect' );

//add_action('init','reality_login_page_redirect');
// function reality_login_page_redirect(){
//  global $pagenow;
//  if( 'wp-login.php' == $pagenow && empty( $_POST ) ) {
//   wp_redirect( site_url( 'login' ) );
//   exit();
//  }
// }

function reality_login_page_redirect() {

	if ( !isset($_POST['log']) && !isset($_REQUEST['action']) ) {
		wp_redirect( site_url( 'login' ) );
		exit();
	}

}
add_action( 'login_init', 'reality_login_page_redirect' );

// HIDE ADMIN BAR FOR ALL
function reality_disabled_admin_bar() {
	if ( !current_user_can( 'administrator' ) ) {
		show_admin_bar(false);
	}
}
add_action( 'init', 'reality_disabled_admin_bar' );

// ADMIN BAR FILTER
function reality_add_admin_bar_class( $classes ) {
	
	if ( is_admin_bar_showing() ) {
		$classes[] = 'admin-bar';
	}
	
	return $classes;
}
add_filter( 'body_class', 'reality_add_admin_bar_class' );

function reality_export_cards_to_csv() {

	if ( isset($_GET['action']) && $_GET['action'] == 'export_cards_to_csv' ) {
	
		require_once( 'admin/parsecsv.lib.php' );
		
		$cards = get_posts( array( 'post_type' => 'reality_cards', 'numberposts' => -1 ) );
		$outputArray = array();
		$headers = array( 'CardNumber', 'CardName' );
		$outputArray[] = $headers;
		
		foreach( $cards as $card ) {
			
			$cardInfo = array( $card->post_name, $card->post_title );
			$outputArray[] = $cardInfo;
			
		}
		
		$csv = new parseCSV();
		$csv2 = new parseCSV();
		
		$csv->output (true, 'cards.csv', $outputArray);
	
	}

}
add_action( 'admin_init', 'reality_export_cards_to_csv' );

//CARD REDIRECT FROM FORM

function reality_redirect_to_card() {

	if ( isset($_GET['card']) ) {
	
		if ( $card = term_exists( $_GET['card'], 'cards-tax' ) ) {
		
			$cardTax = get_term( $card['term_id'], 'cards-tax' );
			$redirect = get_permalink( $cardTax->slug );
			wp_redirect( $redirect );
			exit();
		
		} else {
		
			$redirect = home_url();
			wp_redirect( $redirect );
			exit();
		
		}
	
	}

}
add_action( 'init', 'reality_redirect_to_card', 9999 );

//REMOVE UNWANTED ACTIONS

function reality_remove_default_actions() {

	remove_action( 'bp_notification_settings', 'friends_screen_notification_settings' );
	remove_action( 'bp_actions', 'bp_activity_action_post_update' );

}
add_action( 'init', 'reality_remove_default_actions', 9999 );

// GET ROLES FUNCTION
// function get_editable_roles() {
//     global $wp_roles;
// 
//     $all_roles = $wp_roles->roles;
//     $editable_roles = apply_filters('editable_roles', $all_roles);
// 
//     return $editable_roles;
// }

// POST COMMENTS DISPLAY
function bp_dtheme_blog_comments( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;

	if ( 'pingback' == $comment->comment_type )
		return false;

	if ( 1 == $depth )
		$avatar_size = 50;
	else
		$avatar_size = 25;
	?>

	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<div class="acomment-avatar">
			<a href="<?php echo get_comment_author_url(); ?>" rel="nofollow">
				<?php if ( $comment->user_id ) : ?>
					<?php echo bp_core_fetch_avatar( array( 'item_id' => $comment->user_id, 'width' => $avatar_size, 'height' => $avatar_size, 'email' => $comment->comment_author_email ) ); ?>
				<?php else : ?>
					<?php echo get_avatar( $comment, $avatar_size ); ?>
				<?php endif; ?>
			</a>
		</div>

		<div class="acomment-meta">
			<?php
						/* translators: 1: comment author url, 2: comment author name, 3: comment permalink, 4: comment date/timestamp*/
						printf( __( '<a href="%1$s" rel="nofollow">%2$s</a> said on <a href="%3$s"><span class="time-since">%4$s</span></a>', 'buddypress' ), get_comment_author_url(), get_comment_author(), get_comment_link(), get_comment_date() );
					?>
		</div>

		<div class="acomment-content"><?php comment_text(); ?></div>

		<div class="acomment-options">

			<?php if ( comments_open() ) : ?>
					<?php comment_reply_link( array( 'depth' => $depth, 'max_depth' => 2 ) ); ?>
				<?php endif; ?>
			<div class="clear"></div>
		</div>


<?php
}

// CARD COMMENT -> ACTIVITY HOOK

function reality_add_activity_on_comment( $id, $comment ) {

	global $bp;

	$user_id = $comment->user_id;
	$card = get_post( (int) $comment->comment_post_ID );
	$card_link = '<a href="'.get_permalink($card->ID).'" title="'.$card->post_title.'">#'.$card->post_name.' - '.$card->post_title.'</a>';

	switch( $card->post_type ) {
		case 'reality_cards':
			$post_activity = true;
			
			// Record this on the user's profile
			$from_user_link   = bp_core_get_userlink( $user_id );
			$activity_action  = sprintf( __( '%s commented on the card %s!', 'Reality' ), $from_user_link, $card_link );
			$activity_content = $comment->comment_content;
			$primary_link     = get_permalink( $card->ID );
		
			$activity_args = array(
				'user_id'		=>	$user_id,
				'action'		=>	$activity_action,
				'content'		=>	$activity_content,
				'primary_link'	=> $primary_link,
				'type'			=>	'reality_card_comment',
				'item_id'		=>	$card->ID,
				'component'		=> $bp->activity->id
			);
			break;
		default:
			$post_activity = false;
	
	}
	
	if ( $post_activity ) { 
		$activity = bp_activity_add( $activity_args );
	}

}
add_action( 'wp_insert_comment' , 'reality_add_activity_on_comment', 10, 2 );

// REGISTER SIDEBARS


// REMOVE "PRIVATE:" FROM PRIVATE TITLE POSTS
function the_title_trim($title)
{
  $pattern[0] = '/Protected:/';
  $pattern[1] = '/Private:/';
  $replacement[0] = ''; // Enter some text to put in place of Protected:
  $replacement[1] = ''; // Enter some text to put in place of Private:

  return preg_replace($pattern, $replacement, $title);
}
add_filter('the_title', 'the_title_trim');

// REMOVE DELETE ACCOUNT OPTION
function reality_no_account_delete_option() {

	return '';

}
add_filter( 'bp_get_options_nav_delete-account', 'reality_no_account_delete_option' );

// REALITY USER PERMISSIONS
function reality_user_permissions() {
	
	$reality_roles = array();
	
	$reality_roles[] = array(
		'role' => 'player',
		'display_name' => 'Player',
		'capabilities' => array( 
			'upload_files' => true,
			'read' => true,
			'publish_posts' => true,
			'read_private_pages' => true,
			'read_private_posts' => true )		
	);
	
	$reality_roles[] = array(
		// Add Game Master Role
		'role' => 'game-master',
		'display_name' => 'Game Master',
		'capabilities' => array( 
			'delete_others_pages' => true,
			'delete_others_posts' => true,
			'delete_pages' => true,
			'delete_posts' => true,
			'delete_private_pages' => true,
			'delete_private_posts' => true,
			'delete_published_pages' => true,
			'delete_published_posts' => true,
			'edit_others_pages' => true,
			'edit_others_posts' => true,
			'edit_pages' => true,
			'edit_posts' => true,
			'edit_private_pages' => true,
			'edit_private_posts' => true,
			'edit_published_pages' => true,
			'edit_published_posts' => true,
			'manage_categories' => true,
			'manage_links' => true,
			'moderate_comments' => true,
			'publish_pages' => true,
			'publish_posts' => true,
			'read_private_pages' => true,
			'read_private_posts' => true,
			'unfiltered_html' => true,
			'upload_files' => true,
			'read' => true,
			'publish_posts' => true
		)
	);
	
	if ( get_option('reality_use_departments') ) {
		$depts = get_option('reality_player_departments');
		
		foreach( $depts as $slug => $dept ) {
		
			$reality_roles[] = array(
				'role' => $slug,
				'display_name' => $dept,
				'capabilities' => array( 
					'upload_files' => true,
					'read' => true,
					'publish_posts' => true,
					'read_private_pages' => true,
					'read_private_posts' => true )		
			);
		
		}
	}
	
	foreach( $reality_roles as $role ) {
		add_role( $role['role'], $role['display_name'], $role['capabilities'] );
	}
	
}
add_action( 'init', 'reality_user_permissions' );

// REMOVE UNUSED ROLES

function reality_remove_unused_roles() {

	$supported_roles = array();
	$supported_roles[] = 'administrator';
	$supported_roles[] = 'player';
	$supported_roles[] = 'game-master';
	
	if ( get_option('reality_use_departments') ) {
		$depts = get_option('reality_player_departments');
		
		foreach( $depts as $slug => $dept ) {
		
			$supported_roles[] = $slug;
		
		}
	}
	
	global $wp_roles;
	$roles = $wp_roles->roles;
	foreach( $roles as $slug => $role ) {
	
		if ( array_search( $slug, $supported_roles ) == false && array_search( $slug, $supported_roles ) != 0 ) {
			
			$wp_roles->remove_role( $slug );
			
		}
	
	}
}
add_action( 'init', 'reality_remove_unused_roles', 1 );

// CUSTON LOGIN CSS
function reality_custom_login_css() { 
	echo '<link rel="stylesheet" type="text/css" href="'.get_stylesheet_directory_uri().'/admin/login.css" />'; 
}
add_action('login_head', 'reality_custom_login_css');

// PAGINATION
function reality_archive_nav( $html_id ) {
	global $wp_query;

	$html_id = esc_attr( $html_id );

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
			
			<?php $big = 99999999; ?>
			<?php $args = array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format' => '?paged=%#%',
				'current' => max( 1, get_query_var('paged') ),
				'total' => $wp_query->max_num_pages,
				'type'		=>	'list'
			); ?>
			
				<?php echo paginate_links( $args ); ?>	
		</nav><!-- #<?php echo $html_id; ?> .navigation -->
	<?php endif;
}

function reality_get_player_comments_count( $user_id ) {

	if ( $user_activity = get_the_author_meta( 'reality_player_activities', $user_id, true ) ) {
	
		$user_activity = unserialize( $user_activity );
		$comments = 0;
		isset($user_activity['activity_update']) ? $comments += count($user_activity['activity_update']) : false;
		isset($user_activity['activity_comment']) ? $comments += count($user_activity['activity_comment']) : false;
		isset($user_activity['new_blog_comment']) ? $comments += count($user_activity['new_blog_comment']) : false;
		isset($user_activity['photo_blog_update']) ? $comments += count($user_activity['photo_blog_update']) : false;
		isset($user_activity['reality_card_comment']) ? $comments += count($user_activity['reality_card_comment']) : false;
	
	} else {
		$comments = 0;
	}
	
	return $comments;

}

?>