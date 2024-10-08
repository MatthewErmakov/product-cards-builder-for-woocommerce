<?php 

namespace PCBW\App\Includes\Front\Shortcodes;

use PCBW\App\Traits\Template;
use PCBW\App\Includes\Abstracts\Shortcode;

class Attributes extends Shortcode {
    use Template;

    public function result( array $atts = [], $content = null ): string
    {
        global $product;

        parent::result( $atts, $content );

        if ( ! empty( $atts['style'] ) || ! empty( $atts['hover'] ) ) {
            $id = ! empty( $atts['id'] ) ? $atts['id'] : '';
            $hover = ! empty( $atts['hover'] ) ? $atts['hover'] : '';
            $static = ! empty( $atts['style'] ) ? $atts['style'] : '';
            
            $styles = [
                'static' => $static,
                'hover'  => $hover
            ];

            $this->set_styles($id, $styles)->generate();
        }

        $result = '';
        $display_attribute_name = ! empty( $atts['display_attribute_name'] ) && $atts['display_attribute_name'] === 'true';

        $attributes = $product->get_attributes();

        if ( ! empty( $attributes ) ) {
            foreach ( $attributes as $key => $item ) {
                $data = $item->get_data();
                $value = $data['options'];
    
                if ( is_array( $value ) ) {
                    $result .= '<div class="pcbw_attribute-group">';
    
                    if ( $display_attribute_name ) {
                        if ( ! $data['is_taxonomy'] ) {
                            $result .= '<span class="pcbw_attribute-name">' . $data['name'] . ': </span>';
                        } else {
                            $term = get_taxonomy( $data['name'] );
        
                            $result .= '<span class="pcbw_attribute-name">' . $term->labels->singular_name . ': </span>';
                        }
                    }
                    
                    $attrs = [];
    
                    foreach ($value as $key1 => $attr_item) {
                        if ( ! $data['is_taxonomy'] ) {
                            $attr_item = trim( $attr_item );
                            $attrs[] = sprintf(
                                '<span class="pcbw_attribute-item" data-attribute-name="%s">%s</span>',
                                $attr_item,
                                $attr_item,
                            );
                        } else {
                            $term_item = get_term_by( 'ID', $attr_item, $data['name'] );
                            $term_name = $term_item->name;
                            $attrs[] = sprintf(
                                '<span class="pcbw_attribute-item" data-attribute-name="%s">%s</span>',
                                $term_item->slug,
                                $term_name,
                            );
                        }
                    }    
    
                    $result .= implode('', $attrs) . '</div>';
                }
            }
        }

        if ( $result === '' ) {
            return $result;
        }

        return sprintf( 
            '<div class="pcbw_attributes%s">%s</div>', 
            isset( $atts['id'] ) ? ' pcbw_attributes-' . $atts['id'] : '', 
            $result
        );
    }

    protected function get_current_namespace(): string
    {
        return __NAMESPACE__;
    }
}