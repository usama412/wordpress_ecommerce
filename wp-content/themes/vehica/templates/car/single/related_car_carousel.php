<?php
/* @var \Vehica\Widgets\Car\Single\RelatedCarCarouselSingleCarWidget $vehicaCurrentWidget */
/* @var \Vehica\Components\Card\Car\CardV1 $vehicaCarCard */
global $vehicaCarCard, $vehicaCurrentWidget;
$vehicaCarCard = $vehicaCurrentWidget->getCardV1();
$vehicaRelatedCars = $vehicaCurrentWidget->getCars();

if ($vehicaRelatedCars->isEmpty()) {
    return;
}
?>
<div class="vehica-app">
    <?php if ($vehicaCurrentWidget->showHeading()) : ?>
        <h3 class="vehica-section-label">
            <?php echo esc_html($vehicaCurrentWidget->getHeading()); ?>
        </h3>
    <?php endif; ?>

    <div class="vehica-car-tabs-carousel vehica-carousel-v1--cars-<?php echo esc_attr(count($vehicaRelatedCars)); ?>">
        <div class="vehica-carousel-v1">
            <vehica-swiper
                    :config="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getSwiperConfig())); ?>"
                    widget-id="<?php echo esc_attr($vehicaCurrentWidget->get_id()); ?>"
                    :breakpoints="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getBreakpoints())) ?>"
            >
                <div slot-scope="swiperProps">
                    <div class="vehica-carousel__swiper">
                        <div class="vehica-swiper-container vehica-swiper-container-horizontal">
                            <div class="vehica-swiper-wrapper">
                                <?php
                                global $vehicaCurrentCar;
                                foreach ($vehicaRelatedCars as $vehicaCurrentCar) : ?>
                                    <?php get_template_part('templates/shared/car_carousel'); ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="vehica-carousel-v1-button">
                        <a
                                href="<?php echo esc_url(vehicaApp('car_archive_url')); ?>"
                                class="vehica-button"
                        >
                            <?php echo esc_html(vehicaApp('start_new_search_string')); ?>
                        </a>
                    </div>

                    <template>
                        <div class="vehica-carousel-v1__arrows">
                            <button
                                    class="vehica-carousel__arrow vehica-carousel__arrow--left"
                                    @click.prevent="swiperProps.prevSlide"
                            >
                            </button>

                            <button
                                    class="vehica-carousel__arrow vehica-carousel__arrow--right"
                                    @click.prevent="swiperProps.nextSlide"
                            >
                            </button>
                        </div>
                    </template>
                </div>
            </vehica-swiper>
        </div>
    </div>
</div>
