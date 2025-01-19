<?php
/* @var \Vehica\Widgets\General\ImageCarouselGeneralWidget $vehicaCurrentWidget */

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
                    <?php foreach ($vehicaCurrentWidget->getItems() as $vehicaIndex => $vehicaItem) : ?>
                        <?php if (!empty($vehicaItem['image'])) : ?>
                            <div class="vehica-swiper-slide">
                                <?php if (!empty($vehicaItem['link']['url'])) : ?>
                                    <a
                                            href="<?php echo esc_url($vehicaItem['link']['url']); ?>"
                                        <?php if (!empty($vehicaItem['link']['is_external'])) : ?>
                                            target="_blank"
                                        <?php endif; ?>
                                        <?php if (!empty($vehicaItem['link']['nofollow'])) : ?>
                                            rel="nofollow"
                                        <?php endif; ?>
                                    >
                                        <img
                                                src="<?php echo esc_url($vehicaItem['image']); ?>"
                                                alt="<?php echo esc_attr($vehicaIndex); ?>"
                                        >
                                    </a>
                                <?php else : ?>
                                    <img
                                            src="<?php echo esc_url($vehicaItem['image']); ?>"
                                            alt="<?php echo esc_attr($vehicaIndex); ?>"
                                    >
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </vehica-swiper>
</div>