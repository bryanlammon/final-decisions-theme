<?php
/**
 * Safe fallback template.
 *
 * @package Final_Decisions
 */

get_header();
?>
<main id="main-content" class="site-main">
	<header class="page-header">
		<h1><?php esc_html_e( 'Latest articles', 'final-decisions' ); ?></h1>
	</header>
	<?php if ( have_posts() ) : ?>
		<ol class="article-list">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/article-preview' );
			endwhile;
			?>
		</ol>
		<?php final_decisions_render_pagination(); ?>
	<?php endif; ?>
</main>
<?php
get_footer();
