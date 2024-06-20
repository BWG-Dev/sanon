<header id="masthead" class="home" style="background-image: url('/wp-content/uploads/2019/01/internal-banner.png'); background-size: cover;">

	<?php include __DIR__.'/../header-modules/top-header.php'; ?>
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

                </div>
                <div class="col-8 col-lg-2 button-section">
                    <a href="<?php the_sub_field('button_link'); ?>"
                        class="btn-primary"><?php the_sub_field('button_text'); ?></a>
                </div>
            </div>
            <div class="row justify-content-start cta-row">
                <div class="col-12  col-lg-6 cta-outer woo-home">
                    <div class="gradient-primary">
                        <h1><?php the_sub_field('cta_title'); ?>
                    </div>
                    </h1>
                    <h2><?php the_sub_field('cta_subtitle'); ?>
                    </h2>
                    <div class="row button-row">
                        <?php if (the_sub_field('primary_button_text')) : ?>
                        <div class="col-6 col-lg-6 first-button-outer">
                            <a href="<?php the_sub_field('primary_button_text_url'); ?>"
                                class="btn-primary"><?php the_sub_field('primary_button_text'); ?></a>
                        </div>
                        <?php endif; ?>



                        <?php if (the_sub_field('secondary_button_text')) : ?>
                        <div class="col-6 col-lg-6 second-button-outer">
                            <a href="<?php the_sub_field('secondary_button_url'); ?>"
                                class="btn-secondary"><?php the_sub_field('secondary_button_text'); ?></a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div><!-- .layout-wrap -->
    </div>

</header>
