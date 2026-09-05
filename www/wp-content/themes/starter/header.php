<?php
/**
 * Header template
 *
 * Checkbox hack for mobile menu (CSS-only, no JS).
 * Structure: input#menu-toggle + .flex-container > .mobile-menu
 */

$mirror_link = get_mirror_link();
$theme_uri   = get_template_directory_uri();

// CTA button from ACF options
$cta_text = function_exists( 'get_field' ) ? get_field( 'cta_button_text', 'option' ) : '';
$cta_url  = function_exists( 'get_field' ) ? get_field( 'cta_button_url', 'option' ) : '';
if ( ! $cta_text ) $cta_text = 'Login';
if ( ! $cta_url )  $cta_url  = $mirror_link;

// Language data for switcher (flags + names)
$languages    = function_exists( 'pll_the_languages' ) ? pll_the_languages( [ 'raw' => 1 ] ) : [];
$current_lang = null;
$other_langs  = [];
foreach ( $languages as $lang ) {
    if ( ! empty( $lang['current_lang'] ) ) {
        $current_lang = $lang;
    } else {
        $other_langs[] = $lang;
    }
}

// Get menu items once (DRY)
$nav_items = [];
if ( has_nav_menu( 'primary_menu' ) ) {
    $locations = get_nav_menu_locations();
    $menu_obj = wp_get_nav_menu_object( $locations['primary_menu'] );
    if ( $menu_obj ) {
        $all_items = wp_get_nav_menu_items( $menu_obj->term_id );
        // Filter out all Polylang language switcher items
        $nav_items = array_filter( $all_items, function ( $item ) {
            $classes = implode( ' ', (array) $item->classes );
            if ( str_contains( $classes, 'lang-item' ) || str_contains( $classes, 'pll-parent-menu-item' ) ) {
                return false;
            }
            if ( $item->url === '#pll_switcher' ) {
                return false;
            }
            // Exclude front page — breadcrumbs + logo handle navigation back
            $front_id = (int) get_option( 'page_on_front' );
            if ( $front_id && (int) $item->object_id === $front_id && $item->object === 'page' ) {
                return false;
            }
            return true;
        } );
    }
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    // Dynamic font preload + @font-face
    $current_font  = starter_get_current_font();
    $all_fonts     = starter_get_available_fonts();
    $font_files    = $all_fonts[ $current_font ] ?? [];
    $font_base_url = esc_url( $theme_uri ) . '/assets/fonts/';

    // Preload the base (non-ext) font file for performance
    if ( ! empty( $font_files ) ) :
        // Prefer non-ext file for preload (it covers Base Latin = most glyphs used)
        $preload_file = $font_files[0];
        foreach ( $font_files as $ff ) {
            if ( ! str_contains( strtolower( $ff['basename'] ), '-ext' ) ) {
                $preload_file = $ff;
                break;
            }
        }
        $mime = match( $preload_file['ext'] ) {
            'woff2' => 'font/woff2',
            'woff'  => 'font/woff',
            'otf'   => 'font/otf',
            default => 'font/ttf',
        };
    ?>
    <link rel="preload" href="<?= $font_base_url . esc_attr( $preload_file['file'] ) ?>"
        as="font" type="<?= esc_attr( $mime ) ?>" crossorigin="anonymous" fetchpriority="high">
    <?php endif; ?>
    <?php
    // Preload logo from Customizer (dynamic)
    $logo_id_preload = get_theme_mod( 'custom_logo' );
    if ( $logo_id_preload ) :
        $logo_url_preload = wp_get_attachment_image_url( $logo_id_preload, 'full' );
        $logo_mime = get_post_mime_type( $logo_id_preload );
        if ( $logo_url_preload ) :
    ?>
    <link rel="preload" href="<?= esc_url( $logo_url_preload ) ?>" as="image"<?php if ( $logo_mime ) : ?> type="<?= esc_attr( $logo_mime ) ?>"<?php endif; ?>>
    <?php endif; endif; ?>
    <?php
    // Preload LCP: first slide images with media queries (browser downloads only the matching one)
    $hero_preload = starter_get_hero_preload_data();
    if ( $hero_preload ) :
        if ( ! empty( $hero_preload['mob'] ) ) : ?>
    <link rel="preload" href="<?= esc_url( $hero_preload['mob']['url'] ) ?>" as="image" type="<?= esc_attr( $hero_preload['mob']['type'] ) ?>" media="(max-width: 639px)" fetchpriority="high">
        <?php endif;
        if ( ! empty( $hero_preload['tab'] ) ) : ?>
    <link rel="preload" href="<?= esc_url( $hero_preload['tab']['url'] ) ?>" as="image" type="<?= esc_attr( $hero_preload['tab']['type'] ) ?>" media="(min-width: 640px) and (max-width: 1023px)" fetchpriority="high">
        <?php endif;
        if ( ! empty( $hero_preload['desk'] ) ) : ?>
    <link rel="preload" href="<?= esc_url( $hero_preload['desk']['url'] ) ?>" as="image" type="<?= esc_attr( $hero_preload['desk']['type'] ) ?>" media="(min-width: 1024px)" fetchpriority="high">
        <?php endif; ?>
    <?php endif; ?>
    <?php wp_head(); ?>
    <style>
        :root {
            --color-header-bg: <?= esc_attr( get_theme_mod( 'header_bg_color', '#181b26' ) ) ?>;
            --color-mobile-menu-bg: <?= esc_attr( get_theme_mod( 'mobile_menu_bg_color', '#1f2332' ) ) ?>;
            --color-nav-link: <?= esc_attr( get_theme_mod( 'nav_link_color', '#ffffff' ) ) ?>;
            --color-nav-link-hover: <?= esc_attr( get_theme_mod( 'nav_link_hover_color', '#86efac' ) ) ?>;
            --color-cta-button: <?= esc_attr( get_theme_mod( 'cta_button_color', '#22c55e' ) ) ?>;
            --color-cta-text: <?= esc_attr( get_theme_mod( 'cta_text_color', '#111827' ) ) ?>;
        }
        /* Logo width from Customizer (height auto) */
        .site-logo { width: <?= absint( get_theme_mod( 'logo_width', 120 ) ) ?>px; height: auto; }
        /* Checkbox hack: input:checked + sibling .flex-container */
        #menu-toggle:checked + .flex-container .mobile-menu {
            max-height: 400px;
            opacity: 1;
        }
        .mobile-menu {
            max-height: 0;
            opacity: 0;
            transition: max-height 0.4s ease, opacity 0.3s ease;
            overflow: hidden;
            background-color: var(--color-mobile-menu-bg);
        }
        /* Hamburger animation */
        #menu-toggle:checked + .flex-container .hamburger .line1 { transform: rotate(45deg) translate(5px, 5px); }
        #menu-toggle:checked + .flex-container .hamburger .line2 { opacity: 0; }
        #menu-toggle:checked + .flex-container .hamburger .line3 { transform: rotate(-45deg) translate(7px, -6px); }
        .hamburger span { transition: all 0.3s ease; transform-origin: center; }
        .header-bg { background-color: var(--color-header-bg); }

        /* Nav links: color from Customizer */
        .nav-link { color: var(--color-nav-link); transition: color .2s; }
        .nav-link:hover { color: var(--color-nav-link-hover); }

        /* CTA buttons: color from Customizer */
        .cta-btn { background-color: var(--color-cta-button); color: var(--color-cta-text); }
        .cta-btn:hover { filter: brightness(1.1); }

        /* Language dropdown (CSS-only) */
        .lang-dropdown { position: relative; }
        .lang-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            min-width: 160px;
            background: var(--color-mobile-menu-bg);
            border-radius: 0.5rem;
            box-shadow: 0 8px 24px rgba(0,0,0,.4);
            opacity: 0;
            visibility: hidden;
            transform: translateY(4px);
            transition: opacity .2s, visibility .2s, transform .2s;
            z-index: 100;
            padding: 0.25rem 0;
        }
        .lang-dropdown:hover .lang-dropdown-menu,
        .lang-dropdown:focus-within .lang-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
    </style>
    <?php if ( ! empty( $font_files ) ) : ?>
    <style>
        <?php foreach ( $font_files as $ff ) :
            // Derive weight from filename
            $weight = 400;
            $bn = strtolower( $ff['basename'] );
            if ( str_contains( $bn, 'thin' ) || str_contains( $bn, 'hairline' ) ) $weight = 100;
            elseif ( str_contains( $bn, 'extralight' ) || str_contains( $bn, 'ultralight' ) ) $weight = 200;
            elseif ( str_contains( $bn, 'light' ) ) $weight = 300;
            elseif ( str_contains( $bn, 'medium' ) ) $weight = 500;
            elseif ( str_contains( $bn, 'semibold' ) || str_contains( $bn, 'demibold' ) ) $weight = 600;
            elseif ( str_contains( $bn, 'extrabold' ) || str_contains( $bn, 'ultrabold' ) ) $weight = 800;
            elseif ( str_contains( $bn, 'black' ) || str_contains( $bn, 'heavy' ) ) $weight = 900;
            elseif ( str_contains( $bn, 'bold' ) ) $weight = 700;

            $format = match( $ff['ext'] ) {
                'woff2' => 'woff2',
                'woff'  => 'woff',
                'otf'   => 'opentype',
                default => 'truetype',
            };

            // Detect unicode-range subset from filename suffix
            $unicode_range = '';
            if ( str_contains( $bn, '-ext' ) ) {
                // Latin Extended: covers DE (ä,ö,ü,ß), FR (é,è,ê,ç,œ), NL diacritics
                $unicode_range = 'unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;';
            } else {
                // Latin base: ASCII + common symbols + €
                $unicode_range = 'unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;';
            }
        ?>
        @font-face {
            font-family: '<?= esc_attr( $current_font ) ?>';
            src: url('<?= $font_base_url . esc_attr( $ff['file'] ) ?>') format('<?= $format ?>');
            font-weight: <?= $weight ?>;
            font-style: normal;
            font-display: swap;
            <?= $unicode_range . "\n" ?>
        }
        <?php endforeach; ?>
    </style>
    <?php endif; ?>
</head>
<body <?php body_class( 'text-white flex flex-col min-h-screen' ); ?> style="background-color:<?= esc_attr( get_theme_mod( 'body_bg_color', '#2a2e3b' ) ) ?>;font-family:'<?= esc_attr( $current_font ) ?>',sans-serif;">
<?php wp_body_open(); ?>
<header class="sticky top-0 z-50 shadow-lg header-bg">
    <div class="container mx-auto px-4">
        <input type="checkbox" id="menu-toggle" class="hidden">
        <div class="flex-container">
            <div class="flex items-center justify-between py-4 px-2 xl:px-30 2xl:px-60">
                <!-- Logo (Site Identity → Customizer) -->
                <div class="flex items-center">
                    <?php if ( has_custom_logo() ): ?>
                        <?php
                        $logo_id  = get_theme_mod( 'custom_logo' );
                        $logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
                        $logo_alt = get_post_meta( $logo_id, '_wp_attachment_image_alt', true );
                        ?>
                        <a href="<?= esc_url( home_url() ) ?>">
                            <img src="<?= esc_url( $logo_url ) ?>"
                                alt="<?= esc_attr( $logo_alt ?: get_bloginfo( 'name' ) ) ?>"
                                class="site-logo block"
                                width="<?= absint( wp_get_attachment_metadata( $logo_id )['width'] ?? 180 ) ?>"
                                height="<?= absint( wp_get_attachment_metadata( $logo_id )['height'] ?? 40 ) ?>">
                        </a>
                    <?php else: ?>
                        <a href="<?= esc_url( home_url() ) ?>" class="text-xl font-bold text-white">
                            <?= esc_html( get_bloginfo( 'name' ) ) ?>
                        </a>
                    <?php endif; ?>
                </div>
                <!-- Desktop nav -->
                <nav class="hidden md:flex items-center space-x-6">
                    <?php foreach ( $nav_items as $item ): ?>
                        <a href="<?= esc_url( $item->url ) ?>"
                            class="nav-link transition-colors duration-200 text-sm xl:text-base">
                            <?= esc_html( $item->title ) ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
                <!-- Desktop buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <?php if ( $current_lang ): ?>
                        <div class="lang-dropdown">
                            <button type="button" class="flex items-center space-x-2 text-sm text-gray-300 hover:text-white transition-colors cursor-pointer">
                                <?php if ( $current_lang['flag'] ): ?>
                                    <img src="<?= esc_url( $current_lang['flag'] ) ?>" alt="" width="20" height="14" class="rounded-sm">
                                <?php endif; ?>
                                <span><?= esc_html( $current_lang['name'] ) ?></span>
                                <svg class="w-3 h-3 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="lang-dropdown-menu">
                                <?php foreach ( $other_langs as $lang ): ?>
                                    <a href="<?= esc_url( $lang['url'] ) ?>"
                                        class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                                        <?php if ( $lang['flag'] ): ?>
                                            <img src="<?= esc_url( $lang['flag'] ) ?>" alt="" width="20" height="14" class="rounded-sm">
                                        <?php endif; ?>
                                        <span><?= esc_html( $lang['name'] ) ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <a href="<?= esc_url( $cta_url ) ?>"
                        class="cta-btn px-5 py-2 rounded-lg font-semibold transition-colors">
                        <?= esc_html( $cta_text ) ?>
                    </a>
                </div>
                <!-- Hamburger -->
                <label for="menu-toggle" class="md:hidden cursor-pointer hamburger" aria-label="Toggle navigation menu">
                    <div class="w-6 h-6 flex flex-col justify-center items-center">
                        <span class="line1 w-6 h-0.5 bg-white block mb-1 rounded"></span>
                        <span class="line2 w-6 h-0.5 bg-white block mb-1 rounded"></span>
                        <span class="line3 w-6 h-0.5 bg-white block rounded"></span>
                    </div>
                </label>
            </div>
            <!-- Mobile menu -->
            <div class="mobile-menu md:hidden border-t border-gray-600">
                <nav class="py-4 flex flex-col">
                    <?php if ( $nav_items ): ?>
                        <?php foreach ( $nav_items as $item ): ?>
                            <a href="<?= esc_url( $item->url ) ?>"
                                class="nav-link block px-4 py-3 hover:bg-gray-700 transition-colors rounded-md">
                                <?= esc_html( $item->title ) ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if ( $languages ): ?>
                        <div class="px-4 py-2 border-t border-gray-600 mt-2">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Language</p>
                            <div class="flex flex-col space-y-1">
                                <?php foreach ( $languages as $lang ): ?>
                                    <a href="<?= esc_url( $lang['url'] ) ?>"
                                        class="flex items-center space-x-3 px-3 py-2 rounded-md text-sm transition-colors <?= ! empty( $lang['current_lang'] ) ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?>">
                                        <?php if ( $lang['flag'] ): ?>
                                            <img src="<?= esc_url( $lang['flag'] ) ?>" alt="" width="20" height="14" class="rounded-sm">
                                        <?php endif; ?>
                                        <span><?= esc_html( $lang['name'] ) ?></span>
                                        <?php if ( ! empty( $lang['current_lang'] ) ): ?>
                                            <span class="ml-auto text-green-400">&#10003;</span>
                                        <?php endif; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="px-4 py-3 border-t border-gray-600 mt-2">
                        <a href="<?= esc_url( $cta_url ) ?>"
                            class="cta-btn block mx-auto w-full text-center py-3 rounded-lg font-semibold transition-colors animate-pulse">
                            <?= esc_html( $cta_text ) ?>
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>
<?php starter_breadcrumbs(); ?>