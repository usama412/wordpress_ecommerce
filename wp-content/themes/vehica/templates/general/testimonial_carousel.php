<?php
/* @var \Vehica\Widgets\General\TestimonialCarouselGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
$vehicaTestimonials = $vehicaCurrentWidget->getTestimonials();
if (empty($vehicaTestimonials)) {
    return;
}
?>
<div class="vehica-heading">
    <div class="vehica-heading__icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="34.011" height="29.76" viewBox="0 0 34.011 29.76">
            <path fill="#ff4605"
                  d="M11.691-27.634h-7.44a4.086 4.086 0 0 0-2.989 1.262A4.086 4.086 0 0 0 0-23.383v8.5a4.086 4.086 0 0 0 1.262 2.989 4.086 4.086 0 0 0 2.989 1.262H8.5v1.594a3.58 3.58 0 0 1-1.1 2.624 3.58 3.58 0 0 1-2.624 1.1H3.72a2.571 2.571 0 0 0-1.893.764 2.571 2.571 0 0 0-.764 1.893v2.126a2.571 2.571 0 0 0 .764 1.893 2.571 2.571 0 0 0 1.893.764h1.063A10.925 10.925 0 0 0 10.4.631a11.112 11.112 0 0 0 4.052-4.052 10.925 10.925 0 0 0 1.495-5.613v-14.349a4.086 4.086 0 0 0-1.262-2.989 4.086 4.086 0 0 0-2.994-1.262zm2.126 18.6a8.652 8.652 0 0 1-1.229 4.517A9.354 9.354 0 0 1 9.3-1.229 8.652 8.652 0 0 1 4.783 0H3.72a.508.508 0 0 1-.365-.166.508.508 0 0 1-.166-.365v-2.126a.508.508 0 0 1 .166-.365.508.508 0 0 1 .365-.166h1.063A5.623 5.623 0 0 0 8.9-4.916a5.623 5.623 0 0 0 1.727-4.119v-3.72H4.251a2.043 2.043 0 0 1-1.495-.631 2.043 2.043 0 0 1-.631-1.495v-8.5a2.043 2.043 0 0 1 .631-1.495 2.043 2.043 0 0 1 1.495-.631h7.44a2.043 2.043 0 0 1 1.495.631 2.043 2.043 0 0 1 .631 1.495zm15.943-18.6h-7.44a4.086 4.086 0 0 0-2.989 1.262 4.086 4.086 0 0 0-1.262 2.989v8.5a4.086 4.086 0 0 0 1.262 2.989 4.086 4.086 0 0 0 2.989 1.262h4.251v1.594a3.58 3.58 0 0 1-1.1 2.624 3.58 3.58 0 0 1-2.624 1.1h-1.058a2.571 2.571 0 0 0-1.889.764 2.571 2.571 0 0 0-.764 1.893v2.126a2.571 2.571 0 0 0 .764 1.893 2.571 2.571 0 0 0 1.893.764h1.063A10.925 10.925 0 0 0 28.465.631a11.112 11.112 0 0 0 4.052-4.052 10.925 10.925 0 0 0 1.495-5.613v-14.349a4.086 4.086 0 0 0-1.262-2.989 4.086 4.086 0 0 0-2.99-1.262zm2.126 18.6a8.652 8.652 0 0 1-1.229 4.517 9.354 9.354 0 0 1-3.288 3.288A8.652 8.652 0 0 1 22.851 0h-1.062a.508.508 0 0 1-.365-.166.508.508 0 0 1-.166-.365v-2.126a.508.508 0 0 1 .166-.365.508.508 0 0 1 .365-.166h1.063a5.623 5.623 0 0 0 4.118-1.728 5.623 5.623 0 0 0 1.73-4.118v-3.72h-6.38a2.043 2.043 0 0 1-1.495-.631 2.043 2.043 0 0 1-.631-1.495v-8.5a2.043 2.043 0 0 1 .631-1.495 2.043 2.043 0 0 1 1.495-.631h7.44a2.043 2.043 0 0 1 1.495.631 2.043 2.043 0 0 1 .631 1.495z"
                  transform="translate(0 27.634)"/>
        </svg>
    </div>

    <h3 class="vehica-heading__title">
        <?php echo esc_html($vehicaCurrentWidget->getHeading()); ?>
    </h3>

    <?php if ($vehicaCurrentWidget->hasSubheading()) : ?>
        <div class="vehica-heading__text">
            <?php echo wp_kses_post($vehicaCurrentWidget->getSubheading()); ?>
        </div>
    <?php endif; ?>
</div>

<div class="vehica-app">
    <vehica-testimonial-carousel
            :config="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getConfig())); ?>"
            :breakpoints="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getBreakpoints())); ?>"
    >
        <div slot-scope="carousel" class="vehica-testimonial-carousel">
            <div class="vehica-swiper-container vehica-testimonial-carousel__count-<?php echo esc_attr(count($vehicaTestimonials)); ?>">
                <div class="vehica-swiper-wrapper">
                    <?php foreach ($vehicaTestimonials as $vehicaTestimonial) : ?>
                        <div class="vehica-swiper-slide">
                            <div class="vehica-testimonial-carousel__testimonial">
                                <div class="vehica-testimonial-carousel__content">
                                    <?php if (!empty($vehicaTestimonial['image']['url'])) : ?>
                                        <div class="vehica-testimonial-carousel__image">
                                            <img
                                                    src="<?php echo esc_url($vehicaTestimonial['image']['url']); ?>"
                                                <?php if (!empty($vehicaTestimonial['name'])) : ?>
                                                    alt="<?php echo esc_attr($vehicaTestimonial['name']); ?>"
                                                <?php endif; ?>
                                            >
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($vehicaCurrentWidget->showStars()) : ?>
                                        <div class="vehica-testimonial-carousel__stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($vehicaTestimonial['text'])) : ?>
                                        <div class="vehica-testimonial-carousel__text">
                                            <?php echo esc_html($vehicaTestimonial['text']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="vehica-testimonial-carousel__footer">
                                    <?php if (!empty($vehicaTestimonial['name'])) : ?>
                                        <div class="vehica-testimonial-carousel__name">
                                            <?php echo esc_html($vehicaTestimonial['name']); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($vehicaTestimonial['title'])) : ?>
                                        <div class="vehica-testimonial-carousel__title">
                                            <?php echo esc_html($vehicaTestimonial['title']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($vehicaCurrentWidget->showBullets()) : ?>
                    <div class="vehica-testimonial-carousel__pagination"></div>
                <?php endif; ?>
            </div>

            <div
                    @click="carousel.prevSlide"
                    class="vehica-testimonial-carousel__nav vehica-testimonial-carousel__nav--prev"
            >
                <i class="fas fa-chevron-left"></i>
            </div>

            <div
                    @click="carousel.nextSlide"
                    class="vehica-testimonial-carousel__nav vehica-testimonial-carousel__nav--next"
            >
                <i class="fas fa-chevron-right"></i>
            </div>
        </div>
    </vehica-testimonial-carousel>
</div>