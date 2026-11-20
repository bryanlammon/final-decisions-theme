<?php
/**
 * WordPress taxonomy, author, date, and other archives.
 *
 * @package Final_Decisions
 */

get_header();
?>
<main id="main-content" class="site-main site-main--archive">
	<header class="archive-header">
		<?php the_archive_title( '<h1>', '</h1>' ); ?>
		<?php if ( get_the_archive_description() ) : ?>
			<div class="entry-content archive-introduction"><?php echo wp_kses_post( get_the_archive_description() ); ?></div>
		<?php endif; ?>
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
	<?php else : ?>
		<section class="empty-state" aria-labelledby="archive-empty-title">
			<h2 id="archive-empty-title"><?php esc_html_e( 'No articles are available here yet.', 'final-decisions' ); ?></h2>
			<?php final_decisions_render_search_form(); ?>
		</section>
	<?php endif; ?>
	<?php final_decisions_render_contact_cta(); ?>
</main>
<?php
get_footer();
