<?php 

namespace PCBW\App;

use \PCBW\App\Traits\Classes;

class Kernel {

    use Classes;

    public $version;
    public $path;
    public $url;

    private static $instance;

    public function run() {
        add_action( 'woocommerce_init', [$this, 'register_controllers'] );
    }

    // Constructor should remain protected
    protected function __construct( 
        string $plugin_version, 
        string $plugin_text_domain, 
        string $plugin_path, 
        string $plugin_url 
    ) {
        $this->version = $plugin_version;
        $this->text_domain = $plugin_text_domain;
        $this->path = $plugin_path;
        $this->url = $plugin_url;
    }

    // Static method to get the instance
    public static function get_instance(
        string $plugin_version = '', 
        string $plugin_text_domain = '', 
        string $plugin_path = '', 
        string $plugin_url = '' 
    ): Kernel {
        if (self::$instance === null) {
            self::$instance = new static($plugin_version, $plugin_text_domain, $plugin_path, $plugin_url);
        }
        
        return self::$instance;
    }

    /**
     * Registers all the files from app/Controllers/ folder and
     * looking for "actions" method and runs it once it's found
     * 
     * @param void
     * @return void
     */
    public function register_controllers() {
        $path = $this->path . 'app/Includes/';
        $namespace = __NAMESPACE__ . '\\Includes\\';

        foreach ( [ 'Admin', 'Front' ] as $part ) {
            $this->init_actions( $path . $part . '/*.php', $namespace . $part . '\\', $this );
        }
        
        $this->enqueue_assets();
    }

    public function admin_assets() {
        wp_register_style( 'product-cards-customiser-admin', $this->url . 'public/assets/admin.css', [], filemtime( $this->path . 'public/assets/admin.css' ) );
      
        wp_register_script( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js', ['jquery'], '1.12.1', true );
        wp_register_script( 'product-cards-customiser-admin', $this->url . 'public/assets/admin.min.js', ['jquery', 'wp-tinymce'], filemtime( $this->path . 'public/assets/admin.min.js' ), true );

        wp_enqueue_editor();
        wp_enqueue_style( 'product-cards-customiser-admin' );

        wp_enqueue_script( 'jquery-ui' );
        wp_enqueue_script( 'product-cards-customiser-admin' );

        wp_register_style( 'product-cards-customiser', $this->url . 'public/assets/front.css', [], filemtime( $this->path . 'public/assets/front.css' ) );
        wp_enqueue_style( 'product-cards-customiser' );

        wp_localize_script( 'product-cards-customiser-admin', 'pcbw', [
            'ajax_url'                => admin_url( 'admin-ajax.php' ),
            'nonce_preview'           => wp_create_nonce( 'pcbw_preview' ),
            'nonce_save_template'     => wp_create_nonce( 'pcbw_save_template' ),
            'nonce_activate_template' => wp_create_nonce( 'pcbw_activate_template' )
        ]);
    }

    public function front_assets() {
        wp_register_style( 'product-cards-customiser', $this->url . 'public/assets/front.css', [], filemtime( $this->path . 'public/assets/front.css' ) );
        wp_register_script( 'product-cards-customiser', $this->url . 'public/assets/front.min.js', ['jquery', 'wp-tinymce'], filemtime( $this->path . 'public/assets/front.min.js' ), true );
        
        wp_enqueue_style( 'product-cards-customiser' );
        wp_enqueue_script( 'product-cards-customiser' );
    }

    protected function enqueue_assets() {
        add_action( 'admin_enqueue_scripts', [$this, 'admin_assets'] );
        add_action( 'wp_enqueue_scripts', [$this, 'front_assets'] );
    }
}