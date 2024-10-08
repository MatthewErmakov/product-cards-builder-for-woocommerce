<?php 

namespace PCBW\App\Includes\Front\Shortcodes;

use PCBW\App\Traits\Template;
use PCBW\App\Includes\Abstracts\Shortcode;
use PCBW\App\Includes\Services\StylesGeneratorService;

class AddToCart extends Shortcode {
    use Template;

    public function result( array $atts = [], $content = null ): string
    {
        global $product;

        parent::result( $atts, $content );

        $id = ! empty( $atts['id'] ) ? $atts['id'] : '';
        $hover = ! empty( $atts['hover'] ) ? $atts['hover'] : '';
        $static = ! empty( $atts['style'] ) ? $atts['style'] : '';

        // clear action queue
        if ( has_filter( 'woocommerce_loop_add_to_cart_link', [$this, 'quantity_field'] ) ) {
            remove_filter( 'woocommerce_loop_add_to_cart_link', [$this, 'quantity_field'], 10, 2 );
        }

        /**
         * Add quantity field to product card
         */
        if ( ! empty( $atts['multiple'] ) && 
             $atts['multiple'] === 'true' &&
             $product && 
             $product->is_type( 'simple' ) && 
             $product->is_purchasable() && 
             $product->is_in_stock() && 
             ! $product->is_sold_individually()
           ) {
            add_filter( 'woocommerce_loop_add_to_cart_link', [$this, 'quantity_field'], 10, 2 );
        } else {
            $styles = [
                'static' => $static,
                'hover'  => $hover
            ];

            $styles_generator = $this->set_styles($id, $styles)->generate();
        }

        // Apply styles for view cart button
        if ( ! empty( $atts['view_cart'] ) && $atts['view_cart'] === 'true' ) {
            $this->apply_view_cart_styling();
        }

        ob_start();
        woocommerce_template_loop_add_to_cart();

        return ob_get_clean();
    }

    public function actions(): void
    {
        parent::actions();

        add_filter( 'woocommerce_loop_add_to_cart_args', [$this, 'change_add_to_cart_args'] );
    }

    protected function get_current_namespace(): string
    {
        return __NAMESPACE__;
    }

    public function change_add_to_cart_args( array $args ): array
    {
        $atts = $this->atts;
        $classes = explode(' ', $args['class']);

        if ( ($key = array_search('button', $classes)) !== false ) {
            unset($classes[$key]);
        }

        if ( ! empty( $atts['theme_styles'] ) && $atts['theme_styles'] === 'true' ) {
            $classes[] = 'button';
        }

        if ( ! empty ( $atts['id'] ) ) {
            $classes[] = $this->get_name() . '-' . $atts['id'];
        } else {
            $classes[] = $this->get_name();
        }

        if ( ! empty( $atts['view_cart'] ) && $atts['view_cart'] === 'true' ) {
            $args['attributes']['data-view_cart_classes'] = ( ! empty ( $atts['id'] ) ? 'added_to_cart' . '-' . $atts['id'] : 'added_to_cart' );
        }
        
        $args['class'] = implode( ' ', $classes );

        return $args;
    }

    public function quantity_field ( $html, $product ) 
    {
        $atts = $this->atts;

        $id = ! empty( $atts['id'] ) ? $atts['id'] : '';
        $shorcode_name = $this->get_name();
        $classname = $id !== '' ? ' ' . $shorcode_name . '-' . $id : ' ' . $shorcode_name;
        $html = '<form action="' . esc_url( $product->add_to_cart_url() ) . '" class="cart' . $classname . '" method="post" enctype="multipart/form-data">';
        
        $product_id = 0;

        // Access the cart items
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $product_id = $cart_item['product_id'];
            $quantity = $cart_item['quantity'];
        }

        // If we have match update quantity
        if($product_id == $product->get_ID()) {
            $html .= woocommerce_quantity_input( array('input_value' => $quantity), $product, false );
        } else {
            $html .= woocommerce_quantity_input( array(), $product, false );
        }
        
        $button_classes = 'alt';

        if ( ! empty( $atts['theme_styles'] ) && $atts['theme_styles'] === 'true' ) {
            $button_classes .= ' button';
        }

        $static = ! empty( $atts['style'] ) ? $atts['style'] : '';
        $hover = ! empty( $atts['hover'] ) ? $atts['hover'] : '';

        // generate styles specifically for form button
        $style_generator = new StylesGeneratorService( 
            $this->plugin, 
            ( ! empty ( $id ) ? '.' . $this->get_name() . '-' . $id : '.' . $this->get_name() ) . ' button', 
            [
                'static' => $static,
                'hover' => $hover
            ]
        );

        $style_generator->generate();

        $html .= '<button type="submit" class="' . $button_classes . '">' . esc_html( $product->add_to_cart_text() ) . '</button>';
        $html .= '</form>';

        return $html;
    }

    public function apply_view_cart_styling(): void 
    {
        $atts = $this->atts;
        $shortcode_name = $this->get_name();

        $id = ! empty( $atts['id'] ) ? $atts['id'] : '';

        $hover = ! empty( $atts['hover'] ) ? $atts['hover'] : '';
        $static = ! empty( $atts['style'] ) ? $atts['style'] : '';

        $view_cart_static = ! empty( $atts['view_cart_style'] ) ? $atts['view_cart_style'] : '';
        $view_cart_hover = ! empty( $atts['view_cart_hover'] ) ? $atts['view_cart_hover'] : '';

        // inherit styles from add_to_cart button or no
        $view_cart_static = $view_cart_static === 'inherit' ? $static : $view_cart_static;
        $view_cart_hover = $view_cart_hover === 'inherit' ? $hover : $view_cart_hover;

        // generate styles for this button
        $style_generator = new StylesGeneratorService( 
            $this->plugin, 
            ( ! empty ( $id ) ? 'a.added_to_cart' . '-' . $id : 'a.added_to_cart' ), 
            [
                'static' => $view_cart_static,
                'hover' => $view_cart_hover
            ]
        );
        $style_generator->generate();
    }

    public function set_styles( string $id, array $styles ): StylesGeneratorService
    {
        $shortcode_name = $this->get_name();

        $styles_generator = new StylesGeneratorService( 
            $this->plugin, 
            ( ! empty ( $id ) ? 'a.' . $shortcode_name . '-' . $id : 'a.' . $shortcode_name ), 
            $styles
        );

        return $styles_generator;
    }
}