<?php
/*
 * This is the child theme for Steelthemes Nest theme, generated with Generate Child Theme plugin by catchthemes.
 *
 * (Please see https://developer.wordpress.org/themes/advanced-topics/child-themes/#how-to-create-a-child-theme)
 */
add_action('wp_enqueue_scripts', 'steelthemes_nest_child_enqueue_styles');
function steelthemes_nest_child_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('parent-style'),
        time()
    );
}
/*
 * Your code goes below
 */


 if (!function_exists('steel_chld_thm_custom_js_script')) {
    function steel_chld_thm_custom_js_script() {

        wp_enqueue_style('al-range-slider-css', get_stylesheet_directory_uri() . '/assets/lib/al-range-slider.css', '', '1.0');
        wp_enqueue_script('al-range-slider-js', get_stylesheet_directory_uri() . '/assets/lib/al-range-slider.js', array('jquery'), '1.0', true);

        wp_enqueue_script( 'jquery-ui-slider' );
        wp_enqueue_script('api-js', '//maps.googleapis.com/maps/api/js?key=AIzaSyAhI86z06sBSA_7xDujhDPi9_AxqEdwL0c&libraries=places', array('jquery'), '1.0', true);

        wp_enqueue_style('custom-css', get_stylesheet_directory_uri() . '/assets/style.css', '', '1.0');
        wp_enqueue_script('filter-custom-js', get_stylesheet_directory_uri() . '/assets/custom.js', array('jquery'), '1.0', true);

        wp_localize_script(
            'filter-custom-js',
            'ft_ajax_object',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'min_geo_radius' => 15,
                'max_geo_radius' => 20,
                'initial_radius' => 15,
                'geo_radius_measure' => 'km',
                'miles' => 'Miles',
                'km' => 'km',
                'steel_min_price' => 1,
                'steel_max_price' => 50,
            )
        );
    }
}
add_action('wp_enqueue_scripts', 'steel_chld_thm_custom_js_script', 100);

// Dokan vendor info list
add_action('cropsie_shop_info', 'dokan_store_name_action');
function dokan_store_name_action() {
    if (function_exists('dokan_get_store_info')) {
        $store_info = dokan_get_store_info(get_the_author_meta('ID'));
        $vendor = dokan()->vendor->get(get_the_author_meta('ID'));
        $store_url = dokan_get_store_url(get_the_author_meta('ID'));
        $store_pic = wp_get_attachment_url($store_info["gravatar"]);
?>
<div class="cropsie-vendor-info">
    <div class="cropsie-vendor-pic">
        <img src="<?php echo $store_pic; ?>" alt="<?php echo $store_info["store_name"]; ?>">
    </div>
    <div class="cropsie-vendor-name">
        <a href="<?php echo $store_url; ?>"><?php echo $store_info["store_name"]; ?></a>
    </div>
</div>
<?php
    }
}


add_shortcode('dokan_vendor_name', 'dokan_store_name_shortcode');
function dokan_store_name_shortcode() {
    ob_start();
    if (function_exists('dokan_get_store_info')) {
        $store_info = dokan_get_store_info(get_the_author_meta('ID'));
        $vendor = dokan()->vendor->get(get_the_author_meta('ID'));
        $store_url = dokan_get_store_url(get_the_author_meta('ID'));
        $store_pic = wp_get_attachment_url($store_info["gravatar"]);
    ?>
<div class="cropsie-vendor-info">
    <div class="cropsie-vendor-pic">
        <img src="<?php echo $store_pic; ?>" alt="<?php echo $store_info["store_name"]; ?>">
    </div>
    <div class="cropsie-vendor-name">
        <a href="<?php echo $store_url; ?>"><?php echo $store_info["store_name"]; ?></a>
    </div>
</div>
<?php
    }
    return ob_get_clean();
}

//Product custom field
add_action('woocommerce_product_options_general_product_data', 'add_custom_product_fields');
function add_custom_product_fields() {
    global $product_object;
    echo '<div class="product_custom_fields">';
    woocommerce_wp_text_input(
        array(
            'id' => 'product_unit_measure',
            'label' => __('Product Measurement unit', 'woocommerce'),
            'placeholder' => '',
            'desc_tip' => 'true',
            'description' => __('Enter the measurement unit here.', 'woocommerce')
        )
    );
    echo '</div>';
}

add_action('woocommerce_process_product_meta', 'save_custom_product_fields');
function save_custom_product_fields($product_id) {
    // Save text field
    $custom_field_text = isset($_POST['product_unit_measure']) ? sanitize_text_field($_POST['product_unit_measure']) : '';
    update_post_meta($product_id, 'product_unit_measure', $custom_field_text);
}

add_filter('woocommerce_product_add_to_cart_text', 'woocommerce_custom_product_add_to_cart_text');
function woocommerce_custom_product_add_to_cart_text() {
    return __('Voeg toe', 'woocommerce');
}

add_filter('woocommerce_product_single_add_to_cart_text', 'woocommerce_custom_single_add_to_cart_text');
function woocommerce_custom_single_add_to_cart_text() {
    return __('Voeg toe', 'woocommerce');
}

function remove_parent_function() {
    remove_action('get_nest_product_card_one', 'nest_product_card_one');
}
add_action('wp_loaded', 'remove_parent_function');

//add_action('publish_post', 'new_action');
add_action('get_nest_product_card_one', 'nest_product_card_one_custom', 99999);

function nest_product_card_one_custom() {
    global $product, $nest_theme_mod;
    $badge_enable = isset($nest_theme_mod['badge_enable']) ? $nest_theme_mod['badge_enable'] : '';
    $add_to_cart_enable_disable = isset($nest_theme_mod['add_to_cart_enable_disable']) ? $nest_theme_mod['add_to_cart_enable_disable'] : '';
    ?>
<div class="product_wrapper customProductLoop product-cart-wrap style_one">
    <?php echo do_action("cropsie_shop_info"); ?>
    <div class="product-img-action-wrap">
        <div class="product-img product-img-zoom">
            <a href="<?php echo esc_url(get_permalink(get_the_id())); ?>">
                <?php echo woocommerce_template_loop_product_thumbnail();; ?>
                <?php do_action('get_nest_hover_product_image'); ?>
            </a>
        </div>
        <?php //do_action('nest_get_product_action_button'); 
            ?>
    </div>
    <div class="product-content-wrap">
        <?php do_action('get_nest_brand_title'); ?>
        <h2><a href="<?php echo esc_url(get_permalink(get_the_id())); ?>"><?php the_title(); ?></a></h2>


        <div class="product-card-bottom">
            <div class="product-price">
                <p class="productLoopUnit">Per stuk</p>

                <?php
                    $raw_price = explode(".", $product->price);
                    $int = $raw_price[0];
                    $decimals = isset($raw_price[1]) ? $raw_price[1] : 0;
                    // Edit the output.

                    $formatted_price = '<div class="wooloopprice">' . $int . ',' . '<sup>' . str_pad($decimals, 2, '0', STR_PAD_RIGHT) . '</sup></div>';
                    echo $formatted_price;
                    ?>
            </div>
            <?php if ($add_to_cart_enable_disable == true): ?>
            <div class="add-cart">
                <?php do_action('getsbw_wc_add_buy_now_button_single'); ?>
                <?php woocommerce_template_loop_add_to_cart(); ?>
                <a href="<?php the_permalink(); ?>" class="productLoopUrl">Meer info</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php
}

// add_filter('formatted_woocommerce_price', 'custom_formatted_woocommerce_price', 10, 5);
function custom_formatted_woocommerce_price($formatted_price, $price, $decimal_number, $separator, $thousand_separator) {
    // Gets the integer and decimal part of the product price.
    $raw_price = explode(".", $price);
    $int       = $raw_price[0];
    $decimals  = isset($raw_price[1]) ? $raw_price[1] : 0;
    // Edit the output.

    $formatted_price = '' . $int . $separator . '<sup>' . str_pad($decimals, $decimal_number, '0', STR_PAD_RIGHT) . '</sup>';
    return $formatted_price;
}
// elementor dynamic tags

function register_cropsie_elementor_tags($dynamic_tags_manager) {

    require_once(__DIR__ . '/dynamic-tags/store_infos.php');

    $dynamic_tags_manager->register(new \Elementor_Cropsie_Store_Infos);
}
add_action('elementor/dynamic_tags/register', 'register_cropsie_elementor_tags');


function register_cropsie_widget($widgets_manager) {

    require_once(__DIR__ . '/widgets/user_navigations.php');


    $widgets_manager->register(new \Elementor_user_navigation_Widget());
}
add_action('elementor/widgets/register', 'register_cropsie_widget');

require_once(__DIR__ . '/widgets/shop_sidebar.php');


add_action( 'wp_ajax_nopriv_steel_trigger_filter', 'steel_search_result_ajax_sidebar' );
add_action( 'wp_ajax_steel_trigger_filter', 'steel_search_result_ajax_sidebar' );

function steel_search_result_ajax_sidebar(){

    $geolocation_lat   = !empty($_POST['lat']) ? $_POST['lat'] : '';
    $geolocation_long   = !empty($_POST['long']) ? $_POST['long'] : '';
    $radiuss = !empty($_POST['radius']) ? floatval($_POST['radius']) : 20;
    $order_by = !empty($_POST['orderby']) ? $_POST['orderby'] : 'ASC';
    $min_price = !empty($_POST['startprice']) ? $_POST['startprice'] : 0;
    $max_price = !empty($_POST['endprice']) ? $_POST['endprice'] : 50;

    $category   = !empty($_POST['filters']) ? (array)$_POST['filters'] : [];

    function get_filtered_products($latitude, $longitude, $distance, $min_price, $max_price, $order_by, $category = []) {
        global $wpdb;
    
        // Ensure category is an array
        $category = is_array($category) ? $category : [$category];
    
        // Prepare taxonomy filters
        $tax_query = '';
        $tax_query_placeholders = [];
        if (!empty($category)) {
            $category_placeholders = implode(',', array_fill(0, count($category), '%d'));
            $tax_query .= "
                INNER JOIN {$wpdb->term_relationships} tr_category ON p.ID = tr_category.object_id
                INNER JOIN {$wpdb->term_taxonomy} tt_category ON tr_category.term_taxonomy_id = tt_category.term_taxonomy_id
                INNER JOIN {$wpdb->terms} t_category ON tt_category.term_id = t_category.term_id AND tt_category.taxonomy = 'product_cat' AND t_category.term_id IN ($category_placeholders)
            ";
            $tax_query_placeholders = array_merge($tax_query_placeholders, $category);
        }
    
        // Determine the ORDER BY clause based on the order_by parameter
        if ($order_by == 'low') {
            $order_clause = "CAST(pm_price.meta_value AS DECIMAL(20, 10)) ASC";
        } elseif ($order_by == 'high') {
            $order_clause = "CAST(pm_price.meta_value AS DECIMAL(20, 10)) DESC";
        } else {
            // Default to ordering by post date if neither 'low' nor 'high'
            $order_clause = "p.post_date $order_by";
        }
    
        // Query to fetch products based on geolocation, price, and category filters
        $query = $wpdb->prepare(
            "
            SELECT DISTINCT p.ID, p.post_title, pm_lat.meta_value as lat, pm_lng.meta_value as lng,
            (3959 * acos(
                cos( radians(%s) )
                * cos( radians( pm_lat.meta_value ) )
                * cos( radians( pm_lng.meta_value ) - radians(%s) )
                + sin( radians(%s) )
                * sin( radians( pm_lat.meta_value ) )
            )) AS distance
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm_lat ON p.ID = pm_lat.post_id
            INNER JOIN {$wpdb->postmeta} pm_lng ON p.ID = pm_lng.post_id
            INNER JOIN {$wpdb->postmeta} pm_price ON p.ID = pm_price.post_id AND pm_price.meta_key = '_price'
            $tax_query
            WHERE p.post_status = 'publish'
            AND p.post_type = 'product'
            AND pm_lat.meta_key = 'dokan_geo_latitude'
            AND pm_lng.meta_key = 'dokan_geo_longitude'
            AND CAST(pm_price.meta_value AS DECIMAL(20, 10)) BETWEEN %f AND %f
            HAVING distance < %d
            ORDER BY $order_clause
            ",
            array_merge(
                [$latitude, $longitude, $latitude],
                $tax_query_placeholders,
                [$min_price, $max_price, $distance]
            )
        );
    
        // Execute the query
        $products = $wpdb->get_results($query);
    
        // Return the filtered products
        return $products;
    }
    
    $products = get_filtered_products($geolocation_lat, $geolocation_long, $radiuss, $min_price, $max_price, $order_by, $category);
    
    $in_products = [];
    if(!empty($products)){
        foreach($products as $product){
            $in_products[] = $product->ID;
        }
    }
    if(!empty($in_products)){
        // Set up a custom WP_Query with the product IDs
        $args = [
            'post_type' => 'product',
            'post__in' => $in_products,
            'posts_per_page' => -1
        ];
        
        $query = new WP_Query($args);

        ob_start(); // Start output buffering to capture the HTML output

        if ($query->have_posts()) {
            woocommerce_product_loop_start(); // Begin the WooCommerce product loop structure

            while ($query->have_posts()) {
                $query->the_post();

                /**
                 * Hook: woocommerce_shop_loop.
                 */
                do_action('woocommerce_shop_loop');

                wc_get_template_part('content', 'product'); // Load WooCommerce product template part
            }

            woocommerce_product_loop_end(); // End the WooCommerce product loop structure
        } 

        wp_reset_postdata(); // Reset the post data

        $products_html = ob_get_clean(); // Get the buffered HTML conten

    }else {
        $products_html = '<p>No products found</p>';
    }

    wp_send_json_success(['html' => $products_html]); // Send the HTML back to the frontend
    wp_die();
}