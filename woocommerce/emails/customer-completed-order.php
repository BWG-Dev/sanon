<?php
/**
 * Customer completed order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-completed-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$flag = true;
$flagDonationsWithOthersProducts = false;

foreach ( $order->get_items() as $item_id => $item ) {

    $product_id = $item->get_product_id();
    if($product_id != 1860){
        $flag = false;
    }
}

/*}else{
    foreach ( $order->get_items() as $item_id => $item ) {

        $product_id = $item->get_product_id();
        if($product_id == 1860){
            $flagDonationsWithOthersProducts = true;
        }
    }
}*/

if($flag){
    /*
     * @hooked WC_Emails::email_header() Output the email header
     */
    /*do_action( 'woocommerce_email_header', $email_heading, $email ); */?>



    <?php /* translators: %s: Customer first name */ ?>
    <p><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) ); ?></p>
    <p>Member contributions are vital in assisting S-Anon in carrying out the primary purpose of our fellowship. We greatly appreciate your support!</p>
    <?php

    /*
     * @hooked WC_Emails::order_details() Shows the order details table.
     * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
     * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
     * @since 2.5.0
     */
    do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

    /*
     * @hooked WC_Emails::order_meta() Shows order meta data.
     */
    // do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

    /*
     * @hooked WC_Emails::customer_details() Shows customer details
     * @hooked WC_Emails::email_address() Shows email address
     */
    // do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );


    /*
     * @hooked WC_Emails::email_footer() Output the email footer
     */
    // do_action( 'woocommerce_email_footer', $email );

    echo "<p>S-Anon International Family Groups, Inc. is a 501(c)3 non-profit organization, EIN 74-2696411.</p>";

}else{

    do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

    <?php /* translators: %s: Customer first name */ ?>
    <p><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) ); ?></p>
    <p><?php esc_html_e( 'We have finished processing your order.', 'woocommerce' ); ?></p>
    <?php

    /*if($flagDonationsWithOthersProducts){
        */?><!--
        <p><?php /*printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) ); */?></p>
        <p>Member contributions are vital in assisting S-Anon in carrying out the primary purpose of our fellowship. We greatly appreciate your support!</p>
        --><?php
/*    }*/

    /*
     * @hooked WC_Emails::order_details() Shows the order details table.
     * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
     * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
     * @since 2.5.0
     */
    do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

    /*
     * @hooked WC_Emails::order_meta() Shows order meta data.
     */
    do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

    /*
     * @hooked WC_Emails::customer_details() Shows customer details
     * @hooked WC_Emails::email_address() Shows email address
     */
    do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

    /**
     * Show user-defined additional content - this is set in each email's settings.
     */
    if ( $additional_content ) {
        echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
    }

    /*
     * @hooked WC_Emails::email_footer() Output the email footer
     */
    do_action( 'woocommerce_email_footer', $email );
}