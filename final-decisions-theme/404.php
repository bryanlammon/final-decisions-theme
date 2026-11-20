<?php
/**
 * Not found response.
 *
 * @package Final_Decisions
 */

get_header();
?>
<main id="main-content" class="site-main site-main--not-found">
	<header class="page-header">
		<h1><?php esc_html_e( 'Page not found', 'final-decisions' ); ?></h1>
		<p><?php esc_html_e( 'The page you requested could not be found.', 'final-decisions' ); ?></p>
	</header>
	<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to Home', 'final-decisions' ); ?></a></p>
	<?php final_decisions_render_search_form(); ?>
</main>
<?php
get_footer();
