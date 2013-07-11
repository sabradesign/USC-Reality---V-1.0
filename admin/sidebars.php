<?php

// Register Sidebar
function reality_register_sidebars()  {
	$args = array();
	
	$args[] = array(
		'id'            => 'reality_quick_actions',
		'name'          => __( 'Quick Actions', 'Reality' ),
		'description'   => __( 'This sidebar shows up above the main content on mobile views.', 'Reality' ),
		'class'         => 'quick-actions',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
	);
	
	$args[] = array(
		'id'            => 'reality_members',
		'name'          => __( 'Members Sidebar', 'Reality' ),
		'description'   => __( 'The sidebar on the member\'s index', 'Reality' ),
		'class'         => 'members-sidebar',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>'
	);

	foreach( $args as $arg ) {
		register_sidebar( $arg );
	}
}

// Hook into the 'widgets_init' action
add_action( 'widgets_init', 'reality_register_sidebars' );

?>