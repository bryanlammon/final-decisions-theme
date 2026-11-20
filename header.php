<?php
/**
 * Site header.
 *
 * @package Final_Decisions
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="referrer" content="strict-origin">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content"><?php esc_html_e( 'Skip to content', 'final-decisions' ); ?></a>
<header class="site-header">
	<div class="site-header__inner">
		<a class="wordmark" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Final Decisions PLLC', 'final-decisions' ); ?>">
			<span aria-hidden="true">final decisions</span><span class="wordmark__suffix" aria-hidden="true">pllc</span>
		</a>
		<nav class="site-navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'final-decisions' ); ?>">
			<details class="nav-disclosure" open>
				<summary><?php esc_html_e( 'Menu', 'final-decisions' ); ?></summary>
				<div class="nav-panel">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'menu_class'     => 'menu menu--primary',
							'fallback_cb'    => false,
						)
					);
					?>
					<div class="header-controls">
						<div class="theme-control" data-theme-control hidden>
							<button type="button" class="theme-control__toggle" data-theme-toggle aria-label="<?php esc_attr_e( 'Dark theme', 'final-decisions' ); ?>" title="<?php esc_attr_e( 'Toggle dark theme', 'final-decisions' ); ?>" aria-pressed="false">
								<svg aria-hidden="true" focusable="false" viewBox="0 0 24 24"><path d="M20.5 14.2A8.5 8.5 0 0 1 9.8 3.5a8.5 8.5 0 1 0 10.7 10.7Z" /></svg>
							</button>
							<span class="visually-hidden" data-theme-current></span>
						</div>
						<details class="header-search">
							<summary><?php esc_html_e( 'Search', 'final-decisions' ); ?><svg aria-hidden="true" focusable="false" viewBox="0 0 24 24"><circle cx="10.5" cy="10.5" r="6.5" /><path d="m15.5 15.5 5 5" /></svg></summary>
							<?php final_decisions_render_search_form(); ?>
						</details>
					</div>
					<p class="visually-hidden" data-theme-status role="status" aria-live="polite" aria-atomic="true"></p>
				</div>
			</details>
		</nav>
	</div>
</header>
