<?php

/**
 * Plugin Name:  Frontend Product Editor for WooCommerce
 * Description:  Edit WooCommerce Products from Frontend
 * Version:      1.2
 * Author:       WPVibes
 * Author URI:   https://wpvibes.com
 * License:      GPL-3.0-or-later
 * License URI:  https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:  fpe-woo
 * Domain Path:  /
 * @fs_premium_only /includes/pro/, /build/js/admin.*, /build/css/admin.*
 */


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if(!defined('WPVFPE_FILE')){
	define( 'WPVFPE_VERSION', '1.2' );
	define( 'WPVFPE_MIN_PHP', '7.1' );
	define( 'WPVFPE_MIN_WP', '5.0' );
	define( 'WPVFPE_FILE', __FILE__ );
	define( 'WPVFPE_BASE', plugin_basename( WPVFPE_FILE ) );
	define( 'WPVFPE_PATH', plugin_dir_path( WPVFPE_FILE ) );
	define( 'WPVFPE_URL', plugin_dir_url( WPVFPE_FILE ) );
}


$active_plugins = get_option( 'active_plugins' );
// get current file path
$current_file_path = __FILE__;
$is_pro = false;
$php_version = phpversion();

$free_plugin_path = 'frontend-product-editor/frontend-product-editor.php';
$pro_plugin_path = 'frontend-product-editor-pro/frontend-product-editor.php';

if(strpos($current_file_path, $pro_plugin_path) !== false){
	$is_pro = true;
	if($is_pro){
		// free is also active, unset it so that it won't be loaded
		if(in_array($free_plugin_path, $active_plugins)){
			deactivate_plugins($free_plugin_path);
			// add a admin notice
			add_action( 'admin_notices', function(){
				?>
				<div class="notice notice-error is-dismissible">
					<p><?php _e( 'Frontend Product Editor (Free) cannot be activated along with Pro version as it is not needed', 'wts-eae' ); ?></p>
				</div>
				<?php
			});
		}
	}
}
add_action( 'plugins_loaded', function(){
	$active_plugins = get_option( 'active_plugins' );
	$current_file_path = __FILE__;
	$is_pro = false;

	$free_plugin_path = 'frontend-product-editor/frontend-product-editor.php';
	$pro_plugin_path = 'frontend-product-editor-pro/frontend-product-editor.php';

	if(strpos($current_file_path, $pro_plugin_path) !== false){
		$is_pro = true;
		
		require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/init.php';
	}else{ 
		if(in_array($pro_plugin_path, $active_plugins)){
			
		}else{	
			require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
			require_once plugin_dir_path( __FILE__ ) . 'includes/init.php';
		}
	}
});