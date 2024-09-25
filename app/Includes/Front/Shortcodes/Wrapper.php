<?php 

namespace PCCW\App\Includes\Front\Shortcodes;

use PCCW\App\Traits\Template;
use PCCW\App\Includes\Abstracts\Shortcode;

class Wrapper extends Shortcode {
    use Template;

    public function result( array $atts = [], $content = null ): string
    {
        $tag = 'div';
        $permalink = '';

        parent::result( $atts, $content );

        if ( ! empty( $atts['product_link'] ) && $atts['product_link'] === 'true' ) {
            global $product;

            $permalink = esc_url( $product->get_permalink() );
            $tag = 'a';
        }

        if ( ! empty( $atts['style'] ) || ! empty( $atts['hover'] ) ) {
            $id = ! empty( $atts['id'] ) ? $atts['id'] : '';
            $hover = ! empty( $atts['hover'] ) ? $atts['hover'] : '';
            $static = ! empty( $atts['style'] ) ? $atts['style'] : '';

            $this->set_styles($id, [
                'static' => $static,
                'hover'  => $hover
            ])->generate();
        }

        if ( ! is_null( $content ) ) {;            
            return sprintf( 
                '<%s%s class="pccw_wrapper%s">%s</%s>', 
                $tag, 
                ( $tag === 'a' ? sprintf( ' href="%s"', $permalink ) : '' ),
                isset( $atts['id'] ) ? ' pccw_wrapper-' . $atts['id'] : '', 
                do_shortcode( $content ), 
                $tag 
            );
        }

        return '';
    }

    protected function get_current_namespace(): string
    {
        return __NAMESPACE__;
    }
}