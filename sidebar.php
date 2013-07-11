<?php
/**
 * The sidebar containing the main widget area.
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<aside id="secondary" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</aside><!-- #secondary -->
	<?php else : ?>
	
		<?php if ( current_user_can( 'administrator' ) ) : ?>
			<aside id="secondary" class="widget-area" role="complementary">
		
				<h2><a href="<?php echo admin_url( 'widgets.php' ); ?>" title="Add Widgets"><?php _e('Add widgets to sidebar titled "Sidebar" here.'); ?></a></h2>
		
			</aside>
		<?php endif; ?>
	
	<?php endif; ?>