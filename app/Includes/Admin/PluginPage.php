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
        add_menu_page( 
           __('Product Cards', $this->plugin->text_domain), 
           __('Product Cards', $this->plugin->text_domain), 
           'manage_options',
           'product-cards-customiser-for-woo', 
           [$this, 'view'], 
           'dashicons-art', 
           22
        );

        add_action( 'wp_ajax_pccw_preview', [$this, 'preview'] );
        add_action( 'wp_ajax_nopriv_pccw_preview', [$this, 'preview'] );

        add_action( 'wp_ajax_pccw_save_template', [$this, 'save_template'] );
        add_action( 'wp_ajax_nopriv_pccw_save_template', [$this, 'save_template'] );
    }

    public function view() : void {
        $this->render('admin/plugin-page', [
            'img_folder' => $this->plugin->url . 'public/assets/img/'
        ], true);
    }

    public function preview(): void
    {
        if ( empty ( $_POST['shortcode'] ) ) {
            return;
        }

        if ( ! empty( $_POST['nonce'] ) && ! wp_verify_nonce( $_POST['nonce'], 'pccw_preview' ) ) {
            wp_send_json_error( [
                'error' => 'Nonce not verified'
            ], 403 );
        }

        $shortcode = stripcslashes( sanitize_text_field( $_POST['shortcode'] ) );

        global $product;

        $product = wc_get_product( 12 );
        $shortcode_content = do_shortcode( $shortcode );

        wp_send_json( [
            'data' => [
                'markup' => $shortcode_content,
                'styles' => apply_filters( 'pccw_preview_card_styles', '' )
            ]
        ], 200);
    }

    public function save_template(): void
    {
        if ( empty ( $_POST['shortcode'] ) ) {
            return;
        }

        if ( ! empty( $_POST['nonce'] ) && ! wp_verify_nonce( $_POST['nonce'], 'pccw_save_template' ) ) {
            wp_send_json_error( [
                'error' => 'Nonce not verified'
            ], 403 );
        }

        $data = $_POST['shortcode'];

        if ( update_option( 'pccw_template_shortcode', $data ) ) {
            wp_send_json([
                'data' => [
                    'status' => 'saved'
                ]
            ], 200 );
        } else {
            wp_send_json([
                'data' => [
                    'error' => 'Template hasn\'t been saved'
                ]
            ], 500 );
        }
    }
}