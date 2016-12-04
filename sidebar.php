<?php
/**
 * The template for the sidebar containing the main widget area
 *
 * @package Moridrin
 * @subpackage SSV
 * @since SSV 1.0
 */
?>

<?php if ( is_active_sidebar( 'sidebar-1' )  ) : ?>
	<aside id="secondary" class="sidebar widget-area col-xs-12 col-md-3" role="complementary">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</aside><!-- .sidebar .widget-area -->
<?php endif; ?>
