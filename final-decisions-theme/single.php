<?php
/**
 * Appellate Notes article.
 *
 * @package Final_Decisions
 */

get_header();
?>
<main id="main-content" class="site-main site-main--article">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class( 'article-content' ); ?>>
			<header class="article-header">
				<p class="article-header__publication"><a href="<?php echo esc_url( final_decisions_archive_url() ); ?>"><?php esc_html_e( 'Appellate Notes', 'final-decisions' ); ?></a></p>
				<h1><?php the_title(); ?></h1>
				<?php
				$author         = get_the_author();
				$categories     = get_the_category();
				$category_names = array();

				foreach ( $categories as $category ) {
					$category_names[] = $category->name;
				}
				?>
<p class="article-meta">
		<?php if ( $author ) : ?>
		<span><?php echo esc_html( $author ); ?></span>
		<span aria-hidden="true">/</span>
	<?php endif; ?>

	<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
		<?php echo esc_html( get_the_date() ); ?>
	</time>

		<?php if ( $category_names ) : ?>
		<span aria-hidden="true">/</span>
		<span><?php echo esc_html( implode( ', ', $category_names ) ); ?></span>
	<?php endif; ?>
</p>
			</header>
			<div class="entry-content article-body">
				<?php the_content(); ?>
				<?php
				wp_link_pages(
					array(
						'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Article pages', 'final-decisions' ) . '">',
						'after'  => '</nav>',
					)
				);
				?>
			</div>
		</article>
		<nav class="article-return" aria-label="<?php esc_attr_e( 'Article navigation', 'final-decisions' ); ?>">
			<a href="<?php echo esc_url( final_decisions_archive_url() ); ?>"><?php esc_html_e( 'Back to Appellate Notes', 'final-decisions' ); ?></a>
		</nav>
		<?php final_decisions_render_contact_cta(); ?>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
