<div class="container featured-posts store-loop">

    <?php

    $posts_number = get_sub_field('number_of_posts');
    $order = get_sub_field('order');
    $cat_title = get_sub_field('category_title');
    $title = get_sub_field('loop_title');

?>



    <div class="row featured-outer-row-first row-store-loop">
        <div class="col-12 col-lg-4 store-loop-menu-section">
            <div class="store-loop-inner">
                <h2><?php echo $cat_title; ?>
                </h2>
                <div class="store-line">
                    <hr>
                </div>
                <div class="modules healthnews-cat-menu cat-menu-store">
                    <div class="container menu-cont menu-cont-store">

                        <div class="row row-cat-menu row-store-menu">

                            <ul class="list store-menu-loop">
                                <?php if (have_rows('select_category')): ?>
                                <?php while (have_rows('select_category')): the_row(); ?>

                                <?php 
                            $term = get_sub_field('add_category'); ?>

                                <li id="menu-item <?php echo $term->name; ?>"
                                    class="menu-item menu-item-type-custom menu-item-object-custom healthnews"><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?></a>
                                </li>

                                <?php endwhile; ?>

                                <?php endif; ?>
                            </ul>
                            <a id="next" class="read-more-menu">View All Categories</a>
                            <i class="fa fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-8 featured-first-post-image featured-products-col">
            <div class="row loop-title">
                <h2><?php echo $title; ?>
                </h2>
            </div>
            <div class="row row-for-store">
                <?php 
        global $post;
      $args = array('post_type' => 'product',  'posts_per_page' => $posts_number, 'order_by' => $order, );


        $myposts = get_posts($args);
        foreach ($myposts as $post) :
        setup_postdata($post); ?>

                <div class="col-12 col-lg-6 featured-first-post-image product-col">

                    <a href="<?php the_permalink(); ?>" class="img-fluid product-image-link">
                        <img src="<?php the_post_thumbnail_url(); ?>"
                            class="product-image" alt="" />
                    </a>

                    <h2 class="product-name"><a href="<?php the_permalink(); ?>"
                            class="first-permalink"><?php the_title(); ?>
                        </a></h2>
                    <?php
                  $currency = get_woocommerce_currency_symbol();
                $price = get_post_meta(get_the_ID(), '_regular_price', true);
                $sale = get_post_meta(get_the_ID(), '_sale_price', true);
                    ?>
                    <?php if ($sale) : ?>
                    <p class="product-price"><del><?php echo $currency; echo $price; ?>
                            <?php echo $currency; echo $sale; ?>
                    </p>
                    <?php elseif ($price) : ?>
                    <p class="product-price-currency"><?php echo $currency; echo $price; ?>
                    </p>
                    <?php endif; ?>

                    <?php woocommerce_template_loop_add_to_cart(); //ouptput the woocommerce loop add to cart button?>

                </div>
                <?php endforeach; wp_reset_postdata(); ?>
            </div>
        </div>

    </div>
</div>