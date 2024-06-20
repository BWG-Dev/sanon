<div class="container testimonial">
    <div class="row row-testimonial">

        <?php if (have_rows('testimonials')): ?>
        <?php while (have_rows('testimonials')): the_row(); ?>
        <div class="col-4 col-testimonial">
            <div class="inner">
                <div class="info">
                    <div class="font-primary bold gradient-primary">
                        <p><?php the_sub_field('name'); ?>
                        </p>
                    </div>

                    <div class="font-secondary bold color-primary">
                        <h3><?php the_sub_field('age'); ?>
                        </h3>
                    </div>
                    <hr>
                    </>
                </div>
                <div class="font-secondary bold  color-primary">
                    <?php the_sub_field('paragraph'); ?>
                </div>
            </div>
        </div>
        <?php endwhile; endif;  ?>


    </div>

</div>