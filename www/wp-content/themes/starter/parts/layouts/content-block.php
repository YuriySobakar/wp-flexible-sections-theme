<?php
/**
 * Flexible Content layout: Content Block
 *
 * ACF fields : banner (3 responsive images), h2_title, description (WYSIWYG), content_lists (nested repeater), button
 * Shared     : section_title, section_id, bg_color, heading_color, content_color, padding_y, custom_classes
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$section_id    = get_sub_field( 'section_id' );
$h2_title      = get_sub_field( 'h2_title' );
$description   = get_sub_field( 'description' );
$custom_classes = get_sub_field( 'custom_classes' ) ?: '';
$row_index     = get_row_index(); // 1-based
$styles        = starter_section_styles();
$btn_text      = get_sub_field( 'button_text' );
$lists          = get_sub_field( 'content_lists' );
$has_lists      = ! empty( $lists ) && is_array( $lists );
$checkmark_color = get_sub_field( 'checkmark_color' ) ?: '#4ade80';
$list_description = get_sub_field( 'list_description' );
$additional_content = get_sub_field( 'additional_content' );

// Banner image
$banner_mob    = get_sub_field( 'banner_image_mobile' );
$banner_tab    = get_sub_field( 'banner_image_tablet' );
$banner_desk   = get_sub_field( 'banner_image_desktop' );
$banner_alt    = get_sub_field( 'banner_alt' );
$banner_loading = get_sub_field( 'banner_loading' ) ?: 'lazy';
$has_banner    = $banner_mob || $banner_tab || $banner_desk;

if ( $has_banner && ! $banner_alt ) {
    $banner_alt = $banner_mob['alt'] ?? $banner_desk['alt'] ?? '';
}
$banner_fallback = $banner_mob ? $banner_mob['url'] : ( $banner_desk ? $banner_desk['url'] : '' );
?>

<section
    class="<?= esc_attr( starter_section_classes( 'section-content-block text-white' ) ) ?>"
    <?php if ( $styles ) : ?>style="<?= esc_attr( $styles ) ?>"<?php endif; ?>
    <?php if ( $section_id ) : ?>id="<?= esc_attr( $section_id ) ?>"<?php endif; ?>
>
    <div class="container mx-auto px-4">

        <?php if ( $description || $has_banner || $has_lists || $btn_text ) : ?>
            <div class="section-content-block-body max-w-5xl mx-auto text-sm md:text-base lg:text-lg py-4 py-lg-6 px-4 <?= esc_attr( $custom_classes ) ?>">
                <?php
                    $heading_text = $h2_title ?: get_sub_field( 'section_title' );
                    starter_section_heading(
                        $heading_text ?: '',
                        'text-xl mb-3 md:text-2xl md:mb-4 lg:text-3xl font-bold leading-tight text-center lg:text-start',
                        $row_index - 1
                    ); ?>

                <?php if ( $has_banner ) : ?>
                    <picture class="block lg:mb-3 rounded-lg overflow-hidden shadow-lg">
                        <?php if ( $banner_mob ) : ?>
                        <source
                            srcset="<?= esc_url( $banner_mob['url'] ) ?>"
                            media="(max-width: 639px)"
                            type="<?= esc_attr( $banner_mob['mime_type'] ) ?>"
                        >
                        <?php endif; ?>

                        <?php if ( $banner_tab ) : ?>
                        <source
                            srcset="<?= esc_url( $banner_tab['url'] ) ?>"
                            media="(min-width: 640px) and (max-width: 1023px)"
                            type="<?= esc_attr( $banner_tab['mime_type'] ) ?>"
                        >
                        <?php endif; ?>

                        <?php if ( $banner_desk ) : ?>
                        <source
                            srcset="<?= esc_url( $banner_desk['url'] ) ?>"
                            media="(min-width: 1024px)"
                            type="<?= esc_attr( $banner_desk['mime_type'] ) ?>"
                        >
                        <?php endif; ?>

                        <img
                            src="<?= esc_url( $banner_fallback ) ?>"
                            alt="<?= esc_attr( $banner_alt ) ?>"
                            class="w-full h-auto object-cover rounded-lg"
                            width="<?= esc_attr( $banner_mob ? $banner_mob['width'] : ( $banner_desk ? $banner_desk['width'] : 942 ) ) ?>"
                            height="<?= esc_attr( $banner_mob ? $banner_mob['height'] : ( $banner_desk ? $banner_desk['height'] : 230 ) ) ?>"
                            <?php if ( $banner_loading !== 'none' ) : ?>loading="<?= esc_attr( $banner_loading ) ?>"<?php endif; ?>
                        >
                    </picture>
                <?php endif; ?>
                <?php if ( $description ) : ?>
                    <div class="section-content-block-desc pt-4 lg:pt-0 lg:p-4 mb-6">
                        <?= wp_kses_post( $description ) ?>
                    </div>
                <?php endif; ?>

                <?php if ( $has_lists ) :
                    $total = count( $lists );
                ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <?php foreach ( $lists as $i => $list ) :
                            $heading = $list['list_heading'] ?? '';
                            $items   = $list['list_items'] ?? [];
                            $is_last = ( $i === $total - 1 );
                            $span    = ( $is_last && $total % 2 !== 0 ) ? ' md:col-span-2' : '';
                        ?>
                            <div class="content-list<?= esc_attr( $span ) ?>">
                                <?php if ( $heading ) : ?>
                                    <h3 class="text-lg md:text-xl font-semibold mb-2"><?= esc_html( $heading ) ?></h3>
                                <?php endif; ?>

                                <?php if ( $list_description ) : ?>
                                    <div class="mb-2"><?= wp_kses_post( $list_description ) ?></div>
                                <?php endif; ?>

                                <?php if ( ! empty( $items ) ) : ?>
                                    <ul class="space-y-1">
                                        <?php foreach ( $items as $item ) :
                                            $text = $item['list_item_text'] ?? '';
                                            if ( ! $text ) continue;
                                        ?>
                                            <li class="flex items-start gap-2">
                                                <svg class="w-5 h-5 mt-0.5 shrink-0" style="color:<?= esc_attr( $checkmark_color ) ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                <span><?= wp_kses_post( $text ) ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ( $additional_content ) : ?>
                    <div class="mb-2">
                        <?= wp_kses_post( $additional_content ) ?>
                    </div>
                <?php endif; ?>

                <?php if ( $btn_text ) : ?>
                    <div class="text-center mt-6">
                    <?php starter_render_button([
                        'style'         => 'btn-base',
                        'type'          => get_sub_field( 'button_type' ) ?: 'link',
                        'text'          => $btn_text,
                        'url'           => get_sub_field( 'button_url' ) ?: '#',
                        'aria_label'    => get_sub_field( 'button_aria_label' ) ?: '',
                        'name'          => get_sub_field( 'button_name' ) ?: '',
                        'extra_classes' => get_sub_field( 'button_classes' ) ?: '',
                        'show_arrow'    => (bool) get_sub_field( 'button_show_arrow' ),
                    ]); ?>
                    </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>

    </div>
</section>
