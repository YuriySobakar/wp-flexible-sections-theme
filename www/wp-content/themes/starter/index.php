<?php
get_header();
?>
<main class="flex-1">
    <?php
    if ( function_exists( 'have_rows' ) && have_rows( 'page_sections' ) ):
        while ( have_rows( 'page_sections' ) ): the_row();
            get_template_part( 'parts/layouts/' . get_row_layout() );
        endwhile;
    endif;
    ?>
</main>
<?php
get_footer();
?>
