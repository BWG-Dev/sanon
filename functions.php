<?php
/**
 * Twenty Nineteen functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */
/**
 * Twenty Nineteen only works in WordPress 4.7 or later.
 */
@ini_set('display_errors', '0');
error_reporting(0);
if (version_compare($GLOBALS['wp_version'], '4.7', '<')) {
    require get_template_directory() . '/inc/back-compat.php';
    return;
}

if (! function_exists('twentynineteen_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function twentynineteen_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on Twenty Nineteen, use a find and replace
         * to change 'twentynineteen' to the name of your theme in all the template files.
         */
        load_theme_textdomain('twentynineteen', get_template_directory() . '/languages');
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');
        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');
        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(1568, 9999);
        // This theme uses wp_nav_menu() in two locations.
        register_nav_menus(
            array(
                'menu-1' => __('Primary', 'twentynineteen'),
                'footer' => __('Footer Menu', 'twentynineteen'),
                'social' => __('Social Links Menu', 'twentynineteen'),
            )
        );
        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support(
            'html5',
            array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
            )
        );
        /**
         * Add support for core custom logo.
         *
         * @link https://codex.wordpress.org/Theme_Logo
         */
        add_theme_support(
            'custom-logo',
            array(
                'height'      => 190,
                'width'       => 190,
                'flex-width'  => false,
                'flex-height' => false,
            )
        );
        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');
        // Add support for Block Styles.
        add_theme_support('wp-block-styles');
        // Add support for full and wide align images.
        add_theme_support('align-wide');
        // Add support for editor styles.
        add_theme_support('editor-styles');
        // Enqueue editor styles.
        add_editor_style('style-editor.css');
        // Add custom editor font sizes.
        add_theme_support(
            'editor-font-sizes',
            array(
                array(
                    'name'      => __('Small', 'twentynineteen'),
                    'shortName' => __('S', 'twentynineteen'),
                    'size'      => 19.5,
                    'slug'      => 'small',
                ),
                array(
                    'name'      => __('Normal', 'twentynineteen'),
                    'shortName' => __('M', 'twentynineteen'),
                    'size'      => 22,
                    'slug'      => 'normal',
                ),
                array(
                    'name'      => __('Large', 'twentynineteen'),
                    'shortName' => __('L', 'twentynineteen'),
                    'size'      => 36.5,
                    'slug'      => 'large',
                ),
                array(
                    'name'      => __('Huge', 'twentynineteen'),
                    'shortName' => __('XL', 'twentynineteen'),
                    'size'      => 49.5,
                    'slug'      => 'huge',
                ),
            )
        );
        // Editor color palette.
        add_theme_support(
            'editor-color-palette',
            array(
                array(
                    'name'  => __('Primary', 'twentynineteen'),
                    'slug'  => 'primary',
                    'color' => twentynineteen_hsl_hex('default' === get_theme_mod('primary_color') ? 199 : get_theme_mod('primary_color_hue', 199), 100, 33),
                ),
                array(
                    'name'  => __('Secondary', 'twentynineteen'),
                    'slug'  => 'secondary',
                    'color' => twentynineteen_hsl_hex('default' === get_theme_mod('primary_color') ? 199 : get_theme_mod('primary_color_hue', 199), 100, 23),
                ),
                array(
                    'name'  => __('Dark Gray', 'twentynineteen'),
                    'slug'  => 'dark-gray',
                    'color' => '#111',
                ),
                array(
                    'name'  => __('Light Gray', 'twentynineteen'),
                    'slug'  => 'light-gray',
                    'color' => '#767676',
                ),
                array(
                    'name'  => __('White', 'twentynineteen'),
                    'slug'  => 'white',
                    'color' => '#FFF',
                ),
            )
        );
        // Add support for responsive embedded content.
        add_theme_support('responsive-embeds');
    }
endif;
add_action('after_setup_theme', 'twentynineteen_setup');
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function twentynineteen_widgets_init()
{
    register_sidebar(
        array(
            'name'          => __('Footer', 'twentynineteen'),
            'id'            => 'sidebar-1',
            'description'   => __('Add widgets here to appear in your footer.', 'twentynineteen'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );
}
add_action('widgets_init', 'twentynineteen_widgets_init');
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width Content width.
 */
function twentynineteen_content_width()
{
    // This variable is intended to be overruled from themes.
    // Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
    $GLOBALS['content_width'] = apply_filters('twentynineteen_content_width', 640);
}
add_action('after_setup_theme', 'twentynineteen_content_width', 0);
/**
 * Enqueue scripts and styles. wp_get_theme()->get('Version')
 */
function twentynineteen_scripts()
{
    wp_enqueue_style('twentynineteen-style', get_stylesheet_uri(), array(), '1.1.4');
    wp_style_add_data('twentynineteen-style', 'rtl', 'replace');
    if (has_nav_menu('menu-1')) {
        wp_enqueue_script('twentynineteen-priority-menu', get_theme_file_uri('/js/priority-menu.js'), array(), '1.0', true);
        wp_enqueue_script('twentynineteen-touch-navigation', get_theme_file_uri('/js/touch-keyboard-navigation.js'), array(), '1.0', true);
    }
    wp_enqueue_style('twentynineteen-print-style', get_template_directory_uri() . '/print.css', array(), wp_get_theme()->get('Version'), 'print');
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'twentynineteen_scripts');
/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * @link https://git.io/vWdr2
 */
function twentynineteen_skip_link_focus_fix()
{
    // The following is minified via `terser --compress --mangle -- js/skip-link-focus-fix.js`.
    ?>
    <script>
        /(trident|msie)/i.test(navigator.userAgent) && document.getElementById && window.addEventListener && window
        .addEventListener("hashchange", function() {
            var t, e = location.hash.substring(1);
            /^[A-z0-9_-]+$/.test(e) && (t = document.getElementById(e)) && (/^(?:a|select|input|button|textarea)$/i
                .test(t.tagName) || (t.tabIndex = -1), t.focus())
        }, !1);
    </script>
    <?php
}
add_action('wp_print_footer_scripts', 'twentynineteen_skip_link_focus_fix');
/**
 * Enqueue supplemental block editor styles.
 */
function twentynineteen_editor_customizer_styles()
{
    wp_enqueue_style('twentynineteen-editor-customizer-styles', get_theme_file_uri('/style-editor-customizer.css'), false, '1.0', 'all');
    if ('custom' === get_theme_mod('primary_color')) {
        // Include color patterns.
        require_once get_parent_theme_file_path('/inc/color-patterns.php');
        wp_add_inline_style('twentynineteen-editor-customizer-styles', twentynineteen_custom_colors_css());
    }
}
add_action('enqueue_block_editor_assets', 'twentynineteen_editor_customizer_styles');
/**
 * Display custom color CSS in customizer and on frontend.
 */
function twentynineteen_colors_css_wrap()
{
    // Only include custom colors in customizer or frontend.
    if ((! is_customize_preview() && 'default' === get_theme_mod('primary_color', 'default')) || is_admin()) {
        return;
    }
    require_once get_parent_theme_file_path('/inc/color-patterns.php');
    $primary_color = 199;
    if ('default' !== get_theme_mod('primary_color', 'default')) {
        $primary_color = get_theme_mod('primary_color_hue', 199);
    } ?>
    <style type="text/css" id="custom-theme-colors" <?php echo is_customize_preview() ?
    'data-hue="' . absint($primary_color) . '"' :
    ''; ?>>
    <?php echo twentynineteen_custom_colors_css(); ?>
</style>
<?php
}
add_action('wp_head', 'twentynineteen_colors_css_wrap');
/**
 * SVG Icons class.
 */
require get_template_directory() . '/classes/class-twentynineteen-svg-icons.php';
/**
 * Custom Comment Walker template.
 */
require get_template_directory() . '/classes/class-twentynineteen-walker-comment.php';
/**
 * Enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';
/**
 * SVG Icons related functions.
 */
require get_template_directory() . '/inc/icon-functions.php';
/**
 * Custom template tags for the theme.
 */
require get_template_directory() . '/inc/template-tags.php';
/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title'    => 'Theme General Settings',
        'menu_title'    => 'Theme Settings',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
    acf_add_options_sub_page(array(
        'page_title'    => 'Theme Header Settings',
        'menu_title'    => 'Header',
        'parent_slug'   => 'theme-general-settings',
    ));
    acf_add_options_sub_page(array(
        'page_title'    => 'Theme Footer Settings',
        'menu_title'    => 'Footer',
        'parent_slug'   => 'theme-general-settings',
    ));
}
function pwwp_enqueue_my_scripts()
{
    // jQuery is stated as a dependancy of bootstrap-js - it will be loaded by WordPress before the BS scripts
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js', array('jquery'), true); // all the bootstrap javascript goodness
}
add_action('wp_enqueue_scripts', 'pwwp_enqueue_my_scripts');
function pwwp_enqueue_my_styles()
{
    wp_enqueue_style('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css');
    wp_enqueue_style('font-awesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style( 'style-wp', get_template_directory_uri() . '/style-wp.css', array(), filemtime( get_template_directory_uri() . '/style-wp.css' ) );
    // this will add the stylesheet from it's default theme location if your theme doesn't already
    //wp_enqueue_style( 'my-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'pwwp_enqueue_my_styles');
function wpb_add_google_fonts()
{
    wp_enqueue_style('wpb-google-fonts', 'https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i|Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i
        ', false);
}
add_action('wp_enqueue_scripts', 'wpb_add_google_fonts');
/**
 * Change number or products per row to 3
 */
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
    function loop_columns()
    {
        return 3; // 3 products per row
    }
}
function woocommerce_after_shop_loop_item_title_short_description()
{
    global $product;
    if (! $product->get_short_description()) {
        return;
    } ?>
    <div itemprop="description">
        <?php echo '<hr>' . apply_filters('woocommerce_short_description', $product->get_short_description()) ?>
    </div>
    <?php
}
add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_after_shop_loop_item_title_short_description', 5);
add_filter('acf/format_value/type=text', 'do_shortcode');
function mytheme_add_woocommerce_support()
{
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'mytheme_add_woocommerce_support');
// test
/**
 * Remove related products output
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
/**
 * Remove product data tabs
 */
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );
function woo_remove_product_tabs( $tabs ) {
    unset( $tabs['additional_information'] );   // Remove the additional information tab
    return $tabs;
}
function wpdocs_theme_name_scripts() {
    wp_enqueue_script( 'custom-js', get_template_directory_uri() . '/js/custom.js', array(), '1.0.0', true );

}
add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );
add_filter('woocommerce_checkout_fields', 'custom_woocommerce_billing_fields');
function custom_woocommerce_billing_fields($fields)
{
    $fields['billing']['billing_options'] = array(
        'label' => __('Customer ID', 'woocommerce'), // Add custom field label
        'placeholder' => _x('', 'placeholder', 'woocommerce'), // Add custom field placeholder
        'required' => false, // if field is required or not
        'clear' => false, // add clear or not
        'type' => 'text', // add field type
        'class' => array('my-css')   // add class name
    );
    return $fields;
}
/*function custom_woocommerce_billing_fields($fields)
{
    $fields['billing']['billing_options'] = array(
        'label' => __('Customer ID', 'woocommerce'), // Add custom field label
        'placeholder' => _x('', 'placeholder', 'woocommerce'), // Add custom field placeholder
        'required' => false, // if field is required or not
        'clear' => false, // add clear or not
        'type' => 'text', // add field type
        'class' => array('my-css')   // add class name
    );
    return $fields;
}
*/
if(get_current_blog_id() == 1){
    add_filter( 'gettext', 'change_cart_totals_text', 20, 3 );
    function change_cart_totals_text( $translated, $text, $domain ) {
        if( function_exists('is_cart') && is_cart() && $translated == 'Total' ){
            $translated = __('Sub Total', 'woocommerce');
        }
        return $translated;
    }
}
/*
function disable_shipping_calc_on_cart( $show_shipping ) {
    foreach ( WC()->cart->get_cart() as $cart_item ) {
        $product_id = $cart_item['product_id'];
       if($product_id==1860){
           return false;
       }
       else{
          return $show_shipping;
       }
    }
}
add_filter( 'woocommerce_cart_ready_to_calc_shipping', 'disable_shipping_calc_on_cart', 99 );
*/
/**
* Add Continue Shopping Button on Cart Page & Checkout page
* Add to theme functions.php file or Code Snippets plugin
*/
add_action( 'woocommerce_before_cart_table', 'woo_add_continue_shopping_button_to_cart' );
add_action( 'woocommerce_before_checkout_form', 'woo_add_continue_shopping_button_to_cart' );
function woo_add_continue_shopping_button_to_cart() {
   $shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );
   echo '<div class="woocommerce-message">';
   echo ' <a href="'.$shop_page_url.'" class="button">Continue Shopping →</a> Would you like some more goods?';
   echo '</div>';
}
require_once __DIR__ .'/inc/post-type.php';
include_once __DIR__ .'/inc/shortcodes.php';
// Remove cross-sells at cart
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
function my_myme_types($mime_types){
    $mime_types['epub'] = 'application/epub+zip';
    $mime_types['mobi'] = 'application/x-mobipocket-ebook';
    return $mime_types;
}
add_filter('upload_mimes', 'my_myme_types', 1, 1);
if( !function_exists( 'custom_sanon_checkout_question_field' ) ) {
  function custom_sanon_checkout_question_field( $checkout ) {
    echo "<div class='custom-question-field-wrapper custom-question-1'>";
    //echo sprintf( '<p>%s</p>', __( "Is this order for an S-Anon group?" ) );
    woocommerce_form_field( 'custom_question_field', array(
      'type'     => 'radio',
      'required' => false,
      'label'         => 'Is this order for an S-Anon group?',
      'class'           => array('custom-group-question-field custom_question_field', 'form-row-wide'),
      'options'         => array(
        'yes'         => 'Yes',
        'no'    => 'No',
    ),
      'default' => 'no',
  ), $checkout->get_value( 'custom_question_field' ) );
    woocommerce_form_field( 'custom_question_text_group_id', array(
      'type'            => 'text',
      'label'           => 'Group ID',
      //'required'        => true,
      'class'           => array('custom-question-group_id', 'form-row-wide'),
  ), $checkout->get_value( 'custom_question_text_group_id' ) );
    woocommerce_form_field( 'custom_question_textarea_group_details', array(
      'type'            => 'textarea',
      'label'           => 'Enter the Group Name, City, State, Day and Time',
      'required'        => true,
      'class'           => array('custom-question-group-details', 'form-row-wide'),
  ), $checkout->get_value( 'custom_question_textarea_group_details' ) );
    echo "</div>";
}
add_action( 'woocommerce_after_checkout_billing_form', 'custom_sanon_checkout_question_field' );
}
if( !function_exists( 'custom_question_conditional_javascript' ) ) {
  function custom_question_conditional_javascript() {
    ?>
    <script type="text/javascript">
        (function() {
      // Check if jquery exists
      if(!window.jQuery) {
        return;
    };
    var $ = window.jQuery;
    $(document).ready(function() {
        var questionField       = $('.custom-group-question-field'),
        groupId  = $('.custom-question-group_id'),
        groupDetails           = $('.custom-question-group-details');
        // Check that all fields exist
        if( !questionField.length ||  !groupId.length ||  !groupDetails.length ) {
          return;
      }
      function toggleVisibleFields() {
          var selectedAnswer = questionField.find('input:checked').val();
          if(selectedAnswer === 'yes') {
            groupId.show();
            groupDetails.show();
        } else if(selectedAnswer === 'no') {
            groupId.hide();
            groupDetails.hide();
        }else {
            groupId.hide();
            groupDetails.hide();
        }
    }
    $(document).on('change', 'input[name=custom_question_field]', toggleVisibleFields);
    $(document).on('updated_checkout', toggleVisibleFields);
    toggleVisibleFields();
});
})();
</script>
<?php
}
add_action( 'wp_footer', 'custom_question_conditional_javascript', 1000 );
}
if( !function_exists( 'custom_checkout_question_get_field_values' ) ) {
  function custom_checkout_question_get_field_values() {
    $fields = [
      'custom_question_field'                       => '',
      'custom_question_text_group_id'               => '',
      'custom_question_textarea_group_details'      => '',
  ];
  foreach( $fields as $field_name => $value ) {
      if( !empty( $_POST[ $field_name ] ) ) {
        $fields[ $field_name ] = sanitize_text_field( $_POST[ $field_name ] );
    } else {
        unset( $fields[ $field_name ] );
    }
}
return $fields;
}
}
if( !function_exists( 'custom_checkout_question_field_validate' ) ) {
  /**
   * Custom woocommerce field validation to prevent user for completing checkout
   *
   * @author Girish Sharma <g23sharma@gmail.com>
   */
  function custom_checkout_question_field_validate() {
    $field_values = custom_checkout_question_get_field_values();
    if ( empty( $field_values['custom_question_field'] ) ) {
      wc_add_notice( 'Please select an answer for S-Anon Group.', 'error' );
  }
  if ( !empty( $field_values['custom_question_field'] ) && $field_values['custom_question_field'] == 'yes' && empty( $field_values['custom_question_textarea_group_details'] )) {
      wc_add_notice( 'Please add Group Name, City, State, Day and Time.', 'error' );
  }
}
  //add_action( 'woocommerce_checkout_process', 'custom_checkout_question_field_validate' );
}
if( !function_exists( 'custom_checkout_question_field_save' ) ) {
  /**
   * Update order post meta based on submitted form values
   * @author Girish Sharma <g23sharma@gmail.com>
   */
  function custom_checkout_question_field_save( $order_id ) {
    $field_values = custom_checkout_question_get_field_values();
    foreach( $field_values as $field_name => $value ) {
      if( !empty( $field_values[ $field_name ] ) ) {
        update_post_meta( $order_id, $field_name, $value );
    }
}
}
add_action( 'woocommerce_checkout_update_order_meta', 'custom_checkout_question_field_save' );
}
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'edit_woocommerce_checkout_page', 10, 1 );
function edit_woocommerce_checkout_page($order){
    global $post_id;
    $order = new WC_Order( $post_id );
    echo '<p><strong>'.__('Is this order for S-Anon group?').':</strong> ' . get_post_meta($order->get_id(), 'custom_question_field', true ) . '</p>';
    echo '<p><strong>'.__('Group ID').':</strong> ' . get_post_meta($order->get_id(), 'custom_question_text_group_id', true ) . '</p>';
    echo '<p><strong>'.__('Group Details').':</strong> ' . get_post_meta($order->get_id(), 'custom_question_textarea_group_details', true ) . '</p>';
}
add_filter( 'woocommerce_email_order_meta_fields', 'add_delivery_date_to_emails' , 10, 3 );
function add_delivery_date_to_emails ( $fields, $sent_to_admin, $order ) {
    if( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">=" ) ) {
        $order_id = $order->get_id();
    } else {
        $order_id = $order->id;
    }
    $custom_question_field = get_post_meta( $order_id, 'custom_question_field', true );
    $group_id = get_post_meta( $order_id, 'custom_question_text_group_id', true );
    $group_details = get_post_meta( $order_id, 'custom_question_textarea_group_details', true );
    if ( '' != $custom_question_field ) {
       $fields[ 'for S-Anon Group' ] = array(
           'label' => __( 'Is this order for S-Anon Group?', 'add_extra_fields' ),
           'value' => $custom_question_field,
       );
   }
   if ( '' != $group_id ) {
       $fields[ 'Group Details' ] = array(
           'label' => __( 'Group ID', 'add_extra_fields' ),
           'value' => $group_id,
       );
   }
   if ( '' != $group_details ) {
       $fields[ 'Delivery Date' ] = array(
           'label' => __( 'Group Details', 'add_extra_fields' ),
           'value' => $group_details,
       );
   }
   return $fields;
}
/**
 * Load Areas for Meetings
 */
require_once get_parent_theme_file_path('/inc/meeting-areas/meeting-areas.php');
/**
 * Allow HTML in term (category, tag) descriptions
 */
remove_filter( 'pre_term_description', 'wp_filter_kses' );
remove_filter( 'term_description', 'wp_kses_data' );
/**
 * Register our sidebars and widgetized areas.
 *
 */
function pba_widgets_init() {
    register_sidebar( array(
        'name'          => 'Page Bottom Area',
        'id'            => 'page_bottom_area',
        'before_widget' => '<div>',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="rounded">',
        'after_title'   => '</h2>',
    ));
    register_sidebar( array(
        'name'          => __('Topbar', 'twentynineteen'),
        'id'            => 'home-topbar',
        'description'   => __('Add widgets here to appear in your topbar.', 'twentynineteen'),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
}
add_action( 'widgets_init', 'pba_widgets_init' );
add_action( 'after_setup_theme', 'tu_disable_wc_lightbox', 20 );
function tu_disable_wc_lightbox() {
    remove_theme_support( 'wc-product-gallery-lightbox' );
}
function disable_shipping_calc_on_cart( $show_shipping ) {
    if( is_cart() ) {
        return false;
    }
    return $show_shipping;
}
add_filter( 'woocommerce_cart_ready_to_calc_shipping', 'disable_shipping_calc_on_cart', 99 );
function twentynineteen_blog_widgets_init()
{
    register_sidebar(
        array(
            'name'          => __('Blog Sidebar', 'twentynineteen'),
            'id'            => 'sanonblog',
            'description'   => __('Add widgets here to appear in your Blog Page.', 'twentynineteen'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title"> <span>',
            'after_title'   => ' </span> </h2>',
        )
    );
}
add_action('widgets_init', 'twentynineteen_blog_widgets_init');
/*For adding extra fields in print invoice*/
function sanon_print_custom_order_fields( $fields, $order ) {
    $new_fields = array();
    if( get_post_meta( $order->id, 'custom_question_field', true ) == 'yes' ) {
        $new_fields['custom_question_text_for_group'] = array(
            'label' => 'Is this order for S-Anon group?',
            'value' => 'Yes'
        );
        if( !empty(get_post_meta( $order->id, 'custom_question_text_group_id', true )) ) {
            $new_fields['custom_question_text_group_id'] = array(
                'label' => 'Group ID',
                'value' => get_post_meta( $order->id, 'custom_question_text_group_id', true )
            );
        }
        if( !empty(get_post_meta( $order->id, 'custom_question_field', true )) ) {
            $new_fields['custom_question_textarea_group_details'] = array(
                'label' => 'Group Details',
                'value' => get_post_meta( $order->id, 'custom_question_textarea_group_details', true )
            );
        }
    }
    return array_merge( $fields, $new_fields );
}
add_filter( 'wcdn_order_info_fields', 'sanon_print_custom_order_fields', 10, 2 );
function tracking_code_header() {
    ?>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-85185709-1', 'auto');
      ga('send', 'pageview');
  </script>
  <?php
}
// swapped to Woocommerce Google Analytics plugin for e-comm tracking support
#add_action('wp_head', 'tracking_code_header');
// Add min value to the quantity field (default = 1)
add_filter('woocommerce_quantity_input_min', 'sanon_min_decimal', 99, 2);
function sanon_min_decimal($val, $product) {
    $pid = $product->get_id();
    if($pid == 1860 || $pid == 1861 || $pid == 1863 || $pid == 1864 || $pid == 1865){
      return 0.1;
  }else{
      return 1;
  }
}
// Add step value to the quantity field (default = 1)
add_filter('woocommerce_quantity_input_step', 'sanon_nsk_allow_decimal', 99, 2);
function sanon_nsk_allow_decimal($val, $product) {
    $pid = $product->get_id();
    if($pid == 1860 || $pid == 1861 || $pid == 1863 || $pid == 1864 || $pid == 1865 ) {
        return 0.1;
    }else{
        return 1;
    }
}
// Removes the WooCommerce filter, that is validating the quantity to be an int
remove_filter('woocommerce_stock_amount', 'intval');
// Add a filter, that validates the quantity to be a float
add_filter('woocommerce_stock_amount', 'floatval');
// Add unit price fix when showing the unit price on processed orders
add_filter('woocommerce_order_amount_item_total', 'sanon_unit_price_fix', 10, 5);
function sanon_unit_price_fix($price, $order, $item, $inc_tax = false, $round = true) {
    $qty = (!empty($item['qty']) && $item['qty'] != 0) ? $item['qty'] : 1;
    if($inc_tax) {
        $price = ($item['line_total'] + $item['line_tax']) / $qty;
    } else {
        $price = $item['line_total'] / $qty;
    }
    $price = $round ? round( $price, 2 ) : $price;
    return $price;
}
add_filter( 'woocommerce_product_add_to_cart_text', 'bp_cat_add_to_cart_text' );
add_filter( 'woocommerce_product_single_add_to_cart_text', 'bp_cat_add_to_cart_text' );
function bp_cat_add_to_cart_text($default) {
    global $product;
    $terms = get_the_terms( $product->ID, 'product_cat' );
    foreach ($terms as $term) {
        $product_cat = $term->name;
        break;
    }
    switch($product_cat)
    {
        case 'Audio Resources';
        return 'Select Recordings'; break;
        default;
        return 'Add to cart'; break;
    }
}
add_filter( 'woocommerce_product_add_to_cart_url', 'bp_cat_add_to_cart_url', 10, 3 );
add_filter( 'woocommerce_product_single_add_to_cart_url', 'bp_cat_add_to_cart_url' , 10, 3 );
function bp_cat_add_to_cart_url($default) {
    global $product;
    global $wp;
    $product_slug = $product->get_slug();
    $add_to_cart_url = home_url( $wp->request)  . '/?add-to-cart=' . $product->get_id() ;
    $terms = get_the_terms( $product->ID, 'product_cat' );
    foreach ($terms as $term) {
        $product_cat = $term->name;
        break;
    }
    switch($product_cat)
    {
        case 'Audio Resources';
        return get_permalink( $product->get_id() ); break;
        default;
        return $add_to_cart_url; break;
    }
}
/**
 * Loop Add to Cart
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
  * @version     2.1.0
  */
if ( ! defined( 'ABSPATH' ) ) {
     exit; // Exit if accessed directly
 }
 /** Changed donation product price */
 add_action( 'wp_footer', 'hiddenproductfield' );
 function hiddenproductfield() {
   global $product;
   ?>
   <script type="text/javascript">
       (function($){
           $('#don_amount').change( function(){
            var donation_amm=$("#don_amount").val();
            $('<input>').attr({type: 'hidden', name: 'quotesystem', value: donation_amm}).appendTo('form');
        });
       })(jQuery);
   </script>
   <?php
}
function add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
     // Has our option been selected?
   if( ! empty( $_POST['quotesystem'] ) ) {
       $product = wc_get_product( $product_id );
       $price = $product->get_price();
         // Store the overall price for the product, including the cost of the warranty
       $cart_item_data['quote_price'] = $_POST['quotesystem'];
   }
   return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'add_cart_item_data', 10, 3 );
function before_calculate_totals( $cart_obj ) {
 if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
     //return;
 }
   // Iterate through each cart item
 foreach( $cart_obj->get_cart() as $key=>$value ) {
   if( isset( $value['quote_price'] ) && !empty($value['quote_price']) ) {
     $value['data']->set_price( ( $value['quote_price']) );
 }
}
}
 //add_action( 'woocommerce_before_calculate_totals', 'before_calculate_totals', 10, 1 );
/**Donation price code end */
/* function admin_default_page() {
 // return '/forums';
}
add_filter('login_redirect', 'admin_default_page');*/
/*function forums_redirect() {
    if ( !is_user_logged_in() && is_post_type_archive( 'forum' ) )
    {
        wp_redirect( '/my-account' );
        die;
    }
}
add_action( 'template_redirect', 'forums_redirect' );*/
/**
 * @param $order
 * @return bool
 * @description Logic to handle the different order scenario and the emails tied to it
 */
function check_for_donation($order){
    $flag = true;
    foreach ( $order->get_items() as $item_id => $item ) {
        $product_id = $item->get_product_id();
        if($product_id != 1860){
            return false;
        }
    }
    return $flag;
}
function check_for_virtuals($order){
    $flag = true;
    foreach ( $order->get_items() as $item_id => $item ) {
        $product_id = $item->get_product_id();
        $_product = wc_get_product( $product_id );
        if (!$_product->is_virtual()) {
            $flag = false;
        }
    }
    return $flag;
}
/**
 * Change order to completed directly when the cart just has a donation
 */
add_action('woocommerce_order_status_changed', 'sanon_donation_complete_order');
function sanon_donation_complete_order($order_id)
{
    if ( ! $order_id ) {
        return;
    }
    $order = wc_get_order( $order_id );
    $flag =  check_for_donation($order);
    $flagVirtuals = check_for_virtuals($order);
    if ($order->data['status'] == 'processing') {
        if ($flag || $flagVirtuals){
            $order->update_status( 'completed' );
            add_filter( 'woocommerce_email_enabled_new_order', function($yesno, $object){
                return false;
            }, 10, 2);
        }
    }
}
add_filter( 'woocommerce_email_recipient_customer_processing_order', 'change_email_recipient_depending_of_product_id', 10, 2 );
function change_email_recipient_depending_of_product_id( $recipient, $order ) {
    global $woocommerce;
    $flag = check_for_donation($order);
    $flagVirtuals = check_for_virtuals($order);
    if($flag || $flagVirtuals){
        return '';
    }
    return $recipient;
}
add_filter( 'woocommerce_email_recipient_customer_completed_order', 'dont_send_completed_order_email', 10, 2 );
function dont_send_completed_order_email( $recipient, $order ) {
    global $woocommerce;
    $flag = check_for_donation($order);
    $flagVirtuals = check_for_virtuals($order);
    if(!$flag && !$flagVirtuals){
        return '';
    }
    return $recipient;
}
add_filter('woocommerce_email_subject_customer_completed_order', 'change_admin_email_subject', 1, 2);
function change_admin_email_subject( $subject, $order ) {
    $flag = check_for_donation($order);
    if($flag){
        $subject = 'Thank you for your 7th Tradition Contribution.';
    }
    return $subject;
}



add_action('woocommerce_order_status_changed', 'sanon_auto_complete_virtual');

function sanon_auto_complete_virtual($order_id)
{
  
  if ( ! $order_id ) {
        return;
  }
  
  global $product;
  $order = wc_get_order( $order_id );
  
  if ($order->data['status'] == 'processing') {
    
    $virtual_order = null;

    if ( count( $order->get_items() ) > 0 ) {

      foreach( $order->get_items() as $item ) {

        if ( 'line_item' == $item['type'] ) {

          $_product = $order->get_product_from_item( $item );

          if ( ! $_product->is_virtual() ) {
            // once we find one non-virtual product, break out of the loop
            $virtual_order = false;
            break;
          } 
          else {
            $virtual_order = true;
          }
       }
     }
   }

    // if all are virtual products, mark as completed
    if ( $virtual_order ) {
      $order->update_status( 'completed' );
    }
  }    
}

/**
 * Adding the import Page
 */
add_action('admin_menu', function(){
    add_menu_page('Import Meeting Script', 'Import Meeting Script', 'manage_options', 'sanon-main', 'sanon_meeting_import' , '',110);
});

function sanon_meeting_import(){
    $content = sanon_template(get_stylesheet_directory() . '/templates/import.php',array());
    echo $content;
}

function sanon_template( $file, $args ){
    // ensure the file exists
    if ( !file_exists( $file ) ) {
        return '';
    }

    // Make values in the associative array easier to access by extracting them
    if ( is_array( $args ) ){
        extract( $args );
    }

    // buffer the output (including the file is "output")
    ob_start();
    include $file;
    return ob_get_clean();
}


function exclude_custom_post_types_from_search( $query ) {
    if ( ! is_admin() && $query->is_search && $query->is_main_query() ) {
        // Replace 'custom_post_type' with the name of your custom post type
        $query->set( 'post_type', array( 'post', 'page', 'product' ) );
        // Debugging: Log a message to the PHP error log
        error_log('Custom search query modification applied.');
    }
}
add_action( 'pre_get_posts', 'exclude_custom_post_types_from_search' );



// Modify the search query to remove special characters
function custom_search_query($query) {
    if ($query->is_search && !is_admin()) {
        $search_query = $query->query_vars['s'];
        $search_query = preg_replace('/[^\p{L}\p{N}\s]/u', '', $search_query); // Remove special characters
        $query->set('s', $search_query);
    }
    return $query;
}
add_filter('pre_get_posts','custom_search_query');


function custom_body_class($classes) {
    if (is_search()) {
        global $wp_query;
        if ($wp_query->found_posts > 0) {
            $classes[] = 'search-results-found';
        } else {
            $classes[] = 'no-search-results-found';
        }
    }
    return $classes;
}
add_filter('body_class', 'custom_body_class');