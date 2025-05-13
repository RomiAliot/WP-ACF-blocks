<?php

// Register the WordPress action for the AJAX request
add_action('wp_ajax_load_more_events', 'load_more_events');
add_action('wp_ajax_nopriv_load_more_events', 'load_more_events');

function load_more_events()
{
    // AJAX request parameters
    $today = date('Y-m-d'); // Get current date in YYYY-MM-DD format
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $posts_per_page = isset($_POST['init']) && $_POST['init'] == 'true' ? intval($_POST['posts_per_page']) : 9;
    $offset = isset($_POST['init']) && $_POST['init'] == 'true' ? 0 : 14;
    $regions_string = isset($_POST['regions']) ? $_POST['regions'] : '';
    $regions = array_filter(explode(',', $regions_string));

    // Build initial meta_query
    $meta_query = array(
        'relation' => 'AND',
        array(
            'key' => 'start_date',
            'value' => $today,
            'compare' => '>=', // Match dates equal to or after today
            'type' => 'DATE',
        ),
    );

    // Add region filters if provided
    if (!empty($regions)) {
        $region_queries = array('relation' => 'OR');
        $region_queries[] = array(
            'key' => 'region',
            'value' => 'virtual',
            'compare' => '=',
        );
        foreach ($regions as $region) {
            $region_queries[] = array(
                'key' => 'region',
                'value' => $region,
                'compare' => '=',
            );
        }
        $meta_query[] = $region_queries;
    }

    // Query total event count
    $total_events_query = new WP_Query(array(
        'post_type' => 'events',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => $meta_query,
    ));

    $total_events = $total_events_query->found_posts;

    // Build a new meta_query with multi-day event conditions
    $meta_query = array(
        'relation' => 'AND',
        array(
            'relation' => 'OR',
            array(
                'key' => 'start_date',
                'value' => $today,
                'compare' => '>=',
                'type' => 'DATE',
            ),
            array(
                'relation' => 'AND',
                array(
                    'key' => 'multy_day_event',
                    'value' => 1,
                    'compare' => '=',
                    'type' => 'NUMERIC',
                ),
                array(
                    'key' => 'end_date',
                    'value' => $today,
                    'compare' => '>=',
                    'type' => 'DATE',
                ),
            ),
        ),
    );

    if (!empty($regions)) {
        $region_queries = array('relation' => 'OR');
        $region_queries[] = array(
            'key' => 'region',
            'value' => 'virtual',
            'compare' => '=',
        );
        foreach ($regions as $region) {
            $region_queries[] = array(
                'key' => 'region',
                'value' => $region,
                'compare' => '=',
            );
        }
        $meta_query[] = $region_queries;
    }

    $args = array(
        'post_type' => 'events',
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,
        'offset' => $offset,
        'paged' => $page,
        'meta_query' => $meta_query,
        'orderby' => 'meta_value',
        'meta_key' => 'start_date',
        'order' => 'ASC',
    );

    $events_query = new WP_Query($args);

    // Calculate loaded and remaining events
    $loaded_events_count = isset($_POST['init']) && $_POST['init'] == 'true'
        ? min($total_events, $events_query->post_count)
        : ($page == 1
            ? min($total_events, $events_query->post_count + 14)
            : min($total_events, $events_query->post_count + 14 + ($page - 1) * $posts_per_page));

    $remaining_events_count = max(0, $total_events - $loaded_events_count);

    // Generate HTML
    ob_start();
    if ($events_query->have_posts()) {
        $index = 0;
        while ($events_query->have_posts()) {
            $index++;
            $events_query->the_post();
            if (isset($_POST['init']) && $_POST['init'] == 'true') {
                if ($index == 1) {
                    get_template_part('loop-templates/events/card-event', 'featured');
                } elseif ($index < 6) {
                    get_template_part('loop-templates/events/card-event', 'default');
                } else {
                    get_template_part('loop-templates/events/card-event', 'collapsed');
                }
            } else {
                get_template_part('loop-templates/events/card-event', 'collapsed');
            }
        }
        wp_reset_postdata();
    }

    $html = ob_get_clean();

    // Prepare and send AJAX response
    $response = array(
        'posts' => $events_query->post_count,
        'init' => $_POST['init'],
        'query' => $args,
        'query2' => $total_events_query,
        'regions' => $_POST['regions'],
        'html' => $html,
        'loaded_events_count' => $loaded_events_count,
        'remaining_events' => $remaining_events_count,
        'total_events' => $total_events,
        'events_count' => $loaded_events_count,
    );

    wp_send_json($response);
    wp_die();
}

// Register AJAX action for loading past events
add_action('wp_ajax_load_more_events_past', 'load_more_events_past');
add_action('wp_ajax_nopriv_load_more_events_past', 'load_more_events_past');

function load_more_events_past()
{
    // AJAX request parameters
    $today = date('Ymd');
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 12;

    // Query total number of past events
    $total_events_query = new WP_Query(array(
        'post_type' => 'events',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'start_date',
                'value' => $today,
                'compare' => '<',
                'type' => 'DATE',
            ),
        ),
    ));

    $total_events = $total_events_query->found_posts;

    // Query for past events
    $args = array(
        'post_type' => 'events',
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
        'meta_key' => 'start_date',
        'orderby' => 'meta_value',
        'order' => 'desc',
        'meta_query' => array(
            array(
                'key' => 'start_date',
                'value' => $today,
                'compare' => '<',
                'type' => 'DATE',
            ),
        ),
    );

    $events_query = new WP_Query($args);

    // Calculate loaded and remaining events
    $loaded_events_count = ($page == 1
        ? min($total_events, $events_query->post_count)
        : min($total_events, $events_query->post_count + ($page - 1) * $posts_per_page));

    $remaining_events_count = max(0, $total_events - $loaded_events_count);
    $posts_by_year = array();

    // Generate HTML grouped by year
    ob_start();
    if ($events_query->have_posts()) {
        while ($events_query->have_posts()) {
            $events_query->the_post();

            $timestamp = strtotime(get_field('start_date'));
            $year = date("Y", $timestamp);

            if (!isset($posts_by_year[$year])) {
                $posts_by_year[$year] = array();
            }

            ob_start();
            get_template_part('loop-templates/events/card-event', 'old');
            $post_html = ob_get_clean();
            $posts_by_year[$year][] = $post_html;
        }
        wp_reset_postdata();
    } else {
        ob_start();
        $html = ob_get_clean();
    }

    // Send AJAX response
    $response = array(
        'html' => isset($html) ? $html : $posts_by_year,
        'loaded_events_count' => $loaded_events_count,
        'remaining_events' => $remaining_events_count,
        'total_events' => $total_events,
        'events_count' => $loaded_events_count,
    );

    wp_send_json($response);
    wp_die();
}
