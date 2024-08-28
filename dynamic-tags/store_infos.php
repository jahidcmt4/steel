<?php
class Elementor_Cropsie_Store_Infos extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'cropsie-store-info';
    }
    public function get_title() {
        return esc_html__('Store information', 'elementor-random-number-dynamic-tag');
    }
    public function get_group() {
        return ['dokan'];
    }
    public function get_categories() {
        return [
            \Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
            \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY,
            \Elementor\Modules\DynamicTags\Module::POST_META_CATEGORY,
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
            \Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY
        ];
    }
    protected function register_controls() {
        $this->add_control(
            'info_type',
            [
                'type' => \Elementor\Controls_Manager::SELECT,
                'label' => esc_html__('Select field', 'dokan'),
                'options' => [
                    'store_name' => esc_html__('Store Name', 'dokan'),
                    'phone' => esc_html__('Phone', 'dokan'),
                    'email' => esc_html__('Email', 'dokan'),
                    'address' => esc_html__('Address', 'dokan'),
                    'bio' => esc_html__('Store Description', 'dokan'),

                ],
                'default' => 'store_name',
            ]
        );
    }
    public function render() {

        $fields = $this->get_settings('info_type');

        if (function_exists('dokan_get_store_info')) {
            $seller_id = (int) get_query_var('author');
            $store_info = dokan_get_store_info($seller_id);
            $store_url = dokan_get_store_url($seller_id);

            //$address = $store_info["address"]["street_1"] ? $store_info["address"]["street_1"] . " " . $store_info["address"]["street_2"] : '' . ", " . $store_info["address"]["city"] . ", " . $store_info["address"]["state"] . ", " . $store_info["address"]["zip"] . ", " . $store_info["address"]["country"];
            $vendor = dokan()->vendor->get($seller_id);
            $author = get_user_by('id', $seller_id);


            if ($fields == "bio") {
                echo $store_info["vendor_biography"];
            } elseif ($fields == "email") {
                echo $author->user_email;
            } elseif ($fields == "address") {
                echo $store_info["find_address"];
                //echo $store_info["address"]["street_1"] . " " . $store_info["address"]["street_2"] . ", " . $store_info["address"]["city"] . ", " . $store_info["address"]["state"] . ", " . $store_info["address"]["zip"] . ", " . $store_info["address"]["country"];
            } else {
                echo $store_info[$fields];
            }
        }
    }
}