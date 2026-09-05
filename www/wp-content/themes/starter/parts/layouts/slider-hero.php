<?php
/**
 * Flexible Content layout: Slider Hero
 *
 * ACF fields : slides (repeater), slider_link_url, slider_link_target, slider_link_aria
 * Shared     : section_title, section_id, bg_color, padding_y, custom_classes
 *
 * Requires   : Splide  (assets/vendor/splide/)
 *              carousel.js (assets/js/carousel.js)
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$section_id  = get_sub_field( 'section_id' );
$link_url    = get_sub_field( 'slider_link_url' );
$link_target = get_sub_field( 'slider_link_target' ) ?: '_blank';
$link_aria   = get_sub_field( 'slider_link_aria' );
$h1_title    = get_sub_field( 'h1_title' );
$row_index   = get_row_index(); // 1-based (ACF)
$styles      = starter_section_styles();
$description_text = get_sub_field( 'hero_description' );
$btn_text = get_sub_field( 'button_text' );
$has_slider    = get_sub_field( 'show_slider' );
$slider_autoplay = get_sub_field( 'slider_autoplay' );
$slider_delay    = get_sub_field( 'slider_delay' );
$slider_speed    = get_sub_field( 'slider_speed' );
$slides          = get_sub_field( 'slides' );
$slide_count     = is_array( $slides ) ? count( $slides ) : 0;
?>

<section
    class="<?= esc_attr( starter_section_classes( 'section-slider-hero text-white' ) ) ?>"
    <?php if ( $styles ) : ?>style="<?= esc_attr( $styles ) ?>"<?php endif; ?>
    <?php if ( $section_id ) : ?>id="<?= esc_attr( $section_id ) ?>"<?php endif; ?>
>
    <div class="container mx-auto px-4 text-center">

        <?php
        // Use h1_title if provided, fall back to section_title (admin label)
        $heading_text = $h1_title ?: get_sub_field( 'section_title' );
        starter_section_heading(
            $heading_text ?: '',
            'text-xl mb-3 md:text-3xl md:mb-4 lg:text-4xl xl:text-4xl text-shadow-md font-bold leading-tight',
            $row_index - 1
        ); ?>
        
        <?php if ( $has_slider ) : ?>

        <?php if ( $slide_count > 1 ) : ?>
        <div
            class="splide js-hero-slider max-w-5xl mx-auto mb-6 rounded-lg overflow-hidden shadow-lg"
            data-autoplay="<?= esc_attr( $slider_autoplay ? 'true' : 'false' ) ?>"
            data-delay="<?= esc_attr( (int) $slider_delay ) ?>"
            data-speed="<?= esc_attr( (int) $slider_speed ) ?>"
            aria-label="<?= esc_attr( $link_aria ?: __( 'Hero Slider', 'starter' ) ) ?>"
        >
            <div class="splide__track">
                <ul class="splide__list">
                    <?php if ( have_rows( 'slides' ) ) : while ( have_rows( 'slides' ) ) : the_row();
                        $mob  = get_sub_field( 'slide_image_mobile' );
                        $tab  = get_sub_field( 'slide_image_tablet' );
                        $desk = get_sub_field( 'slide_image_desktop' );
                        $alt  = get_sub_field( 'slide_alt' );

                        if ( ! $alt ) {
                            $alt = $mob['alt'] ?? $desk['alt'] ?? '';
                        }

                        $is_first = ( get_row_index() === 1 );
                        $fallback = $mob ? $mob['url'] : ( $desk ? $desk['url'] : '' );
                    ?>
                    <li class="splide__slide">
                        <?php if ( $link_url ) : ?>
                        <a
                            href="<?= esc_url( $link_url ) ?>"
                            target="<?= esc_attr( $link_target ) ?>"
                            rel="noreferrer noopener nofollow"
                            referrerpolicy="no-referrer"
                            aria-label="<?= esc_attr( $link_aria ) ?>"
                            class="block w-full"
                        >
                        <?php endif; ?>

                        <picture class="w-full">
                            <?php if ( $mob ) : ?>
                            <source
                                srcset="<?= esc_url( $mob['url'] ) ?>"
                                media="(max-width: 639px)"
                                type="<?= esc_attr( $mob['mime_type'] ) ?>"
                            >
                            <?php endif; ?>

                            <?php if ( $tab ) : ?>
                            <source
                                srcset="<?= esc_url( $tab['url'] ) ?>"
                                media="(min-width: 640px) and (max-width: 1023px)"
                                type="<?= esc_attr( $tab['mime_type'] ) ?>"
                            >
                            <?php endif; ?>

                            <?php if ( $desk ) : ?>
                            <source
                                srcset="<?= esc_url( $desk['url'] ) ?>"
                                media="(min-width: 1024px)"
                                type="<?= esc_attr( $desk['mime_type'] ) ?>"
                            >
                            <?php endif; ?>

                            <img
                                src="<?= esc_url( $fallback ) ?>"
                                alt="<?= esc_attr( $alt ) ?>"
                                class="w-full h-auto object-cover rounded-lg"
                                <?php if ( $is_first ) : ?>
                                    width="<?= esc_attr( $mob ? $mob['width'] : 320 ) ?>"
                                    height="<?= esc_attr( $mob ? $mob['height'] : 196 ) ?>"
                                    fetchpriority="high"
                                <?php else : ?>
                                    loading="lazy"
                                <?php endif; ?>
                            >
                        </picture>

                        <?php if ( $link_url ) : ?>
                        </a>
                        <?php endif; ?>
                    </li>
                    <?php endwhile; endif; ?>
                </ul>
            </div>
        </div>

        <?php else : ?>
        <?php // Single slide — static image, no Splide overhead
            $single = $slides[0];
            $mob  = $single['slide_image_mobile'] ?? null;
            $tab  = $single['slide_image_tablet'] ?? null;
            $desk = $single['slide_image_desktop'] ?? null;
            $alt  = $single['slide_alt'] ?? '';
            if ( ! $alt ) {
                $alt = $mob['alt'] ?? $desk['alt'] ?? '';
            }
            $fallback = $mob ? $mob['url'] : ( $desk ? $desk['url'] : '' );
        ?>
        <div class="max-w-5xl mx-auto mb-6 rounded-lg overflow-hidden shadow-lg">
            <?php if ( $link_url ) : ?>
            <a
                href="<?= esc_url( $link_url ) ?>"
                target="<?= esc_attr( $link_target ) ?>"
                rel="noreferrer noopener nofollow"
                referrerpolicy="no-referrer"
                aria-label="<?= esc_attr( $link_aria ) ?>"
                class="block w-full"
            >
            <?php endif; ?>

            <picture class="w-full">
                <?php if ( $mob ) : ?>
                <source
                    srcset="<?= esc_url( $mob['url'] ) ?>"
                    media="(max-width: 639px)"
                    type="<?= esc_attr( $mob['mime_type'] ) ?>"
                >
                <?php endif; ?>

                <?php if ( $tab ) : ?>
                <source
                    srcset="<?= esc_url( $tab['url'] ) ?>"
                    media="(min-width: 640px) and (max-width: 1023px)"
                    type="<?= esc_attr( $tab['mime_type'] ) ?>"
                >
                <?php endif; ?>

                <?php if ( $desk ) : ?>
                <source
                    srcset="<?= esc_url( $desk['url'] ) ?>"
                    media="(min-width: 1024px)"
                    type="<?= esc_attr( $desk['mime_type'] ) ?>"
                >
                <?php endif; ?>

                <img
                    src="<?= esc_url( $fallback ) ?>"
                    alt="<?= esc_attr( $alt ) ?>"
                    class="w-full h-auto object-cover rounded-lg"
                    width="<?= esc_attr( $mob ? $mob['width'] : 320 ) ?>"
                    height="<?= esc_attr( $mob ? $mob['height'] : 196 ) ?>"
                    fetchpriority="high"
                >
            </picture>

            <?php if ( $link_url ) : ?>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php endif; ?>

        <?php if ( $description_text || $btn_text ) : ?>
            <div class="section-slider-hero-content max-w-5xl mx-auto text-sm md:text-base lg:text-lg text-shadow-sm p-4 <?= esc_attr( get_sub_field( 'custom_classes' ) ?: '' ) ?>">
                <?= $description_text ?>
                <?php if ( $btn_text ) : ?>
                    <?php starter_render_button([
                        'style'         => 'btn-base',
                        'type'          => get_sub_field( 'button_type' ) ?: 'link',
                        'text'          => $btn_text,
                        'url'           => get_sub_field( 'button_url' ) ?: '#',
                        'aria_label'    => get_sub_field( 'button_aria_label' ) ?: '',
                        'name'          => get_sub_field( 'button_name' ) ?: '',
                        'extra_classes' => get_sub_field( 'button_classes' ) ?: '',
                        'show_arrow'    => (bool) get_sub_field( 'button_show_arrow' ),
                    ]);
                    endif;?>
            </div>
        <?php endif; ?>
    </div>
</section>
