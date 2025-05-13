<?php
// Register the Service Header block
function register_services_blocks() {
    acf_register_block_type(array(
        'name'              => 'service-header',
        'title'             => __('Service Header'),
        'render_template'   => THEME_DIR . '/blocks/services/service-header.php',
        'category'          => 'services',
        // 'icon'              => 'admin-comments',
        'keywords'          => array('service', 'header', 'acf'),
        'mode' => 'edit',
    ));
    acf_register_block_type(array(
        'name'              => 'why-us',
        'title'             => __('Service "Why Us" Section'),
        'render_template'   => THEME_DIR . '/blocks/services/service-why-us.php',
        'category'          => 'services',
        // 'icon'              => 'admin-comments',
        'keywords'          => array('service', 'cards', 'acf'),
        'mode' => 'edit',
    ));
    acf_register_block_type(array(
        'name'              => 'media-text',
        'title'             => __('Media / Text Section'),
        // 'render_template'   => THEME_DIR . '/blocks/services/media-text.php',
        'category'          => 'wuxi',
        // 'icon'              => 'admin-comments',
        'keywords'          => array('service', 'media', 'acf'),
        'render_callback' => 'render_media_text_block',
        'mode' => 'edit',
    ));
    acf_register_block_type(array(
        'name'              => 'cta-section',
        'title'             => __('CTA Section'),
        'render_template'   => THEME_DIR . '/blocks/cta-section.php',
        'category'          => 'wuxi',
        // 'icon'              => 'admin-comments',
        'keywords'          => array('wuxi', 'cta'),
        'mode' => 'edit',
    ));
    acf_register_block_type(array(
        'name'              => 'tabs',
        'title'             => __('Service Tabs Section'),
        'render_template'   => THEME_DIR . '/blocks/services/service-tabs.php',
        'category'          => 'service',
        // 'icon'              => 'admin-comments',
        'keywords'          => array('services', 'wuxi', 'cta'),
        'mode' => 'edit',
    ));
    acf_register_block_type(array(
        'name'              => 'masonry',
        'title'             => __('Service Masonry Section'),
        'render_template'   => THEME_DIR . '/blocks/services/service-masonry.php',
        'category'          => 'service',
        // 'icon'              => 'admin-comments',
        'keywords'          => array('services', 'wuxi', 'cta'),
        'mode' => 'edit',
    ));
    acf_register_block_type(array(
        'name'              => 'image-text-columns',
        'title'             => __('Image / Text Columns Section'),
        'render_template'   => THEME_DIR . '/blocks/services/image-text-columns.php',
        'category'          => 'service',
        // 'icon'              => 'admin-comments',
        'keywords'          => array('services', 'wuxi', 'cta'),
        'mode' => 'edit',
    ));
    acf_register_block_type(array(
        'name'              => 'Testimonial',
        'title'             => __('Testimonial'),
        'render_template'   => THEME_DIR . '/blocks/testimonial.php',
        'category'          => 'wuxi',
        // 'icon'              => 'admin-comments',
        'keywords'          => array('services', 'wuxi', 'cta'),
        'mode' => 'edit',
    ));
    acf_register_block_type(array(
      'name'              => 'Filter and Search Resources',
      'title'             => __('Filter and Search Resources'),
      'render_template'   => THEME_DIR . '/blocks/resources-filter.php',
      'category'          => 'wuxi',
      // 'icon'              => 'admin-comments',
      'keywords'          => array('services', 'wuxi', 'cta'),
      'mode' => 'edit',
    ));
    acf_register_block_type(array(
      'name'              => 'service-cta-banner',
      'title'             => __('Service CTA banner'),
      'render_template'   => THEME_DIR . '/blocks/services/service-cta-banner.php',
      'category'          => 'service',
      // 'icon'              => 'admin-comments',
      'keywords'          => array('services', 'wuxi', 'cta'),
      'mode' => 'edit',
    ));
}

function render_media_text_block($block) {
    $prefix = 'media_text_';
    $img_position = get_field($prefix . 'image_position');
    if ($img_position === 'right') {
        get_template_part( 'blocks/media-text/media-text-right' );
    } else {
        get_template_part( 'blocks/media-text/media-text' );
    }
}

// Make it so the only blocks available for the Services page are the ones we've registered
add_filter('allowed_block_types', 'limit_allowed_block_types');

function limit_allowed_block_types($allowed_block_types) {
    if (get_post_type() === 'services' || get_post_type() === 'technologies') {
        $allowed_block_types = array(
            'acf/service-header',
            'acf/why-us',
            'acf/service-cta-banner',
            'acf/media-text',
            'acf/cta-section',
            'acf/tabs',
            'acf/masonry',
            'acf/image-text-columns',
            'acf/Testimonial'
        );
    }

    return $allowed_block_types;
}

add_action('acf/init', 'register_services_blocks');