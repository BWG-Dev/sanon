<div class="container literature">
    <div class="row">

        <?php if (have_rows('literature')): ?>
        <?php while (have_rows('literature')): the_row(); ?>
        <div class="col-4 col-literature">
            <div class="image-holder">
                <img src="<?php the_sub_field('image'); ?>"
                    class="img-fluid">
            </div>
            <div class="gradient-primary font-primary bold">
                <h2><?php the_sub_field('title'); ?>
                </h2>
            </div>
            <div class="line">
                <hr>
            </div>
            <div class="font-secondary color-secondary bold">
                <?php the_sub_field('paragraph'); ?>
            </div>
            <?php if (get_sub_field('link')): ?>
            <a href="<?php the_sub_field('link'); ?>"
                class="btn-primary">Read More</a>
            <?php endif; ?>



        </div>
        <?php endwhile; endif;  ?>


    </div>

</div>