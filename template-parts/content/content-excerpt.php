<?php
/**
 * Template part for displaying post archives and search results
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" class="col-12 col-lg-4 for-cats sanon_blogs">
	<div class="article-inner">
		<header class="entry-header">
			<?php
        if (is_sticky() && is_home() && ! is_paged()) {
            printf('<span class="sticky-post">%s</span>', _x('Featured', 'post', 'twentynineteen'));
        }
        the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>');
        ?>
		</header><!-- .entry-header -->
        
        <footer class="entry-footer">
			<?php twentynineteen_entry_footer(); ?>
		</footer>

		<?php twentynineteen_post_thumbnail(); ?>
        <hr />
        <div class="date_box"><i class="fa fa-calendar-o" aria-hidden="true"></i>&nbsp;<?php echo get_the_modified_time('F j, Y');?></div>

		<div class="entry-content">
			<?php echo wp_trim_words( get_the_content(), 30 ); ?>
        </div><!-- .entry-content -->

		<?php /*?><footer class="entry-footer">
			<?php twentynineteen_entry_footer(); ?>
		</footer><?php */?><!-- .entry-footer -->
	</div>
</article><!-- #post-${ID} -->