<div class="container subscribe">
    <a id="welcome-packet">&nbsp;</a>
    <div class="row row-subscribe">
        <div class="col-12 col-lg-6 image-col">
            <div class="image-holder">
                <img src="<?php the_sub_field('image'); ?>"
                    class="img-fluid">
            </div>
        </div>
        <div class="col-12 col-lg-6 paragraph-col">
            <div class="bold font-primary gradient-primary bold title-area">
                <h3><?php the_sub_field('title'); ?>
                </h3>
            </div>
            <div class="form holder font-secondary color-secondary main-form">
                <?php the_sub_field('subscribe'); ?>
            </div>
        </div>

    </div>

</div>