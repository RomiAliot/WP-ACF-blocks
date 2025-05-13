<section class="pt-12 pb-6  flex justify-center items-center">
    <div class="container">
        <div class="flex gap-4  <?php echo isset($args['width']) ? $args['width'] : ''; ?>">
            <div class=" w-1 bg-cyan-600"></div>
            <div class="flex gap-1 lg:gap-14 items-start lg:items-center flex-col lg:flex-row ">

                <h2 class="text-h2 "><?php echo __($args['section_title']); ?></h2>

                <?php echo (isset($args['section_description']) && !empty($args['section_description'])) ? '<p class="text-regular-medium font-medium md:font-normal">' . $args['section_description'] . '</p>' : ''; ?>
            </div>
        </div>
    </div>
</section>