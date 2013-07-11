<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

	<div id="primary" class="site-content wrapper">
		<div id="content" role="main">

			<article id="post-0" class="post error404 no-results not-found">
				<?php $content = get_option('reality_404_content'); ?>
				<?php $content = str_replace( '\\', '', $content ); ?>
				<?php echo apply_filters( 'the_content', $content ); ?>
			</article><!-- #post-0 -->

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>