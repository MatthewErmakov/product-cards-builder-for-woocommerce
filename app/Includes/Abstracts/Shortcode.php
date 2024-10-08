<?php 

namespace PCBW\App\Includes\Abstracts;

use PCBW\App\Kernel;
use PCBW\App\Includes\Services\StylesGeneratorService;

abstract class Shortcode {
    private $prefix = 'pcbw_';
    protected $atts;

    public function __construct( Kernel $plugin )
    {
        $this->plugin = $plugin;
    }

    public function result( array $atts = [], $content = null ): string 
    {
        if ( ! empty( $atts ) ) {
            foreach ( $atts as $key => $attribute ) {
                $atts[$key] = sanitize_text_field( $attribute );
            }
        }

        $this->atts = $atts;
        $this->content = trim( $content );

        return $content;
    }

    abstract protected function get_current_namespace(): string;

    public function actions(): void {
        add_shortcode( $this->get_name(), [$this, 'result'] );
    }   

    protected function get_name(): string
    {
        return $this->prefix . strtolower(
            preg_replace(
                '/(?<!^)[A-Z]/', 
                '_$0', 
                str_replace( $this->get_current_namespace() . '\\', '', get_called_class() )
            )
        );
    }

    public function set_styles( string $id, array $styles ): StylesGeneratorService
    {
        $shortcode_name = $this->get_name();

        $styles_generator = new StylesGeneratorService( 
            $this->plugin, 
            ( ! empty ( $id ) ? '.' . $shortcode_name . '-' . $id : '.' . $shortcode_name ), 
            $styles 
        );

        return $styles_generator;
    }
}