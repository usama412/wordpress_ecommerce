<?php
/* @var \Vehica\Widgets\General\SliderGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
$vehicaSlides = $vehicaCurrentWidget->getSlides();
?>
<div class="vehica-app">
    <vehica-slider :config="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getSliderConfig())); ?>">
        <div slot-scope="slider">
            <div class="vehica-slider vehica-swiper-container">
                <?php if ($vehicaCurrentWidget->showArrows() && count($vehicaSlides) > 1) : ?>
                    <div
                            @click="slider.prevSlide"
                            class="vehica-slider__nav vehica-slider__nav--left"
                    >
                        <i class="fas fa-chevron-left"></i>
                    </div>

                    <div
                            @click="slider.nextSlide"
                            class="vehica-slider__nav vehica-slider__nav--right"
                    >
                        <i class="fas fa-chevron-right"></i>
                    </div>
                <?php endif; ?>

                <div class="vehica-swiper-wrapper">
                    <?php foreach ($vehicaSlides as $vehicaSlide) : ?>
                        <div
                                class="vehica-slider__slide vehica-swiper-slide elementor-repeater-item-<?php echo esc_attr($vehicaSlide['_id']); ?>"
                                style="background-image: url('<?php echo esc_url($vehicaSlide['image']['url']); ?>');"
                        >
                            <a
                                    class="vehica-slider__slide__link"
                                    href="<?php echo esc_url($vehicaSlide['button_url']['url']); ?>"
                                <?php if (!empty($vehicaSlide['button_url']['is_external'])) : ?>
                                    target="_blank"
                                <?php endif; ?>
                                <?php if (!empty($vehicaSlide['button_url']['nofollow'])) : ?>
                                    rel="nofollow"
                                <?php endif; ?>
                            ></a>

                            <?php if ($vehicaCurrentWidget->displayMask()) : ?>
                                <div class="vehica-slider__mask"></div>
                            <?php endif; ?>
                                <div class="vehica-slider__mask-additional"></div>

                            <div class="vehica-slider__content">
                                <div class="vehica-slider__content-inner">
                                    <div class="vehica-slider__title">
                                        <?php echo wp_kses_post($vehicaSlide['heading']); ?>
                                    </div>

                                    <?php if (!empty($vehicaSlide['show_button']) && !empty($vehicaSlide['button_url']['url'])) : ?>
                                        <div class="vehica-slider__button">
                                            <a
                                                    class="vehica-button"
                                                    href="<?php echo esc_url($vehicaSlide['button_url']['url']); ?>"
                                                <?php if (!empty($vehicaSlide['button_url']['is_external'])) : ?>
                                                    target="_blank"
                                                <?php endif; ?>
                                                <?php if (!empty($vehicaSlide['button_url']['nofollow'])) : ?>
                                                    rel="nofollow"
                                                <?php endif; ?>
                                            >
                                                <?php echo esc_html($vehicaSlide['button_text']); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($vehicaCurrentWidget->showBullets()) : ?>
                    <div class="vehica-swiper-pagination"></div>
                <?php endif; ?>
            </div>
        </div>
    </vehica-slider>
</div>