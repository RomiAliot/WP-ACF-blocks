  <section class="cta relative">
      <div class="container  py-10 md:py-24">
          <div class="md:max-w-[680px] mx-auto flex flex-col gap-12 text-white text-center items-center justify-center text-[20px] desktop/strong/large ">
              <h2 class="text-h2 !text-white "><?php echo get_field('title', 'cta-section') ?? 'Let\'s get started'; ?></h2>
              <h4 class="text-h4"><?php echo get_field('content', 'cta-section') ?? 'Tell us about your program and connect with experts'; ?></h4>
              <a href="<?php echo get_field('button_url', 'cta-section') ?? '/sales/'; ?>" class="text-label text-bold-900 w-full md:w-auto text-center bg-white py-4 px-6 ease-in-out duration-300  hover:text-white hover:bg-surface"><?php echo get_field('button_text', 'cta-section') ?? 'Start Now'; ?></a>
          </div>
      </div>
      <div class="absolute -z-10 top-0  left-0 w-full min-h-full h-full bg-cta-mobile   bg-cover ">
          <video autoplay muted loop class="w-full h-full object-cover hidden md:block">
              <source src="<?php echo get_field('background_video', 'cta-section'); ?>" type="video/mp4">
              Your browser does not support the video tag.
          </video>
      </div>
  </section>