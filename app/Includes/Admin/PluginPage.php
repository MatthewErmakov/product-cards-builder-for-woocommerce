<?php

namespace PCBW\App\Includes\Admin;

use \PCBW\App\Includes\Abstracts\Admin;
use PCBW\App\Traits\Template;

/**
 * Add a plugin page on admin panel
 */
class PluginPage extends Admin {

    use Template;

    public function actions(): void {
        add_action( 'admin_menu', [$this, 'add_submenu_item'] );

        add_action( 'wp_ajax_pcbw_preview', [$this, 'preview'] );
        add_action( 'wp_ajax_nopriv_pcbw_preview', [$this, 'preview'] );

        add_action( 'wp_ajax_pcbw_save_template', [$this, 'save_template'] );
        add_action( 'wp_ajax_nopriv_pcbw_save_template', [$this, 'save_template'] );

        add_action( 'wp_ajax_pcbw_activate_template', [$this, 'activate_template'] );
        add_action( 'wp_ajax_nopriv_pcbw_activate_template', [$this, 'activate_template'] );
    }

    public function view() : void {
        $products_query = wc_get_products([
            'post_type' => 'product',
            'limit' => 10,
        ]);

        $i = 0;
        $products = [];

        foreach ( $products_query as $product ) {
            $products[$i]['id'] = $product->get_id();
            $products[$i]['label'] = $product->get_title();

            $i++;
        }

        $this->render('admin/plugin-page', [
            'products'           => $products,
            'preview_product_id' => (int) get_option( 'pcbw_product_to_preview', false ),
            'activate_template'  => get_option( 'pcbw_activate_template', false )
        ], true);
    }

    public function preview(): void
    {
        if ( ! empty( $_POST['nonce'] ) && ! wp_verify_nonce( $_POST['nonce'], 'pcbw_preview' ) ) {
            wp_send_json_error( [
                'error' => __('Nonce not verified', 'pcbw')
            ], 403 );
        }

        $shortcode_content = '';
        $shortcode = isset( $_POST['shortcode'] ) ? stripcslashes( sanitize_text_field( $_POST['shortcode'] ) ) : '';
        $product_id = false;
        $styles = '';

        global $product;

        if ( ! empty ( $_POST['product_id'] ) && $_POST['product_id'] !== 'false' ) {
            $product_id = $_POST['product_id'];
        } else {
            $product_id = get_option( 'pcbw_product_to_preview', false );
            $product_id = $product_id === 'false' ? false : $product_id;
        }

        if ( $product_id && $shortcode ) {
            $product = wc_get_product($product_id);
            $shortcode_content = do_shortcode( $shortcode );
            $styles = apply_filters( 'pcbw_preview_card_styles', '' );
        } else {
            if ( ! $product_id ) {
                $shortcode_content = sprintf('<div class="preview message">%s</div>', __('Please, choose a product to preview the template', 'pcbw'));
            }
            
            if ( empty( $shortcode ) ) {
                $shortcode_content = sprintf('<div class="preview message">%s</div>', __('Template is empty', 'pcbw'));
            } 
        }

        wp_send_json( [
            'data' => [
                'markup' => $shortcode_content,
                'styles' => $styles
            ]
        ], 200);
    }

    public function save_template(): void
    {
        if ( ! empty( $_POST['nonce'] ) && ! wp_verify_nonce( $_POST['nonce'], 'pcbw_save_template' ) ) {
            wp_send_json_error( [
                'error' => __('Nonce not verified', 'pcbw')
            ], 403 );
        }

        $data = isset( $_POST['shortcode'] ) ? sanitize_textarea_field( $_POST['shortcode'] ) : '';
        $product_id = ! empty ( $_POST['product_id'] ) ? $_POST['product_id'] : false;

        if ( update_option( 'pcbw_template_shortcode', $data ) || update_option( 'pcbw_product_to_preview', $product_id ) ) {
            wp_send_json([
                'data' => [
                    'status' => 'saved',
                    'message' => __('Template has been successfully saved.', 'pcbw')
                ]
            ], 200 );
        } else {
            wp_send_json([
                'data' => [
                    'status' => 'not-saved',
                    'message' => __('Template was already saved.', 'pcbw')
                ]
            ], 200 );
        }
    }

    public function activate_template(): void
    {
        if ( 
            empty( $_POST['nonce'] ) || 
            ( ! empty( $_POST['nonce'] && ! wp_verify_nonce( $_POST['nonce'], 'pcbw_activate_template' ) ) 
        ) ) {
            wp_send_json( [
                'data' => [
                    'status'  => 'not-saved',
                    'message' => 'Nonce not verified'
                ]
            ], 401 );
        }

        if ( empty( $_POST['activate_template'] ) ) {
            wp_send_json( [
                'data' => [
                    'status'  => 'not-saved',
                    'message' => 'Data is empty'
                ]
            ], 200 );
        }

        $data = $_POST['activate_template'];

        $was_updated = update_option( 'pcbw_activate_template', $data );

        if ( $was_updated ) {
            $message = $data === 'yes' ? 'Template has been successfully activated.' : 'Template has been successfully deactivated.';

            wp_send_json( [
                'data' => [
                    'status' => 'saved',
                    'message' => $message
                ]
            ], 200 );
        } else {
            wp_send_json( [
                'data' => [
                    'status' => 'not-saved',
                    'message' => 'Template hasn\'t been saved.'
                ]
            ], 200 );
        }
    }

    public function add_submenu_item(): void
    {
        add_submenu_page( 
            'woocommerce', 
            __('Product Cards Builder', 'pcbw'), 
            __('Product Cards Builder', 'pcbw'), 
            'manage_options', 
            'product-cards-customiser-for-woo', 
            [$this, 'view'],
            22 
        );
    }
}