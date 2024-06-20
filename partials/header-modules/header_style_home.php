<header id="masthead" class="home" style="background: url('/wp-content/uploads/2019/01/internal-banner.png');">
	<?php include __DIR__.'/../header-modules/top-header.php'; ?>
    <div class="mobile-menu-holder">
        <p>
            <a class="mobile-burger" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false"
                aria-controls="collapseExample">
                <img
                    src="<?php the_sub_field('mobile_burger'); ?>">
            </a>
        </p>
        <div class="collapse for-mobile" id="collapseExample">
            <div class="card card-body">
                <?php if (has_nav_menu('menu-1')) : ?>

                <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'menu-1',
                            'menu_class'     => 'main-menu',
                            'items_wrap'     => '<ul id="%1$s" class="%2$s" tabindex="0">%3$s</ul>',
                        )
                    );
                    ?>

                <?php endif; ?>
                <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    close
                </a>
            </div>
        </div>
    </div>



    <div class="site-branding-container">
        <div class="container">
            <div class="row menu-row">
                <div class="col-4 col-lg-2">
                    <a href="<?php echo esc_url(home_url('/')); ?>"
                        rel="home">
                        <img
                            src="<?php the_sub_field('logo'); ?>">
                    </a>
                </div>
                <div class="col-8 col-lg-8 main-menu-container">
                    <?php if (has_nav_menu('menu-1')) : ?>
                    <nav id="site-navigation" class="main-navigation"
                        aria-label="<?php esc_attr_e('Top Menu', 'twentynineteen'); ?>">
                        <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'menu-1',
                            'menu_class'     => 'main-menu',
                            'items_wrap'     => '<ul id="%1$s" class="%2$s" tabindex="0">%3$s</ul>',
                        )
                    );
                    ?>
                    </nav><!-- #site-navigation -->
                    <?php endif; ?>

                    <div class="search-wrapper">
                        <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="search-form-wrapper">
                            <?php echo get_search_form(); ?>
                            <span class="close-icons-cls">
                                <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="50px" height="50px"><path d="M 7.71875 6.28125 L 6.28125 7.71875 L 23.5625 25 L 6.28125 42.28125 L 7.71875 43.71875 L 25 26.4375 L 42.28125 43.71875 L 43.71875 42.28125 L 26.4375 25 L 43.71875 7.71875 L 42.28125 6.28125 L 25 23.5625 Z"/></svg>
                            </span>
                        </div>
                    </div>

                </div>
                <!-- <div class="col-8 col-lg-2 button-section">
                    <a href="<?php //the_sub_field('button_link'); ?>"
                        class="btn-primary"><?php //the_sub_field('button_text'); ?></a>
                </div> -->
            </div>
            <div class="row justify-content-start cta-row">
                <div class="col-12  col-lg-6 cta-outer">
                    <div class="gradient-primary ">
                        <h1><?php the_sub_field('cta_title'); ?>
                    </div>
                    </h1>
                    <h2><?php the_sub_field('cta_subtitle'); ?>
                    </h2>
                    <div class="row button-row">
                        <div class="col-6 col-lg-6 first-button-outer">
                            <a href="<?php the_sub_field('primary_button_text_url'); ?>"
                                class="btn-primary"><?php the_sub_field('primary_button_text'); ?></a>
                        </div>
                        <div class="col-6 col-lg-6 second-button-outer">
                            <a href="<?php the_sub_field('secondary_button_url'); ?>"
                                class="btn-secondary"><?php the_sub_field('secondary_button_text'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .layout-wrap -->
    </div>

</header>
