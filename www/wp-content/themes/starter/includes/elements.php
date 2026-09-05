<?php
/**
 * Reusable UI elements — buttons, links, etc.
 *
 * Usage:
 *   starter_render_button([
 *       'style'       => 'btn-base',          // key from starter_get_button_classes()
 *       'type'        => 'link',               // 'link' | 'button'
 *       'text'        => 'Click me',
 *       'url'         => '/some-path',         // for link
 *       'aria_label'  => 'Go to page',         // for link
 *       'name'        => 'cta',                // for button — HTML name attr
 *       'extra_classes' => 'border-green-300 text-green-300 hover:bg-green-400',
 *       'show_arrow'  => true,
 *   ]);
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Return an array of base structural Tailwind classes for a named button style.
 * Colours / skin are NOT included — they come from the ACF "extra classes" field.
 *
 * @param string $name Style key.
 * @return string[] CSS classes.
 */
function starter_get_button_classes( string $name = 'btn-base' ): array {
    $styles = [
        'btn-base' => [
            'inline-flex',
            'items-center',
            'justify-center',
            'px-6',
            'py-2',
            'border-1',
            'my-3',
            'font-semibold',
            'rounded-lg',
            'transition-colors',
            'duration-300',
            'ease-in-out',
            'focus:outline-none',
            'focus:ring-2',
            'focus:ring-offset-2',
        ],
        'btn-sm' => [
            'inline-flex',
            'items-center',
            'justify-center',
            'px-4',
            'py-1.5',
            'border',
            'text-sm',
            'font-medium',
            'rounded-md',
            'transition-colors',
            'duration-200',
            'focus:outline-none',
            'focus:ring-2',
            'focus:ring-offset-2',
        ],
        'btn-lg' => [
            'inline-flex',
            'items-center',
            'justify-center',
            'px-8',
            'py-3',
            'border-2',
            'text-lg',
            'font-bold',
            'rounded-xl',
            'transition-colors',
            'duration-300',
            'ease-in-out',
            'focus:outline-none',
            'focus:ring-2',
            'focus:ring-offset-2',
        ],
    ];

    return $styles[ $name ] ?? $styles['btn-base'];
}

/**
 * Render a button / link element.
 *
 * @param array $args {
 *     @type string $style         Key for starter_get_button_classes()  (default: 'btn-base')
 *     @type string $type          'link' | 'button'                     (default: 'link')
 *     @type string $text          Visible label
 *     @type string $url           href value (link only)
 *     @type string $aria_label    aria-label (link only)
 *     @type string $name          HTML name attribute (button only)
 *     @type string $extra_classes Additional Tailwind classes (colours, etc.)
 *     @type bool   $show_arrow    Append arrow SVG icon                 (default: true)
 * }
 */
function starter_render_button( array $args = [] ): void {
    $defaults = [
        'style'         => 'btn-base',
        'type'          => 'link',
        'text'          => '',
        'url'           => '#',
        'aria_label'    => '',
        'name'          => '',
        'extra_classes' => '',
        'show_arrow'    => true,
    ];
    $args = wp_parse_args( $args, $defaults );

    if ( ! $args['text'] ) return;

    // Build class string
    $base    = starter_get_button_classes( $args['style'] );
    $classes = implode( ' ', $base );
    if ( $args['extra_classes'] ) {
        $classes .= ' ' . $args['extra_classes'];
    }

    // Arrow SVG
    $arrow = '';
    if ( $args['show_arrow'] ) {
        $arrow = '<svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">'
               . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>'
               . '</svg>';
    }

    match ( $args['type'] ) {
        'button' => printf(
            '<button type="button" role="button" name="%s" class="%s">%s%s</button>',
            esc_attr( $args['name'] ),
            esc_attr( $classes ),
            esc_html( $args['text'] ),
            $arrow
        ),
        default => printf(
            '<a href="%s" aria-label="%s" class="%s" target="_blank" rel="noreferrer noopener nofollow" referrerpolicy="no-referrer">%s%s</a>',
            esc_url( $args['url'] ),
            esc_attr( $args['aria_label'] ?: $args['text'] ),
            esc_attr( $classes ),
            esc_html( $args['text'] ),
            $arrow
        ),
    };
}
