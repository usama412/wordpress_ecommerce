<?php
/* @var \Vehica\Widgets\Car\Single\GallerySingleCarWidget $vehicaCurrentWidget */
/* @var \Vehica\Model\Post\Car $vehicaCar */
global $vehicaCurrentWidget, $vehicaCar;

if (!$vehicaCar || !$vehicaCurrentWidget) {
    return;
}

$vehicaCarImages = $vehicaCurrentWidget->getMainImages();
$vehicaShowThumbnails = $vehicaCurrentWidget->showThumbnails() && $vehicaCarImages->count() > 1;

if ($vehicaCarImages->isNotEmpty()) :
    if ($vehicaShowThumbnails) : ?>
        <style>
            .vehica-gallery-thumbs .vehica-swiper-slide {
                width: calc(100% / <?php echo esc_attr($vehicaCurrentWidget->getMobileThumbsPerView()); ?> - 17px);
            }

            @media (min-width: 900px) {
                .vehica-gallery-thumbs .vehica-swiper-slide {
                    width: calc(100% / <?php echo esc_attr($vehicaCurrentWidget->getTabletThumbsPerView()); ?> - 17px);
                }
            }

            @media (min-width: 1200px) {
                .vehica-gallery-thumbs .vehica-swiper-slide {
                    width: calc(100% / <?php echo esc_attr($vehicaCurrentWidget->getThumbsPerView()); ?> - 17px);
                }
            }
        </style>
    <?php endif; ?>

    <div class="vehica-app">
        <vehica-car-field-gallery
                :config="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getSwiperConfig())); ?>"
                :show-thumbnails="<?php echo esc_attr($vehicaCurrentWidget->showThumbnails() && $vehicaCarImages->count() > 1 ? 'true' : 'false'); ?>"
                :lazy-prev-next="<?php echo esc_attr($vehicaCurrentWidget->getThumbsPerView()); ?>"
        >
            <div slot-scope="galleryProps" class="vehica-car-gallery vehica-car-gallery__count-<?php echo esc_attr($vehicaCarImages->count()); ?>">
                <div class="vehica-gallery-main__wrapper">
                    <div class="vehica-swiper-container vehica-gallery-main">
                        <div class="vehica-swiper-wrapper">
                            <?php foreach ($vehicaCarImages as $vehicaImageIndex => $vehicaImage) :
                                /* @var \Vehica\Model\Post\Image $vehicaImage */
                                ?>
                                <div
                                        class="vehica-swiper-slide"
                                        data-index="<?php echo esc_attr($vehicaImageIndex); ?>"
                                        data-src="<?php echo esc_url($vehicaImage->getSrc('full')); ?>"
                                        data-msrc="<?php echo esc_url($vehicaImage->getSrc()); ?>"
                                        data-width="<?php echo esc_attr($vehicaImage->getWidth()); ?>"
                                        data-height="<?php echo esc_attr($vehicaImage->getHeight()); ?>"
                                        data-title="<?php echo esc_attr($vehicaImage->getAlt() !== '' ? $vehicaImage->getAlt() : $vehicaCar->getName()); ?>"
                                >
                                    <?php if (!$vehicaImageIndex) : ?>
                                        <img
                                                src="<?php echo esc_attr($vehicaImage->getSrc('large')); ?>"
                                                alt="<?php echo esc_attr($vehicaImage->getAlt()); ?>"
                                        >
                                    <?php else : ?>
                                        <img
                                                class="lazyload"
                                                src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                data-srcset="<?php echo esc_attr($vehicaImage->getSrcset('large')); ?>"
                                                data-sizes="auto"
                                                alt="<?php echo esc_attr($vehicaImage->getAlt()); ?>"
                                        >
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <template>
                            <div class="vehica-car-gallery__count">
                                <i class="far fa-images"></i>
                                <span>{{ galleryProps.activeIndex + 1 }}</span><span>/</span><span><?php echo esc_html($vehicaCarImages->count()); ?></span>
                            </div>
                        </template>

                        <?php if ($vehicaCurrentWidget->showThumbnails() && $vehicaCarImages->count() > 1) : ?>
                            <div class="vehica-car-gallery__arrows">
                                <div
                                        @click.prevent="galleryProps.prevSlide"
                                        class="vehica-carousel__arrow vehica-carousel__arrow--left"
                                ></div>
                                <div
                                        @click.prevent="galleryProps.nextSlide"
                                        class="vehica-carousel__arrow vehica-carousel__arrow--right"
                                ></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($vehicaCurrentWidget->showThumbnails() && $vehicaCarImages->count() > 1) : ?>
                    <div class="vehica-gallery-thumbs__wrapper">
                        <div class="vehica-swiper-container vehica-gallery-thumbs">
                            <div class="vehica-swiper-wrapper">
                                <?php foreach ($vehicaCurrentWidget->getThumbnails() as $vehicaImageIndex => $vehicaImage) :
                                    /* @var \Vehica\Model\Post\Image $vehicaImage */
                                    ?>
                                    <div
                                            class="vehica-swiper-slide"
                                            data-index="<?php echo esc_attr($vehicaImageIndex); ?>"
                                    >
                                        <div class="vehica-gallery-thumbs__single">
                                            <img
                                                    src="<?php echo esc_attr($vehicaImage->getSrc('vehica_167.5_93')); ?>"
                                                    alt="<?php echo esc_attr($vehicaImage->getAlt()); ?>"
                                            >
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </vehica-car-field-gallery>
    </div>

    <div class="vehica-app">
        <template>
            <portal to="footer">
                <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="pswp__bg"></div>
                    <div class="pswp__scroll-wrap">
                        <div class="pswp__container">
                            <div class="pswp__item"></div>
                            <div class="pswp__item"></div>
                            <div class="pswp__item"></div>
                        </div>

                        <div class="pswp__ui pswp__ui--hidden">

                            <div class="pswp__top-bar">

                                <div class="pswp__counter"></div>

                                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                                <div class="pswp__preloader">
                                    <div class="pswp__preloader__icn">
                                        <div class="pswp__preloader__cut">
                                            <div class="pswp__preloader__donut"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                                <div class="pswp__share-tooltip"></div>
                            </div>

                            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
                            </button>

                            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
                            </button>

                            <div class="pswp__caption">
                                <div class="pswp__caption__center"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </portal>
        </template>
    </div>
<?php
endif;