<?php
/*
**==============================   
**  Nest_get_star_rating
**==============================
*/
add_action('get_nest_get_star_rating', 'nest_get_star_rating');
function nest_get_star_rating() {
    global $woocommerce, $product,  $nest_theme_mod;
    $rating_enable = isset($nest_theme_mod['rating_enable']) ? $nest_theme_mod['rating_enable'] : '';
    if ($rating_enable == false):
        return false;
    endif;
    $average = $product->get_average_rating();
    $ratingcount = $product->get_review_count();
    echo '<div class="product-rate-cover">
    <div class="product-rate d-inline-block"><span class="star-rating"><span style="width:' . ((esc_attr($average) / 5) * 100) . '%"><strong  class="rating">' . esc_attr($average) . '</strong> ' . __('out of 5', 'steelthemes-nest') . '</span></span> <span class="ml-5 font-small text-muted"> ' . esc_attr($ratingcount) . '</span></div></div>';
}
/*
**==============================   
** nest Product Short Description
**==============================
*/
add_action('nest_get_show_excerpt_shop_page', 'nest_show_excerpt_shop_page', 5);
function nest_show_excerpt_shop_page() {
    global $product;
    echo get_the_excerpt($product->get_id());
}
/*
* ==============================   
**   Product store name
**==============================
*/
add_action('get_nest_product_store_name', 'nest_product_store_name');
function nest_product_store_name() {
    global $product, $nest_theme_mod;
    $vendor_name_enable = isset($nest_theme_mod['vendor_name_enable']) ? $nest_theme_mod['vendor_name_enable'] : '';
    if ($vendor_name_enable == false):
        return false;
    endif;
    $porduct_store_name = get_post_meta(get_the_ID(), 'porduct_store_name', true);
    $porduct_store_link = get_post_meta(get_the_ID(), 'porduct_store_link', true);
    if (!empty($porduct_store_name)): ?>
        <div class="vendord">
            <span class="font-small text-muted"><?php echo esc_html__('By', 'steelthemes-nest') ?>
                <a href="<?php if (!empty($porduct_store_link)): ?><?php echo esc_attr($porduct_store_link);
                                                                else: echo esc_html('#', 'steelthemes-nest');
                                                                endif; ?>"
                    target="_blank">
                    <?php echo esc_attr($porduct_store_name); ?>
                </a></span>
        </div>
        <?php
    endif;
}
/*
* ==============================   
**   Product Brand
**==============================
*/
add_action('get_nest_brand_title', 'nest_get_brand_title');
function nest_get_brand_title() {
    global $product, $nest_theme_mod;
    $brand_enable = isset($nest_theme_mod['brand_enable']) ? $nest_theme_mod['brand_enable'] : '';
    $brand_type = isset($nest_theme_mod['brand_type']) ? $nest_theme_mod['brand_type'] : '';
    if ($brand_enable == false) {
        return false;
    }
    $product_id = get_the_ID(); // Assuming you're inside the product loop
    $brand_terms = get_the_terms($product_id, 'brand');
    if ($brand_type == 'image') {
        if (! is_wp_error($brand_terms) && ! empty($brand_terms)) {
            $brand_term = array_shift($brand_terms);
            $brand_name = $brand_term->name;
            $brand_image = get_term_meta($brand_term->term_id, 'brand_image', true);
            if ($brand_image) {  ?>
                <div class="br_image">
                    <img src="<?php echo esc_url($brand_image); ?>" alt="<?php echo esc_attr($brand_name); ?>">
                </div>
            <?php
            }
        }
    } else {
        if (! is_wp_error($brand_terms) && ! empty($brand_terms)) {
            $brand_term = array_shift($brand_terms);
            $brand_name = $brand_term->name;
            $brand_link = get_term_link($brand_term);
            ?>
            <div class="product-category">
                <div class="pro_cat">
                    <a href="<?php echo esc_url($brand_link); ?>">
                        <?php echo esc_attr($brand_name); ?>
                    </a>
                </div>
            </div>
        <?php
        }
    }
}
/*
* ==============================   
**   Product Hover Image 
**==============================
*/
add_action('get_nest_hover_product_image', 'nest_hover_product_image');
function nest_hover_product_image() {
    global $product;
    $product_hover_images =   get_post_meta(get_the_ID(), 'hover_product_image', true);
    $product_hover_images =   get_post_meta(get_the_ID(), 'hover_product_image', true);
    $porduct_store_name = get_post_meta(get_the_ID(), 'porduct_store_name', true);
    $porduct_store_link = get_post_meta(get_the_ID(), 'porduct_store_link', true);
    if (is_array($product_hover_images) || is_object($product_hover_images)):
        if (!empty($product_hover_images['url'])):
        ?>
            <img src="<?php echo esc_url($product_hover_images['url']); ?>" class="hover-img attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="<?php the_title(); ?>">
    <?php
        endif;
    endif;
}
/*
* ==============================   
**   Product Card Style One
**==============================
*/
add_action('get_nest_product_card_one', 'nest_product_card_one');
function nest_product_card_one() {
    global $product, $nest_theme_mod;
    $badge_enable = isset($nest_theme_mod['badge_enable']) ? $nest_theme_mod['badge_enable'] : '';
    $add_to_cart_enable_disable = isset($nest_theme_mod['add_to_cart_enable_disable']) ? $nest_theme_mod['add_to_cart_enable_disable'] : '';
    ?>
    <div class="product_wrapper product-cart-wrap boda style_one">
        <div class="product-img-action-wrap">
            <div class="product-img product-img-zoom">
                <a href="<?php echo esc_url(get_permalink(get_the_id())); ?>">
                    <?php echo woocommerce_template_loop_product_thumbnail();; ?>
                    <?php do_action('get_nest_hover_product_image'); ?>
                </a>
            </div>
            <?php do_action('nest_get_product_action_button'); ?>
            <?php if ($badge_enable == true): ?>
                <div class="product-badges product-badges-position product-badges-mrg">
                    <?php do_action('get_nest_sales_badges'); ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="product-content-wrap">
            <?php do_action('get_nest_current_product_category'); ?>
            <?php do_action('get_nest_brand_title'); ?>
            <h2><a href="<?php echo esc_url(get_permalink(get_the_id())); ?>"><?php the_title(); ?></a></h2>
            <?php do_action('get_nest_get_star_rating'); ?>
            <?php do_action('get_nest_product_store_name') ?>
            <div class="product-card-bottom">
                <div class="product-price">
                    <?php woocommerce_template_loop_price(); ?>
                </div>
                <?php if ($add_to_cart_enable_disable == true): ?>
                    <div class="add-cart">
                        <?php do_action('getsbw_wc_add_buy_now_button_single'); ?>
                        <?php woocommerce_template_loop_add_to_cart(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php
}
/*
* ==============================   
**   Product Card Style One
**==============================
*/
add_action('get_nest_product_card_onesold', 'nest_product_card_one_sold');
function nest_product_card_one_sold() {
    global $product, $nest_theme_mod;
    $badge_enable = isset($nest_theme_mod['badge_enable']) ? $nest_theme_mod['badge_enable'] : '';
    $add_to_cart_enable_disable = isset($nest_theme_mod['add_to_cart_enable_disable']) ? $nest_theme_mod['add_to_cart_enable_disable'] : '';
?>
    <div class="product_wrapper product-cart-wrap bal style_one">
        <div class="product-img-action-wrap">
            <div class="product-img product-img-zoom">
                <a href="<?php echo esc_url(get_permalink(get_the_id())); ?>">
                    <?php echo woocommerce_template_loop_product_thumbnail();; ?>
                    <?php do_action('get_nest_hover_product_image'); ?>
                </a>
            </div>
            <?php do_action('nest_get_product_action_button'); ?>
            <?php if ($badge_enable == true): ?>
                <div class="product-badges product-badges-position product-badges-mrg">
                    <?php do_action('get_nest_sales_badges'); ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="product-content-wrap">
            <?php do_action('get_nest_current_product_category'); ?>
            <?php do_action('get_nest_brand_title'); ?>
            <h2><a href="<?php echo esc_url(get_permalink(get_the_id())); ?>"><?php the_title(); ?></a></h2>
            <?php do_action('get_nest_get_star_rating'); ?>
            <?php do_action('get_nest_product_store_name') ?>
            <?php do_action('get_nest_shop_product_sold_count'); ?>
            <div class="product-card-bottom">
                <div class="product-price">
                    <?php woocommerce_template_loop_price(); ?>
                </div>
                <?php if ($add_to_cart_enable_disable == true): ?>
                    <div class="add-cart">
                        <?php woocommerce_template_loop_add_to_cart(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php
}
/*
* ==============================   
**   Product Card Style One
**==============================
*/
add_action('get_nest_product_card_two', 'nest_product_card_two');
function nest_product_card_two() {
    global $product, $nest_theme_mod;
    $badge_enable = isset($nest_theme_mod['badge_enable']) ? $nest_theme_mod['badge_enable'] : '';
    $add_to_cart_enable_disable = isset($nest_theme_mod['add_to_cart_enable_disable']) ? $nest_theme_mod['add_to_cart_enable_disable'] : '';
?>
    <div class="product_wrapper style2 product-cart-wrap style_two">
        <div class="product-img-action-wrap">
            <div class="product-img product-img-zoom">
                <a href="<?php echo esc_url(get_permalink(get_the_id())); ?>">
                    <?php echo woocommerce_template_loop_product_thumbnail();; ?>
                    <?php do_action('get_nest_hover_product_image'); ?>
                </a>
            </div>
            <?php do_action('nest_get_product_action_button'); ?>
            <?php if ($badge_enable == true): ?>
                <div class="product-badges product-badges-position product-badges-mrg">
                    <?php do_action('get_nest_sales_badges'); ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="product-content-wrap">
            <?php do_action('get_nest_current_product_category'); ?>
            <?php do_action('get_nest_brand_title'); ?>
            <h2><a href="<?php echo esc_url(get_permalink(get_the_id())); ?>"><?php the_title(); ?></a></h2>
            <?php do_action('get_nest_get_star_rating'); ?>
            <div class="mt-10 product-price">
                <?php woocommerce_template_loop_price(); ?>
            </div>
            <?php do_action('get_nest_shop_product_sold_count'); ?>
            <?php if ($add_to_cart_enable_disable == true): ?>
                <div class="mt-10 position-relative">
                    <?php woocommerce_template_loop_add_to_cart(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php
}
/*
* ==============================   
**   Product Card Style Three
**==============================
*/
add_action('get_nest_product_card_three', 'nest_product_card_three');
function nest_product_card_three() {
    global $product, $nest_theme_mod;
    $add_to_cart_enable_disable = isset($nest_theme_mod['add_to_cart_enable_disable']) ? $nest_theme_mod['add_to_cart_enable_disable'] : '';
?>
    <div class="product-list-small style3 product_wrapper style_three_list animated">
        <article class="d-flex align-items-center hover-up">
            <div class="mb-0 col-md-4 pr-15">
                <a href="<?php echo esc_url(get_permalink(get_the_id())); ?>">
                    <?php echo woocommerce_template_loop_product_thumbnail();; ?>
                </a>
            </div>
            <div class="mb-0 col-md-8">
                <h6 class="pro_title"><a
                        href="<?php echo esc_url(get_permalink(get_the_id())); ?>"><?php the_title(); ?></a></h6>
                <?php do_action('get_nest_get_star_rating'); ?>
                <div class="product-price">
                    <?php woocommerce_template_loop_price(); ?>
                </div>
            </div>
        </article>
    </div>
<?php
}
/*
* ==============================   
**   Product Card Style four
**==============================
*/
add_action('get_nest_product_card_four', 'nest_product_card_four');
function nest_product_card_four() {
    global $product, $nest_theme_mod;
    $badge_enable = isset($nest_theme_mod['badge_enable']) ? $nest_theme_mod['badge_enable'] : '';
    $add_to_cart_enable_disable = isset($nest_theme_mod['add_to_cart_enable_disable']) ? $nest_theme_mod['add_to_cart_enable_disable'] : '';
    $myexcerpt = wp_trim_words(get_the_excerpt());
?>
    <div class="product-list">
        <div class="product-cart-wrap stylelist product_wrapper product_list_type">
            <div class="product-img-action-wrap">
                <div class="product-img product-img-zoom">
                    <div class="product-img-inner">
                        <a href="<?php echo esc_url(get_permalink(get_the_id())); ?>">
                            <?php echo woocommerce_template_loop_product_thumbnail();; ?>
                            <?php do_action('get_nest_hover_product_image') ?>
                        </a>
                    </div>
                </div>
                <?php do_action('nest_get_product_action_button'); ?>
                <?php if ($badge_enable == true): ?>
                    <div class="product-badges product-badges-position product-badges-mrg">
                        <?php do_action('get_nest_sales_badges'); ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="product-content-wrap">
                <?php do_action('get_nest_current_product_category'); ?>
                <?php do_action('get_nest_brand_title'); ?>
                <h2><a href="<?php echo esc_url(get_permalink(get_the_id())); ?>"><?php the_title(); ?></a></h2>
                <?php do_action('get_nest_get_star_rating'); ?>
                <div class="mb-20 mt-15">
                    <?php echo esc_attr($myexcerpt); ?>
                </div>
                <div class="product-price">
                    <?php woocommerce_template_loop_price(); ?>
                </div>
                <?php if ($add_to_cart_enable_disable == true): ?>
                    <div class="mt-30 align-items-center d-inline-block position-relative">
                        <?php woocommerce_template_loop_add_to_cart(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php
}
/*
* ==============================   
**   Product Card Style Five
**==============================
*/
add_action('get_nest_product_card_five', 'nest_product_card_five');
function nest_product_card_five() {
    global $product, $nest_theme_mod;
    $badge_enable = isset($nest_theme_mod['badge_enable']) ? $nest_theme_mod['badge_enable'] : '';
    $add_to_cart_enable_disable = isset($nest_theme_mod['add_to_cart_enable_disable']) ? $nest_theme_mod['add_to_cart_enable_disable'] : '';
?>
    <div class="product_style_five product-cart-wrap styl5 product_wrapper">
        <div class="product-img-inner">
            <a href="<?php echo esc_url(get_permalink(get_the_id())); ?>">
                <?php echo woocommerce_template_loop_product_thumbnail();; ?>
                <?php do_action('get_nest_hover_product_image') ?>
            </a>
            <?php if ($badge_enable == true): ?>
                <div class="product-badges">
                    <?php do_action('get_nest_sales_badges'); ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="product-content-wrap">
            <?php do_action('get_nest_get_star_rating'); ?>
            <h5><a href="<?php echo esc_url(get_permalink(get_the_id())); ?>"><?php the_title(); ?></a></h5>
            <div class="product-price">
                <?php woocommerce_template_loop_price(); ?>
            </div>
            <div class="mt-10 align-items-center d-inline-block position-relative">
                <?php if ($add_to_cart_enable_disable == true): ?>
                    <?php woocommerce_template_loop_add_to_cart(); ?>
                <?php endif; ?>
                <?php do_action('nest_get_product_action_button'); ?>
            </div>
        </div>
    </div>
<?php
}
/*
* ==========================================   
**   woocommerce single product card two
**=========================================
*/
function nest_product_single_archive_card_one() {
    global $product, $nest_theme_mod;
    $badge_enable = isset($nest_theme_mod['badge_enable']) ? $nest_theme_mod['badge_enable'] : '';
    $add_to_cart_enable_disable = isset($nest_theme_mod['add_to_cart_enable_disable']) ? $nest_theme_mod['add_to_cart_enable_disable'] : '';
?>
    <div class="product_singleized product_wrapper dhon product-cart-wrap style_one woocommerce-product-gallery--with-images images">
        <figure class="woocommerce-product-gallery__wrapper position-relative"><a class="obsulink" href="<?php echo esc_url(get_permalink(get_the_id())); ?>"> </a>
            <?php $post_thumbnail_id = $product->get_image_id();
            if ($post_thumbnail_id) {
                $html = wc_get_gallery_image_html($post_thumbnail_id, true);
            } else {
                $html  = '<div class="woocommerce-product-gallery__image--placeholder">';
                $html .= sprintf('<img src="%s" alt="%s" class="wp-post-image" />', esc_url(wc_placeholder_img_src('woocommerce_single')), esc_html__('Awaiting product image', 'steelthemes-nest'));
                $html .= '</div>';
            }
            echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
            ?>
            <?php if ($badge_enable == true): ?>
                <div class="product-badges">
                    <?php do_action('get_nest_sales_badges'); ?>
                </div>
            <?php endif; ?>
            <?php do_action('nest_get_product_action_button'); ?>
        </figure>
        <div class="content_mained product-content-wrap">
            <?php do_action('get_nest_get_star_rating'); ?>
            <h2><a href="<?php echo esc_url(get_permalink(get_the_id())); ?>"><?php the_title(); ?></a></h2>
            <div class="product-price">
                <?php woocommerce_template_single_price(); ?>
            </div>
            <div class="on_hover">
                <div class="carted">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>
            </div>
        </div>
    </div>
<?php
}
add_action('get_nest_product_single_card_one', 'nest_product_single_archive_card_one', 15, 9);
/*
* ==========================================   
**   woocommerce single product card two
**=========================================
*/
function nest_product_single_archive_card_two() {
    global $product;
?>
    <div class="product_singleized product_wrapper style33 style_two woocommerce-product-gallery--with-images images">
        <figure class="woocommerce-product-gallery__wrapper position-relative"><a class="obsulink" href="<?php echo esc_url(get_permalink(get_the_id())); ?>"></a>
            <?php $post_thumbnail_id = $product->get_image_id();
            if ($post_thumbnail_id) {
                $html = wc_get_gallery_image_html($post_thumbnail_id, true);
            } else {
                $html  = '<div class="woocommerce-product-gallery__image--placeholder">';
                $html .= sprintf('<img src="%s" alt="%s" class="wp-post-image" />', esc_url(wc_placeholder_img_src('woocommerce_single')), esc_html__('Awaiting product image', 'steelthemes-nest'));
                $html .= '</div>';
            }
            echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
            ?>
        </figure>
        <div class="content_mained">
            <?php do_action('nest_get_product_action_button'); ?>
            <?php do_action('get_nest_get_star_rating'); ?>
            <h2><a href="<?php echo esc_url(get_permalink(get_the_id())); ?>"><?php the_title(); ?></a></h2>
            <?php do_action('get_nest_current_product_category'); ?>
            <?php do_action('get_nest_brand_title'); ?>
            <?php woocommerce_template_single_price(); ?>
            <?php if ($add_to_cart_enable_disable == true): ?>
                <div class="carted">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php
}
add_action('get_nest_product_single_card_two', 'nest_product_single_archive_card_two');
//====================== card style lsit =================================
add_action('get_nest_product_card_list', 'nest_product_card_list');
function nest_product_card_list() {
    global $product, $nest_theme_mod;
    $badge_enable = isset($nest_theme_mod['badge_enable']) ? $nest_theme_mod['badge_enable'] : '';
    $add_to_cart_enable_disable = isset($nest_theme_mod['add_to_cart_enable_disable']) ? $nest_theme_mod['add_to_cart_enable_disable'] : '';
    $short_description_enable = isset($nest_theme_mod['short_description_enable']) ? $nest_theme_mod['short_description_enable'] : '';
?>
    <div class="product_wrapper char product-cart-wrap style_one style_list">
        <div class="d-flex align-items-center">
            <div class="product-img-action-wrap woocommerce-product-gallery--with-images images">
                <figure class="woocommerce-product-gallery__wrapper position-relative"><a class="obsulink" href="<?php echo esc_url(get_permalink(get_the_id())); ?>"></a>
                    <?php $post_thumbnail_id = $product->get_image_id();
                    if ($post_thumbnail_id) {
                        $html = wc_get_gallery_image_html($post_thumbnail_id, true);
                    } else {
                        $html  = '<div class="woocommerce-product-gallery__image--placeholder">';
                        $html .= sprintf('<img src="%s" alt="%s" class="wp-post-image" />', esc_url(wc_placeholder_img_src('woocommerce_single')), esc_html__('Awaiting product image', 'steelthemes-nest'));
                        $html .= '</div>';
                    }
                    echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
                    ?>
                </figure>
                <?php if ($badge_enable == true): ?>
                    <div class="product-badges product-badges-position product-badges-mrg">
                        <?php do_action('get_nest_sales_badges'); ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="product-content-wrap">
                <?php do_action('get_nest_current_product_category'); ?>
                <?php do_action('get_nest_brand_title'); ?>
                <h2><a href="<?php echo esc_url(get_permalink(get_the_id())); ?>"><?php the_title(); ?></a></h2>
                <?php do_action('get_nest_get_star_rating'); ?>
                <?php do_action('get_nest_product_store_name') ?>
                <?php do_action('get_nest_shop_product_sold_count'); ?>
                <div class="product-card-bottom d-block">
                    <div class="product-price">
                        <?php woocommerce_template_loop_price(); ?>
                    </div>
                    <?php if ($add_to_cart_enable_disable == true): ?>
                        <div class="add-cart">
                            <?php woocommerce_template_single_add_to_cart(); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php do_action('nest_get_product_action_button'); ?>
            </div>
        </div>
        <?php if ($short_description_enable == true): ?>
            <div class="list-features down">
                <?php do_action('nest_get_show_excerpt_shop_page'); ?>
            </div>
        <?php endif; ?>
    </div>
<?php
}
/*
* ==============================   
**   Product Card Style One
**==============================
*/
add_action('get_nest_product_card_six', 'nest_product_card_six');
function nest_product_card_six() {
    global $product, $nest_theme_mod;
    $badge_enable = isset($nest_theme_mod['badge_enable']) ? $nest_theme_mod['badge_enable'] : '';
    $add_to_cart_enable_disable = isset($nest_theme_mod['add_to_cart_enable_disable']) ? $nest_theme_mod['add_to_cart_enable_disable'] : '';
    $short_description_enable = isset($nest_theme_mod['short_description_enable']) ? $nest_theme_mod['short_description_enable'] : '';
?>
    <div class="product_wrapper style6 product-cart-wrap style_six">
        <div class="product-img-action-wrap">
            <div class="product-img product-img-zoom">
                <a href="<?php echo esc_url(get_permalink(get_the_id())); ?>">
                    <?php echo woocommerce_template_loop_product_thumbnail();; ?>
                    <?php do_action('get_nest_hover_product_image'); ?>
                </a>
            </div>
            <?php do_action('nest_get_product_action_button'); ?>
            <?php if ($badge_enable == true): ?>
                <div class="product-badges product-badges-position product-badges-mrg">
                    <?php do_action('get_nest_sales_badges'); ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="product-content-wrap">
            <?php do_action('get_nest_get_star_rating'); ?>
            <h2><a href="<?php echo esc_url(get_permalink(get_the_id())); ?>"><?php the_title(); ?></a></h2>
            <?php do_action('get_nest_current_product_category'); ?>
            <?php do_action('get_nest_brand_title'); ?>
            <?php do_action('get_nest_shop_product_sold_count'); ?>
            <div class="position-relative">
                <div class="mb-10 product-price">
                    <?php woocommerce_template_loop_price(); ?>
                </div>
                <?php if ($add_to_cart_enable_disable == true): ?>
                    <?php woocommerce_template_loop_add_to_cart(); ?>
                <?php endif; ?>
            </div>
            <?php if ($short_description_enable == true): ?>
                <div class="space_div"></div>
                <div class="list-features-six">
                    <?php do_action('nest_get_show_excerpt_shop_page'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php
}
