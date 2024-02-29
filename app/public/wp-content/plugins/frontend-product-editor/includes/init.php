<?php 

namespace WPV_FPE;

use WPV_FPE\Editor\Single;

class Plugin{

    private static $instance;

    private static $allow_singular_edit;

    private static $is_pro = false;

    public static function init() {
		if ( null === self::$instance ) {
			self::$instance = new Plugin();
		}

		return self::$instance;
	}


    /**
	 * The Constructor.
	 */
	public function __construct() {
        
        if(file_exists(WPVFPE_PATH.'includes/pro/init.php')){
            require_once(WPVFPE_PATH.'includes/pro/init.php');
            self::$is_pro = true;
        }

        add_action( 'init', [ $this, 'load_textdomain'] );

        // add upgrade to pro link on plugin page
        add_filter('plugin_action_links_'.plugin_basename(WPVFPE_PATH.'frontend-product-editor.php'), [$this, 'add_upgrade_link']);

        new Single();
        
	}

    /**
     * Add upgrade to pro link on plugin page
     * @param array $links
     * @return array
     */
    function add_upgrade_link($links){
        // return if pro is active
        if(self::$is_pro){
            return $links;
        }

        $links[] = '<a style="font-weight:900; color:#3e6b27;" href="https://wpvibes.link/go/fpe" target="_blank">Upgrade to Pro</a>';
        return $links;
    }

    public function load_textdomain(){
        load_plugin_textdomain( 'wpv-fpe', false, 'frontend-product-editor' ); 
    }

    /**
     * Decide if the admin bar link should be shown or not
     * @return bool
     */
    function show_admin_bar_link(){

        if(self::$allow_singular_edit === null){

            self::$allow_singular_edit  = false; 
            
            if(is_singular('product') && current_user_can( 'manage_options' )){
            
                global $product;
                $product_object = get_page_by_path( $product, OBJECT, 'product' );
    
                if ( ! empty( $product_object ) ) {
                    $product_object = wc_get_product( $product_object );
    
                    $type = $product_object->get_type();
    
                    if($type === 'simple'){
                        self::$allow_singular_edit = true;
                    }
                }                
            }
            
        }
        return self::$allow_singular_edit;
    }

    /**
     * Decide if editing from single product should be allowed
     * @return bool
     */
    public function allow_singular_edit(){
        return apply_filters('wpv-fpe/allow-singular-edit', true);
    }

}
Plugin::init();