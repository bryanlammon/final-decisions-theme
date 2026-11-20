<?php
/**
 * Theme-owned front-page content.
 *
 * @package Final_Decisions
 */

if ( is_home() && ! is_page() ) {
	require get_theme_file_path( 'home.php' );
	return;
}

get_header();
?>
<main id="main-content" class="site-main site-main--home">
	<?php
	while ( have_posts() ) :
		the_post();
		if ( post_password_required() ) {
			the_content();
		} else {
			get_template_part( 'template-parts/static/home' );
		}
	endwhile;
	?>
</main>
<?php
get_footer();
