<?php 

namespace PCBW\App\Includes\Front\Shortcodes;

use PCBW\App\Traits\Template;
use PCBW\App\Includes\Abstracts\Shortcode;

class Rating extends Shortcode {
    public function result( array $atts = [], $content = null ): string 
    {
        global $product;

        parent::result( $atts, $content );
        
        $result = '';
        $product_id = $product->get_id();

        $display_reviews_amount = !empty( $atts['display_reviews_amount'] ) && $atts['display_reviews_amount'] === 'true';
        $reviews_amount_location = !empty( $atts['reviews_amount_location'] ) ? $atts['reviews_amount_location'] : 'botton';
        $hide_if_empty = !empty( $atts['hide_if_empty'] ) && $atts['hide_if_empty'] === 'true';

        $comments = get_comments(['post_id' => $product_id]);
        $rating = $this->calculate_rating( $comments, $product_id );
        $reviews_amount = count( $comments );

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

        if ( $hide_if_empty && ! intval( $rating ) ) {
            return '';
        }

        return sprintf( 
            '<div class="pcbw_rating%s%s%s"><div class="stars rating-%s">%s</div>%s</div>', 
            ! empty( $atts['id'] ) ? esc_attr( ' pcbw_rating-' . $atts['id'] ) : '',
            $display_reviews_amount ? ' caption' : '',
            esc_attr( ' ' . $reviews_amount_location ),
            esc_attr( $rating ),
            str_repeat('<span></span>', 5),
            $display_reviews_amount ? (
                apply_filters( 'pcbw_reviews_caption', sprintf(
                    "%d %s", 
                    esc_html( $reviews_amount ),
                    $reviews_amount === 1 ? __('review', 'pcbw') : __('reviews', 'pcbw'),
                ), $reviews_amount )
            ) : ''
        );
    }

    public function get_current_namespace(): string
    {
        return __NAMESPACE__;
    }

    private function calculate_rating( array $comments, int $product_id ): string
    {
        $count = 0;
        $general_rating = 0;
        
        $result = '';

        foreach( $comments as $comment ) {
            $general_rating += (float)get_comment_meta( $comment->comment_ID, 'rating', true );
            $count++;
        }

        if ( $count ) {
            $result = implode( '-', explode( '.', $this->round_to_quarter( $general_rating / $count ) ) );
        } else {
            $result = $this->round_to_quarter( $general_rating );
        }

        /**
         * Used pcbw_rating filter
         * in case of different reviews plugin used
         * and it's needed to modify reviews output
         */

        return apply_filters( 'pcbw_rating', $result, $product_id );
    }

    private function round_to_quarter( float $number ): string
    {
        return strval( round($number * 4) / 4 );
    }
}