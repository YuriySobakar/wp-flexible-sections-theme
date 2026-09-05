<?php
/**
 * ACF fields for the "FAQ" Flexible Content layout.
 *
 * Tabs: Content (h2_title, FAQ repeater with question + answer, icon/divider colors).
 * Shared Settings tab is prepended automatically via _shared.php.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'starter_section_fields_faq', function ( array $fields ): array {
    $p = 'layout_faq_';

    return [
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
            'key'           => $p . 'icon_color',
            'label'         => 'Toggle Icon Color',
            'name'          => 'icon_color',
            'type'          => 'color_picker',
            'default_value' => '#4ade80',
            'instructions'  => 'Color of the +/× toggle icon.',
            'wrapper'       => [ 'width' => '30%' ],
        ],
        [
            'key'           => $p . 'divider_color',
            'label'         => 'Divider Color',
            'name'          => 'divider_color',
            'type'          => 'color_picker',
            'default_value' => '#374151',
            'instructions'  => 'Color of the line between FAQ items.',
            'wrapper'       => [ 'width' => '30%' ],
        ],
        // ── FAQ Repeater ──
        [
            'key'          => $p . 'faq_items',
            'label'        => 'FAQ Items',
            'name'         => 'faq_items',
            'type'         => 'repeater',
            'min'          => 1,
            'max'          => 50,
            'layout'       => 'block',
            'button_label' => 'Add Question',
            'sub_fields'   => [
                [
                    'key'      => $p . 'faq_question',
                    'label'    => 'Question',
                    'name'     => 'faq_question',
                    'type'     => 'text',
                    'required' => 1,
                ],
                [
                    'key'          => $p . 'faq_answer',
                    'label'        => 'Answer',
                    'name'         => 'faq_answer',
                    'type'         => 'textarea',
                    'rows'         => 3,
                    'required'     => 1,
                ],
            ],
        ],
    ];
});
