<?php
/**
 * Site footer.
 *
 * @package Final_Decisions
 */

?>
<footer class="site-footer">
	<div class="site-footer__inner">
		<a class="wordmark wordmark--footer" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Final Decisions PLLC', 'final-decisions' ); ?>">
			<span aria-hidden="true">final decisions</span><span class="wordmark__suffix" aria-hidden="true">pllc</span>
		</a>
		<nav aria-label="<?php esc_attr_e( 'Footer navigation', 'final-decisions' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer_primary',
					'container'      => false,
					'menu_class'     => 'menu menu--footer',
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>
		<nav aria-label="<?php esc_attr_e( 'Policy navigation', 'final-decisions' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer_legal',
					'container'      => false,
					'menu_class'     => 'menu menu--footer menu--legal',
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>
		<?php $footer_notice = final_decisions_options()['footer_notice']; ?>
		<?php if ( '' !== $footer_notice ) : ?>
			<p class="site-footer__notice"><?php echo esc_html( $footer_notice ); ?></p>
		<?php endif; ?>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
