<?php 

namespace PCCW\App\Traits;

use PCCW\App\Kernel;

/**
 * * USED IN KERNEL *
 * 
 * @see wp-content/plugins/product-cards-customiser-for-woocommerce/app/Kernel.php
 */
trait Classes {

    /**
     * Creates an instance of a class found in a directory
     * and runs 'actions' method
     * 
     * @param string $path
     * @param string $namespace
     * @param string $method_name
     */
    public function init_actions( $path, $namespace, Kernel $plugin, $method_name = 'actions' ) {
        $files = glob( $path );
        $mask = str_replace( '*.php', '', $path );

        if ( ! $files ) {
            return;
        }

        foreach ( $files as $file ) {
            $classname = str_replace( '.php', '', $file );
            $classname = str_replace( $mask, '', $classname );
            $classname = $namespace . $classname;

            if ( class_exists( $classname ) && method_exists( $classname, $method_name ) ) {
                ( new ( $classname ) ( $plugin ) )->actions();
            }
        }
    }
}