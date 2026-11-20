<?php
/**
 * One WordPress post preview.
 *
 * @package Final_Decisions
 */

$categories     = get_the_category();
$category_names = array();

foreach ( $categories as $category ) {
	$category_names[] = $category->name;
}
?>
<li <?php post_class( 'article-preview' ); ?>>
	<time class="article-preview__date"
			datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
		<?php echo esc_html( get_the_date( 'F Y' ) ); ?>
	</time>

	<?php if ( $category_names ) : ?>
		<p class="article-preview__category">
			<?php echo esc_html( implode( ', ', $category_names ) ); ?>
		</p>
	<?php endif; ?>

	<div class="article-preview__content">
		<h2>
			<a href="<?php echo esc_url( get_permalink() ); ?>">
				<?php the_title(); ?>
			</a>
		</h2>

		<?php if ( '' === get_post_field( 'post_password', get_the_ID() ) ) : ?>
			<?php $excerpt = get_the_excerpt(); ?>
			<?php if ( $excerpt ) : ?>
				<p><?php echo esc_html( wp_trim_words( $excerpt, 35 ) ); ?></p>
			<?php endif; ?>
		<?php endif; ?>

		<p class="article-preview__read">
			<a href="<?php echo esc_url( get_permalink() ); ?>"
				aria-label="<?php /* translators: %s: The article title. */ echo esc_attr( sprintf( __( 'Read %s', 'final-decisions' ), get_the_title() ) ); ?>">
				Read article <span aria-hidden="true">↗</span>
			</a>
		</p>
	</div>
</li>