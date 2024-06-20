<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:image" content="https://www.sanon.org/wp-content/uploads/2019/01/main-logo-1.svg"/>
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>


<body <?php body_class(); ?>>
	<div id="page" class="site">

		<?php
        if(function_exists('have_rows')){
            if (have_rows('header_modules', 'option')):
                while (have_rows('header_modules', 'option')) : the_row();
                    get_template_part('partials/header-modules/'. get_row_layout());
                endwhile;
            endif;

            if (have_rows('header_modules', 'option')):
                if(get_current_blog_id() == 3) {
                    while (have_rows('header_modules', 'option')) : the_row();
                        require('partials/header-modules/member_header.php');
                    endwhile;
                }
            endif;
        }
                
        ?>

		<div id="content" class="site-content">
