<?php
$article = new WP_Query(array(
    'post_type' => 'post',
    'posts_per_page' => 1,
    'order' => 'desc'
));
$today = date('Ymd'); 

$events = new WP_Query(array(
    'post_type' => 'events',
    'offset' => 0,
    'posts_per_page' => 2,
    'meta_key' => 'start_date', 
    'orderby' => 'meta_value', 
    'order' => 'asc',
    'meta_query' => array(
        'relation' => 'OR', 
        array(
            'key' => 'start_date',
            'value' => date('Y-m-d'), 
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
                'value' => date('Y-m-d'),
                'compare' => '>=', 
                'type' => 'DATE',
            ),
        ),
    ),
));
?>
<section class="articles-events pt-2 pb-16 md:py-24">
    <div class="container text-center flex flex-col gap-12 items-start md:items-center justify-center">
        <h2 class="mobile/heading-2 desktop/heading-3 text-primary ">Articles & Events</h2>

        <?php while ($article->have_posts()) :
            $article->the_post();
            $featured_img_url = get_the_post_thumbnail_url() ? get_the_post_thumbnail_url() : get_template_directory_uri() . '/img/news/news-image-placeholder.svg';
            $featured_img_size = get_the_post_thumbnail_url() ? 'object-cover' : 'object-contain';
            $timestamp = strtotime(get_the_date());
        ?>

            <div class="w-full p-4 md:p-16 flex flex-col lg:grid  lg:grid-cols-2 gap-8 md:gap-14 bg-bold-100">
                <div class="w-full h-auto flex justify-center items-center aspect-[16/9] lg:aspect-auto">
                    <img src="<?php echo $featured_img_url; ?>" alt="" class="lg:w-full lg:h-auto lg:max-h-full <?php echo $featured_img_size; ?>">
                </div>
                <div class="text-left flex flex-col justify-start gap-8">
                    <div> <span class="text-kicker text-bold-600"> <time datetime="<?php echo get_the_date('Y-m-d'); ?>">
                                <?php echo date('M j, Y', $timestamp); ?>
                            </time></span>
                        <h4 class="text-h4 line-clamp-3"><?php the_title(); ?></h4>
                    </div>
                    <div class=" text-bold-800 text-regular-medium ">
                        <?php the_excerpt(); ?>
                    </div>
                    <div class="flex flex-col md:flex-row gap-8">

                        <?php new OutlineButton(array(
                            'inner_text' => 'Read More',
                            'href' => get_the_permalink(),
                            'class' => 'mx-none ',
                            'right_icon' => 'chevron-right'
                        ));
                        ?>
                        <?php new GhostButton(array(
                            'inner_text' => 'See All Articles',
                            'href' => '/articles',
                            'class' => 'mx-none ',
                        ));
                        ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>

        <?php if ($events->have_posts()) : ?>
            <div class="w-full flex flex-col lg:grid lg:grid-cols-2 gap-6 text-left">
                <?php while ($events->have_posts()) :
                    $events->the_post(); ?>
                    <?php get_template_part('loop-templates/events/card-event', 'medium'); ?>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <?php new OutlineButton(array(
            'inner_text' => 'See All Events',
            'href' => '/events',
            'class' => 'mx-none w-full lg:w-auto ',
            'right_icon' => 'chevron-right'
        ));
        ?>
    </div>
</section>
