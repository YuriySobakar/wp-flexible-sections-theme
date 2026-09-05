<?php
/**
 * Flexible Content layout: FAQ
 *
 * ACF fields : h2_title, faq_items (repeater: question + answer), icon_color, divider_color
 * Shared     : section_title, section_id, bg_color, heading_color, content_color, padding_y, custom_classes
 *
 * Schema     : Microdata FAQPage (https://schema.org/FAQPage)
 * Accordion  : CSS-only <details>/<summary> — zero JS, native browser support, animated.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$section_id     = get_sub_field( 'section_id' );
$h2_title       = get_sub_field( 'h2_title' );
$custom_classes = get_sub_field( 'custom_classes' ) ?: '';
$row_index      = get_row_index(); // 1-based
$styles         = starter_section_styles();
$icon_color     = get_sub_field( 'icon_color' ) ?: '#4ade80';
$divider_color  = get_sub_field( 'divider_color' ) ?: '#374151';
$faq_items      = get_sub_field( 'faq_items' );
$has_items      = ! empty( $faq_items ) && is_array( $faq_items );

if ( ! $has_items ) return;
?>

<section
    class="<?= esc_attr( starter_section_classes( 'section-faq text-white' ) ) ?>"
    <?php if ( $styles ) : ?>style="<?= esc_attr( $styles ) ?>"<?php endif; ?>
    <?php if ( $section_id ) : ?>id="<?= esc_attr( $section_id ) ?>"<?php endif; ?>
    itemscope
    itemtype="https://schema.org/FAQPage"
>
    <div class="container mx-auto px-4">
        <div class="section-faq-body max-w-5xl mx-auto text-sm md:text-base lg:text-lg <?= esc_attr( $custom_classes ) ?>">

            <?php
            $heading_text = $h2_title ?: get_sub_field( 'section_title' );
            starter_section_heading(
                $heading_text ?: '',
                'text-xl mb-3 md:text-2xl md:mb-4 lg:text-3xl font-bold leading-tight text-center lg:text-start',
                $row_index - 1
            ); ?>

            <div class="faq-accordion divide-y" style="--faq-divider:<?= esc_attr( $divider_color ) ?>;--faq-icon:<?= esc_attr( $icon_color ) ?>">
                <?php foreach ( $faq_items as $item ) :
                    $question = $item['faq_question'] ?? '';
                    $answer   = $item['faq_answer'] ?? '';
                    if ( ! $question || ! $answer ) continue;
                ?>
                <details
                    class="faq-item group border-[var(--faq-divider)]"
                    itemscope
                    itemprop="mainEntity"
                    itemtype="https://schema.org/Question"
                >
                    <summary class="flex items-center justify-between gap-4 cursor-pointer py-4 md:py-5 select-none list-none [&::-webkit-details-marker]:hidden">
                        <h3 class="text-base md:text-lg font-semibold" itemprop="name"><?= esc_html( $question ) ?></h3>
                        <span class="faq-icon shrink-0 w-6 h-6 relative transition-transform duration-300 group-open:rotate-45" style="color:var(--faq-icon)">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                            </svg>
                        </span>
                    </summary>

                    <div
                        class="faq-answer pb-4 md:pb-5 pr-10"
                        itemscope
                        itemprop="acceptedAnswer"
                        itemtype="https://schema.org/Answer"
                    >
                        <div itemprop="text">
                            <?= wp_kses_post( $answer ) ?>
                        </div>
                    </div>
                </details>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</section>
