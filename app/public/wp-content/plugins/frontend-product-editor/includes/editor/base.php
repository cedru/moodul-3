<?php 

namespace WPV_FPE\Editor;

use WPV_FPE\Helper;
use WPV_FPE\Strings;

abstract class BaseEditor{

    // contructor
    public function __construct(){
        
        add_action('wp_enqueue_scripts', [ $this, 'enqueue_scripts']);

        add_action('wp_ajax_fpe_product_update', [$this, 'update_product']);
        add_action('wp_ajax_nopriv_fpe_product_update', [$this, 'update_product']);
        
        // Add Script type module
		add_filter('script_loader_tag', [ $this, 'add_type_attribute' ] , 10, 3);

        add_action('wp_footer', [$this, 'add_root_element']);

        
        
    }

    public function enqueue_scripts(){
        
        wp_enqueue_editor();

        global $post; 

        wp_enqueue_script('fpe', WPVFPE_URL.'includes/assets/fpe.js', [], WPVFPE_VERSION, true);
        wp_enqueue_script('fpe-script', WPVFPE_URL.'build/js/front.js', ['jquery', 'jquery-ui-datepicker'], WPVFPE_VERSION, true);
        wp_enqueue_style('fpe-style', WPVFPE_URL.'build/css/main.css');
        wp_enqueue_style('fpe-style-base', WPVFPE_URL.'build/css/base.css');

        $localize_data = [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'security' => wp_create_nonce('fpe_nonce'),
            'product_id' => $post->ID,
            'tax_enabled' => wc_tax_enabled(),
            'product'   =>  $this->get_product_data($post->ID),
            'data_sets'  =>  $this->get_data_sets(),
            'manage_stocks' => 'yes' === get_option( 'woocommerce_manage_stock' )? '1' : '',
            'strings'   =>  Strings::get_strings(),
            'editor_config' => $this->get_editor_config(),
            'edit_mode' => 'single'
        ];

        $localize_data = apply_filters('wpv-fpe/localize-data', $localize_data);

        wp_localize_script('fpe-script','fpe',$localize_data);

        wp_enqueue_media();
    }

    protected function get_product_data($product_id){

        $product = wc_get_product($product_id);
        $data = [];

        $sale_price_dates_from_timestamp = $product->get_date_on_sale_from( 'edit' ) ? $product->get_date_on_sale_from( 'edit' )->getOffsetTimestamp() : false;
		$sale_price_dates_to_timestamp   = $product->get_date_on_sale_to( 'edit' ) ? $product->get_date_on_sale_to( 'edit' )->getOffsetTimestamp() : false;

		$sale_price_dates_from = $sale_price_dates_from_timestamp ? date_i18n( 'Y-m-d', $sale_price_dates_from_timestamp ) : '';
		$sale_price_dates_to   = $sale_price_dates_to_timestamp ? date_i18n( 'Y-m-d', $sale_price_dates_to_timestamp ) : '';

        $data = [
            'id'    =>  $product->get_id(),
            'type'  =>  $product->get_type(),
            'name'  =>  $product->get_title(),
            'sku'   =>  $product->get_sku(),
            'description'   =>  $product->get_description('edit'),
            'short_description' =>  $product->get_short_description('edit'),
            'featured'  => $product->get_featured('edit'),
            'catalog_visibility'    => $product->get_catalog_visibility('edit'),
            'regular_price' => $product->get_regular_price('edit'),
            'sale_price'    => $product->get_sale_price('edit'),
            'date_on_sale_from' => $sale_price_dates_from,
            'date_on_sale_to'   => $sale_price_dates_to, 
            'categories'    =>  $product->get_category_ids(),
            'tags'          =>  Helper::get_product_tag_slugs($product->get_tag_ids()),
            'weight'        =>  $product->get_weight('edit'),
            'length'        =>  $product->get_length('edit'),
            'width'         =>  $product->get_width('edit'),
            'height'        =>  $product->get_height('edit'),
            'shipping_class_id' =>  $product->get_shipping_class_id('edit')? ''.$product->get_shipping_class_id('edit'): '',
            'sold_individually' => $product->get_sold_individually('edit'),
        ];

        // Stock Data 
        if ( 'yes' === get_option( 'woocommerce_manage_stock' ) ) {

            $data['manage_stocks']  =   $product->get_manage_stock( 'edit' );
            $data['backorders']     =   $product->get_backorders( 'edit' );
            $data['stock_quantity'] =   wc_stock_amount( $product->get_stock_quantity( 'edit' ) );
            $data['low_stock_amount']   =   $product->get_low_stock_amount( 'edit' ); 
        }

        $data['stock_status']   =   $product->get_stock_status( 'edit' );

        if ( wc_tax_enabled() ){
            $data['tax_status'] =   $product->get_tax_status( 'edit' );
            $data['tax_class']  =   $product->get_tax_class('edit');
        }
        
        $data = apply_filters('wpv-fpe/product-data', $data, $product);
        return $data;
    }

    protected function get_data_sets(){

        $data['stock_statuses'] =   wc_get_product_stock_status_options();
        $data['catalog_visibility'] =   wc_get_product_visibility_options();
        $data['backorder_options']  =   wc_get_product_backorder_options();
        $data['tax_statuses']   =   [
            'taxable'  => __( 'Taxable', 'woocommerce' ),
            'shipping' => __( 'Shipping only', 'woocommerce' ),
            'none'     => _x( 'None', 'Tax status', 'woocommerce' ),
        ];

        $data['tax_classes']    =   wc_get_product_tax_class_options();
        $data['tax_enabled']    =   wc_tax_enabled();
        $data['low_stock_threshold'] = sprintf(
            /* translators: %d: Amount of stock left */
            esc_attr__( 'Store-wide threshold (%d)', 'woocommerce' ),
            esc_attr( get_option( 'woocommerce_notify_low_stock_amount' ) )
        );

        $data['product_categories'] =  Helper::get_product_categories();
        //$data['product_tags']   =   Helper::get_product_tags();

        $data['shipping_classes']   =   Helper::get_shipping_classes();

        $data['attribute_taxonomies']  =   Helper::get_attribute_taxonomies();
        
        return $data;

    }

    function add_root_element(){
        
        $root_classes = apply_filters('wpv-fpe/root-classes', ['fpe-dir-right']);

        if($root_classes){
            $class = 'class="' . implode(' ', $root_classes). '"';
        }

        $root_style = [
            '--fpe-main-color' => '#9e77ed',
        ];

        $root_style = apply_filters('wpv-fpe/root-style', $root_style);

        if($root_style){
            $style = 'style="';
            foreach($root_style as $key => $value){
                $style .= $key.':'.$value.';';
            }
            $style .= '"';
        }

        

        ?>
        <div id="fpe-root" <?php echo $class; ?> <?php echo $style; ?> ></div>
        <div class="fpe-panel-overlay">
            <img src="<?php echo WPVFPE_URL.'includes/assets/loading.gif'; ?>" alt="Loading...">
        </div>
        <?php
    }

    /**
     * Add type="module" to script tag
     * @param string $tag
     * @param string $handle
     * @param string $src
     * @return string
     */
    function add_type_attribute($tag, $handle, $src){
		// if not your script, do nothing and return original $tag
		if ( 'fpe-script' !== $handle ) {
			return $tag;
		}
		// change the script tag by adding type="module" and return it.
		$tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
		return $tag;
	}

    protected function get_editor_config(){

        $config = [
            'tabs_collapsed' => false,
            'default_tab' => 'general',
            'editor_position' => 'right',
        ];

        $config = apply_filters('wpv-fpe/editor/config', $config);
        return $config;
    }

    function update_product(){

        // get data from request
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
        
        // check if user is allowed to update product

        // get current user role
        $user = wp_get_current_user();
        $user_roles = (array) $user->roles;

        // get allowed user roles
        $allowed_user_roles = apply_filters('wpv_fpe/allowed_user_roles', ['administrator']);

        // check if current user role is in $allowed_user_roles
        if(!array_intersect($user_roles, $allowed_user_roles)){
            return wp_send_json([
                'success' => 0
            ]);
        }
        
        check_ajax_referer('fpe_nonce', 'fpe_nonce');
        
        // check if valid product id is available in request. 
        $product_id = intval($data['product_id']);
        $product_data = $data['product'];

       
        $classname    = \WC_Product_Factory::get_product_classname( $product_id, 'simple' );
		$product      = new $classname( $product_id );

        $product = wc_get_product($product_id);
        
        $success = 0;
        $product_url = '';


        if(!empty( $product_data['manage_stocks'] )){
            $manage_stock = wc_clean($product_data['manage_stocks']);
        }else{
            $manage_stock = 'false';
        }

        $featured = !empty($product_data['featured'])? wc_clean($product_data['featured']) : false; 

        // Handle dates.
		$date_on_sale_from = '';
		$date_on_sale_to   = '';

		// Force date from to beginning of day.
		if ( isset( $product_data['date_on_sale_from'] ) ) {
			$date_on_sale_from = wc_clean( wp_unslash( $product_data['date_on_sale_from'] ) );
            
			if ( ! empty( $date_on_sale_from ) ) {
				$date_on_sale_from = date( 'Y-m-d 00:00:00', strtotime( $date_on_sale_from ) ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
			}
		}

		// Force date to to the end of the day.
		if ( isset( $product_data['date_on_sale_to'] ) ) {
			$date_on_sale_to = wc_clean( wp_unslash( $product_data['date_on_sale_to'] ) );

			if ( ! empty( $date_on_sale_to ) ) {
				$date_on_sale_to = date( 'Y-m-d 23:59:59', strtotime( $date_on_sale_to ) ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
			}
		}
       
        if($product_id){
            
            $errors = $product->set_props(
                [
                    'name'               => wc_clean(wp_unslash($product_data['name'])),
                    'sku'                => isset( $product_data['sku'] ) ? wc_clean( wp_unslash( $product_data['sku'] ) ) : null,
                    'description'        => isset( $product_data['description'] ) ? wp_kses_post( wp_unslash( $product_data['description'] ) ) : null,
                    'short_description'  => isset( $product_data['short_description'] ) ? wp_kses_post( wp_unslash( $product_data['short_description'] ) ) : null,
                    'featured'           => $featured,
                    'catalog_visibility' => wc_clean(wp_unslash($product_data['catalog_visibility'])),
                    'manage_stock'       => $manage_stock,
				    'backorders'         => isset( $product_data['backorders'] ) ? wc_clean( wp_unslash( $product_data['backorders'] ) ) : null,
				    'stock_status'       => isset( $product_data['stock_status'] ) ? wc_clean( wp_unslash( $product_data['stock_status'] ) ) : null,
				    'stock_quantity'     => isset( $product_data['stock_quantity'] ) ? wc_stock_amount( wp_unslash( $product_data['stock_quantity'] ) ) : null ,
				    'low_stock_amount'   => isset( $product_data['low_stock_amount'] ) && '' !== $product_data['low_stock_amount'] ? wc_stock_amount( wp_unslash( $product_data['low_stock_amount'] ) ) : '',
                    'sold_individually'  => isset( $product_data['sold_individually'] ) ? wc_clean( wp_unslash( $product_data['sold_individually'] ) ) : null,
                    'regular_price'      => isset( $product_data['regular_price'] ) ? wc_clean( wp_unslash( $product_data['regular_price'] ) ) : null,
                    'sale_price'         => isset( $product_data['sale_price'] ) ? wc_clean( wp_unslash( $product_data['sale_price'] ) ) : null,
                    'date_on_sale_from'  => $date_on_sale_from,
                    'date_on_sale_to'    => $date_on_sale_to,
                    'tax_class'          => isset( $product_data['tax_class'] ) ? wc_clean( wp_unslash( $product_data['tax_class'] ) ) : null,
                    'tax_status'          => isset( $product_data['tax_status'] ) ? wc_clean( wp_unslash( $product_data['tax_status'] ) ) : null,
                    'weight'             => isset( $product_data['weight'] ) ? wc_clean( wp_unslash( $product_data['weight'] ) ) : null,
                    'length'             => isset( $product_data['length'] ) ? wc_clean( wp_unslash( $product_data['length'] ) ) : null,
                    'width'              => isset( $product_data['width'] ) ? wc_clean( wp_unslash( $product_data['width'] ) ) : null,
                    'height'             => isset( $product_data['height'] ) ? wc_clean( wp_unslash( $product_data['height'] ) ) : null,
                    'shipping_class_id'  => isset( $product_data['shipping_class_id'] ) ? absint( wp_unslash( $product_data['shipping_class_id'] ) ) : null,
                ]
            );
            
            $product = apply_filters('wpv-fpe/before-product-save', $product, $product_data);
            
            if ( ! is_wp_error( $errors ) ) {
                $product->save();
                // save post content
                if(isset($product_data['description'])){
                    $post = array(
                        'ID'           => $product_id,
                        'post_content' => wp_kses_post( wp_unslash( $product_data['description'] ) )
                    );
                    wp_update_post( $post );
                }
                // update product categories
                if(isset($product_data['categories'])){
                    wp_set_object_terms( $product_id, $product_data['categories'], 'product_cat' );
                }

                // update product tags
                if(isset($product_data['tags'])){
                    wp_set_object_terms( $product_id, $product_data['tags'], 'product_tag' );
                }

                do_action('wpv-fpe/after-product-save', $product, $product_data);
                // update successful. Return product url
                $success = 1;
                $product_url = get_permalink($product_id);
            }else{
               
            }
        }
        
        return wp_send_json([
            'success' => $success,
            'product_url' => $product_url
        ]);
    }

    

}