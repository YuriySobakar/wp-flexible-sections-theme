<?php
/**
 * ACF Flexible Content: Page Sections — Master Registry
 *
 * Single source of truth for available section layouts.
 * To add a new section:
 *   1. Add slug to $starter_sections below
 *   2. Create includes/acf/sections/{slug}.php (field definition)
 *   3. Create parts/layouts/{slug}.php (template)
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// === MASTER SECTION REGISTRY ===
global $starter_sections;
$starter_sections = [
    'slider-hero',
    'content-block',
    'faq',
];
sort( $starter_sections );

// Load shared fields + per-section field definitions
require_once __DIR__ . '/sections/_shared.php';
foreach ( $starter_sections as $section ) {
    $file = __DIR__ . "/sections/{$section}.php";
    if ( file_exists( $file ) ) {
        require_once $file;
    }
}

/**
 * Build layouts array for ACF Flexible Content from section slugs.
 *
 * @param array $sections Array of section slug strings.
 * @return array ACF layouts configuration.
 */
function starter_build_section_layouts( array $sections ): array {
    $layouts = [];
    foreach ( $sections as $slug ) {
        $label = ucwords( str_replace( '-', ' ', $slug ) );

        // Collect section-specific fields via filter
        $fields = apply_filters( "starter_section_fields_{$slug}", [] );

        // Append shared fields (Settings tab)
        $fields = apply_filters( 'starter_section_shared_fields', $fields, $slug );

        $layouts[ "layout_{$slug}" ] = [
            'key'        => "layout_{$slug}",
            'name'       => $slug,
            'label'      => $label,
            'display'    => 'block',
            'sub_fields' => $fields,
        ];
    }
    return $layouts;
}

// === Register ACF Field Group ===
add_action( 'acf/init', function () {
    global $starter_sections;
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;
    if ( empty( $starter_sections ) ) return;

    acf_add_local_field_group([
        'key'      => 'group_page_sections',
        'title'    => 'Page Sections',
        'fields'   => [
            [
                'key'          => 'field_page_sections',
                'label'        => 'Sections',
                'name'         => 'page_sections',
                'type'         => 'flexible_content',
                'button_label' => 'Add Section',
                'layouts'      => starter_build_section_layouts( $starter_sections ),
            ],
        ],
        'location' => [
            [[ 'param' => 'post_type', 'operator' => '==', 'value' => 'page' ]],
        ],
        'position'        => 'acf_after_title',
        'hide_on_screen'  => [ 'the_content' ],
        'menu_order'      => 0,
    ]);
});

// === Admin UX: show section_title in collapsed layout header ===
add_filter( 'acf/fields/flexible_content/layout_title', function ( $title, $field, $layout, $i ) {
    $section_title = get_sub_field( 'section_title' );
    if ( $section_title ) {
        return '<strong>' . esc_html( $section_title ) . '</strong> — <small>' . $title . '</small>';
    }
    return $title;
}, 10, 4 );
