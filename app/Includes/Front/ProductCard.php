<?php 

namespace PCBW\App\Includes\Front;

use PCBW\App\Includes\Abstracts\Front;
use PCBW\App\Traits\Classes;

class ProductCard extends Front {
    use Classes;

    public function actions(): void
    {
        $path = $this->plugin->path;

        $this->shortcodes();
        
        add_filter( 'wc_get_template_part', [$this, 'rewrite_template'], 11 );

        if ( ! is_admin() && ! defined('DOING_AJAX') ) {
            add_action( 'wp_footer', function (){
                echo $this->inline_css_output();
            });
        } else {
            add_filter( 'pcbw_preview_card_styles', [$this, 'inline_css_output'], 99 );
        }
    }

    private function shortcodes(): void
    {
        $path = $this->plugin->path . 'app/Includes/Front/Shortcodes/';
        $namespace = __NAMESPACE__ . '\\Shortcodes';

        $this->init_actions( $path . '/*.php', $namespace . '\\', $this->plugin );
    }

    public function rewrite_template ( $template )  {
        if ( function_exists( 'wc_current_theme_is_fse_theme' ) ) {
            if ( ! wc_current_theme_is_fse_theme() ) {
                if ( stripos( $template, 'content-product.php' ) !== false && get_option( 'pcbw_activate_template', 'no' ) === 'yes' ) {
                    return $this->plugin->path . 'views/front/product-card.php';
                }
            }
        }

        return $template;
    }

    public function inline_css_output(): string
    {
        return sprintf( '<style id="pcbw-inline-css">%s</style>', apply_filters( 'pcbw_style', '' ) );
    }
}