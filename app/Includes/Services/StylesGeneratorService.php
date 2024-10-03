<?php 

namespace PCCW\App\Includes\Services;

use PCCW\App\Kernel;

class StylesGeneratorService {

    public function __construct( Kernel $plugin, string $selector, array $styles ) {
        $this->plugin = $plugin;
        $this->selector = sanitize_text_field( $selector );
        
        foreach ( $styles as $key => $style ) {
            $styles[$key] = sanitize_text_field( $style );
        }

        $this->styles = $styles;
    }

    public function generate(): void {
        add_filter( 'pccw_style', [$this, 'process_styles'] );
    }

    public function ungenerate(): void
    {
        remove_filter( 'pccw_style', [$this, 'process_styles'] );
    }

    public function process_styles( string $style ): string
    {
        $selector = $this->selector;
        $styles = $this->styles;
        
        $static = ! empty( $styles['static'] ) ? $styles['static'] : '';
        $hover = ! empty( $styles['hover'] ) ? $styles['hover'] : '';

        if ( ! is_admin() && ! defined('DOING_AJAX') ) {
            if ( $static !== '' && stripos( $style, $selector  ) === false ) {
                $style .= sprintf('html body.woocommerce ul.products li.product %s{%s}', $selector, $static );
            }
    
            if ( $hover !== '' && stripos( $style, $selector . ':hover'  ) === false ) {
                $style .= sprintf('html body.woocommerce ul.products li.product %s:hover{%s}', $selector, $hover );
            }
        } else {
            if ( $static !== '' && stripos( $style, $selector  ) === false ) {
                $style .= sprintf('html body .product-cards-customiser .preview-wrapper .preview-block %s{%s}', $selector, $static );
            }
    
            if ( $hover !== '' && stripos( $style, $selector . ':hover'  ) === false ) {
                $style .= sprintf('html body .product-cards-customiser .preview-wrapper .preview-block %s:hover{%s}', $selector, $hover );
            }
        }
        
        
        return $style;
    }
}