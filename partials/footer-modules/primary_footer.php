<div class="container-fluid">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-9 first-main-part">
                <div class="row">
                    <div class="col-12 col-lg-2">
                        <a href="<?php echo esc_url(home_url('/')); ?>"
                            rel="home">
                            <img
                                src="<?php the_sub_field('links_footer_image'); ?>">
                        </a>
                    </div>
                    <div class="col-12 col-lg-10 second-main-part">
                        <div class="row">
                            <div class="col-6 col-lg-4 font-primary bold third-section">
                                <h3><?php the_sub_field('links_title'); ?>
                                </h3>
                                <hr>
                                <ul>
                                    <?php if (have_rows('links_copy')): ?>
                                    <?php while (have_rows('links_copy')): the_row(); ?>

                                    <li><a href=" <?php the_sub_field('link_url'); ?>"
                                            rel="home">
                                            <?php the_sub_field('link_title'); ?>
                                        </a>
                                    </li>
                                    <?php endwhile; endif;  ?>
                                </ul>
<div class="social-links"><a href="https://www.instagram.com/sanon_wso/" target="_blank"><img src="https://sanon.org/wp-content/uploads/2023/07/Instagram_Glyph_White.png" /></a>
<a href="https://www.tiktok.com/@sanon_wso" target="_blank"><img style="max-height: 30px;" src="https://sanon.org/wp-content/uploads/2023/07/TikTok-Social-Icon-Mono-White.png" /></a>
</div>
                            </div>
                            <div class="col-6 col-lg-4 font-primary bold  third-section">
                                <h3><?php the_sub_field('resources_title'); ?>
                                </h3>
                                <hr>
                                <ul>
                                    <?php if (have_rows('resources_copy')): ?>
                                    <?php while (have_rows('resources_copy')): the_row(); ?>

                                    <li><a href=" <?php the_sub_field('resource_url'); ?>"
                                            rel="home">
                                            <?php the_sub_field('resource_title'); ?>
                                        </a>
                                    </li>
                                    <?php endwhile; endif;  ?>
                                </ul>
                            </div>
                            <div class="col-12 col-lg-4 font-primary bold  third-section">
                                <h3><?php the_sub_field('contact_title'); ?>
                                </h3>
                                <hr>
                                <div class="font-secondary">
                                    <?php the_sub_field('contact_content'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 first-main-part d-none d-lg-block">
                                <div class="row">
                                    <div class="col-12 col-lg-7 font-secondary larger-section">
                                        <p><?php the_sub_field('copyright'); ?>
                                        </p>
                                    </div>
                                    <div class="col-12 col-lg-5 smaller-section">
                                        <div class="font-secondary">
                                            <p><a href="<?php the_sub_field('privacy_policy_text_link'); ?>"
                                                    rel="home" class="privacy"> <?php the_sub_field('privacy_policy_text'); ?>
                                                </a>

                                                <a href=" <?php the_sub_field('permission_link'); ?>"
                                                    rel="home" class="per"> <?php the_sub_field('permission_text'); ?>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
            <div class="col-12 col-lg-3 font-primary bold">
                <h3><?php the_sub_field('recent_posts_title'); ?>
                </h3>
                <hr>

                <?php $cat = get_sub_field('recent_posts_category_select'); ?>
                <div class="font-secondary">
                    <ul>
                        <?php
                global $post;
                $args = array( 'posts_per_page' => 4, 'offset'=> 1);
                $myposts = get_posts($args);
                foreach ($myposts as $post) :
                setup_postdata($post); ?>
                        <li>
                            <a href="<?php the_permalink(); ?>">
                                <h2><?php the_title(); ?>
                                </h2>
                                <p><?php echo get_the_date('m / d / y');?>
                                </p>
                            </a>
                        </li>
                        <?php endforeach; wp_reset_postdata(); ?>
                    </ul>
                </div>
            </div>


        </div>
        <div class="row mobile-only">
            <div class="col-12 first-main-part d-lg-none">
                <div class="row">
                    <div class="col-12 col-lg-7 font-secondary larger-section">
                        <p>Copyright &copy;<?php echo date('Y'); ?> <?php the_sub_field('copyright'); ?>
                        </p>
                    </div>
                    <div class="col-12 col-lg-5 smaller-section">
                        <div class="font-secondary">
                            <p><a href="<?php the_sub_field('privacy_policy_text_link'); ?>"
                                    rel="home" class="privacy"> <?php the_sub_field('privacy_policy_text'); ?>
                                </a>

                                <a href=" <?php the_sub_field('permission_link'); ?>"
                                    rel="home" class="per"> <?php the_sub_field('permission_text'); ?>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>