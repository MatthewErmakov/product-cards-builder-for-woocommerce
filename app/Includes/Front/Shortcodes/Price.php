<?php 

namespace PCCW\App\Includes\Front\Shortcodes;

use PCCW\App\Traits\Template;
use PCCW\App\Includes\Abstracts\Shortcode;
use PCCW\App\Includes\Services\StylesGeneratorService;

class Price extends Shortcode {
    use Template;

    public function result( array $atts = [], $content = null ): string
    {
        global $product;

        parent::result( $atts, $content );

        $price_html = $product->get_price_html();

        if ( ! empty( $atts['style'] ) || ! empty( $atts['hover'] ) ) {
            $id = ! empty( $atts['id'] ) ? $atts['id'] : '';
            $hover = ! empty( $atts['hover'] ) ? $atts['hover'] : '';
            $static = ! empty( $atts['style'] ) ? $atts['style'] : '';
            $shortcode_name = $this->get_name();

            $styles_generator = new StylesGeneratorService(
                $this->plugin,
                ! empty( $id ) ? '.' . $shortcode_name . '-' . $id  : '.' . $shortcode_name,
                [
                    'static' => $static,
                    'hover' => $hover
                ]
            );
    
            $styles_generator->generate();
        }

        if ( ! empty( $atts['sale_price_style'] ) || ! empty( $atts['sale_price_hover'] ) ) {
            $this->sale_price_styles();
        }

        if ( $price_html !== '' ) {
            return sprintf( 
                '<div class="pccw_price%s">%s</div>', 
                ! empty( $atts['id'] ) ? ( ' pccw_price-' . $atts['id'] ) : '',
                $price_html
            );
        }
        
        return '';
    }

    protected function get_current_namespace(): string
    {
        return __NAMESPACE__;
    }

    public function sale_price_styles(): void
    {
        $atts = $this->atts;
        $shortcode_name = $this->get_name();

        $static = ! empty ( $atts['sale_price_style'] ) ? $atts['sale_price_style'] : '';
        $hover = ! empty ( $atts['sale_price_hover'] ) ? $atts['sale_price_hover'] : '';

        $styles_generator = new StylesGeneratorService(
            $this->plugin,
            ! empty( $atts['id'] ) ? '.' . $shortcode_name . '-' . $atts['id'] . ' ins .woocommerce-Price-amount.amount bdi' : '.' . $shortcode_name . ' ins .woocommerce-Price-amount.amount bdi',
            [
                'static' => $static,
                'hover' => $hover
            ]
        );

        $styles_generator->generate();
    }
}