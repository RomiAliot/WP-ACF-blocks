<?php
$prefix = 'testimonial_';
$quote = get_field($prefix . 'quote') ?? 'Overall, TESSA enables us to test new constructs much faster and cheaper now which is why we like to keep using it.';
$author = get_field($prefix . 'author') ?? "Mr. Me";

?>


<section class="py-10 md:py-24 bg-bold-50">
    <div class="max-w-[792px] px-4 mx-auto">
        <blockquote class="space-y-6">
            <h2 class="testimonial-block__quote">
                <?php echo $quote; ?>
            </h2>
            <footer>
                <cite class="text-h5 not-italic text-bold-600"><?php echo $author; ?></cite>
            </footer>
        </blockquote>
    </div>
</section>