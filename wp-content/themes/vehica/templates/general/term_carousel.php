<?php
/* @var \Vehica\Widgets\General\TermCarouselGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
?>
<div class="vehica-app vehica-carousel-term-img vehica-<?php echo esc_attr($vehicaCurrentWidget->get_id_int()); ?>">
    <vehica-swiper
            :config="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getSwiperConfig())); ?>"
            :breakpoints="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getBreakpoints())); ?>"
    >
        <div slot-scope="swiperProps">
            <button
                    class="vehica-carousel__arrow vehica-carousel__arrow--left"
                    @click="swiperProps.prevSlide"
            ></button>
            <button
                    class="vehica-carousel__arrow vehica-carousel__arrow--right"
                    @click="swiperProps.nextSlide"
            ></button>

            <div class="vehica-swiper-container vehica-swiper-container-horizontal">
                <div class="vehica-swiper-wrapper">
                    <?php foreach ($vehicaCurrentWidget->getTerms() as $vehicaTerm) : ?>
                        <div class="vehica-swiper-slide">
                            <a
                                    href="<?php echo esc_url($vehicaTerm['url']); ?>"
                                    title="<?php echo esc_attr($vehicaTerm['name']); ?>"
                            >
                                <?php if (!empty($vehicaTerm['image'])) : ?>
                                    <img
                                            src="<?php echo esc_url($vehicaTerm['image']); ?>"
                                            alt="<?php echo esc_attr($vehicaTerm['name']); ?>"
                                    >
                                <?php else : ?>
                                    <?php echo esc_html($vehicaTerm['name']); ?>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </vehica-swiper>
</div>