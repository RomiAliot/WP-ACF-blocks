 <section class="bg-bold-900 mt-4">
     <div class="container flex flex-col gap-10  lg:gap-12  py-10 md:py-24">
     <div class="md:max-w-[680px] mx-auto flex flex-col gap-6 lg:gap-6 text-white text-center items-start lg:items-center justify-center text-[20px]  ">
             <h2 class=" text-[28px] md:text-[44px] font-semibold !text-white text-left lg:text-center "><?php the_field('title', 'getintouch-section'); ?></h2>
             <p class="text-[18px] leading-8  text-left lg:text-center "><?php the_field('content', 'getintouch-section'); ?></p>
         </div>
         <div class="flex flex-col lg:grid lg:grid-cols-2 gap-6">
             <?php if (have_rows('help', 'getintouch-section')) : ?>
                 <?php while (have_rows('help', 'getintouch-section')) : the_row(); ?>
                     <div class="bg-white text-primary  p-8 lg:p-12 flex flex-col gap-8 justify-center items-center">
                         <div><?php new WuXi_Icon('help');  ?></div>
                         <div class=" flex flex-col gap-2 lg:gap-8 justify-center items-center">
                             <h4 class="text-h4"><?php the_sub_field('title'); ?></h4>
                             <p class="text-regular-medium"><?php the_sub_field('description'); ?></p>
                         </div>
                         <?php
                            $link = get_sub_field('button_url');
                            $link_url = $link['url'];
                            $link_target = $link['target'] ?  'target="' . $link['target'] . '"' :  'target="' . '_self' . '"';
                            new PrimaryButton(array(
                                'inner_text' => get_sub_field('button_text'),
                                'href' => $link_url,
                                'class' => 'mx-auto w-full lg:w-auto lg:min-w-72',
                                'attrs' => $link_target,
                            ));
                            ?>
                     </div>
                 <?php endwhile; ?>
             <?php endif; ?>
             <?php if (have_rows('contact', 'getintouch-section')) : ?>
                 <?php while (have_rows('contact', 'getintouch-section')) : the_row(); ?>
                     <div class="bg-white text-primary  p-8 lg:p-12 flex flex-col gap-8 justify-center items-center">
                         <div><?php new WuXi_Icon('contact');  ?></div>
                         <div class=" flex flex-col gap-2 lg:gap-8 justify-center items-center">
                             <h4 class="text-h4"><?php the_sub_field('title'); ?></h4>
                             <p class="text-regular-medium"><?php the_sub_field('description'); ?></p>
                         </div>
                         <?php
                            $link = get_sub_field('button_url');
                            $link_url = $link['url'];
                            $link_target = $link['target'] ?  'target="' . $link['target'] . '"' :  'target="' . '_self' . '"';
                            new OutlineButton(array(
                                'inner_text' => get_sub_field('button_text'),
                                'href' => $link_url,
                                'class' => 'mx-auto w-full lg:w-auto lg:min-w-72',
                                'attrs' => $link_target,
                            ));
                            ?>
                     </div>
                 <?php endwhile; ?>
             <?php endif; ?>
         </div>
     </div>
 </section>