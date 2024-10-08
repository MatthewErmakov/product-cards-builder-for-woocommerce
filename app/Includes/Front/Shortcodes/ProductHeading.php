<?php 

namespace PCBW\App\Includes\Front\Shortcodes;

use PCBW\App\Traits\Template;
use PCBW\App\Includes\Abstracts\Shortcode;

class ProductHeading extends Shortcode {
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

        return sprintf( 
            '<h2 class="pcbw_product_heading%s %s">%s</h2>', 
            ! empty( $atts['id'] ) ? ( ' pcbw_product_heading-' . $atts['id'] ) : '',
            esc_attr( 
                apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) 
            ),
            esc_html( $product->get_title() )
        );
    }

    protected function get_current_namespace(): string
    {
        return __NAMESPACE__;
    }
}