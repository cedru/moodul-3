<?php

namespace WPV_FPE;

/**
 * Helper class
 */
class Helper{

	/**
	 * Get all product categories
	 * @param int $parent_id
	 * @return array
	 */
	public static function get_product_categories( $parent_id = 0 ) {
		$categories = get_terms( [
			'taxonomy' => 'product_cat',
			'hide_empty' => false,
			'parent' => $parent_id,
		] );
	
		$result = [];
	
		foreach ( $categories as $category ) {
	
			$data = [
				'id' => $category->term_id,
				'label' => $category->name
			];
	
			$children = self::get_product_categories( $category->term_id );
	
			if ( $children ) {
				$data['children'] = $children;
			}
	
			
			$result[] = $data;
		}
	
		return $result;
	}

	/**
	 * Get all shipping classes
	 * @return array
	 */
	public static function get_shipping_classes(){
		
		// Initialize with No option value & label
		$classes[''] = 'No Shipping Class';
		$shipping_classes = get_terms( [
			'taxonomy' => 'product_shipping_class',
			'hide_empty'	=>	false
		] );
		
		foreach ( $shipping_classes as $shipping_class ) {
			$classes[$shipping_class->term_id] = $shipping_class->name;
		}
		
		return $classes;
	}

	/**
	 * Get array of product tag slugs
	 * @param array $tag_ids
	 * @return array
	 */
	public static function get_product_tag_slugs($tag_ids = []){
		
		$slugs = [];

		if(!is_array($tag_ids) || count($tag_ids) === 0){
			return $slugs;
		}
		
		// get product tags
		$tags = get_terms( [
			'taxonomy' => 'product_tag',
			'hide_empty' => false,
			'include' => $tag_ids
		] );

		foreach($tags as $tag){
			$slug[] = $tag->slug;
		}

		return $slug;
	}


	/**
	 * Get attribute taxonomies
	 * @return array
	 */
	public static function get_attribute_taxonomies(){

        $taxonomies = wc_get_attribute_taxonomies();
        $attributes = [];

        foreach($taxonomies as $taxonomy){
            $attributes[] = [
                'id' => $taxonomy->attribute_id,
                'name' => $taxonomy->attribute_name,
                'label' => $taxonomy->attribute_label ? $taxonomy->attribute_label : $taxonomy->attribute_name,
                'name_with_prefix' => wc_attribute_taxonomy_name($taxonomy->attribute_name),
                'slug' => $taxonomy->attribute_label,
                'type' => $taxonomy->attribute_type,
                'order_by' => $taxonomy->attribute_orderby,
                'has_archives' => $taxonomy->attribute_public,
            ];
        }

        return $attributes;
    }
}