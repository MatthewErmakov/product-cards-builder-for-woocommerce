<?php 

namespace PCCW\App\Includes\Front\Shortcodes;

use PCCW\App\Traits\Template;
use PCCW\App\Includes\Abstracts\Shortcode;

class ProductImage extends Shortcode {
    use Template;

    public function result( array $atts = [], $content = null ): string
    {
        global $product;

        parent::result( $atts, $content );

        if ( ! empty( $atts['style'] ) || ! empty( $atts['hover'] ) ) {
            $id = ! empty( $atts['id'] ) ? $atts['id'] : '';
            $hover = ! empty( $atts['hover'] ) ? $atts['hover'] : '';
            $static = ! empty( $atts['style'] ) ? $atts['style'] : '';

            $this->set_styles($id, [
                'static' => $static,
                'hover'  => $hover
            ])->generate();
        }

        return $product->get_image( 'woocommerce_thumbnail', [
            'alt' => $product->get_name(), 'class' => isset( $atts['id'] ) ? 'pccw_product_image-' . $atts['id'] : 'pccw_product_image'
        ]);
    }

    protected function get_current_namespace(): string
    {
        return __NAMESPACE__;
    }
}