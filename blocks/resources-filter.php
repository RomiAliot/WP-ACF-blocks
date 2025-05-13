<?php
// Variables
$selectTopicField = get_field('select_topic');

$cpt = 'resources';
$all_categories = get_categories(array(
    'hide_empty' => false,
));

// Filter categories that have posts in the 'resources' CPT
$categories = array();
foreach ($all_categories as $category) {
    $posts = get_posts(array(
        'post_type' => $cpt,
        'category' => $category->term_id,
        'posts_per_page' => 1,
        'fields' => 'ids'
    ));
    
    if (!empty($posts)) {
        $categories[] = $category;
    }
}

// Function to get unique resource types
function get_unique_resource_types() {
  $unique_types = array();

  // Query to get all posts from the 'resources' CPT
  $args = array(
      'post_type' => 'resources',
      'posts_per_page' => -1, 
      'fields' => 'ids', 
  );
  $query = new WP_Query($args);

  if ($query->have_posts()) {
      while ($query->have_posts()) {
          $query->the_post();
          // Get the ACF field value
          $type = get_field('select_resource_type', get_the_ID());
          // Add the type to the array if not already present
          if ($type && !in_array($type, $unique_types)) {
              $unique_types[] = $type;
          }
      }
  }

  wp_reset_postdata();

  return $unique_types;
}
$resource_types = get_unique_resource_types();

?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="search-container pt-8">
      <div class="dropdowns">
          <div class="mb-4">
            
            <div class="flex  items-end justify-between container flex-wrap lg:flex-nowrap">
              <div class="flex flex-col lg:flex-row items-center justify-between gap-4 w-full lg:w-[580px]">
                <div class="w-[100%]">
                  <label class="block text-gray-700 text-sm font-bold mb-2" for="resource-category-select">
                      Topics
                  </label>
                  <div class="flex items-center">
                  <select id="category-select" name="category" class="z-20 bg-transparent cursor-pointer block appearance-none w-full text-bold-500  border border-bold-800 hover:border-gray-500 px-4 py-2 pr-8  focus:outline-none focus:shadow-outline">
                      <option value="all"  data-selected="true">All</option>
                      <?php
                      if (!empty($categories)) {
                          foreach ($categories as $category) {
                              echo '<option value="' . esc_attr($category->slug) . '" data-selected="false">' . esc_html($category->name) . '</option>';
                          }
                      } else {
                          echo '<option value="">No categories available</option>';
                      }
                      ?>
                  </select>
                  <span class="ml-[-35px]"><?php echo do_shortcode('[wuxi_icon name="right-dropdown"]'); ?></span>
                  </div>
                  
                </div>
                <div class="w-[100%]">
                  <label class="block text-gray-700 text-sm font-bold mb-2" for="resource-category-select">
                  Resource Type
                  </label>
                  <div class="flex items-center">
                    <select id="type-select" name="type" class="z-20 bg-transparent cursor-pointer block appearance-none w-full text-bold-500  border border-bold-800 hover:border-gray-500 px-4 py-2 pr-8  focus:outline-none focus:shadow-outline">
                      <option value="all"  data-selected="true">All</option>
                      <?php
                      if (!empty($resource_types)) {
                          foreach ($resource_types as $type) {
                              echo '<option value="' . esc_attr($type) . '" data-selected="false">' . esc_html($type) . '</option>';
                          }
                      } else {
                          echo '<option value="">No types available</option>';
                      }
                      ?>
                  
                    </select>
                    <span class="ml-[-35px]"><?php echo do_shortcode('[wuxi_icon name="right-dropdown"]'); ?></span>
                  </div>
              
                </div>
              </div>
              
              <div class="flex lg:justify-end flex-col w-full lg:w-[556px] mt-4 md:mt-0 items-center gap-6 lg:flex-row">
                <input class="w-full lg:w-[282px] px-4 py-2 bg-white border-b border-solid border-bold-800 justify-start items-center gap-10 inline-flex" type="text" id="search-input" placeholder="Search">
                <button id="search-button" class="hover:text-accent hover:border-accent font-semibold w-full lg:w-[130px]   px-6 py-2 border border-solid border-black justify-center text-bold-900 items-center gap-2 inline-flex">Search
                <?php echo do_shortcode('[wuxi_icon name="glass"]'); ?>
                </button>
              </div>
                
            </div>
      </div>
      
    </div>
    
</div>

</div><!-- #post-<?php the_ID(); ?> -->
