<?php

if ( ! defined( 'ABSPATH' ) ) exit;

// === 1. ASSETS & SETUP ===
function starter_assets() {
    $css_ver = file_exists( get_template_directory() . '/assets/css/style.css' ) 
        ? filemtime( get_template_directory() . '/assets/css/style.css' ) 
        : '1.0.0';
    
    wp_enqueue_style( 'main-style', get_template_directory_uri() . '/assets/css/style.css', [], $css_ver );
}
add_action( 'wp_enqueue_scripts', 'starter_assets' );

/**
 * Conditionally enqueue Splide + carousel.js when a page uses the slider-hero layout.
 */
function starter_slider_assets() {
    if ( ! is_singular( 'page' ) ) return;

    $sections = get_field( 'page_sections' );
    if ( ! $sections || ! is_array( $sections ) ) return;

    $needs_slider = false;
    foreach ( $sections as $section ) {
        if ( ( $section['acf_fc_layout'] ?? '' ) === 'slider-hero' ) {
            $needs_slider = true;
            break;
        }
    }

    if ( ! $needs_slider ) return;

    $tpl = get_template_directory();
    $uri = get_template_directory_uri();

    wp_enqueue_style( 'splide-css', $uri . '/assets/vendor/splide/splide.min.css', [], '4.1.4' );
    wp_enqueue_script( 'splide-js', $uri . '/assets/vendor/splide/splide.min.js', [], '4.1.4', true );

    $js_ver = file_exists( $tpl . '/assets/js/carousel.js' )
        ? filemtime( $tpl . '/assets/js/carousel.js' )
        : '1.0.0';
    wp_enqueue_script( 'hero-carousel', $uri . '/assets/js/carousel.js', [ 'splide-js' ], $js_ver, true );
}
add_action( 'wp_enqueue_scripts', 'starter_slider_assets' );

function starter_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', [
        'height'      => 37,
        'width'       => 180,
        'flex-height' => true,
        'flex-width'  => true,
    ] );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );

    register_nav_menus([
        'primary_menu' => 'Header Main Menu',
        'footer_menu'  => 'Footer Menu',
    ]);
}
add_action( 'after_setup_theme', 'starter_setup' );


// === 2. ACF OPTIONS PAGE ===
if ( function_exists( 'acf_add_options_page' ) ) {
    acf_add_options_page([
        'page_title'    => 'Theme Settings',
        'menu_title'    => 'Theme Settings',
        'menu_slug'     => 'theme-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ]);
}

// === ACF: Field registrations ===
require_once get_template_directory() . '/includes/acf/header.php';
require_once get_template_directory() . '/includes/acf/footer.php';

// === ACF: Flexible Content sections ===
require_once get_template_directory() . '/includes/helpers.php';
require_once get_template_directory() . '/includes/elements.php';
require_once get_template_directory() . '/includes/acf/page-sections.php';

// === 3. POLYLANG STRINGS ===
function starter_pll_strings() {
    if ( function_exists( 'pll_register_string' ) ) {
        pll_register_string( 'starter', 'Login', 'Header Buttons' );
        pll_register_string( 'starter', 'Sign Up', 'Header Buttons' );
        pll_register_string( 'starter', 'Current Login', 'Header Buttons' );
        pll_register_string( 'starter', 'Fast Access', 'Footer Buttons' );
    }
}
add_action( 'init', 'starter_pll_strings' );

function starter_allow_avif( $mimes ) {
    $mimes['avif'] = 'image/avif';
    $mimes['svg']  = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'starter_allow_avif' );

// === SHORTCODE: [year] — outputs current year ===
add_shortcode( 'year', function () {
    return date( 'Y' );
});

// === Font scanner: reads assets/fonts/ directory ===
function starter_get_available_fonts(): array {
    $fonts_dir  = get_template_directory() . '/assets/fonts';
    $fonts      = [];

    if ( ! is_dir( $fonts_dir ) ) return $fonts;

    $allowed_ext = [ 'ttf', 'woff', 'woff2', 'otf' ];
    foreach ( glob( $fonts_dir . '/*' ) as $file ) {
        $ext = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
        if ( ! in_array( $ext, $allowed_ext, true ) ) continue;

        $basename = pathinfo( $file, PATHINFO_FILENAME ); // e.g. Roboto-Light
        // Extract font family: everything before the first hyphen or the full name
        $family = preg_replace( '/[-_](Light|Regular|Medium|Bold|SemiBold|ExtraBold|Thin|Black|Italic|ExtraLight).*$/i', '', $basename );
        if ( ! isset( $fonts[ $family ] ) ) {
            $fonts[ $family ] = [];
        }
        $fonts[ $family ][] = [
            'file'     => basename( $file ),
            'basename' => $basename,
            'ext'      => $ext,
        ];
    }

    return $fonts;
}

/**
 * Get the currently selected font family name from Customizer.
 */
function starter_get_current_font(): string {
    return get_theme_mod( 'theme_font_family', 'Roboto' );
}

// === CUSTOMIZER: Global + Header + Footer Colors ===
function starter_customizer( $wp_customize ) {
    // === Logo Size (in Site Identity section) ===
    $wp_customize->add_setting( 'logo_width', [
        'default'           => 120,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'logo_width', [
        'label'       => 'Logo Width (px)',
        'description' => 'Drag to set logo width (30–200 px). Height scales automatically. Applies to both header and footer.',
        'section'     => 'title_tagline',
        'type'        => 'range',
        'input_attrs' => [ 'min' => 30, 'max' => 200, 'step' => 1 ],
        'priority'    => 8,
    ] );

    // === Global Colors ===
    $wp_customize->add_section( 'starter_global_colors', [
        'title'    => 'Global Colors',
        'priority' => 29,
    ] );

    $wp_customize->add_setting( 'body_bg_color', [ 'default' => '#2a2e3b', 'sanitize_callback' => 'sanitize_hex_color' ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'body_bg_color', [
        'label'   => 'Body Background',
        'section' => 'starter_global_colors',
    ] ) );

    // Font family dropdown (auto-scans assets/fonts/)
    $available = starter_get_available_fonts();
    $font_choices = [];
    foreach ( $available as $family => $files ) {
        $font_choices[ $family ] = $family . ' (' . count( $files ) . ' files)';
    }
    if ( empty( $font_choices ) ) {
        $font_choices['sans-serif'] = 'No fonts found — add .ttf/.woff2 to assets/fonts/';
    }

    $wp_customize->add_setting( 'theme_font_family', [
        'default'           => 'Roboto',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'theme_font_family', [
        'label'   => 'Theme Font',
        'section' => 'starter_global_colors',
        'type'    => 'select',
        'choices' => $font_choices,
    ] );

    // === Header Colors ===
    $wp_customize->add_section( 'starter_header_colors', [
        'title'    => 'Header Colors',
        'priority' => 30,
    ] );

    // Header background
    $wp_customize->add_setting( 'header_bg_color', [ 'default' => '#181b26', 'sanitize_callback' => 'sanitize_hex_color' ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_bg_color', [
        'label'   => 'Header Background',
        'section' => 'starter_header_colors',
    ] ) );

    // Mobile menu background
    $wp_customize->add_setting( 'mobile_menu_bg_color', [ 'default' => '#1f2332', 'sanitize_callback' => 'sanitize_hex_color' ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mobile_menu_bg_color', [
        'label'   => 'Mobile Menu / Dropdown BG',
        'section' => 'starter_header_colors',
    ] ) );

    // Nav link color
    $wp_customize->add_setting( 'nav_link_color', [ 'default' => '#ffffff', 'sanitize_callback' => 'sanitize_hex_color' ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_link_color', [
        'label'   => 'Nav Link Color',
        'section' => 'starter_header_colors',
    ] ) );

    // Nav link hover color
    $wp_customize->add_setting( 'nav_link_hover_color', [ 'default' => '#86efac', 'sanitize_callback' => 'sanitize_hex_color' ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_link_hover_color', [
        'label'   => 'Nav Link Hover Color',
        'section' => 'starter_header_colors',
    ] ) );

    // CTA button color
    $wp_customize->add_setting( 'cta_button_color', [ 'default' => '#22c55e', 'sanitize_callback' => 'sanitize_hex_color' ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cta_button_color', [
        'label'   => 'CTA Button Color',
        'section' => 'starter_header_colors',
    ] ) );

    // CTA button text color
    $wp_customize->add_setting( 'cta_text_color', [ 'default' => '#111827', 'sanitize_callback' => 'sanitize_hex_color' ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cta_text_color', [
        'label'   => 'CTA Button Text Color',
        'section' => 'starter_header_colors',
    ] ) );

    // === Footer Colors ===
    $wp_customize->add_section( 'starter_footer_colors', [
        'title'    => 'Footer Colors',
        'priority' => 31,
    ] );

    $wp_customize->add_setting( 'footer_bg_color', [ 'default' => '#1f2937', 'sanitize_callback' => 'sanitize_hex_color' ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_bg_color', [
        'label'   => 'Footer Background',
        'section' => 'starter_footer_colors',
    ] ) );

    $wp_customize->add_setting( 'footer_text_color', [ 'default' => '#d1d5db', 'sanitize_callback' => 'sanitize_hex_color' ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_text_color', [
        'label'   => 'Footer Text Color',
        'section' => 'starter_footer_colors',
    ] ) );

    $wp_customize->add_setting( 'footer_heading_color', [ 'default' => '#ffffff', 'sanitize_callback' => 'sanitize_hex_color' ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_heading_color', [
        'label'   => 'Footer Heading Color',
        'section' => 'starter_footer_colors',
    ] ) );

    $wp_customize->add_setting( 'footer_link_hover_color', [ 'default' => '#86efac', 'sanitize_callback' => 'sanitize_hex_color' ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_link_hover_color', [
        'label'   => 'Footer Link Hover Color',
        'section' => 'starter_footer_colors',
    ] ) );

    $wp_customize->add_setting( 'footer_border_color', [ 'default' => '#374151', 'sanitize_callback' => 'sanitize_hex_color' ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_border_color', [
        'label'   => 'Footer Border Color',
        'section' => 'starter_footer_colors',
    ] ) );
}
add_action( 'customize_register', 'starter_customizer' );

// Show current px value next to the logo_width range slider in Customizer
add_action( 'customize_controls_enqueue_scripts', function () {
    $js = <<<'JS'
    (function($){
        wp.customize.control('logo_width', function(c){
            c.deferred.embedded.done(function(){
                var $input = c.container.find('input[type=range]');
                var $val = $('<output style="display:inline-block;margin-left:8px;font-weight:600;min-width:40px"></output>');
                $input.after($val);
                $val.text($input.val() + 'px');
                $input.on('input change', function(){ $val.text(this.value + 'px'); });
            });
        });
    })(jQuery);
JS;
    wp_add_inline_script( 'customize-controls', $js );
} );

// === Yoast Breadcrumbs wrapper (hidden on front page) ===
function starter_breadcrumbs(): void {
    if ( is_front_page() ) return;
    if ( function_exists( 'yoast_breadcrumb' ) ) {
        yoast_breadcrumb(
            '<nav class="breadcrumbs container mx-auto px-4 pt-2 text-xs md:text-sm opacity-70" aria-label="Breadcrumb"><div class="px-2 xl:px-30 2xl:px-60">',
            '</div></nav>'
        );
    }
}

// === JS defer for non-critical scripts ===
add_filter( 'script_loader_tag', function ( string $tag, string $handle ): string {
    if ( in_array( $handle, [ 'splide-js', 'hero-carousel' ], true ) ) {
        return str_replace( ' src', ' defer src', $tag );
    }
    return $tag;
}, 10, 2 );

/**
 * Detect the first slide of slider-hero and return responsive preload data.
 * Called from header.php to inject <link rel="preload"> for LCP image.
 *
 * @return array{mob?:array, tab?:array, desk?:array}|null Null if no slider-hero or no slides.
 */
function starter_get_hero_preload_data(): ?array {
    if ( ! function_exists( 'get_field' ) || ! is_singular( 'page' ) ) return null;

    $sections = get_field( 'page_sections' );
    if ( ! $sections || ! is_array( $sections ) ) return null;

    foreach ( $sections as $section ) {
        if ( ( $section['acf_fc_layout'] ?? '' ) !== 'slider-hero' ) continue;

        $slides = $section['slides'] ?? [];
        if ( empty( $slides ) ) return null;

        $first = $slides[0];
        $data  = [];

        if ( ! empty( $first['slide_image_mobile'] ) ) {
            $data['mob'] = [
                'url'  => $first['slide_image_mobile']['url'],
                'type' => $first['slide_image_mobile']['mime_type'],
            ];
        }
        if ( ! empty( $first['slide_image_tablet'] ) ) {
            $data['tab'] = [
                'url'  => $first['slide_image_tablet']['url'],
                'type' => $first['slide_image_tablet']['mime_type'],
            ];
        }
        if ( ! empty( $first['slide_image_desktop'] ) ) {
            $data['desk'] = [
                'url'  => $first['slide_image_desktop']['url'],
                'type' => $first['slide_image_desktop']['mime_type'],
            ];
        }

        return ! empty( $data ) ? $data : null;
    }

    return null;
}


function get_mirror_link() {
    if ( function_exists('get_field') ) {
        $link = get_field('global_mirror_link', 'option');
        if($link) return $link;
    }
    return '#'; // Fallback
}

function pll_e_safe( $string ) {
    if ( function_exists( 'pll_e' ) ) pll_e( $string );
    else echo esc_html( $string );
}

// === Polylang: translate ACF Options page per language ===
add_filter( 'acf/validate_post_id', function ( $post_id ) {
    if ( $post_id === 'options' && function_exists( 'pll_current_language' ) ) {
        $lang = pll_current_language( 'slug' );
        if ( $lang && $lang !== pll_default_language( 'slug' ) ) {
            $post_id = 'options_' . $lang;
        }
    }
    return $post_id;
} );

// === Performance: strip WordPress bloat ===
add_action( 'init', function () {
    // Remove emoji scripts & styles
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

    // Remove oEmbed discovery
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );

    // Remove RSD, WLW, shortlink, generator, REST API link
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'rest_output_link_wp_head' );
    remove_action( 'wp_head', 'wp_resource_hints', 2 );
    remove_action( 'wp_head', 'feed_links', 2 );
    remove_action( 'wp_head', 'feed_links_extra', 3 );
} );

// Disable XML-RPC
add_filter( 'xmlrpc_enabled', '__return_false' );

// Remove jQuery migrate (not needed)
add_action( 'wp_default_scripts', function ( $scripts ) {
    if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
        $scripts->registered['jquery']->deps = array_diff(
            $scripts->registered['jquery']->deps, [ 'jquery-migrate' ]
        );
    }
} );

// Disable self-pingbacks
add_action( 'pre_ping', function ( &$links ) {
    $home = home_url();
    foreach ( $links as $i => $link ) {
        if ( str_starts_with( $link, $home ) ) unset( $links[ $i ] );
    }
} );

// Remove global styles (Gutenberg) on front-end when not using block editor
add_action( 'wp_enqueue_scripts', function () {
    wp_dequeue_style( 'global-styles' );
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'classic-theme-styles' );
}, 100 );

// Disable WP embeds script
add_action( 'wp_footer', function () {
    wp_deregister_script( 'wp-embed' );
} );

// Add resource hints — preconnect to external origins if needed
add_filter( 'wp_resource_hints', function ( array $hints, string $relation ): array {
    if ( $relation === 'dns-prefetch' ) {
        $hints[] = '//fonts.googleapis.com';
    }
    return $hints;
}, 10, 2 );

// === Vary cache by language (for any caching plugin) ===
add_action( 'send_headers', function () {
    if ( function_exists( 'pll_current_language' ) ) {
        header( 'Vary: Accept-Language, Cookie' );
    }
} );
?>