<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

?>
<div class="container">
    <div class="row">

        <?php if (! twentynineteen_can_show_post_thumbnail()) : ?>

        <?php endif; ?>

        <div class="entry-content container">
            <?php
        the_content();

        wp_link_pages(
            array(
                'before' => '<div class="page-links">' . __('Pages:', 'twentynineteen'),
                'after'  => '</div>',
            )
        );
        ?>
        </div><!-- .entry-content -->


<?php if ( is_active_sidebar( 'page_bottom_area' ) ) : ?>
	<div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
		<?php dynamic_sidebar( 'page_bottom_area' ); ?>
	</div><!-- #primary-sidebar -->
<?php endif; ?>


        <?php if (get_edit_post_link()) : ?>
        <footer class="entry-footer">
            <?php
            edit_post_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __('Edit <span class="screen-reader-text">%s</span>', 'twentynineteen'),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    get_the_title()
                ),
                '<span class="edit-link">',
                '</span>'
            );
            ?>
    </div>
</div>
</footer><!-- .entry-footer -->
<?php endif;
