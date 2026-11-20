<?php
/**
 * Appellate Notes archive.
 *
 * @package Final_Decisions
 */

get_header();
?>
<main id="main-content" class="site-main site-main--archive notes-archive">
<header class="archive-header">
	<p class="archive-eyebrow">Writing about federal appeals</p>
	<h1><?php esc_html_e( 'Appellate Notes', 'final-decisions' ); ?></h1>
	<div class="entry-content archive-introduction">
		<?php get_template_part( 'template-parts/static/archive' ); ?>
	</div>
</header>

<p class="archive-list-label">Recent writing</p>
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
