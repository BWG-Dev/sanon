<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

?>

</div><!-- #content -->

<footer id="colophon" class="site-footer">
    <div id="#totalvalue"></div>
    <?php
    if (have_rows('footer_modules', 'option')) {
        if(get_current_blog_id() == 1) {
            while (have_rows('footer_modules', 'option')) : the_row();
                get_template_part('partials/footer-modules/' . get_row_layout());
            endwhile;
        }
    }
    if (have_rows('footer_member_modules', 'option')) {
        if(get_current_blog_id() == 3) {
            while (have_rows('footer_member_modules', 'option')) : the_row();
                require('partials/footer-modules/member_footer.php');
            endwhile;
        }
    }
    ?>
    <div class="site-info">
        <?php if (has_nav_menu('footer')) : ?>
            <nav class="footer-navigation" aria-label="<?php esc_attr_e('Footer Menu', 'twentynineteen'); ?>">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'footer',
                        'menu_class'     => 'footer-menu',
                        'depth'          => 1,
                    )
                );
                ?>
            </nav><!-- .footer-navigation -->
        <?php endif; ?>
    </div><!-- .site-info -->
</footer><!-- #colophon -->

<!-- The Modal -->
<div id="snon-Modal" class="snon-modal">

    <!-- Modal content -->
    <div class="snon-modal-content">

        <p>
            You are now leaving the official website for S-Anon International Family Groups, Inc.

            This link is made available to provide information about local S-Anon & S-Ateen groups. By providing this link we do not imply review, endorsement or approval of the linked site.

            Thank you for visiting www.sanon.org. We hope that you have found the information you were seeking.
        </p>
        <span class="gotosite"> </span> <span class="snon-close btn btn-primary"> Deny </span>
    </div>

</div>

</div><!-- #page -->



<?php wp_footer(); ?>
<script>
    jQuery(document).ready(function() {
        jQuery("a[href^=http]").each(function(){
            if(this.href.indexOf(location.hostname) == -1 || this.href.indexOf(".pdf") != -1 ) {
                jQuery(this).attr({
                    target: "_blank"
                });
            }
        })
    });
</script>
<script>
    jQuery(document).ready(function($) {
        // first hide all
        $('.row-show').css('display', 'none')

        // how many to show
        var display_count = 0;

        // function to show
        function show(start, end) {
            for (var i = start; i < end; i++) {
                $('.row-show').eq(i).css('display', 'block')
            }
        }

        // bind click event to show between n and n+2
        $('.load-more').click(function(event) {
            show(display_count, display_count + 8);
            display_count += 8;
        })
            // trigger the first time
            .trigger('click');

        var trigger = $('.woo_sanon_cat');
        var list = $('#menu-shop-categories');

        trigger.click(function() {
            trigger.toggleClass('active');
            list.slideToggle(200);
        });

        list.click(function() {
            trigger.click();
        });


        $('.bundle_form').submit(function() {

            var total = $('.bundle_wrap .bundle_price p.price span.woocommerce-Price-amount').text();

            if (total == '$0.00') {
                alert('Please select the recordings you wish to add');
                return false;
            }
        });
    });

</script>

</body>
</html>
