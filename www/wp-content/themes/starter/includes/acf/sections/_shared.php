<?php
/**
 * Shared ACF fields appended to every section layout.
 *
 * Adds a "Settings" tab with: section_title, section_id, bg_color, padding, custom_classes.
 * Attached via filter priority 99 to run after section-specific fields.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'starter_section_shared_fields', function ( array $fields, string $slug ): array {
    $p = "layout_{$slug}_";

    $shared = [
        [ 'key' => $p . 'tab_settings', 'label' => 'Settings', 'type' => 'tab' ],
        [
            'key' => $p . 'section_title', 'label' => 'Section Title',
            'name' => 'section_title', 'type' => 'text',
            'instructions' => 'Displayed as heading and in the admin layout bar.',
        ],
        [
            'key' => $p . 'section_id', 'label' => 'Section ID',
            'name' => 'section_id', 'type' => 'text',
            'instructions' => 'HTML id for anchor links.',
        ],
        [
            'key'          => $p . 'bg_color',
            'label'        => 'Background Color',
            'name'         => 'bg_color',
            'type'         => 'color_picker',
            'default_value' => '',
            'instructions'  => 'Pick a color or enter a hex value (e.g. #2a2e3b). Leave empty for transparent.',
            'wrapper'       => [ 'width' => '30%' ],
        ],
        [
            'key'          => $p . 'heading_color',
            'label'        => 'Heading Text Color',
            'name'         => 'heading_color',
            'type'         => 'color_picker',
            'default_value' => '',
            'instructions'  => 'Color for section heading (h1/h2). Leave empty for default.',
            'wrapper'       => [ 'width' => '30%' ],
        ],
        [
            'key'          => $p . 'content_color',
            'label'        => 'Content Text Color',
            'name'         => 'content_color',
            'type'         => 'color_picker',
            'default_value' => '',
            'instructions'  => 'Color for body text / content. Leave empty for default.',
            'wrapper'       => [ 'width' => '30%' ],
        ],
        [
            'key' => $p . 'padding_top', 'label' => 'Padding Top',
            'name' => 'padding_top', 'type' => 'select',
            'instructions' => 'Top padding for the section.',
            'wrapper' => [ 'width' => '25%' ],
            'choices' => [
                'pt-2 md:pt-4'  => 'Small',
                'pt-6 md:pt-8'  => 'Medium (default)',
                'pt-8 md:pt-12' => 'Large',
                'pt-0'          => 'None',
            ],
            'default_value' => 'pt-6 md:pt-8',
        ],
        [
            'key' => $p . 'padding_bottom', 'label' => 'Padding Bottom',
            'name' => 'padding_bottom', 'type' => 'select',
            'instructions' => 'Bottom padding for the section.',
            'wrapper' => [ 'width' => '25%' ],
            'choices' => [
                'pb-2 md:pb-4'  => 'Small',
                'pb-6 md:pb-8'  => 'Medium (default)',
                'pb-8 md:pb-12' => 'Large',
                'pb-0'          => 'None',
            ],
            'default_value' => 'pb-6 md:pb-8',
        ],
        [
            'key' => $p . 'custom_classes', 'label' => 'Custom CSS Classes',
            'name' => 'custom_classes', 'type' => 'text',
            'instructions' => 'Extra Tailwind classes, space-separated.',
            'wrapper' => [ 'width' => '50%' ],
        ],
    ];

    // Settings tab FIRST, then section-specific tabs
    return array_merge( $shared, $fields );
}, 99, 2 );
