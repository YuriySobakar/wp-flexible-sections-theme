<?php
/**
 * ACF: Footer fields registration
 *
 * Registers footer-related fields on the Theme Settings options page.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function starter_acf_footer_fields() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group( [
        'key'      => 'group_footer_settings',
        'title'    => 'Footer Settings',
        'fields'   => [
            [
                'key'           => 'field_show_footer_nav',
                'label'         => 'Show Navigation in Footer',
                'name'          => 'show_footer_nav',
                'type'          => 'true_false',
                'default_value' => 1,
                'ui'            => 1,
                'instructions'  => 'Toggle to show/hide the navigation menu in the footer.',
                'wrapper'       => [ 'width' => '50%' ],
            ],
            [
                'key'           => 'field_show_footer_logo',
                'label'         => 'Show Logo in Footer',
                'name'          => 'show_footer_logo',
                'type'          => 'true_false',
                'default_value' => 1,
                'ui'            => 1,
                'instructions'  => 'Toggle to show/hide the logo in the footer.',
                'wrapper'       => [ 'width' => '50%' ],
            ],
            [
                'key'           => 'field_footer_disclaimer_title',
                'label'         => 'Disclaimer Title',
                'name'          => 'footer_disclaimer_title',
                'type'          => 'text',
                'default_value' => 'Responsible Gaming & Disclaimer',
            ],
            [
                'key'          => 'field_footer_disclaimer',
                'label'        => 'Disclaimer Text',
                'name'         => 'footer_disclaimer',
                'type'         => 'wysiwyg',
                'tabs'         => 'all',
                'toolbar'      => 'basic',
                'media_upload' => 0,
            ],
            [
                'key'           => 'field_footer_copyright',
                'label'        => 'Copyright Text',
                'name'          => 'footer_copyright',
                'type'          => 'text',
                'default_value' => '© 2025 Company Name. All rights reserved.',
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'theme-settings',
                ],
            ],
        ],
        'menu_order' => 10,
        'style'      => 'default',
    ] );
}
add_action( 'acf/init', 'starter_acf_footer_fields' );
