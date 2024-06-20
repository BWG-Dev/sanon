<div class="container-fluid section-background-image " style="<?php the_sub_field('background_image_style'); ?>;background-image: url(<?php the_sub_field('background_image'); ?>);">
   <?php
                if (have_rows('section_background_image')):
                while (have_rows('section_background_image')) : the_row();
                get_template_part('partials/modules-layouts/section-background-image/'. get_row_layout());
                endwhile;
                endif;
                ?>

</div>