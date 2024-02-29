<?php

namespace WPV_FPE;

/**
 * Helper class
 */
class Strings
{
    /**
     * Get all strings
     * @return array
     */
    public static function get_strings()
    {
        $locale        = localeconv();
		$decimal_point = isset( $locale['decimal_point'] ) ? $locale['decimal_point'] : '.';
		$decimal       = ( ! empty( wc_get_price_decimal_separator() ) ) ? wc_get_price_decimal_separator() : $decimal_point;

        $strings = [
            'update_product' => __('Update Product', 'fpe-woo'),
            'section_title_general' => __('General', 'fpe-woo'),
            'section_title_price' => __('Price', 'fpe-woo'),
            'section_title_inventory' => __('Inventory', 'fpe-woo'),
            'section_title_shipping' => __('Shipping', 'fpe-woo'),
            'section_title_linked_products' => __('Linked Products', 'fpe-woo'),
            'section_title_downloads' => __('Downloads', 'fpe-woo'),
            'section_title_attributes' => __('Attributes', 'fpe-woo'),

            'label_product_name'    =>  __('Product Name', 'fpe-woo'),
            'label_catalog_visibility'    =>  __('Catalog Visibility', 'fpe-woo'),
            'label_is_featured'    =>  __('Is Featured', 'fpe-woo'),
            'label_category'    =>  __('Category', 'fpe-woo'),
            'label_tags'    =>  __('Tags', 'fpe-woo'),
            'placeholder_input' =>  __('Please input', 'fpe-woo'),

            'label_regular_price'    =>  __( 'Regular price', 'fpe-woo' ) . ' (' . get_woocommerce_currency_symbol() . ')',
            'label_sale_price'    =>  __( 'Sale price', 'fpe-woo' ) . ' (' . get_woocommerce_currency_symbol() . ')',
            'label_schedule_sale'    =>  __('Schedule sale', 'fpe-woo'),

            'placeholder_start_date'    =>  __('Start date', 'fpe-woo'),
            'placeholder_end_date'    =>  __('End date', 'fpe-woo'),

            'label_tax_status'    =>  __('Tax status', 'fpe-woo'),
            'label_tax_class'    =>  __('Tax class', 'fpe-woo'),

            'label_sku'    =>  __('SKU', 'fpe-woo'),
            'label_manage_stocks'    =>  __('Manage stocks', 'fpe-woo'),
            'label_stock_status'    =>  __('Stock status', 'fpe-woo'),
            'label_stock_quantity'    =>  __('Stock quantity', 'fpe-woo'),
            'label_allow_backorders'    =>  __('Allow backorders', 'fpe-woo'),
            'label_low_stock_threshold'    =>  __('Low stock threshold', 'fpe-woo'),
            'label_description' => __('Description', 'fpe-woo'),
            'label_short_description' => __('Short description', 'fpe-woo'),
            'label_upload_image' => __('Product Image', 'fpe-woo'),
            'label_upload_gallery_images' => __('Product Gallery', 'fpe-woo'),

            'label_upsell_products' => __('Upsell Products', 'fpe-woo'),
            'label_crosssell_products' => __('Cross-sell Products', 'fpe-woo'),
            'label_grouped_products' => __('Grouped Products', 'fpe-woo'),
            'label_weight'    =>  sprintf(
                /* translators: %s: Weight unit */
                __( 'Weight (%s)', 'woocommerce' ),
                get_option( 'woocommerce_weight_unit', 'kg' ) 
            ),

            'label_dimensions'  =>  sprintf(
                /* translators: WooCommerce dimension unit */
                esc_html__( 'Dimensions (%s)', 'woocommerce' ),
                ( get_option( 'woocommerce_dimension_unit' )  )
            ),

            'label_shipping_class' => __('Shipping class', 'fpe-woo'),

            'label_downloadable_files' => __('Downloadable files', 'fpe-woo'),
            'label_download_limit' => __('Download limit', 'fpe-woo'),
            'label_download_expiry' => __('Download expiry', 'fpe-woo'),

            'label_product_url' => __('Product URL', 'fpe-woo'),
            'label_button_text' => __('Button text', 'fpe-woo'),

            'label_sold_individually' => __('Sold individually', 'fpe-woo'),

            'update_button' =>  __('Update', 'fpe-woo'),
            'cancel_button' =>  __('Cancel', 'fpe-woo'), 

            'tooltip_manage_stocks' =>  __('Enabled stock management at product level', 'fpe-woo' ),
            'tooltip_dimensions'    =>  __('LxWxH in decimal form', 'fpe-woo'),

            'validation_error_decimal'  =>  sprintf( __( 'Please enter a value with one decimal point (%s) without thousand separators.', 'fpe-woo' ), $decimal ),
            'validation_error_sale_price_higher' => __('Please enter in a value less than the regular price', 'fpe-woo')

        ];

        $strings = apply_filters('wpv_fpe_strings', $strings);

        return $strings;
    }
}
