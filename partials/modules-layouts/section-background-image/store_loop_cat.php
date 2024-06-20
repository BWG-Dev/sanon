<div class="container featured-posts">
    <?php
    $posts_number = get_sub_field('number_of_posts');
    $order = get_sub_field('order');

if ($term):
$term = get_sub_field('post_category');
$term = $term->name; ?>

    <h2><?php echo $term; ?>
    </h2>
    <?php endif
    ?>



    <div class="row featured-outer-row-first">
        <?php 
        global $post;
      $args = array('post_type' => 'product',  'posts_per_page' => $posts_number, 'offset' => 1, 'category_name' => $term, 'order_by' => $order, );


        $myposts = get_posts($args);
        foreach ($myposts as $post) :
        setup_postdata($post); ?>

        <div class="col-12 col-lg-8 featured-first-post-image">

            <a href="<?php the_permalink(); ?>">
                <img src="<?php the_post_thumbnail_url(); ?>" alt="" />
            </a>
        </div>
        <div class="col-12 col-lg-4 featured-first-post-content">

            <h2><a href="<?php the_permalink(); ?>" class="first-permalink"><?php the_title(); ?>
                </a></h2>
            <p class="date"> <?php echo get_the_date('M j, Y'); ?>
            </p>
            <p><?php the_excerpt(); ?>
            </p>
            <a href="<?php the_permalink(); ?>" class="continue-reading btn">
                Continue Reading
            </a>

        </div>
        <?php endforeach; wp_reset_postdata(); ?>
    </div>

</div>