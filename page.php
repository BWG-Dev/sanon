<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

 if (get_field('select_header') == 'home'):
get_template_part('header-home');
else:
 get_template_part('header');
endif;

?>

<section id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
                while (have_posts()) :
                the_post();
              

                if (have_rows('modules')):
                while (have_rows('modules')) : the_row();
                get_template_part('partials/modules-layouts/'. get_row_layout());
                endwhile;
                endif;
                // If comments are open or we have at least one comment, load up the comment template.
               echo '<div class="page-content-editor">';
                get_template_part('template-parts/content/content', 'page');
              echo  '</div>';
                endwhile; // End of the loop.
            ?>
    </main><!-- #main -->
</section><!-- #primary -->

<?php get_footer(); ?>
