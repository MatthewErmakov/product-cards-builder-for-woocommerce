<?php 

namespace PCBW\App\Includes\Front\Shortcodes;

use PCBW\App\Traits\Template;
use PCBW\App\Includes\Abstracts\Shortcode;
use PCBW\App\Includes\Services\TaxonomyDataHandlerService;
use PCBW\App\Includes\Services\StylesGeneratorService;

class TaxonomyTerms extends Shortcode {
    use Template;

    public function result( array $atts = [], $content = null ): string
    {
        global $product;

        parent::result( $atts, $content );

        $result = '';

        $id = ! empty( $atts['id'] ) ? $atts['id'] : '';

        if ( ! empty( $atts['style'] ) || ! empty( $atts['hover'] ) ) {
            $hover = ! empty( $atts['hover'] ) ? $atts['hover'] : '';
            $static = ! empty( $atts['style'] ) ? $atts['style'] : '';

            $this->set_styles($id, [
                'static' => $static,
                'hover'  => $hover
            ])->generate();
        }

        if ( ! empty( $atts['term_style'] ) || ! empty( $atts['term_hover'] ) ) {
            $term_static = ! empty( $atts['term_style'] ) ? $atts['term_style'] : '';
            $term_hover = ! empty( $atts['term_hover'] ) ? $atts['term_hover'] : '';

            $term_selector = '.pcbw_taxonomy_terms';

            if ( ! empty ( $id ) ) {
                $term_selector .= '.pcbw_taxonomy_terms-'.$id;
            }

            if ( ! empty( $this->atts['links'] ) && $this->atts['links'] === 'true' ) {
                $term_selector .= ' a, '. $term_selector . ' span';
            } else {
                $term_selector .= ' span';
            }

            $styles_generator = new StylesGeneratorService( $this->plugin, $term_selector, [
                'static' => $term_static,
                'hover'  => $term_hover
            ] );
            $styles_generator->generate();
        }

        $result .= $this->output_taxonomy_terms( $product->get_id() );

        return sprintf('<div class="pcbw_taxonomy_terms%s">%s</div>', !empty($id) ? ' pcbw_taxonomy_terms'.'-'.esc_attr($id) :'', $result);
    }

    protected function get_current_namespace(): string
    {
        return __NAMESPACE__;
    }

    public function output_taxonomy_terms( int $product_id ): string
    {
        $query = new TaxonomyDataHandlerService($this->plugin, $product_id, $this->atts);

        $data = $query->request();

        if ( ! empty( $data['error'] ) && $data['error']['status'] === 'Not found' ) {
            return '';
        } 

        ob_start();
        $this->output_terms($data);

        return ob_get_clean();
    }

    /**
     * Output terms
     * 
     * @param void
     * @return void
     */
    public function output_terms( &$data ): void
    {
        $count = !empty($this->atts['count']) && $this->atts['count'] === 'true';
        $links_target = !empty($this->atts['links_target']) ? $this->atts['links_target'] : '_self';

        foreach ( $data as $key => $item ) {
            if ( is_array( $item ) ) {
                if ( ! empty( $item['link'] ) ) {
                    printf(
                        '<a class="pcbw_term-item" data-term-slug="%s" href="%s" target="%s">%s%s</a>',
                        esc_attr( $item['slug'] ),
                        esc_url( $item['link'] ),
                        esc_attr( $links_target ),
                        esc_html( $item['name'] ),
                        $count ? ' (' . esc_html( $item['count'] ) . ')' : ''
                    );
                } else {
                    printf(
                        '<span class="pcbw_term-item" data-term-slug="%s">%s%s</span>',
                        esc_attr( $item['slug'] ),
                        esc_html( $item['name'] ),
                        $count ? '(' . esc_html( $item['count'] ) . ')' : ''
                    );
                }
            }

            if ( isset($item['children']) && count( $item['children'] ) !== 0 ) {
                $this->output_terms( $item['children'] );
            }
        }
    }
}