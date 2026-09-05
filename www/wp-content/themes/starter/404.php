<?php
/**
 * 404 — Page Not Found
 */
get_header();
?>

<main class="flex-1 flex items-center justify-center py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-6xl md:text-8xl font-bold mb-4 opacity-20">404</h1>
        <h2 class="text-2xl md:text-3xl font-semibold mb-4"><?= esc_html__( 'Page Not Found', 'starter' ) ?></h2>
        <p class="text-lg mb-8 opacity-70 max-w-md mx-auto">
            <?= esc_html__( 'The page you are looking for does not exist or has been moved.', 'starter' ) ?>
        </p>
        <a href="<?= esc_url( home_url( '/' ) ) ?>"
           class="<?= esc_attr( implode( ' ', starter_get_button_classes( 'btn-base' ) ) ) ?> border-green-300 text-green-300 hover:bg-green-400 hover:text-black">
            <?= esc_html__( 'Go Home', 'starter' ) ?>
            <svg class="w-5 h-5 inline-block ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</main>

<?php get_footer(); ?>
