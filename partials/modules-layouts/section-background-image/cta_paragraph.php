<div class="container cta-paragraph narrow-inner text-center">
    <div class="row cta-row" style="background-image: url(<?php the_sub_field('background_image'); ?>);">

        <div class="gradient-primary font-primary bold title-cta">
            <h2><?php the_sub_field('title'); ?>
            </h2>
        </div>
        <div class="color-secondary">
            <p><?php the_sub_field('text'); ?>
            </p>
        </div>
        <div class="button-holder">
            <a href="<?php the_sub_field('cta_link'); ?>"
                class="btn-primary"><?php the_sub_field('cta_text'); ?></a>
        </div>
    </div>


</div>