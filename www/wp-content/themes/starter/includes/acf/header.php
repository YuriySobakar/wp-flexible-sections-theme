<?php
/**
 * ACF: Header CTA fields registration
 *
 * CTA button text and link on the Theme Settings options page.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function starter_acf_header_fields() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group( [
        'key'      => 'group_header_settings',
        'title'    => 'Header CTA Button',
        'fields'   => [
            [
                'key'           => 'field_cta_button_text',
                'label'         => 'CTA Button Text',
                'name'          => 'cta_button_text',
                'type'          => 'text',
                'default_value' => 'Login',
                'instructions'  => 'Text displayed on the header CTA button.',
                'wrapper'       => [ 'width' => '50%' ],
            ],
            [
                'key'           => 'field_cta_button_url',
                'label'         => 'CTA Button URL',
                'name'          => 'cta_button_url',
                'type'          => 'text',
                'default_value' => '',
                'instructions'  => 'Link for the header CTA button.',
                'wrapper'       => [ 'width' => '50%' ],
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
        'menu_order' => 5,
        'style'      => 'default',
    ] );
}
add_action( 'acf/init', 'starter_acf_header_fields' );
