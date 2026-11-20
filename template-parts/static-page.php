<?php
/**
 * Shared wrapper for theme-owned pages.
 *
 * @package Final_Decisions
 */

$name    = $args['page'];
$titles  = final_decisions_static_pages();
$headers = array(
	'about'     => array(
		'eyebrow' => 'About the firm',
		'intro'   => 'Final Decisions PLLC represents parties in federal civil appeals and works with trial counsel on appellate issues during litigation.',
	),
	'services'  => array(
		'eyebrow' => 'Services',
		'intro'   => 'Appeals, briefing, and appellate advice in federal civil cases.',
	),
	'contact'   => array(
		'eyebrow' => 'Contact',
		'intro'   => 'Tell us about your appeal or issue and the kind of assistance you are considering.',
	),
	'research'  => array(
		'eyebrow' => 'Research',
		'intro'   => 'Articles on federal appellate jurisdiction and procedure.',
	),
	'copyright' => array(
		'eyebrow' => 'Copyright',
		'intro'   => '',
	),
	'privacy'   => array(
		'eyebrow' => 'Privacy',
		'intro'   => '',
	),
	'legal'     => array(
		'eyebrow' => 'Legal',
		'intro'   => '',
	),
);
get_header();
?>
<main id="main-content" class="site-main<?php echo 'research' === $name ? ' site-main--research' : ''; ?>">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class( 'page-content' . ( in_array( $name, array( 'about', 'services', 'contact' ), true ) ? ' page-content--editorial' : '' ) ); ?>>
		<header class="page-header static-page-header">
	<div>
		<p class="static-page-header__eyebrow">
			<?php echo esc_html( $headers[ $name ]['eyebrow'] ); ?>
		</p>
		<h1><?php echo esc_html( $titles[ $name ] ); ?></h1>
	</div>
		<?php if ( ! post_password_required() ) : ?>
		<p class="static-page-header__intro">
			<?php echo esc_html( $headers[ $name ]['intro'] ); ?>
		</p>
	<?php endif; ?>
</header>
			<div class="entry-content<?php echo 'research' === $name ? ' research-content' : ''; ?>">
				<?php
				if ( post_password_required() ) {
					the_content();
				} else {
					get_template_part( 'template-parts/static/' . $name );
				}
				?>
			</div>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
