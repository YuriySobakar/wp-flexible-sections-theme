<?php
/**
 * ACF fields for the "Content Block" Flexible Content layout.
 *
 * Tabs: Banner Settings (responsive images), Content (h2, WYSIWYG, lists repeater, button).
 * Shared Settings tab is prepended automatically via _shared.php.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'starter_section_fields_content-block', function ( array $fields ): array {
    $p = 'layout_content-block_';

    return [
        // ── Tab: Banner Settings ──
        [
            'key'   => $p . 'tab_banner',
            'label' => 'Banner',
            'type'  => 'tab',
        ],
        [
            'key'           => $p . 'banner_image_mobile',
            'label'         => 'Banner Image — Mobile',
            'name'          => 'banner_image_mobile',
            'type'          => 'image',
            'return_format' => 'array',
            'preview_size'  => 'medium',
            'mime_types'    => 'avif,webp,jpg,png',
            'instructions'  => 'Recommended: 320×196 AVIF. Shown below 640 px.',
            'wrapper'       => [ 'width' => '33%' ],
        ],
        [
            'key'           => $p . 'banner_image_tablet',
            'label'         => 'Banner Image — Tablet',
            'name'          => 'banner_image_tablet',
            'type'          => 'image',
            'return_format' => 'array',
            'preview_size'  => 'medium',
            'mime_types'    => 'avif,webp,jpg,png',
            'instructions'  => 'Recommended: 800×491 WebP. Shown 640–1023 px.',
            'wrapper'       => [ 'width' => '33%' ],
        ],
        [
            'key'           => $p . 'banner_image_desktop',
            'label'         => 'Banner Image — Desktop',
            'name'          => 'banner_image_desktop',
            'type'          => 'image',
            'return_format' => 'array',
            'preview_size'  => 'medium',
            'mime_types'    => 'avif,webp,jpg,png',
            'instructions'  => 'Recommended: 1800 px wide WebP. Shown 1024 px+.',
            'wrapper'       => [ 'width' => '33%' ],
        ],
        [
            'key'          => $p . 'banner_alt',
            'label'        => 'Banner Alt Text',
            'name'         => 'banner_alt',
            'type'         => 'text',
            'instructions' => 'Override alt text. Falls back to Media Library alt if empty.',
            'wrapper'      => [ 'width' => '50%' ],
        ],
        [
            'key'           => $p . 'banner_loading',
            'label'         => 'Lazy Loading',
            'name'          => 'banner_loading',
            'type'          => 'select',
            'choices'       => [
                'lazy'  => 'lazy (default)',
                'eager' => 'eager (above the fold)',
                'none'  => 'none (no attribute)',
            ],
            'default_value' => 'lazy',
            'instructions'  => 'Set loading attribute. Use "eager" if banner is above the fold.',
            'wrapper'       => [ 'width' => '50%' ],
        ],
        // ── Tab: Content ──
        [
            'key'   => $p . 'tab_content',
            'label' => 'Content',
            'type'  => 'tab',
        ],
        [
            'key'          => $p . 'h2_title',
            'label'        => 'H2 Title',
            'name'         => 'h2_title',
            'type'         => 'text',
            'instructions' => 'Visible heading on the frontend (h2). Leave empty to hide.',
        ],
        [
            'key'          => $p . 'description',
            'label'        => 'Description',
            'name'         => 'description',
            'type'         => 'wysiwyg',
            'instructions' => 'Main content text. Supports rich formatting.',
            'tabs'         => 'all',
            'toolbar'      => 'full',
            'media_upload' => 1,
        ],
        [
            'key'           => $p . 'checkmark_color',
            'label'         => 'List Checkmark Color',
            'name'          => 'checkmark_color',
            'type'          => 'color_picker',
            'default_value' => '#4ade80',
            'instructions'  => 'Color of the checkmark icons in lists.',
            'wrapper'       => [ 'width' => '30%' ],
        ],
        // ── Lists (repeater of repeaters) ──
        [
            'key'          => $p . 'content_lists',
            'label'        => 'Lists',
            'name'         => 'content_lists',
            'type'         => 'repeater',
            'min'          => 0,
            'max'          => 6,
            'layout'       => 'block',
            'button_label' => 'Add List',
            'instructions' => 'Each list gets its own column (2 per row). Odd last item spans full width.',
            'sub_fields'   => [
                [
                    'key'          => $p . 'list_heading',
                    'label'        => 'List Heading (H3)',
                    'name'         => 'list_heading',
                    'type'         => 'text',
                    'instructions' => 'Optional. Leave empty to hide.',
                ],
                [
                    'key'  => $p . '-description',
                    'label' => 'List Description',
                    'name' => 'list_description',
                    'type' => 'wysiwyg',
                ],
                [
                    'key'          => $p . 'list_items',
                    'label'        => 'Items',
                    'name'         => 'list_items',
                    'type'         => 'repeater',
                    'min'          => 1,
                    'max'          => 30,
                    'layout'       => 'table',
                    'button_label' => 'Add Item',
                    'sub_fields'   => [
                        [
                            'key'   => $p . 'list_item_text',
                            'label' => 'Text',
                            'name'  => 'list_item_text',
                            'type'  => 'textarea',
                            'rows'  => 2,
                            'new_lines' => 'br',
                        ],
                    ],
                ],
            ],
        ],
        [
            'key'           => $p . 'additional_content',
            'label'         => 'Additional Content',
            'name'          => 'additional_content',
            'type'          => 'wysiwyg',
            'instructions'  => 'Additional content area below lists. Supports rich formatting.',
            'tabs'         => 'all',
            'toolbar'      => 'full',
            'media_upload' => 1,
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
            'instructions' => 'HTML name attribute for the button tag.',
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
    ];
});
