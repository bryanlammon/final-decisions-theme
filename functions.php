<?php
/**
 * Theme setup and shared front-end helpers.
 *
 * @package Final_Decisions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FINAL_DECISIONS_VERSION', '5.0.0' );

/**
 * Register theme features, editor styles, and navigation locations.
 *
 * @return void
 */
function final_decisions_setup() {
	load_theme_textdomain( 'final-decisions', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
	add_editor_style( 'assets/css/editor.css' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_emoji_styles' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );

	register_nav_menus(
		array(
			'primary'        => __( 'Primary navigation', 'final-decisions' ),
			'footer_primary' => __( 'Footer navigation', 'final-decisions' ),
			'footer_legal'   => __( 'Footer policy navigation', 'final-decisions' ),
		)
	);
}
add_action( 'after_setup_theme', 'final_decisions_setup' );

/**
 * Use the classic editor for posts and pages.
 *
 * @param bool   $use_block_editor Existing editor choice.
 * @param string $post_type        Post type being edited.
 * @return bool Whether to use the block editor.
 */
function final_decisions_use_classic_editor( $use_block_editor, $post_type ) {
	return in_array( $post_type, array( 'post', 'page' ), true ) ? false : $use_block_editor;
}
add_filter( 'use_block_editor_for_post_type', 'final_decisions_use_classic_editor', 10, 2 );

/**
 * Enqueue the theme stylesheet and front-end scripts.
 *
 * @return void
 */
function final_decisions_enqueue_assets() {
	$theme_version = wp_get_theme()->get( 'Version' );
	$css_path      = get_template_directory() . '/assets/css/theme.css';
	$css_version   = file_exists( $css_path ) ? (string) filemtime( $css_path ) : $theme_version;

	wp_enqueue_style(
		'final-decisions-theme',
		get_template_directory_uri() . '/assets/css/theme.css',
		array(),
		$css_version
	);

	wp_enqueue_script(
		'final-decisions-theme-init',
		get_template_directory_uri() . '/assets/js/theme-init.js',
		array(),
		$theme_version,
		false
	);

	wp_enqueue_script(
		'final-decisions-interactions',
		get_template_directory_uri() . '/assets/js/interactions.js',
		array(),
		$theme_version,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'final_decisions_enqueue_assets' );

/**
 * Configure the public main archive and search queries.
 *
 * @param WP_Query $query Query being configured.
 * @return void
 */
function final_decisions_configure_main_queries( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_home() ) {
		$query->set( 'posts_per_page', 10 );
		$query->set( 'post_status', 'publish' );
		$query->set( 'ignore_sticky_posts', true );
	}

	if ( $query->is_search() ) {
		$query->set( 'post_type', array( 'post', 'page' ) );
		$query->set( 'post_status', 'publish' );
		$query->set( 'has_password', false );
		$query->set( 'ignore_sticky_posts', true );
		if ( '' === trim( (string) $query->get( 's' ) ) ) {
			$query->set( 'post__in', array( 0 ) );
		}
	}
}
add_action( 'pre_get_posts', 'final_decisions_configure_main_queries' );

/**
 * Get the default theme settings.
 *
 * @return array Default settings keyed by option name.
 */
function final_decisions_default_options() {
	return array(
		'contact_page_id' => 0,
		'cta_heading'     => __( 'Have an appellate question?', 'final-decisions' ),
		'cta_text'        => __( 'Final Decisions PLLC works with lawyers on federal civil appeals and appellate questions during litigation.', 'final-decisions' ),
		'footer_notice'   => __( "Final Decisions PLLC has an office in Virginia. Bryan Lammon is not admitted in Virginia, and the firm's practice is limited to federal appeals.", 'final-decisions' ),
	);
}

/**
 * Validate and sanitize submitted theme settings.
 *
 * @param mixed $input Submitted settings.
 * @return array Sanitized settings with defaults for missing values.
 */
function final_decisions_sanitize_options( $input ) {
	$defaults = final_decisions_default_options();
	if ( ! is_array( $input ) ) {
		return $defaults;
	}

	$clean                    = $defaults;
	$page_id                  = isset( $input['contact_page_id'] ) ? absint( $input['contact_page_id'] ) : 0;
	$page                     = $page_id ? get_post( $page_id ) : null;
	$clean['contact_page_id'] = ( $page && 'page' === $page->post_type && 'publish' === $page->post_status && '' === $page->post_password ) ? $page_id : 0;
	if ( $page_id && ! $clean['contact_page_id'] ) {
		add_settings_error( 'final_decisions_settings', 'invalid_contact_page', __( 'Select a published Contact page without a password.', 'final-decisions' ) );
	}
	foreach ( array( 'cta_heading', 'cta_text', 'footer_notice' ) as $key ) {
		if ( isset( $input[ $key ] ) && is_string( $input[ $key ] ) ) {
			$clean[ $key ] = sanitize_textarea_field( $input[ $key ] );
		}
	}

	return $clean;
}

/**
 * Register the theme settings and their fields.
 *
 * @return void
 */
function final_decisions_register_settings() {
	register_setting(
		'final_decisions_settings_group',
		'final_decisions_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'final_decisions_sanitize_options',
			'default'           => final_decisions_default_options(),
		)
	);

	add_settings_section(
		'final_decisions_page_links',
		__( 'Page links', 'final-decisions' ),
		'__return_false',
		'final-decisions-settings'
	);

	add_settings_field(
		'final_decisions_contact_page',
		__( 'Contact page', 'final-decisions' ),
		'final_decisions_render_contact_page_field',
		'final-decisions-settings',
		'final_decisions_page_links',
		array( 'label_for' => 'final-decisions-contact-page' )
	);
	foreach (
		array(
			'cta_heading'   => __( 'Contact call-to-action heading', 'final-decisions' ),
			'cta_text'      => __( 'Contact call-to-action text', 'final-decisions' ),
			'footer_notice' => __( 'Footer notice', 'final-decisions' ),
		) as $key => $label
	) {
		add_settings_field(
			$key,
			$label,
			'final_decisions_render_text_field',
			'final-decisions-settings',
			'final_decisions_page_links',
			array(
				'key'       => $key,
				'label_for' => 'final-decisions-' . $key,
			)
		);
	}
}
add_action( 'admin_init', 'final_decisions_register_settings' );

/**
 * Add the theme settings page to the Appearance menu.
 *
 * @return void
 */
function final_decisions_add_settings_page() {
	add_theme_page(
		__( 'Final Decisions theme settings', 'final-decisions' ),
		__( 'Theme settings', 'final-decisions' ),
		'edit_theme_options',
		'final-decisions-settings',
		'final_decisions_render_settings_page'
	);
}
add_action( 'admin_menu', 'final_decisions_add_settings_page' );

/**
 * Render the published contact-page selector.
 *
 * @return void
 */
function final_decisions_render_contact_page_field() {
	$options       = get_option( 'final_decisions_settings', array() );
	$options       = is_array( $options ) ? $options : array();
	$selected_page = isset( $options['contact_page_id'] ) ? absint( $options['contact_page_id'] ) : 0;
	$pages         = get_pages(
		array(
			'post_status' => 'publish',
			'sort_column' => 'post_title',
		)
	);
	?>
	<select id="final-decisions-contact-page" name="final_decisions_settings[contact_page_id]">
		<option value="0"><?php esc_html_e( 'Select a published page', 'final-decisions' ); ?></option>
		<?php foreach ( $pages as $page ) : ?>
			<?php
			if ( '' !== $page->post_password ) {
				continue; }
			?>
			<option value="<?php echo esc_attr( (string) $page->ID ); ?>" <?php selected( $selected_page, $page->ID ); ?>>
				<?php echo esc_html( $page->post_title ); ?>
			</option>
		<?php endforeach; ?>
	</select>
	<p class="description"><?php esc_html_e( 'Used by the archive and article contact links. The page permalink is generated by WordPress.', 'final-decisions' ); ?></p>
	<?php
}

/**
 * Get saved theme settings merged with defaults.
 *
 * @return array Settings keyed by option name.
 */
function final_decisions_options() {
	$options = get_option( 'final_decisions_settings', array() );
	return wp_parse_args( is_array( $options ) ? $options : array(), final_decisions_default_options() );
}

/**
 * Get the theme-owned static page names and titles.
 *
 * @return array Static page titles keyed by page name.
 */
function final_decisions_static_pages() {
	return array(
		'about'     => __( 'From appellate scholarship to appellate practice.', 'final-decisions' ),
		'services'  => __( 'From issues to arguments—and everything in between', 'final-decisions' ),
		'contact'   => __( 'Start with a conversation', 'final-decisions' ),
		'research'  => __( 'Research', 'final-decisions' ),
		'copyright' => __( 'Copyright', 'final-decisions' ),
		'privacy'   => __( 'Privacy Policy', 'final-decisions' ),
		'legal'     => __( 'Legal Disclaimers', 'final-decisions' ),
	);
}

/**
 * Find the unique published, unprotected page assigned a static template.
 *
 * @param string $name Static page name.
 * @return int Page ID, or zero when no unique matching page exists.
 */
function final_decisions_static_page_id( $name ) {
	static $matches = null;

	$static_pages = final_decisions_static_pages();
	if ( ! isset( $static_pages[ $name ] ) ) {
		return 0;
	}

	if ( null === $matches ) {
		$matches   = array();
		$templates = array();

		foreach ( array_keys( $static_pages ) as $page_name ) {
			$matches[ $page_name ]                           = array();
			$templates[ 'templates/' . $page_name . '.php' ] = $page_name;
		}

		$pages = get_pages(
			array(
				'post_status'  => 'publish',
				'hierarchical' => false,
			)
		);

		foreach ( $pages as $page ) {
			if ( '' !== $page->post_password ) {
				continue;
			}

			$template = get_page_template_slug( $page->ID );
			if ( isset( $templates[ $template ] ) ) {
				$matches[ $templates[ $template ] ][] = (int) $page->ID;
			}
		}
	}

	return 1 === count( $matches[ $name ] ) ? $matches[ $name ][0] : 0;
}



/**
 * Render a link to an assigned static page or the posts archive.
 *
 * @param string $destination Static page name or archive.
 * @param string $label       Link text.
 * @param string $link_class       Optional link class.
 * @return void
 */
function final_decisions_static_link( $destination, $label, $link_class = '' ) {
	if ( 'contact' === $destination ) {
		$url = final_decisions_contact_url();
	} elseif ( 'archive' === $destination ) {
		$page_id = absint( get_option( 'page_for_posts' ) );
		$page    = $page_id ? get_post( $page_id ) : null;
		$url     = $page && 'publish' === $page->post_status && '' === $page->post_password ? get_permalink( $page_id ) : '';
	} else {
		$page_id = final_decisions_static_page_id( $destination );
		$url     = $page_id ? get_permalink( $page_id ) : '';
	}
	if ( $url ) {
		printf( '<p><a href="%1$s"%3$s>%2$s</a></p>', esc_url( $url ), esc_html( $label ), $link_class ? ' class="' . esc_attr( $link_class ) . '"' : '' );
	}
}

/**
 * Capture the rendered content of a theme-owned page.
 *
 * @param string $name Static page name, home, or archive.
 * @return string Rendered content, or an empty string for an invalid name.
 */
function final_decisions_static_content( $name ) {
	if ( ! in_array( $name, array_merge( array_keys( final_decisions_static_pages() ), array( 'home', 'archive' ) ), true ) ) {
		return '';
	}
	ob_start();
	get_template_part( 'template-parts/static/' . $name );
	return (string) ob_get_clean();
}

/**
 * Get titles for the theme-owned static pages, home, and archive.
 *
 * @return array Titles keyed by page name.
 */
function final_decisions_static_titles() {
	$titles            = final_decisions_static_pages();
	$titles['home']    = __( 'Research deeply. Write clearly. Argue creatively.', 'final-decisions' );
	$titles['archive'] = __( 'Appellate Notes', 'final-decisions' );
	return $titles;
}

/**
 * Identify the theme-owned page represented by a WordPress page.
 *
 * @param int $post_id Page ID.
 * @return string Theme page name, or an empty string if not assigned.
 */
function final_decisions_static_page_name( $post_id ) {
	if ( 'page' !== get_post_type( $post_id ) ) {
		return '';
	}
	if ( 'page' === get_option( 'show_on_front' ) ) {
		if ( (int) get_option( 'page_on_front' ) === $post_id ) {
			return 'home';
		}
		if ( (int) get_option( 'page_for_posts' ) === $post_id ) {
			return 'archive';
		}
	}
	$template = get_page_template_slug( $post_id );
	foreach ( array_keys( final_decisions_static_pages() ) as $name ) {
		if ( 'templates/' . $name . '.php' === $template ) {
			return $name;
		}
	}
	return '';
}

/**
 * Warn editors when the theme supplies the page's displayed content.
 *
 * @param WP_Post $post Page being edited.
 * @return void
 */
function final_decisions_static_editor_notice( $post ) {
	if ( final_decisions_static_page_name( (int) $post->ID ) ) {
		echo '<div class="notice notice-info inline"><p>' . esc_html__( 'This page displays copy from the theme, not the editor below. Its existing editor content is retained but changes to it will not appear while this template or Reading setting is in use.', 'final-decisions' ) . '</p></div>';
	}
}
add_action( 'edit_form_after_title', 'final_decisions_static_editor_notice' );

/**
 * Search rendered theme-owned content instead of retained page content.
 *
 * @param string   $search Search SQL fragment.
 * @param WP_Query $query  Query being filtered.
 * @return string Filtered search SQL fragment.
 */
function final_decisions_static_search_content( $search, $query ) {
	global $wpdb;
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() || ! $search ) {
		return $search;
	}
	$pages = array();
	foreach ( get_pages(
		array(
			'post_status'  => 'publish',
			'hierarchical' => false,
		)
	) as $page ) {
		$name = final_decisions_static_page_name( (int) $page->ID );
		if ( $name && '' === $page->post_password ) {
			$pages[ (int) $page->ID ] = $name;
		}
	}
	if ( ! $pages ) {
		return $search;
	}
	$content_cases = '';
	$excerpt_cases = '';
	$title_cases   = '';
	$titles        = final_decisions_static_titles();
	foreach ( $pages as $id => $name ) {
		$text           = html_entity_decode( wp_strip_all_tags( final_decisions_static_content( $name ), true ), ENT_QUOTES, get_bloginfo( 'charset' ) );
		$content_cases .= $wpdb->prepare( ' WHEN %d THEN %s', $id, $text );
		$excerpt_cases .= $wpdb->prepare( " WHEN %d THEN ''", $id );
		$title_cases   .= $wpdb->prepare( ' WHEN %d THEN %s', $id, $titles[ $name ] );
	}
	$content = "(CASE {$wpdb->posts}.ID{$content_cases} ELSE {$wpdb->posts}.post_content END)";
	$excerpt = "(CASE {$wpdb->posts}.ID{$excerpt_cases} ELSE {$wpdb->posts}.post_excerpt END)";
	$title   = "(CASE {$wpdb->posts}.ID{$title_cases} ELSE {$wpdb->posts}.post_title END)";
	// Match the rendered template instead of the page's retained editor content.
	return str_replace(
		array( "{$wpdb->posts}.post_content", "{$wpdb->posts}.post_excerpt", "{$wpdb->posts}.post_title" ),
		array( $content, $excerpt, $title ),
		$search
	);
}
add_filter( 'posts_search', 'final_decisions_static_search_content', 10, 2 );

/**
 * Render a text field for an editable theme setting.
 *
 * @param array $args Settings field arguments containing the option key.
 * @return void
 */
function final_decisions_render_text_field( $args ) {
	$options = final_decisions_options();
	$key     = $args['key'];
	?>
	<textarea class="large-text" rows="3" id="<?php echo esc_attr( 'final-decisions-' . $key ); ?>" name="<?php echo esc_attr( 'final_decisions_settings[' . $key . ']' ); ?>"><?php echo esc_textarea( $options[ $key ] ); ?></textarea>
	<?php
}

/**
 * Warn authorized administrators about missing theme setup.
 *
 * @return void
 */
function final_decisions_setup_notice() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	$missing    = array();
	$home_id    = absint( get_option( 'page_on_front' ) );
	$archive_id = absint( get_option( 'page_for_posts' ) );
	if ( 'page' !== get_option( 'show_on_front' ) || ! $home_id || ! $archive_id || $home_id === $archive_id || 'publish' !== get_post_status( $home_id ) || 'publish' !== get_post_status( $archive_id ) ) {
		$missing[] = __( 'Select a static Home page and an Appellate Notes posts page under Settings → Reading.', 'final-decisions' );
	}
	$locations = get_nav_menu_locations();
	foreach ( array( 'primary', 'footer_primary', 'footer_legal' ) as $location ) {
		$items = ! empty( $locations[ $location ] ) ? wp_get_nav_menu_items( $locations[ $location ] ) : false;
		if ( ! $items || is_wp_error( $items ) ) {
			$missing[] = __( 'Assign a nonempty menu to each location under Appearance → Menus; existing menus can be reused.', 'final-decisions' );
			break;
		}
	}
	if ( ! final_decisions_contact_url() ) {
		$missing[] = __( 'Select a published Contact page under Appearance → Theme settings.', 'final-decisions' );
	}
	foreach ( final_decisions_static_pages() as $name => $title ) {
		if ( ! final_decisions_static_page_id( $name ) ) {
			/* translators: %s: The title of the theme-owned page. */
			$missing[] = sprintf( __( 'Assign the %s template to exactly one published, unprotected page.', 'final-decisions' ), $title );
		}
	}
	$contact_id = final_decisions_static_page_id( 'contact' );
	if ( $contact_id && final_decisions_contact_url() && get_permalink( $contact_id ) !== final_decisions_contact_url() ) {
		$missing[] = __( 'The selected Contact page and the page using the Contact template do not match.', 'final-decisions' );
	}
	if ( ! $missing ) {
		return;
	}
	?>
	<div class="notice notice-warning"><p><strong><?php esc_html_e( 'Final Decisions theme setup:', 'final-decisions' ); ?></strong></p><ul>
		<?php foreach ( $missing as $message ) : ?>
			<li><?php echo esc_html( $message ); ?></li>
		<?php endforeach; ?>
	</ul></div>
	<?php
}
add_action( 'admin_notices', 'final_decisions_setup_notice' );

/**
 * Render the theme settings page for authorized administrators.
 *
 * @return void
 */
function final_decisions_render_settings_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Final Decisions theme settings', 'final-decisions' ); ?></h1>
		<p><?php esc_html_e( 'Assign the site pages and editable navigation menus in WordPress. Select the Appellate Notes page under Settings → Reading, then create and assign the primary and footer menus under Appearance → Menus.', 'final-decisions' ); ?></p>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'final_decisions_settings_group' );
			do_settings_sections( 'final-decisions-settings' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Get the permalink of the selected published, unprotected contact page.
 *
 * @return string|false Contact page URL, empty string if invalid, or false if unavailable.
 */
function final_decisions_contact_url() {
	$options = final_decisions_options();
	$page_id = isset( $options['contact_page_id'] ) ? absint( $options['contact_page_id'] ) : 0;

	$page = $page_id ? get_post( $page_id ) : null;
	if ( ! $page || 'publish' !== $page->post_status || 'page' !== $page->post_type || '' !== $page->post_password ) {
		return '';
	}

	return get_permalink( $page_id );
}

/**
 * Get the posts-page permalink, falling back to the home URL.
 *
 * @return string|false Archive URL, or false if the posts permalink is unavailable.
 */
function final_decisions_archive_url() {
	$posts_page_id = absint( get_option( 'page_for_posts' ) );

	if ( $posts_page_id && 'publish' === get_post_status( $posts_page_id ) ) {
		return get_permalink( $posts_page_id );
	}

	return home_url( '/' );
}

/**
 * Render the contact call to action when a contact page is available.
 *
 * @return void
 */
function final_decisions_render_contact_cta() {
	$url = final_decisions_contact_url();
	if ( ! $url ) {
		return;
	}
	$options = final_decisions_options();
	?>
	<aside class="contact-cta" aria-label="<?php esc_attr_e( 'Contact the firm', 'final-decisions' ); ?>">
		<span class="contact-cta__label" aria-hidden="true"><?php esc_html_e( 'Contact', 'final-decisions' ); ?></span>
		<div class="contact-cta__content">
			<?php
			if ( '' !== $options['cta_heading'] ) :
				?>
				<h2><?php echo esc_html( $options['cta_heading'] ); ?></h2><?php endif; ?>
			<?php
			if ( '' !== $options['cta_text'] ) :
				?>
				<p><?php echo esc_html( $options['cta_text'] ); ?></p><?php endif; ?>
			<p class="contact-cta__link"><a href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Contact the firm', 'final-decisions' ); ?></a></p>
		</div>
	</aside>
	<?php
}

/**
 * Render a search form with a unique input ID.
 *
 * @return void
 */
function final_decisions_render_search_form() {
	static $search_form_number = 0;
	++$search_form_number;
	$search_id = 'site-search-' . $search_form_number;
	?>
	<form class="search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label for="<?php echo esc_attr( $search_id ); ?>"><?php esc_html_e( 'Search the site', 'final-decisions' ); ?></label>
		<div class="search-form__controls">
			<input id="<?php echo esc_attr( $search_id ); ?>" type="search" name="s" value="<?php echo esc_attr( get_search_query( false ) ); ?>">
			<button type="submit"><?php esc_html_e( 'Search', 'final-decisions' ); ?></button>
		</div>
	</form>
	<?php
}

/**
 * Render navigation for the current posts query.
 *
 * @return void
 */
function final_decisions_render_pagination() {
	the_posts_pagination(
		array(
			'mid_size'           => 1,
			'prev_text'          => __( 'Previous', 'final-decisions' ),
			'next_text'          => __( 'Next', 'final-decisions' ),
			'screen_reader_text' => __( 'Content pagination', 'final-decisions' ),
		)
	);
}

/**
 * Get the display label for the current search result type.
 *
 * @return string Page or article label.
 */
function final_decisions_result_type() {
	return get_post_type() === 'page' ? __( 'Page', 'final-decisions' ) : __( 'Article', 'final-decisions' );
}
