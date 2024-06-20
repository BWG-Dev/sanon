<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

get_header();
?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main">
		
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<header class="page-header">
					<h1 class="page-title">
						<?php _e( 'Search results for:', 'twentynineteen' ); ?>
					</h1>
					<div class="page-description"><?php echo get_search_query(); ?></div>
				</header><!-- .page-header -->
				<div class="search-result-wrapper row">
				<?php
				// Start the Loop.
				while ( have_posts() ) :
					the_post();
					/*
					* Include the Post-Format-specific template for the content.
					* If you want to override this in a child theme, then include a file
					* called content-___.php (where ___ is the Post Format name) and that will be used instead.
					*/
					get_template_part( 'template-parts/content/content', 'excerpt' );

					// End the loop.
				endwhile;
				?>
				</div>
				<?php

				// Previous/next page navigation.
				function twentynineteen_child_the_posts_navigation() {
					the_posts_pagination( array(
						'prev_text' => __( '<i class="fa fa-chevron-left"></i> Previous', 'twentynineteen-child' ),
						'next_text' => __( 'Next <i class="fa fa-chevron-right"></i>', 'twentynineteen-child' ),
					) );
				}
				twentynineteen_child_the_posts_navigation();
				

				// If no content, include the "No posts found" template.
			else :
				get_template_part( 'template-parts/content/content', 'none' );
			endif;
			?>
		</div>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
