<div class="container image-left">
    <div class="row">
        <div class="col-6 image-col">
            <img src="<?php the_sub_field('image'); ?>"
                class="img-fluid">
        </div>
        <div class="col-6 para">
            <div class="color-secondary font-secondary bold">
                <?php the_sub_field('paragraph'); ?>
            </div>
            <div class="cta holder">
                <a href="<?php the_sub_field('cta_link'); ?>"
                    class="btn-primary"><?php the_sub_field('cta_text'); ?></a>
            </div>
        </div>

    </div>

</div>