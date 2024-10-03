<?php

namespace PCCW\App\Includes\Admin;

use \PCCW\App\Includes\Abstracts\Admin;
use PCCW\App\Traits\Template;

/**
 * Add a plugin page on admin panel
 */
class PluginPage extends Admin {

    use Template;

    public function actions(): void {
        add_action( 'admin_menu', [$this, 'add_submenu_item'] );

        add_action( 'wp_ajax_pccw_preview', [$this, 'preview'] );
        add_action( 'wp_ajax_nopriv_pccw_preview', [$this, 'preview'] );

        add_action( 'wp_ajax_pccw_save_template', [$this, 'save_template'] );
        add_action( 'wp_ajax_nopriv_pccw_save_template', [$this, 'save_template'] );
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

        $this->render('admin/plugin-page', ['products' => $products], true);
    }

    public function preview(): void
    {
        if ( ! empty( $_POST['nonce'] ) && ! wp_verify_nonce( $_POST['nonce'], 'pccw_preview' ) ) {
            wp_send_json_error( [
                'error' => __('Nonce not verified', 'pccw')
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
            $product_id = get_option( 'pccw_product_to_preview', false );
            $product_id = $product_id === 'false' ? false : $product_id;
        }

        if ( $product_id && $shortcode ) {
            $product = wc_get_product($product_id);
            $shortcode_content = do_shortcode( $shortcode );
            $styles = apply_filters( 'pccw_preview_card_styles', '' );
        } else {
            if ( ! $product_id ) {
                $shortcode_content = sprintf('<div class="preview message">%s</div>', __('Please, choose a product to preview the template', 'pccw'));
            }
            
            if ( empty( $shortcode ) ) {
                $shortcode_content = sprintf('<div class="preview message">%s</div>', __('Template is empty', 'pccw'));
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
        if ( ! empty( $_POST['nonce'] ) && ! wp_verify_nonce( $_POST['nonce'], 'pccw_save_template' ) ) {
            wp_send_json_error( [
                'error' => __('Nonce not verified', 'pccw')
            ], 403 );
        }

        $data = isset( $_POST['shortcode'] ) ? sanitize_textarea_field( $_POST['shortcode'] ) : '';
        $product_id = ! empty ( $_POST['product_id'] ) ? $_POST['product_id'] : false;

        if ( update_option( 'pccw_template_shortcode', $data ) || update_option( 'pccw_product_to_preview', $product_id ) ) {
            wp_send_json([
                'data' => [
                    'status' => 'saved',
                    'message' => __('Template has been successfully saved.', 'pccw')
                ]
            ], 200 );
        } else {
            wp_send_json([
                'data' => [
                    'status' => 'not-saved',
                    'message' => __('Template was already saved.', 'pccw')
                ]
            ], 200 );
        }
    }

    public function add_submenu_item(): void
    {
        add_submenu_page( 
            'woocommerce', 
            __('Product Cards Customiser', 'pccw'), 
            __('Product Cards Customiser', 'pccw'), 
            'manage_options', 
            'product-cards-customiser-for-woo', 
            [$this, 'view'],
            22 
        );
    }
}