<?php
/**
 * Common template
 */

get_header(); ?>

<section id="page-content container" class="blog-content">
    <div class="container">
		<?php while (have_posts()):
		the_post(); ?>
		<div class="row post-inner">
			<div class="col-12 col-lg-6 all-posts-info-col">
				<h2><?php the_title(); ?></h2>
				<p class="date"> <?php echo get_the_date('M j, Y'); ?></p>
				<p class="excerpt"><?php echo wp_trim_words( get_the_content(), 40, '...' ); ?></p>
				<a href="<?php the_permalink(); ?>" class="read-more"> Read More </a>
				<i class="fa fa-angle-right"></i>
				<hr>
			</div>
		</div>

		<?php endwhile; ?>

		<?php
			the_posts_pagination(array(
				'prev_text' => '<span class="screen-reader-text">&laquo;&nbsp;Prev</span>',
				'next_text' => '<span class="screen-reader-text">Next&nbsp;&raquo;</span>',
				'screen_reader_text' => ' ',
				'before_page_number' => '<span class="meta-nav screen-reader-text">Page </span>',
			));
		?>
	</div>
</div>
</section>

<?php get_footer(); ?>
