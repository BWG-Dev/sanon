<div class="container-fluid section-background-image section-mobile-design" style="<?php the_sub_field('section_container_style');
 ?>;">

    <?php
      if (have_rows('section_standard')):
         while (have_rows('section_standard')) : the_row();
            get_template_part('partials/modules-layouts/section-standard/'. get_row_layout());
         endwhile;
      endif;
      ?>

</div>