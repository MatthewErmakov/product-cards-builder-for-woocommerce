<?php
/**
 * Plugin Name:          Product Cards Customiser for WooCommerce
 * Plugin URI:           https://github.com/MatthewErmakov/product-cards-customiser-for-woocommerce
 * Author:               Matthew V. Yermakov
 * Author URI:           https://github.com/MatthewErmakov
 * GitHub Plugin URI:    https://github.com/MatthewErmakov/product-cards-customiser-for-woocommerce
 *
 * Description:          Allows you to customise your woocommerce product cards view
 * 
 * Version:              1.0.1
 * Requires at least:    5.4
 * Tested up to:         6.6.2
 *
 * Text Domain:          pccw
 * Domain Path:          /languages/
 *
 * @category             Plugin
 * @copyright            Copyright © 2024 Matthew V. Yermakov, Copyright © 2024
 * @author               Matthew V. Yermakov
 * @package              PCCW
 * @license              GPL2
 */

namespace PCCW;

require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

$kernel = App\Kernel::get_instance( 
    '1.0.1',                      // Plugin version
    'pccw',                       // Plugin text domain
    plugin_dir_path( __FILE__ ),  // Plugin directory path
    plugin_dir_url( __FILE__ )    // Plugin URL path
);

// run Kernel instance
$kernel->run();
