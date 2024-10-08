<?php 

namespace PCBW\App\Traits;

/**
 * * USED IN CONTROLLERS *
 */
trait Template {

    /**
     * Renders template view
     * 
     * @param string $template_name
     * @param array $args = []
     * @param bool $output = false
     * 
     * @return void|bool $output
     */
    public function render( $template_name, $args = [], $output = false ) {
        extract( $args );

        ob_start();
        include $this->plugin->path . 'views/' . $template_name . '.php';
        $result = ob_get_clean();

        if ( $output ) {
            print $result;
        } else {
            return $result;
        }
    } 
}