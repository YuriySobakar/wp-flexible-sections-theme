<?php
/**
 * Footer template
 *
 * Logo from Site Identity, optional nav (ACF toggle), disclaimer, copyright.
 * Colors controlled via Customizer → Footer Colors.
 */

// ACF fields (with fallbacks)
$show_nav          = function_exists( 'get_field' ) ? get_field( 'show_footer_nav', 'option' ) : true;
$show_logo         = function_exists( 'get_field' ) ? get_field( 'show_footer_logo', 'option' ) : true;
$disclaimer_title  = function_exists( 'get_field' ) ? get_field( 'footer_disclaimer_title', 'option' ) : '';
$disclaimer_text   = function_exists( 'get_field' ) ? get_field( 'footer_disclaimer', 'option' ) : '';
$copyright         = function_exists( 'get_field' ) ? get_field( 'footer_copyright', 'option' ) : '';

// Footer navigation (own menu location)
$footer_nav_items = [];
if ( $show_nav && has_nav_menu( 'footer_menu' ) ) {
    $locations = get_nav_menu_locations();
    $menu_obj  = wp_get_nav_menu_object( $locations['footer_menu'] );
    if ( $menu_obj ) {
        $all_items = wp_get_nav_menu_items( $menu_obj->term_id );
        $footer_nav_items = array_filter( $all_items, function ( $item ) {
            $classes = implode( ' ', (array) $item->classes );
            if ( str_contains( $classes, 'lang-item' ) || str_contains( $classes, 'pll-parent-menu-item' ) ) {
                return false;
            }
            if ( $item->url === '#pll_switcher' ) {
                return false;
            }
            return true;
        } );
    }
}
?>

<style>
    :root {
        --color-footer-bg: <?= esc_attr( get_theme_mod( 'footer_bg_color', '#1f2937' ) ) ?>;
        --color-footer-text: <?= esc_attr( get_theme_mod( 'footer_text_color', '#d1d5db' ) ) ?>;
        --color-footer-heading: <?= esc_attr( get_theme_mod( 'footer_heading_color', '#ffffff' ) ) ?>;
        --color-footer-link-hover: <?= esc_attr( get_theme_mod( 'footer_link_hover_color', '#86efac' ) ) ?>;
        --color-footer-border: <?= esc_attr( get_theme_mod( 'footer_border_color', '#374151' ) ) ?>;
    }
    .footer-bg { background-color: var(--color-footer-bg); color: var(--color-footer-text); }
    .footer-heading { color: var(--color-footer-heading); }
    .footer-link { color: var(--color-footer-text); transition: color .2s; }
    .footer-link:hover { color: var(--color-footer-link-hover); }
    .footer-border { border-color: var(--color-footer-border); }
</style>

<footer class="footer-bg py-8">
    <div class="container mx-auto px-4">
        <div class="px-2 xl:px-30 2xl:px-60">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-center md:text-left">
            <!-- Left column: Logo + Nav -->
            <div>
                <?php if ( $show_logo ) : ?>
                <?php if ( has_custom_logo() ):
                    $logo_id  = get_theme_mod( 'custom_logo' );
                    $logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
                    $logo_alt = get_post_meta( $logo_id, '_wp_attachment_image_alt', true );
                ?>
                    <a href="<?= esc_url( home_url() ) ?>" class="inline-block mb-4">
                        <img src="<?= esc_url( $logo_url ) ?>"
                            alt="<?= esc_attr( $logo_alt ?: get_bloginfo( 'name' ) ) ?>"
                            class="site-logo block"
                            width="<?= absint( wp_get_attachment_metadata( $logo_id )['width'] ?? 180 ) ?>"
                            height="<?= absint( wp_get_attachment_metadata( $logo_id )['height'] ?? 40 ) ?>"
                            fetchpriority="high">
                    </a>
                <?php else: ?>
                    <a href="<?= esc_url( home_url() ) ?>" class="inline-block mb-4 text-xl font-bold footer-heading">
                        <?= esc_html( get_bloginfo( 'name' ) ) ?>
                    </a>
                <?php endif; ?>
                <?php endif; ?>

                <?php if ( $show_nav && $footer_nav_items ): ?>
                    <nav class="flex flex-wrap gap-4 justify-center md:justify-start">
                        <?php foreach ( $footer_nav_items as $item ): ?>
                            <a href="<?= esc_url( $item->url ) ?>" class="footer-link text-sm">
                                <?= esc_html( $item->title ) ?>
                            </a>
                        <?php endforeach; ?>
                    </nav>
                <?php endif; ?>
            </div>

            <!-- Right column: Disclaimer -->
            <?php if ( $disclaimer_title || $disclaimer_text ): ?>
                <div>
                    <?php if ( $disclaimer_title ): ?>
                        <h3 class="footer-heading text-lg font-bold text-right mb-4"><?= esc_html( $disclaimer_title ) ?></h3>
                    <?php endif; ?>
                    <?php if ( $disclaimer_text ): ?>
                        <div class="text-sm leading-relaxed footer-disclaimer text-right">
                            <?= wp_kses_post( $disclaimer_text ) ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Copyright -->
        <?php if ( $copyright ): ?>
            <div class="mt-8 text-center footer-border border-t pt-4">
                <p class="text-sm"><?=wp_kses_post( do_shortcode( $copyright ) ) ?></p>
            </div>
        <?php endif; ?>

        </div><!-- /.px-2 xl:px-30 2xl:px-60 -->
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
