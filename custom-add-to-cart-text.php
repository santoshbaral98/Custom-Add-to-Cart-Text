<?php
/*
Plugin Name: Custom Add to Cart Text
Description: This plugin allows you to customize the "Add to Cart" text on your WooCommerce store.
Version: 1.0
Author: Santosh Baral
Author URL: https://www.linkedin.com/in/santosh-baral-a94019233/
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('Custom_Add_To_Cart_Text')) {

    class Custom_Add_To_Cart_Text
    {
        public function __construct()
        {
            add_action('admin_menu', array($this, 'add_plugin_page'));
            add_action('admin_init', array($this, 'page_init'));
            add_filter('woocommerce_product_single_add_to_cart_text', array($this, 'custom_cart_button_text')); 
            add_filter('woocommerce_product_add_to_cart_text', array($this, 'custom_cart_button_text')); 
        }

        public function add_plugin_page()
        {
            add_options_page(
                'Settings Admin', 
                'Custom Add To Cart Text', 
                'manage_options', 
                'custom-add-to-cart-text', 
                array($this, 'create_admin_page')
            );
        }

        public function create_admin_page()
        {
            $this->options = get_option('custom_cart_text');
            ?>
            <div class="wrap">
                <h1>Custom Add To Cart Text</h1>
                <form method="post" action="options.php">
                <?php
                    settings_fields('custom_cart_text_group');
                    do_settings_sections('custom-add-to-cart-text-setting-admin');
                    submit_button(); 
                ?>
                </form>
            </div>
            <?php
        }

        public function page_init()
        {        
            register_setting(
                'custom_cart_text_group',
                'custom_cart_text',
                array($this, 'sanitize')
            );

            add_settings_section(
                'setting_section_id',
                'Settings',
                array($this, 'print_section_info'),
                'custom-add-to-cart-text-setting-admin'
            );  

            add_settings_field(
                'cart_button_text',
                'Add To Cart Text',
                array($this, 'cart_button_text_callback'),
                'custom-add-to-cart-text-setting-admin',
                'setting_section_id'
            );      
        }

        public function sanitize($input)
{
    return array(
        'cart_button_text' => sanitize_text_field($input['cart_button_text'])
    );
}


        public function print_section_info()
        {
            print 'Enter your settings below:';
        }

        public function cart_button_text_callback()
        {
            printf(
                '<input type="text" id="cart_button_text" name="custom_cart_text[cart_button_text]" value="%s" />',
                isset($this->options['cart_button_text']) ? esc_attr($this->options['cart_button_text']) : ''
            );
        }

        function custom_cart_button_text() 
        {
            return $this->options['cart_button_text'] ?: 'Add to Cart';
        }

    }
}

if(is_admin()) {
    $custom_add_to_cart_text = new Custom_Add_To_Cart_Text();
}
?>
