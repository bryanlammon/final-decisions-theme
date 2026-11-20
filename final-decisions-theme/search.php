<?php
/**
 * Public search results.
 *
 * @package Final_Decisions
 */

get_header();
$search_term = get_search_query( false );
?>
<main id="main-content" class="site-main site-main--search">
	<?php if ( '' === trim( (string) $search_term ) ) : ?>
		<header class="page-header">
			<h1><?php esc_html_e( 'Search', 'final-decisions' ); ?></h1>
			<p><?php esc_html_e( 'Enter a word or phrase to search the site.', 'final-decisions' ); ?></p>
		</header>
		<?php final_decisions_render_search_form(); ?>
	<?php else : ?>
		<header class="page-header">
			<?php if ( have_posts() ) : ?>
				<h1><?php /* translators: %s: The search query. */ printf( esc_html__( 'Search results for “%s”', 'final-decisions' ), esc_html( $search_term ) ); ?></h1>
			<?php else : ?>
				<h1><?php /* translators: %s: The search query. */ printf( esc_html__( 'No results for “%s”', 'final-decisions' ), esc_html( $search_term ) ); ?></h1>
			<?php endif; ?>
		</header>
		<?php if ( have_posts() ) : ?>
			<p class="result-count"><?php esc_html_e( 'Search results', 'final-decisions' ); ?></p>
			<ol class="search-results">
				<?php
				while ( have_posts() ) :
					the_post();
					$static_name   = final_decisions_static_page_name( get_the_ID() );
					$static_titles = final_decisions_static_titles();
					?>
					<li class="search-result">
						<p class="search-result__type"><?php echo esc_html( final_decisions_result_type() ); ?></p>
						<h2><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo $static_name ? esc_html( $static_titles[ $static_name ] ) : esc_html( get_the_title() ); ?></a></h2>
						<?php if ( $static_name ) : ?>
							<p><?php echo esc_html( wp_trim_words( html_entity_decode( wp_strip_all_tags( final_decisions_static_content( $static_name ), true ), ENT_QUOTES, get_bloginfo( 'charset' ) ), 35 ) ); ?></p>
						<?php elseif ( has_excerpt() || get_the_content() ) : ?>
							<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 35 ) ); ?></p>
						<?php endif; ?>
					</li>
					<?php
				endwhile;
				?>
			</ol>
			<?php final_decisions_render_pagination(); ?>
		<?php else : ?>
			<section class="empty-state" aria-label="<?php esc_attr_e( 'Search again', 'final-decisions' ); ?>">
				<p><?php esc_html_e( 'Try a different word or phrase.', 'final-decisions' ); ?></p>
				<?php final_decisions_render_search_form(); ?>
			</section>
		<?php endif; ?>
	<?php endif; ?>
</main>
<?php
get_footer();
