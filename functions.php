<?php

/**
 * x-client functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package  x-client
 */

if (!defined('_S_VERSION')) {
  // Replace the version number of the theme on each release.
  $theme = wp_get_theme();
  $theme_version = $theme->get('Version');
  define('_S_VERSION', $theme_version);
}

define('THEME_DIR', get_template_directory());

require_once THEME_DIR . '/vendor/autoload.php';

require_once get_stylesheet_directory() . '/inc/classes/ContactFormNews.php';
require_once get_template_directory() . '/inc/custom-posts-type.php';
require_once get_template_directory() . '/inc/advanced-custom-fields.php';
require_once get_template_directory() . '/inc/mobile-megamenu-walker.php';
require_once get_template_directory() . '/inc/megamenu-walker.php';
require_once get_template_directory() . '/inc/wuxi-icons.php';
require_once get_template_directory() . '/components/components.php';
require_once get_template_directory() . '/inc/acf.php';
require_once get_template_directory() . '/inc/acf/blocks.php';
require_once get_template_directory() . '/inc/acf/custom-fields.php';
require_once get_template_directory() . '/inc/utils.php';
require_once get_template_directory() . '/inc/landings.php';
require_once get_template_directory() . '/inc/sales-form-api.php';
require_once THEME_DIR . '/inc/resources-validation-functions.php';
require_once THEME_DIR . '/inc/gravity-forms-actions.php';
require_once get_template_directory() . '/inc/rest-routes/rest-routes.php';

// add Aqua resizer for images 
require_once get_template_directory() . '/inc/aq_resizer.php';



function guidv4($data = null)
{
  // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
  $data = $data ?? random_bytes(16);
  assert(strlen($data) == 16);

  // Set version to 0100
  $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
  // Set bits 6-7 to 10
  $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

  // Output the 36 character UUID.
  return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

/** Load More Events **/
require_once get_template_directory() . '/inc/ajax/events.php';
/** Search Results **/
require_once get_template_directory() . '/inc/ajax/search.php';
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function x_client_setup()
{
  /*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on x_client, use a find and replace
		* to change 'x_client' to the name of your theme in all the template files.
		*/
  load_theme_textdomain('x_client', get_template_directory() . '/languages');

  // Add default posts and comments RSS feed links to head.
  add_theme_support('automatic-feed-links');

  /*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
  add_theme_support('title-tag');

  /*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
  add_theme_support('post-thumbnails');

  // This theme uses wp_nav_menu() in one location.
  register_nav_menus(
    array(
      'menu-1' => esc_html__('Primary', 'x_client'),
    )
  );

  /*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
  add_theme_support(
    'html5',
    array(
      'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
      'style',
      'script',
    )
  );

  // Set up the WordPress core custom background feature.
  add_theme_support(
    'custom-background',
    apply_filters(
      'x_clients_custom_background_args',
      array(
        'default-color' => 'ffffff',
        'default-image' => '',
      )
    )
  );

  // Add theme support for selective refresh for widgets.
  add_theme_support('customize-selective-refresh-widgets');

  /**
   * Add support for core custom logo.
   *
   * @link https://codex.wordpress.org/Theme_Logo
   */
  add_theme_support(
    'custom-logo',
    array(
      'height'      => 250,
      'width'       => 250,
      'flex-width'  => true,
      'flex-height' => true,
    )
  );
}
add_action('after_setup_theme', 'x_client_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function x_client_content_width()
{
  $GLOBALS['content_width'] = apply_filters('x_client_content_width', 640);
}
add_action('after_setup_theme', 'x_client_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function x_client_widgets_init()
{
  register_sidebar(
    array(
      'name'          => esc_html__('Sidebar', 'x-client'),
      'id'            => 'sidebar-1',
      'description'   => esc_html__('Add widgets here.', 'x_client'),
      'before_widget' => '<section id="%1$s" class="widget %2$s">',
      'after_widget'  => '</section>',
      'before_title'  => '<h2 class="widget-title">',
      'after_title'   => '</h2>',
    )
  );
}
add_action('widgets_init', 'x_client_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function x_client_scripts()
{
  wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
  wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), null, true);
  wp_enqueue_script('mailchimp-js', get_template_directory_uri() . '/js/mailchimp.js', array(), _S_VERSION, true);
  wp_enqueue_style('x_client-style', get_stylesheet_uri(), array(), _S_VERSION);
  // wp_enqueue_style( 'preflight', get_template_directory_uri() . '/modern-normalize.css', array(), _S_VERSION );
  wp_enqueue_style('tailwind', get_template_directory_uri() . '/dist/css/styles.css', array(), _S_VERSION);
  wp_style_add_data('x_client-style', 'rtl', 'replace');
  wp_enqueue_script('x_client-global', get_template_directory_uri() . '/js/global.js', array(), _S_VERSION, true);

  wp_enqueue_script('x_client-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);


  wp_enqueue_script('x_client-javascript', get_template_directory_uri() . '/dist/js/app.js', array(), _S_VERSION, true);
}
add_action('wp_enqueue_scripts', 'x_client_scripts');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
  require get_template_directory() . '/inc/jetpack.php';
}



// Agrega la variable ajaxurl al pie de página
function add_ajaxurl_to_footer()
{
?>
  <script>
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
  </script>
  <?php
}
add_action('wp_footer', 'add_ajaxurl_to_footer');

function get_url_content($url) {
  // Inicializar cURL para obtener el contenido de la URL
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Seguir redirecciones si las hay
  curl_setopt($ch, CURLOPT_MAXREDIRS, 10); // Límite de redirecciones para evitar bucles infinitos

  $content = curl_exec($ch);
  curl_close($ch);

  return $content;
}

function get_video_id($video_url) {
  $url_parts = parse_url($video_url);
  if (isset($url_parts['query'])) {
      parse_str($url_parts['query'], $query);
      if (isset($query['v'])) {
          return $query['v'];
      }
  }
  return false;
}

$thumbnail_cache = [];

// Función para obtener el proveedor de video (YouTube o Vimeo)
function get_video_provider($video_url) {
  if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
      return 'youtube';
  } elseif (strpos($video_url, 'vimeo.com') !== false) {
      return 'vimeo';
  } else {
      return '';
  }
}

function get_video_thumbnail($video_url) {
  global $thumbnail_cache;

  // Verificar si ya tenemos la URL de la imagen de portada en caché
  if (isset($thumbnail_cache[$video_url])) {
      return $thumbnail_cache[$video_url];
  }

  // Obtener el proveedor de video
  $video_provider = get_video_provider($video_url);

  switch ($video_provider) {
      case 'youtube':
          $thumbnail_url = get_youtube_thumbnail($video_url);
          break;
      case 'vimeo':
          $thumbnail_url = get_vimeo_thumbnail($video_url);
          break;
      default:
          $thumbnail_url = '';
          break;
  }

  // Guardar en caché la URL de la imagen de portada
  if (!empty($thumbnail_url)) {
      $thumbnail_cache[$video_url] = $thumbnail_url;
  }

  return $thumbnail_url;
}


function get_youtube_thumbnail($video_url) {
  // Obtener el ID del video de YouTube
  $video_id = get_video_id($video_url);
  
  if (!$video_id) {
      return ''; 
  }

  
    // Construir la URL de la miniatura de alta calidad (HQ)
    $thumbnail_url = "https://img.youtube.com/vi/{$video_id}/hqdefault.jpg";

    // Guardar en caché la URL de la imagen de portada
    $thumbnail_cache[$video_url] = $thumbnail_url;
    
    return $thumbnail_url;
}

function get_vimeo_thumbnail($video_url) {
  // Obtener el ID del video de YouTube
  $video_id = get_video_id($video_url);
  
  if (!$video_id) {
      return ''; 
  }

  // Hacer una solicitud al video de YouTube para obtener el contenido de la página
  $video_page_content = get_url_content($video_url);

  // Buscar la URL de la imagen de portada en el contenido de la página
  preg_match('/<meta property="og:image" content="([^"]+)"/', $video_page_content, $matches);
  if (isset($matches[1])) {
      $thumbnail_url = $matches[1];
      // Guardar en caché la URL de la imagen de portada
      $thumbnail_cache[$video_url] = $thumbnail_url;
      return $thumbnail_url;
  } else {
      return ''; // Devolver una cadena vacía si no se encuentra la URL de la imagen de portada
  }
}

// Registrar la acción de WordPress para la solicitud AJAX
add_action('wp_ajax_load_more_resources', 'load_more_resources');
add_action('wp_ajax_nopriv_load_more_resources', 'load_more_resources');

function load_more_resources()
{
  // Parámetros de la solicitud AJAX
  $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
  $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 12;
  $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
  $type= isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
  $search_term = isset($_POST['search_term']) ? sanitize_text_field($_POST['search_term']) : '';
  $total_resources_query;
  $args;

  // Modificar la consulta si la categoría no es 'all'
  if ($category !== 'all'  && $type == 'all') {
    $total_resources_query = new WP_Query(array(
      'post_type' => 'resources',
      'posts_per_page' => -1,
      's' => $search_term,
      'tax_query' => array(
        array(
          'taxonomy' => 'category', 
          'field' => 'slug',
          'terms' => $category,
        ),
      ),
    ));
    // Consulta para obtener los recursos
    $args = array(
      'post_type' => 'resources',
      'posts_per_page' => $posts_per_page,
      'paged' => $page,
      'tax_query' => array(
        array(
          'taxonomy' => 'category', 
          'field' => 'slug',
          'terms' => $category,
        ),
      ),
      's' => $search_term,
    );
  }else if($category == 'all' && $type == 'all')  {
    $total_resources_query = new WP_Query(array(
      'post_type' => 'resources',
      'posts_per_page' => -1,
      's' => $search_term,
    ));
    // Consulta para obtener los recursos
    $args = array(
      'post_type' => 'resources',
      'posts_per_page' => $posts_per_page,
      'paged' => $page,
      's' => $search_term,
    );
  } else if ($category == 'all' && $type !== 'all') {
    $total_resources_query = new WP_Query(array(
      'post_type' => 'resources',
      'posts_per_page' => -1,
      's' => $search_term,
      'meta_query' => array(
        array(
            'key' => 'select_resource_type',
            'value' => $type,
            'compare' => '='
        )
      )
    ));
    // Consulta para obtener los recursos
    $args = array(
      'post_type' => 'resources',
      'posts_per_page' => $posts_per_page,
      'paged' => $page,
      'meta_query' => array(
        array(
            'key' => 'select_resource_type',
            'value' => $type,
            'compare' => '='
        )
        ),
      's' => $search_term,
    );
  }else if($category !== 'all' && $type !== 'all'){
    $total_resources_query = new WP_Query(array(
      'post_type' => 'resources',
      'posts_per_page' => -1,
      's' => $search_term,
      'meta_query' => array(
        array(
            'key' => 'select_resource_type',
            'value' => $type,
            'compare' => '='
        )
        ),
        'tax_query' => array(
          array(
            'taxonomy' => 'category', 
            'field' => 'slug',
            'terms' => $category,
          ),
        )
    ));
    // Consulta para obtener los recursos
    $args = array(
      'post_type' => 'resources',
      'posts_per_page' => $posts_per_page,
      'paged' => $page,
      'meta_query' => array(
        array(
            'key' => 'select_resource_type',
            'value' => $type,
            'compare' => '='
        )
        ),
        'tax_query' => array(
          array(
            'taxonomy' => 'category', 
            'field' => 'slug',
            'terms' => $category,
          ),
        ),
      's' => $search_term,
    );
  };

  
  $total_resources = $total_resources_query->found_posts;
  $resources_query = new WP_Query($args);


  // Calcular el número de recursos cargados
  $loaded_resources_count = min($total_resources, $page * $posts_per_page);

  // Calcular el número restante de recursos
  $remaining_resources_count = max(0, $total_resources - $loaded_resources_count);


  // Generar el HTML de los recursos
  ob_start();
  if ($resources_query->have_posts()) {
    while ($resources_query->have_posts()) {
      $resources_query->the_post();

      //variables 
      $type = get_field('select_resource_type');
      $image = get_field('add_image');
      $formatted_date = get_the_date('M Y');
      $categories = get_the_category();
      $category_names = array();
      foreach ($categories as $category) {
        $category_names[] = $category->name;
      }
      $excerpt = get_the_excerpt();
      $trimmed_excerpt = wp_trim_words($excerpt, 20, '...');
      $title = get_the_title();
      $video_id = '';
      $video_url = get_field('add_video_url');
      $thumbnail_url = get_video_thumbnail($video_url);


     // Obtener la URL de la imagen redimensionada
     if (!empty($image)) {
      // Obtener la URL del tamaño personalizado 'custom-thumbnail'
     
      $image_thumb = wp_get_attachment_image_src($image['ID'], 'medium');
      $image_thumb_url = $image_thumb[0];
      $image_thumb_smaller = aq_resize($image_thumb_url, 166, 216, true); 
      $image_alt = get_post_meta($image['ID'], '_wp_attachment_image_alt', true);
    }

    
  ?>
      <a href="<?php echo the_permalink(); ?>" class="cursor-pointer group">
        <div class="resource pointer  h-[100%] relative p-[24px]  border border-1 border-solid border-bold-400">
          <div class="backcolor group-hover:bg-[#E5F7FF] bg-bold-200 absolute top-0 left-0 w-full md:h-[33%] h-[52%] "></div>
          <?php
          if (!empty($thumbnail_url) && $type == 'Video') { ?>
            <div class="flex justify-center items-center relative mx-auto mb-[20px] z-10 h-[156px] object-cover">
              <img class="group-hover:translate-y-[-3px] transition duration-300 ease-in-out  h-[128px] object-cover aspect-video" style="box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.30);" src="<?php echo esc_url($thumbnail_url); ?>" alt="" />
              <span class="absolute left-1/2 transform -translate-x-1/2">
                <img src="<?php echo get_template_directory_uri() . '/img/mdi_play-circle-outline.svg'; ?>" alt="">
              </span>
            </div>
          <?php
          } else if (empty($thumbnail_url) && $type == 'Video') { ?>
            <div class="group-hover:translate-y-[-3px] transition duration-300 ease-in-out justify-center   flex items-center relative mx-auto mb-[20px] z-10 h-[128px] object-cover aspect-video">
              <img class="h-[128px] object-cover" style="box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.30);" src="<?php echo get_template_directory_uri() . '/img/video-defult.png'; ?>" alt="">
            </div>
          <?php
          } else if (!empty($image)) { ?>
          <div class="">
            <img class="group-hover:translate-y-[-3px] transition  h-[150px] duration-300 ease-in-out   relative mx-auto mb-[20px] z-10 object-cover" style="box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.30);" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
          </div>
            
          <?php
          } else { ?>
          <div>
            <img class="group-hover:translate-y-[-3px] transition duration-300 ease-in-out  relative mx-auto mb-[20px] z-10 h-[156px] object-cover" style="box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.30);" src="<?php echo get_template_directory_uri() . '/img/image-defult.png'; ?>" alt="" />
          </div>
           
          <?php
          } ?>
          <div class="metadata flex pb-[8px] justify-between">
            <div class="date text-[12px] uppercase text-bold-600 font-semibold"><?php echo esc_html($formatted_date); ?></div>
            <div class="categories text-[12px] uppercase text-bold-400 font-semibold"><?php echo $type; ?></div>
          </div>
          <h2 class="pb-[8px] leading-6 text-[16px] text-bold-900 font-medium"><?php echo substr($title, 0, 60) . '...' ?></h2>
          <div class="content hidden md:block leading-5.4 text-[12px] text-bold-900 font-regular"><?php echo $trimmed_excerpt; ?></div>
        </div>
      </a>
    <?php
    }
    wp_reset_postdata();
  } else {
    ?>
    <div class="no-resources-message col-start-1 col-span-4 justify-center mt-8 text-lg text-center">
    We're sorry, but there are currently no resources that match your selected filters.
    </div>
<?php
  }
  $html = ob_get_clean();

  $response = array(
    'html' => $html, // HTML de los recursos cargados
    'remaining_resources' => $remaining_resources_count,
    'total_resources' => $total_resources,
    'resources_count' => $loaded_resources_count,
    'search-query' => $search_term,
    'type' => $type,
  );

  // Enviar la respuesta AJAX
  wp_send_json($response);
  wp_die();
  //test
}

add_filter('gform_next_button', 'form_next_button', 10, 2);
function form_next_button($button, $form)
{


  // replace the input tag for a button tag
  $button = preg_replace('/<input/', '<button', $button);
  $icon = new WuXi_Icon('chevron-right', false);
  $icon = $icon->get_icon();
  $button .= '<span>Next</span>' . $icon;
  $button .= '</button>';


  return $button;
  // return "<button class='button gform_next_button' id='gform_next_button_{$form['id']}'><span>Next</span>". $icon ."</button>";
}

add_filter('gform_previous_button', 'form_previous_button', 10, 2);
function form_previous_button($button, $form)
{
  // get value with a regex ex <input value="sarasa"
  $value = preg_match('/value="([^"]+)"/', $button, $matches);
  $button = preg_replace('/<input/', '<button', $button);

  $icon = new WuXi_Icon('chevron-left', false);
  $icon = $icon->get_icon();
  $button .= $icon . '<span>Previous</span>';
  $button .= '</button>';
  return $button;
  // return "<button class='button gform_previous_button' id='gform_previous_button_{$form['id']}'>" . $icon . " <span>Previous</span></button>";
}




if (function_exists('acf_add_options_page')) {

  acf_add_options_page(array(
    'page_title'    => 'Theme General Settings',
    'menu_title'    => 'Theme Settings',
    'menu_slug'     => 'theme-general-settings',
    'capability'    => 'edit_posts',
    'redirect'      => false,

  ));

  acf_add_options_sub_page(array(
    'page_title'    => 'CTA Settings',
    'menu_title'    => 'CTA Section',
    'parent_slug'   => 'theme-general-settings',
    'post_id' => 'cta-section'
  ));
  acf_add_options_sub_page(array(
    'page_title'    => 'Get in Touch Settings',
    'menu_title'    => 'Get in Touch Section',
    'parent_slug'   => 'theme-general-settings',
    'post_id' => 'getintouch-section'
  ));
  acf_add_options_sub_page(array(
    'page_title'    => '404 Page Settings',
    'menu_title'    => '404 Page',
    'parent_slug'   => 'theme-general-settings',
    'post_id' => '404-section'
  ));
  acf_add_options_sub_page(array(
    'page_title'    => 'Footer',
    'menu_title'    => 'Footer section',
    'parent_slug'   => 'theme-general-settings',
    'post_id' => 'footer-section'
  ));
  acf_add_options_sub_page(array(
    'page_title'    => 'Resources Signup',
    'menu_title'    => 'Resources Signup',
    'parent_slug'   => 'theme-general-settings',
    'post_id' => 'resources-signup'
  ));
}
// icon shortcode
include 'page-templates/components/car-tcr-icons.php';
add_shortcode('car_tcr_icon', 'car_tcr_icon');
function car_tcr_icon($atts)
{
  $icon_name = $atts['name'];
  ob_start();
  new Car_Tcr_Icon($icon_name);
  return ob_get_clean();
}


// adding widget area to footer

function registrar_area_de_widgets_footer()
{
  register_sidebar(array(
    'name'          => __('Footer', 'footer-widgets'),
    'id'            => 'widget-area-footer',
    'description'   => __('Wigets area for footer', 'footer-widgets'),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="widget-title">',
    'after_title'   => '</h2>',
  ));
}
add_action('widgets_init', 'registrar_area_de_widgets_footer');

// Add the custom column to the 'events' post type after the title
function add_custom_columns($columns)
{
  $new_columns = array();
  foreach ($columns as $key => $value) {
    $new_columns[$key] = $value;
    if ($key == 'title') {
      $new_columns['start_date'] = 'Start Date';
    }
  }
  return $new_columns;
}
add_filter('manage_events_posts_columns', 'add_custom_columns');

// Display the content of the custom field in the custom column
function display_custom_column_content($column, $post_id)
{
  if ($column == 'start_date') {
    $start_date = get_post_meta($post_id, 'start_date', true);
    if ($start_date) {
      $formatted_date = date('Y/m/d', strtotime($start_date));
      echo $formatted_date;
    }
  }
}
add_action('manage_events_posts_custom_column', 'display_custom_column_content', 10, 2);

// Make the 'start_date' column sortable
function make_columns_sortable($columns)
{
  $columns['start_date'] = 'start_date';
  return $columns;
}
add_filter('manage_edit-events_sortable_columns', 'make_columns_sortable');

// Set the default order for the 'start_date' column
function sort_custom_columns($query)
{
  if (!is_admin()) {
    return;
  }

  $orderby = $query->get('orderby');

  if ('start_date' == $orderby) {
    $query->set('meta_key', 'start_date');
    $query->set('orderby', 'meta_value');
  }
}
add_action('pre_get_posts', 'sort_custom_columns');
