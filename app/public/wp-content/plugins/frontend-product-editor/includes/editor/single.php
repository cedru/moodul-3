<?php 
namespace WPV_FPE\Editor;


class Single extends BaseEditor{

    static $allow_edit = false;
    // constructor
    public function __construct(){
        parent::__construct();

        add_action('admin_bar_menu', [$this, 'add_link_to_admin_bar'], 999);

        add_action('wp', [ $this, 'set_allow_edit']);
    }

    function add_link_to_admin_bar($admin_bar){

        if(!self::$allow_edit){
            return;
        }
     
        $show_admin_bar_link = apply_filters('wpv_fpe/admin_bar_trigger', __return_true());

        if(!$show_admin_bar_link){
            return;
        }

        $args = array(
            'id' => 'fpe-trigger',
            'title' => 'Quick Edit Product', 
            'href' => '#'
        );
        $admin_bar->add_node($args);

    }

    function set_allow_edit(){

        self::$allow_edit = false; 

        $allowed_product_type = apply_filters('wpv_fpe/allowed_product_type', ['simple']);

        $allowed_user_roles = apply_filters('wpv_fpe/allowed_user_roles', ['administrator']);

        // check if current user role is in $allowed_user_roles
        $user = wp_get_current_user();
        $user_roles = (array) $user->roles;
       
        if(is_singular('product') && array_intersect($user_roles, $allowed_user_roles)){
            
            global $post; 
           
            $product = wc_get_product($post); 
            if(in_array($product->get_type(), $allowed_product_type)){
                self::$allow_edit = true;
            } 
        }   
    } 
    
    public function enqueue_scripts(){
        if(!self::$allow_edit){
            return;
        }

        parent::enqueue_scripts();
    }

    public function add_root_element(){
        if(self::$allow_edit){
            parent::add_root_element();
        }
    }
}