<?php if (!is_front_page()) : ?>
	<div class="top-header">
		<ul class="container">
            <!-- <li><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/account.png"> Account</a></li>
 -->
            <li><a href="/cart/"><img src="<?php echo get_template_directory_uri(); ?>/cart.png"> Cart / Checkout</a></li>
		</ul>
	</div>
<?php endif; ?>
