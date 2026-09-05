<?php
/**
 * Theme helper functions shared across section templates.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Build CSS classes string for a section from its shared ACF fields.
 *
 * @param string $base_class Section-specific base class.
 * @return string Space-separated CSS classes.
 */
function starter_section_classes( string $base_class = '' ): string {
    $classes = array_filter([
        $base_class,
        get_sub_field( 'padding_top' )    ?: 'pt-6 md:pt-8',
        get_sub_field( 'padding_bottom' ) ?: 'pb-6 md:pb-8',
    ]);
    return implode( ' ', $classes );
}

/**
 * Build inline style string from shared color-picker ACF fields.
 *
 * @return string Ready for use in style="…" attribute (no wrapping quotes).
 */
function starter_section_styles(): string {
    $styles = [];

    $bg = get_sub_field( 'bg_color' );
    if ( $bg ) {
        $styles[] = 'background-color:' . esc_attr( $bg );
    }

    $content_color = get_sub_field( 'content_color' );
    if ( $content_color ) {
        $styles[] = 'color:' . esc_attr( $content_color );
    }

    return implode( ';', $styles );
}

/**
 * Render section heading: h1 for first section on page, h2 for others.
 * Applies heading_color as inline style when set.
 *
 * @param string $text    Heading text.
 * @param string $classes Tailwind classes for the heading.
 * @param int    $index   Section index (0-based).
 */
function starter_section_heading( string $text, string $classes = '', int $index = 1 ): void {
    if ( ! $text ) return;
    $tag   = ( $index === 0 ) ? 'h1' : 'h2';
    $color = get_sub_field( 'heading_color' );
    $style = $color ? ' style="color:' . esc_attr( $color ) . '"' : '';
    printf( '<%s class="%s"%s>%s</%s>', $tag, esc_attr( $classes ), $style, esc_html( $text ), $tag );
}
