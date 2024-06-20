<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || ! $product->is_visible()) {
    return;
}
?>
<div class="col-12 col-lg-6 featured-first-post-image product-col">

    <a href="<?php the_permalink(); ?>" class="img-fluid product-image-link">
        <img src="<?php the_post_thumbnail_url(); ?>" class="product-image"
            alt="" />
    </a>

    <h2 class="product-name-x"><a href="<?php the_permalink(); ?>"
            class="first-permalink"><?php the_title(); ?>
        </a></h2>
		<?php
			$currency = get_woocommerce_currency_symbol();
			$price = get_post_meta(get_the_ID(), '_regular_price', true);
			$sale = get_post_meta(get_the_ID(), '_sale_price', true);
		?>

	<?php if ($price) : ?>
		<p class="product-price-currency"><?php echo $currency; echo number_format($price, 2); ?></p>
	<?php endif; ?>

    <?php woocommerce_template_loop_add_to_cart(); //ouptput the woocommerce loop add to cart button?>
    <i class="fa fa-long-arrow-right"></i>

</div>
