<?php
class Elementor_user_navigation_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'cropsie_user_navigation';
    }

    public function get_title() {
        return esc_html__('User Navigation', 'elementor-addon');
    }

    public function get_icon() {
        return 'eicon-code';
    }

    public function get_categories() {
        return ['basic'];
    }

    public function get_keywords() {
        return ['user', 'navigation'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'seller_items',
            [
                'label' => esc_html__('Seller Navigation Item', 'cropsie'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'seller_list',
            [
                'label' => esc_html__('Seller nav list', 'textdomain'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'item_title',
                        'label' => esc_html__('Title', 'textdomain'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => esc_html__('Title', 'cropsie'),
                        'label_block' => true,
                    ],
                    [
                        'name' => 'item_link',
                        'label' => esc_html__('Link', 'textdomain'),
                        'type' => \Elementor\Controls_Manager::URL,
                        'options' => ['url', 'is_external', 'nofollow'],
                        'default' => [
                            'url' => '',
                            'is_external' => false,
                            'nofollow' => false,
                            // 'custom_attributes' => '',
                        ],
                        'label_block' => true,
                    ]
                ],
                'default' => [
                    [
                        'item_title' => esc_html__('Dashboard', 'textdomain'),
                        'item_link' =>  ['url' => esc_url('/dashboard', 'textdomain'), 'is_external' => false, 'nofollow' => false],
                    ],
                    [
                        'item_title' => esc_html__('Products', 'textdomain'),
                        'item_link' =>  ['url' => esc_url('/dashboard/products', 'textdomain'), 'is_external' => false, 'nofollow' => false],
                    ],
                    [
                        'item_title' => esc_html__('Orders', 'textdomain'),
                        'item_link' =>  ['url' => esc_url('/dashboard/orders', 'textdomain'), 'is_external' => false, 'nofollow' => false],
                    ],
                    [
                        'item_title' => esc_html__('Coupons', 'textdomain'),
                        'item_link' =>  ['url' => esc_url('/dashboard/coupons', 'textdomain'), 'is_external' => false, 'nofollow' => false],
                    ],
                    [
                        'item_title' => esc_html__('Settings', 'textdomain'),
                        'item_link' =>  ['url' => esc_url('/dashboard/settings/store/', 'textdomain'), 'is_external' => false, 'nofollow' => false],
                    ],

                ],
                'title_field' => '{{{ item_title }}}',
            ]
        );
        $this->add_control(
            'customer_list',
            [
                'label' => esc_html__('Customer nav list', 'textdomain'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'item_title',
                        'label' => esc_html__('Title', 'textdomain'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => esc_html__('Title', 'cropsie'),
                        'label_block' => true,
                    ],
                    [
                        'name' => 'item_link',
                        'label' => esc_html__('Link', 'textdomain'),
                        'type' => \Elementor\Controls_Manager::URL,
                        'options' => ['url', 'is_external', 'nofollow'],
                        'default' => [
                            'url' => '',
                            'is_external' => false,
                            'nofollow' => false,
                            // 'custom_attributes' => '',
                        ],
                        'label_block' => true,
                    ]
                ],
                'default' => [
                    [
                        'item_title' => esc_html__('Orders', 'textdomain'),
                        'item_link' =>  ['url' => esc_url('my-account/orders/', 'textdomain'), 'is_external' => false, 'nofollow' => false],
                    ],
                    [
                        'item_title' => esc_html__('Edit accounts', 'textdomain'),
                        'item_link' =>  ['url' => esc_url('/my-account/edit-account/', 'textdomain'), 'is_external' => false, 'nofollow' => false],
                    ]

                ],
                'title_field' => '{{{ item_title }}}',
            ]
        );


        // $this->end_controls_section();

        // // Content Tab End


        // // Style Tab Start

        // $this->start_controls_section(
        //     'section_title_style',
        //     [
        //         'label' => esc_html__('Title', 'elementor-addon'),
        //         'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        //     ]
        // );

        // $this->add_control(
        //     'title_color',
        //     [
        //         'label' => esc_html__('Text Color', 'elementor-addon'),
        //         'type' => \Elementor\Controls_Manager::COLOR,
        //         'selectors' => [
        //             '{{WRAPPER}} .hello-world' => 'color: {{VALUE}};',
        //         ],
        //     ]
        // );

        // $this->end_controls_section();

        // // Style Tab End

    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if (is_user_logged_in()):
            $seller_lists = $settings['seller_list'];
            $customer_lists = $settings['customer_list'];
            $userID = get_current_user_id();
            $store_info = dokan_get_store_info($userID);
            $user_meta = get_userdata($userID);
            $user_roles = in_array("seller", $user_meta->roles) ? "seller" : (in_array("administrator", $user_meta->roles) ? "admin" : "customer");
            $store_pic =  $user_roles == "admin" || $user_roles == "seller" ?  wp_get_attachment_url($store_info["gravatar"]) : get_avatar_url($userID);
            $dashboard_url = $user_roles == "admin" || $user_roles == "seller" ? "/dashboard" : get_permalink(wc_get_page_id('myaccount'));

?>
            <div class="userNavigationWrapper">
                <div class="userNavigationInner">
                    <div class="userNavigationImage">
                        <a href="<?php echo $dashboard_url; ?>"><img src="<?php echo $store_pic; ?>" alt="<?php echo $store_info["store_name"]; ?>"></a>
                    </div>
                    <div class="userNavDropdown">
                        <div class="userNavDropdownInner">
                            <div class="usernavHeader">
                                <a href="<?php echo $dashboard_url; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                        <path d="M64 0C28.7 0 0 28.7 0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-384c0-35.3-28.7-64-64-64L64 0zm96 320l64 0c44.2 0 80 35.8 80 80c0 8.8-7.2 16-16 16L96 416c-8.8 0-16-7.2-16-16c0-44.2 35.8-80 80-80zm-32-96a64 64 0 1 1 128 0 64 64 0 1 1 -128 0zM144 64l96 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-96 0c-8.8 0-16-7.2-16-16s7.2-16 16-16z" />
                                    </svg><?php echo $store_info["store_name"]; ?></a>
                            </div>

                            <ul>
                                <?php if ($user_roles == "admin" || $user_roles == "seller") : ?>
                                    <?php foreach ($seller_lists as $list) : ?>
                                        <li><a href="<?php echo $list['item_link']["url"]; ?>"><?php echo $list['item_title']; ?></a></li>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <?php foreach ($customer_lists as $list) : ?>
                                        <?php var_dump($list['item_link']); ?>
                                        <li><a href="<?php echo $list['item_link']; ?>"><?php echo $list['item_title']; ?></a></li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                            <div class="usernavFooter">

                                <a href="<?php echo wp_logout_url(); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                        <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z" />
                                    </svg>
                                    Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                .userNavigationImage {
                    width: 45px;
                    height: 45px;
                    border-radius: 50%;
                    overflow: hidden;
                }

                .usernavFooter,
                .usernavHeader {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }


                .userNavDropdown {
                    width: 230px;
                    padding: 0;
                    border-radius: 8px;
                    background: white;
                    box-shadow: -1px 5px 12px 1px #c6ffec78;
                    overflow: hidden;
                    height: 0;
                    transition: all .2s;
                    position: absolute;
                    z-index: 999;
                    right: 0;
                }

                .userNavDropdownInner ul li a {
                    padding: 5px 20px;
                    display: block;
                    font-size: 14px;
                }

                .usernavFooter svg,
                .usernavHeader svg {
                    width: 18px;
                    height: 18px;
                    margin-right: 4px;
                }

                .usernavFooter svg path,
                .usernavHeader svg path {
                    fill: #304f38;
                }

                .usernavFooter a,
                .usernavHeader a {
                    padding: 15px 20px;
                    border-bottom: 1px dashed #304f3840;
                    width: 100%;
                }

                .userNavDropdownInner ul {
                    padding-top: 15px;
                    padding-bottom: 15px;
                }

                .userNavigationInner {
                    position: relative;
                    cursor: pointer;
                    width: 45px;
                    height: 45px;
                }

                .userNavigationInner:hover .userNavDropdown {
                    height: auto;
                }

                .usernavFooter a {
                    border-top: 1px dashed #304f3840 !important;
                    border-bottom: 0 !important;
                }
            </style>
        <?php
        endif;
    }

    protected function content_template() {
        ?>
        <#

            #>
            <div class="userNavigationWrapper">
                <div class="userNavigationInner">
                    <div class="userNavigationImage">
                        <a href=" { dashboard_url }"><img src="{store_pic}" alt=""></a>
                    </div>
                </div>
            </div>
    <?php
    }
}
