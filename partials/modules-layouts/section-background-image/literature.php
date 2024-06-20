<div class="container literature">
    <div class="row">

        <?php if (have_rows('literature')): ?>
        <?php while (have_rows('literature')): the_row(); ?>
        <div class="col-12 col-lg-4 col-literature">
            <div class="image-holder">
				<a href="<?php the_sub_field('link'); ?>">
					<img src="<?php the_sub_field('image'); ?>" class="img-fluid">
				</a>
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
        </div>
        <?php endwhile; endif;  ?>


    </div>

</div>
