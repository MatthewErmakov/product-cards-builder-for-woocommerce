<?php 

namespace PCCW\App\Includes\Front\Shortcodes;

use PCCW\App\Traits\Template;
use PCCW\App\Includes\Abstracts\Shortcode;
use PCCW\App\Includes\Services\StylesGeneratorService;

class StockStatus extends Shortcode {
    use Template;

    public function result( array $atts = [], $content = null ): string
    {
        global $product;

        parent::result( $atts, $content );

        $shortcode_name = $this->get_name();

        $is_in_stock = $product->is_in_stock();
        $stock_quantity = $product->get_stock_quantity();

        $caption = __('Out of stock', $this->plugin->text_domain);

        $id = ! empty( $atts['id'] ) ? $atts['id'] : '';

        if ( ! empty( $atts['style'] ) || ! empty( $atts['hover'] ) ) {
            $hover = ! empty( $atts['hover'] ) ? $atts['hover'] : '';
            $static = ! empty( $atts['style'] ) ? $atts['style'] : '';

            $this->set_styles($id, [
                'static' => $static,
                'hover'  => $hover
            ])->generate();
        }

        // add caption instock and wrap in specific block
        // otherwise wrap outofstock in specific block
        if ( $is_in_stock ) {
            $caption = __('In stock', $this->plugin->text_domain);

            if ( ! empty( $atts['show_quantity'] ) && $atts['show_quantity'] === 'true' && ! is_null( $stock_quantity ) ) {
                $caption .= ': ' . $stock_quantity;
            }

            if ( ! empty( $atts['in_stock_style'] ) || ! empty( $atts['in_stock_hover'] ) ) {
                $in_stock_static = ! empty( $atts['in_stock_style'] ) ? $atts['in_stock_style'] : '';
                $in_stock_hover = ! empty( $atts['in_stock_hover'] ) ? $atts['in_stock_hover'] : '';

                $styles_generator = new StylesGeneratorService( 
                    $this->plugin,  
                    ! empty ( $id ) ? '.' . $shortcode_name . '-' . $id . ' .in-stock' : '.' . $shortcode_name . ' .in-stock',
                    [
                        'static' => $in_stock_static,
                        'hover' => $in_stock_hover
                    ]
                );
                $styles_generator->generate();
            }

            $caption = sprintf( '<span class="in-stock">%s</span>', $caption );
        } else {
            if ( ! empty( $atts['out_of_stock_style'] ) || ! empty( $atts['out_of_stock_hover'] ) ) {
                $out_of_stock_static = ! empty( $atts['out_of_stock_style'] ) ? $atts['out_of_stock_style'] : '';
                $out_of_stock_hover = ! empty( $atts['out_of_stock_hover'] ) ? $atts['out_of_stock_hover'] : '';

                $styles_generator = new StylesGeneratorService( 
                    $this->plugin,  
                    ! empty ( $id ) ? '.' . $shortcode_name . '-' . $id . ' .out-of-stock' : '.' . $shortcode_name . ' .out-of-stock',
                    [
                        'static' => $out_of_stock_static,
                        'hover' => $out_of_stock_hover
                    ]
                );
                $styles_generator->generate();
            }

            $caption = sprintf( '<span class="out-of-stock">%s</span>', $caption );
        }

        return sprintf( 
            '<div class="pccw_stock_status%s">%s</div>', 
            ! empty( $atts['id'] ) ? ( ' pccw_stock_status-' . $atts['id'] ) : '',
            $caption
        );
    }

    protected function get_current_namespace(): string
    {
        return __NAMESPACE__;
    }
}