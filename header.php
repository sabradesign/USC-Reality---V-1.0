<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		
		<?php if ( !is_page_template('submission_form.php' ) ) : ?>
			<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
			<meta name="apple-mobile-web-app-capable" content="yes">
			<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		<?php endif; ?>
		
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<!-- <link rel="stylesheet/less" type="text/css" href="<?php bloginfo('stylesheet_directory') ?>/css/styles.less" /> -->
		<?php /* Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. */ ?>
		<!--[if lt IE 9]>
		<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
		<![endif]-->
		
		<?php wp_head(); ?>
	
	</head>
	<body <?php body_class(); ?>>
	<div id="page" class="hfeed site">
	<div class="top-bar">
		<div class="wrapper">
			<a href="<?php echo site_url(); ?>" title="Reality Home">
				<div id="reality-logo"><img src="<?php bloginfo('stylesheet_directory') ?>/images/reality-logo.png" alt="USC Reality" height=55 width=55></div>
			</a>
				<div class="nav-holder">
					<nav id="main-navigation">
						<?php if ( is_user_logged_in() ) : ?>
							<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false ) ); ?>
						<?php else : ?>
							<?php wp_nav_menu( array( 'theme_location' => 'logged_out_menu', 'container' => false ) ); ?>
						<?php endif; ?>
					</nav>
					<select></select>
				</div>
			<div id="nav-user-badge">
			<?php if ( is_user_logged_in() ) : ?>
				<div class="user-badge">
					<?php global $bp; ?>
					<?php $args = array( 'width' => 45, 'height' => 45 ); ?>
					<a href="<?php echo $bp->loggedin_user->domain ?>" title="View/Edit My Profile">
						<?php bp_loggedin_user_avatar( $args ); ?>
					</a>
					<div class="experience-bar">
						<?php $rank = get_the_author_meta( 'reality_current_rank', $bp->loggedin_user->id, true ); ?>
						<?php if ( $rank ) $rank = $rank; ?>
						<div class="progress" style="width:<?php echo $rank['percent_to_next_level']; ?>%;"></div>
					</div>
				</div>
				<div class="user-info">
					<a href="<?php echo $bp->loggedin_user->domain ?>" class="player-name"><strong><?php echo $bp->loggedin_user->fullname ?></strong></a>
					<a href="<?php echo $bp->loggedin_user->domain ?>"><?php if ( $points = get_the_author_meta( 'reality_current_points', $bp->loggedin_user->id, true ) ) { echo $points; } else { echo '0'; } ?> Points</a>
					<a href="<?php echo wp_logout_url( site_url( 'login' ) ); ?>">Log Out</a>
				</div>
				<?php endif; ?>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<header>
		<!-- USC REALITY HEADER -->
		<?php if ( is_home() || is_front_page() ) : ?>
		<div id="header-slider" class="flexslider">
			<ul class="slides">
				<?php
					global $reality;
					if ( isset($reality->current_dept ) && isset( $reality->current_dept_deals ) && is_array( $reality->current_dept_deals ) && !empty( $reality->current_dept_deals ) && $reality->current_dept != 'administrator' && $reality->current_dept != 'game-master' ) {
						$in = $reality->current_dept_deals;
					} else {
						$in = '';
					}
				
					$dealArgs = array(
						'post_type'	=>	'reality_deals',
						'post__in'	=>	$in,
						'posts_per_page'	=>	6
					);
					
					$deals = new WP_Query( $dealArgs );
					$deals_array = array(); ?>
					
					<?php if ( $deals->have_posts() ) : ?>
					
					<?php while( $deals->have_posts() ) : $deals->the_post(); ?>
						<?php $thumbnailsrc = get_the_deal_image_url( get_the_ID() ); ?>
						<?php $point_value = get_post_meta( get_the_ID(), 'REALITY_total_value', true); ?>
				
						<li class="header-slider" style="background-image:url(<?php echo $thumbnailsrc; ?>);">
							<a href="<?php the_permalink() ?>" title="Check out <?php the_title(); ?>">
							<div class="slider-bg" style="background-image:url(<?php echo $thumbnailsrc; ?>);"></div>
							<div class="wrapper">
								<div class="project-info">
									<div class="project-title"><?php the_title(); ?></div>
									<div class="project-points"><?php echo $point_value; ?> points</div>
								</div>
							</div>
							</a>	
						</li>
				
						<?php $deals_array[] = $deals->post; ?>
				
				<?php endwhile; ?>
				
				<?php else : ?>
				
					<li class="header-slider">
						<div class="slider-bg" style="background-image:url(<?php bloginfo( 'stylesheet_directory' ) ?>/images/reality-logo_326x326.png);background-size: 326px 326px;"></div>
					</li>
				
				<?php endif; ?>
			</ul>
		</div>
		<div class="slider-nav">
			<div class="wrapper">
				<ul>
					<?php foreach( $deals_array as $deal ) : ?>
						<?php $thumbnailsrc = timthumb_photo( get_the_deal_image_url( $deal->ID, 'large' ), 140, 80, '', false ); ?>
						<li class="<?php echo $deal->ID ?>" style="background-image:url(<?php echo $thumbnailsrc ?>);"></li>
					<?php endforeach; ?>
				</ul>
				<div class="clear"></div>
			</div>
		</div>
		<div class="horizontal-bar">
			<div class="wrapper">
				<div class="page-tagline">The bullpen belongs to you</div>
			</div>
		</div>
		
		<?php elseif( bp_is_user() ) : ?>
		
		<div id="header-slider" class="flexslider">
			<ul class="slides">
				<?php
				
					$dealArgs = array(
						'post_type'	=>	'reality_deals',
						'posts_per_page'	=>	6,
						'tax_query' => array(
							array(
								'taxonomy' => 'authors-tax',
								'field' => 'slug',
								'terms' => bp_displayed_user_id()
							)
						)
					);
					$deals = new WP_Query( $dealArgs );
					
					if ( $deals->have_posts() ) : ?>
					
					<?php while( $deals->have_posts() ) : ?>
					
					<?php $deals->the_post(); ?>
					
					<?php $thumbnailsrc = get_the_deal_image_url(); ?>
					
					<?php $point_value = get_post_meta( get_the_ID(), 'REALITY_total_value', true); ?>
				
				<li class="header-slider">
					<a href="<?php the_permalink() ?>" title="Check out <?php the_title(); ?>">
					<div class="slider-bg" style="background-image:url(<?php echo $thumbnailsrc; ?>);"></div>
					<div class="wrapper">
						<div class="project-info">
							<div class="project-title"><?php the_title() ?></div>
							<div class="project-points"><?php echo $point_value; ?> points</div>
						</div>
					</div>
					</a>	
				</li>
				
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
				<?php endif; ?>
			</ul>
		</div>
		
		<div class="horizontal-bar">
			<div class="wrapper">
				<div id="item-header-avatar">
					<a href="<?php bp_displayed_user_link(); ?>">
						<?php bp_displayed_user_avatar( 'type=full' ); ?>
					</a>
					<?php if ( bp_is_my_profile() ) : ?>
						<a class="edit-avatar" href="<?php echo bp_core_get_userlink( bp_displayed_user_id(), false, true) ?>profile/change-avatar/" title="Edit My Avatar">Edit</a>
					<?php endif; ?>
				</div>
			<div class="page-tagline">
				<?php bp_displayed_user_fullname(); ?>
				<?php if ( bp_is_my_profile() ) : ?>
					( <a href="<?php echo bp_core_get_userlink( bp_displayed_user_id(), false, true)?>messages/">Messages</a> | <a href="<?php echo bp_core_get_userlink( bp_displayed_user_id(), false, true)?>settings/">Settings</a> )
					<?php endif; ?>
			</div>
		</div>
		
		</div>
		
		<?php elseif( ( bp_is_directory() || is_page() || is_post_type_archive( 'reality_deals' ) || is_post_type_archive( 'reality_cards' ) || is_tax() ) && !is_page_template('reality_login.php') ) : ?>
		
			<?php global $deal_submission_status; ?>
		
			<?php if ( is_page_template('submission_form.php' ) && !isset($deal_submission_status['success']) ) : ?>
			
				<?php if (isset($_POST['maker_card_id']) && $_POST['maker_card_id'] != '' ) : ?>
					<?php $maker_card_id = $_POST['maker_card_id']; ?>
				<?php else : ?>
					<?php $maker_card_id = ''; ?>
				<?php endif; ?>
				<div id="card-header">
					<div class="card-container">
						<?php echo do_shortcode('[reality_card number="' . $maker_card_id . '" side="back"] [reality_card number="' . $maker_card_id . '"]'); ?>
					</div>
<!-- 
					<nav class="nav-single tabs-nav">
						<span class="prev-tab" style="float:left;"><a href="#prev-tab" title="Previous Step">&larr; Previous Step</a></span>
						<span class="next-tab" style="float:right;"><a href="#next-tab" title="Next Step">Next Step &rarr;</a></span>
						<div class="clear"></div>
					</nav>
 -->
			
				</div>
			
			<?php else : ?>
			
				<?php $homePage = get_option( 'page_on_front' ); ?>
				
				<?php $attachmentArgs = array(
					'post_type'			=>	'attachment',
					'post_status'		=>	'inherit',
					'post_parent'		=>	$homePage,
					'posts_per_page'	=>	1,
					'orderby'			=>	'rand'
				);
				$attachments = new WP_Query( $attachmentArgs ); ?>
				
				<?php if ( $attachments->have_posts() ) : ?>
				
					<?php $headerImage = wp_get_attachment_image_src( $attachments->post->ID, 'full' ); ?>
					<div id="header-with-image" style="background-image:url(<?php echo $headerImage[0]; ?>)">
						<div class="aspect-control"></div>
					</div>
				
				<?php else : ?>
					
					<?php //ADD DEFAULT IMAGE ?>
					<?php $headerImage[0] = get_bloginfo( 'stylesheet_directory' ) . '/images/reality-logo_326x326.png'; ?>
				
					<div id="header-with-image" style="background-image:url(<?php echo $headerImage[0]; ?>);background-size: 326px 326px;">
						<div class="aspect-control"></div>
					</div>
				
				<?php endif; ?>
				
				
		
			<?php endif; ?>
		
			<div class="horizontal-bar">
					<div class="wrapper">
					<?php if ( bp_is_directory() || is_page() ) : ?>
						<div class="page-tagline"><?php the_title(); ?></div>
					<?php elseif ( is_post_type_archive( 'reality_deals' ) ) : ?>
						<div class="page-tagline">
							<?php if ( isset( $_GET['deals_search'] ) && $_GET['deals_search'] != '' ) : ?>
								<?php $search_query = $_GET['deals_search']; ?>
							<?php endif; ?>
							<?php _e( 'Deals Archive', 'Reality' ); ?><?php if ( isset( $search_query ) ) echo ': <span class="search_query">'.$search_query.'</span>'; ?>
							<form id="deal-search-form" class="search-form" method="GET"><input type="text" name="deals_search" placeholder="Search deals..."<?php if ( isset( $search_query ) ) echo ' value="'.$search_query.'"'; ?>></form>
						</div>
					<?php elseif ( is_post_type_archive( 'reality_cards' ) ) : ?>
						<div class="page-tagline"><?php _e( 'Random Card', 'Reality' ); ?></div>
					<?php elseif ( is_tax( 'awards-tax' ) ) : ?>
						<div class="page-tagline">Other Recipients of the <?php single_cat_title(); ?> Award.</div>
					<?php endif; ?>
					</div>
				</div>
		
		<?php elseif ( is_singular( 'reality_cards' ) ) : ?>
			<?php global $post; ?>
			<div id="card-header">
			
				<?php echo do_shortcode('[reality_card number="' . $post->post_name . '" side="back"] [reality_card number="' . $post->post_name . '"]'); ?>
			
				<nav class="nav-single">
					<?php if ( get_option( 'reality_card_navigation' ) == 1 ) : ?>
						<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'twentytwelve' ) . '</span> %title' ); ?></span>
						<span class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'twentytwelve' ) . '</span>' ); ?></span>
					<?php endif; ?>
				</nav><!-- .nav-single -->
			
			</div>
		
		<?php endif; ?>
		
	</header>