<?php
class Sidebar_Filter_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'sidebar_filter_widget',
            __( 'Sidebar Filter Widget', 'my-child-theme' ),
            array( 'description' => __( 'A custom widget for displaying information.', 'my-child-theme' ) )
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        ?>
            <div class="geo-locations-search-box">
                <div class="geo-location-search">
                    <label for="">Locatie</label>
                    <div class="geo-box">
                        <input type="text" class="geo_address_field" id="geo_address_field">
                        <input type="hidden" id="geo_lat_data" class="geo_lat_data">
                        <input type="hidden" id="geo_long_data" class="geo_long_data">
                    </div>
                </div>
                <div class="geo-redius-box">
                    <label for="">Afstand</label>
                    <div class="steel_slider_radius_search"></div> 
                    <input type="hidden" id="geolocation_radius" class="geolocation_radius" name="geolocation_radius">
                    <span class="radius_value">0 Km</span>
                </div>
            </div>

            <div class="steel-product-categories">
            <?php $product_categories = get_terms( array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => false, // Set to true to hide empty categories
            ) );

            if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
                echo '<ul class="product-categories">';
                foreach ( $product_categories as $category ) {
                    echo '<li>';
                    $category_id = 'category_' . $category->term_id;
                    echo '<label for="' . esc_attr( $category_id ) . '">';
                    echo '<input type="checkbox" id="' . esc_attr( $category_id ) . '" name="product_cat[]" value="' . esc_attr( $category->term_id ) . '">';
                    echo esc_html( $category->name );
                    echo '</label>'; // Add <br> for spacing between labels
                    echo '</li>';
                }
                echo '</ul>';
            } ?>
            </div>

            <div class="steel-price-range">
                <div class="steel-product-filter-range"></div>
            </div>

            <div class="steel-reset-button">
                <button>Opnieuw instellen</button>
            </div>
        <?php
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] :  'New title';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">Title:</label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        return $instance;
    }
}

function register_sidebar_filter_widget() {
    register_widget( 'Sidebar_Filter_Widget' );
}
add_action( 'widgets_init', 'register_sidebar_filter_widget' );
