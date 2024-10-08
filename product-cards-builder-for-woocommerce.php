<?php
/**
 * Plugin Name:          Product Cards Builder for WooCommerce
 * Plugin URI:           https://github.com/MatthewErmakov/product-cards-builder-for-woocommerce
 * Author:               Matthew V. Yermakov
 * Author URI:           https://github.com/MatthewErmakov
 * GitHub Plugin URI:    https://github.com/MatthewErmakov/product-cards-builder-for-woocommerce
 *
 * Description:          Allows you to build and customise your woocommerce product cards view.
 * 
 * Version:              1.0.2
 * Requires at least:    5.4
 * Tested up to:         6.6.2
 *
 * Text Domain:          pcbw
 * Domain Path:          /languages/
 *
 * @category             Plugin
 * @copyright            Copyright © 2024 Matthew V. Yermakov, Copyright © 2024
 * @author               Matthew V. Yermakov
 * @package              PCBW
 * @license              GPL2
 */

namespace PCBW;

require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

$kernel = App\Kernel::get_instance( 
    '1.0.2',                      // Plugin version
    plugin_dir_path( __FILE__ ),  // Plugin directory path
    plugin_dir_url( __FILE__ )    // Plugin URL path
);

// run Kernel instance
$kernel->run();
