<?php
/**
 * ACF fields for the "Slider Hero" Flexible Content layout.
 *
 * Registers: Slides repeater (mobile / tablet / desktop images + alt),
 *            Link settings (URL, target, aria-label).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'starter_section_fields_slider-hero', function ( array $fields ): array {
    $p = 'layout_slider-hero_';

    return [
        // ── Tab: Slides ──
        [
            'key'   => $p . 'tab_slides',
            'label' => 'Slider',
            'type'  => 'tab',
        ],
        [
            'key'           => $p . '-show-slider',
            'label'         => 'Show Slider',
            'name'          => 'show_slider',
            'type'          => 'true_false',
            'default_value' => 0,
            'ui'            => 1,
            'instructions'  => 'Enable or disable the slider.',
        ],
        [
            'key'          => $p . '-slides',
            'label'        => 'Slider',
            'name'         => 'slides',
            'type'         => 'repeater',
            'layout'       => 'block',
            'button_label' => 'Add Slide',
            'sub_fields'   => [
                [
                    'key'           => $p . 'slide_image_mobile',
                    'label'         => 'Mobile Image',
                    'name'          => 'slide_image_mobile',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'medium',
                    'mime_types'    => 'avif,webp,jpg,png',
                    'instructions'  => 'Recommended: 320×196 AVIF. Shown below 640 px.',
                    'required'      => 1,
                ],
                [
                    'key'           => $p . 'slide_image_tablet',
                    'label'         => 'Tablet Image',
                    'name'          => 'slide_image_tablet',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'medium',
                    'mime_types'    => 'avif,webp,jpg,png',
                    'instructions'  => 'Recommended: 800×491 WebP. Shown 640–1023 px.',
                ],
                [
                    'key'           => $p . 'slide_image_desktop',
                    'label'         => 'Desktop Image',
                    'name'          => 'slide_image_desktop',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'medium',
                    'mime_types'    => 'avif,webp,jpg,png',
                    'instructions'  => 'Recommended: 1800 px wide WebP. Shown 1024 px+.',
                ],
                [
                    'key'          => $p . 'slide_alt',
                    'label'        => 'Alt Text',
                    'name'         => 'slide_alt',
                    'type'         => 'text',
                    'instructions' => 'Leave empty to use Media Library alt.',
                ],
            ],
        ],
        [
            'key' => $p . 'tab_content',
            'label' => 'Content',
            'type'  => 'tab',
        ],
        [
            'key'          => $p . 'h1_title',
            'label'        => 'H1 Title',
            'name'         => 'h1_title',
            'type'         => 'text',
            'instructions' => 'Main visible heading (rendered as h1). Leave empty to hide.',
        ],
        [
            'key' => $p . 'hero_description',
            'label' => 'Hero Description Text',
            'name' => 'hero_description',
            'type' => 'wysiwyg',
            'instructions' => 'Enter the hero description text.',
        ],
        // ── Button / Link ──
        [
            'key'           => $p . 'button_type',
            'label'         => 'Button Type',
            'name'          => 'button_type',
            'type'          => 'select',
            'choices'       => [
                'link'   => 'Link (<a>)',
                'button' => 'Button (<button>)',
            ],
            'default_value' => 'link',
            'wrapper'       => [ 'width' => '30%' ],
        ],
        [
            'key'          => $p . 'button_text',
            'label'        => 'Button Text',
            'name'         => 'button_text',
            'type'         => 'text',
            'wrapper'      => [ 'width' => '70%' ],
        ],
        [
            'key'          => $p . 'button_url',
            'label'        => 'Button URL',
            'name'         => 'button_url',
            'type'         => 'text',
            'instructions' => 'Plain text — supports WP shortlinks / redirect slugs.',
            'conditional_logic' => [
                [[ 'field' => $p . 'button_type', 'operator' => '==', 'value' => 'link' ]],
            ],
            'wrapper' => [ 'width' => '50%' ],
        ],
        [
            'key'          => $p . 'button_aria_label',
            'label'        => 'Aria Label',
            'name'         => 'button_aria_label',
            'type'         => 'text',
            'instructions' => 'Accessible label for screen readers.',
            'conditional_logic' => [
                [[ 'field' => $p . 'button_type', 'operator' => '==', 'value' => 'link' ]],
            ],
            'wrapper' => [ 'width' => '50%' ],
        ],
        [
            'key'          => $p . 'button_name',
            'label'        => 'Button Name',
            'name'         => 'button_name',
            'type'         => 'text',
            'instructions' => 'HTML name attribute for the button html tag.',
            'conditional_logic' => [
                [[ 'field' => $p . 'button_type', 'operator' => '==', 'value' => 'button' ]],
            ],
            'wrapper' => [ 'width' => '50%' ],
        ],
        [
            'key'          => $p . 'button_classes',
            'label'        => 'Button Styling (colors etc.)',
            'name'         => 'button_classes',
            'type'         => 'text',
            'instructions' => 'Tailwind color / hover / ring classes, e.g. border-green-300 text-green-300 hover:bg-green-400 hover:text-black',
            'wrapper' => [ 'width' => '50%' ],
        ],
        [
            'key'           => $p . 'button_show_arrow',
            'label'         => 'Show Arrow Icon',
            'name'          => 'button_show_arrow',
            'type'          => 'true_false',
            'default_value' => 1,
            'ui'            => 1,
            'wrapper'       => [ 'width' => '30%' ],
        ],
        // ── Tab: Link ──
        [
            'key'   => $p . 'tab_link',
            'label' => 'Slider Link',
            'type'  => 'tab',
        ],
        [
            'key'          => $p . 'slider_link_url',
            'label'        => 'Slider Link URL',
            'name'         => 'slider_link_url',
            'type'         => 'text',
            'instructions' => 'The entire slider becomes a clickable link.',
        ],
        [
            'key'           => $p . 'slider_link_target',
            'label'         => 'Slider Link Target',
            'name'          => 'slider_link_target',
            'type'          => 'select',
            'choices'       => [
                '_blank' => 'New Tab',
                '_self'  => 'Same Tab',
            ],
            'default_value' => '_blank',
        ],
        [
            'key'          => $p . 'slider_link_aria',
            'label'        => 'Slider Link Aria Label',
            'name'         => 'slider_link_aria',
            'type'         => 'text',
            'instructions' => 'Accessible label, e.g. "Visit site".',
        ],
        // ── Tab: Animation ──
        [
            'key'   => $p . 'tab_animation',
            'label' => 'Animation',
            'type'  => 'tab',
        ],
        [
            'key'           => $p . 'slider_autoplay',
            'label'         => 'Autoplay',
            'name'          => 'slider_autoplay',
            'type'          => 'true_false',
            'default_value' => 1,
            'ui'            => 1,
            'instructions'  => 'Enable or disable automatic slide rotation.',
            'wrapper'       => [ 'width' => '33%' ],
        ],
        [
            'key'           => $p . 'slider_delay',
            'label'         => 'Delay (ms)',
            'name'          => 'slider_delay',
            'type'          => 'number',
            'default_value' => 4000,
            'min'           => 1000,
            'max'           => 15000,
            'step'          => 500,
            'instructions'  => 'Pause between slides in milliseconds (1000 = 1 sec).',
            'wrapper'       => [ 'width' => '33%' ],
        ],
        [
            'key'           => $p . 'slider_speed',
            'label'         => 'Transition Speed (ms)',
            'name'          => 'slider_speed',
            'type'          => 'number',
            'default_value' => 600,
            'min'           => 100,
            'max'           => 3000,
            'step'          => 100,
            'instructions'  => 'Slide transition duration in milliseconds.',
            'wrapper'       => [ 'width' => '33%' ],
        ],
    ];
});
