<?php
/* @var \Vehica\Widgets\Car\Single\GalleryV3SingleCarWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget, $vehicaCar;

if (!$vehicaCurrentWidget || !$vehicaCar) {
    return;
}

$vehicaImages = $vehicaCurrentWidget->getImages();
?>
<div class="vehica-app">
    <div class="vehica-gallery-v3 vehica-gallery-v3--count-<?php echo esc_attr(count($vehicaImages)); ?>">
        <vehica-gallery-v3
                title="<?php echo esc_attr($vehicaCar->getName()); ?>"
            <?php if ($vehicaCurrentWidget->hasImages()) : ?>
                :images="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getImageData())); ?>"
            <?php endif; ?>
        >
            <div slot-scope="gallery">
                <div class="vehica-swiper-container">
                    <div class="vehica-swiper-wrapper">
                        <?php foreach ($vehicaImages as $vehicaIndex => $vehicaImage) :
                            /* @var \Vehica\Model\Post\Image $vehicaImage */ ?>
                            <div
                                    class="vehica-swiper-slide vehica-gallery-v3__slide"
                                    data-id="<?php echo esc_attr($vehicaIndex); ?>"
                            >
                                <div class="vehica-gallery-v3__image-wrapper">
                                    <img
                                            class="vehica-gallery-v3__image"
                                            src="<?php echo esc_url($vehicaImage->getUrl('large')); ?>"
                                            alt="<?php echo esc_attr($vehicaCar->getName()); ?>"
                                    >
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="vehica-gallery-v3__arrows">
                    <button class="vehica-gallery-v3__arrow vehica-gallery-v3__arrow--left" @click="gallery.onPrev">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="vehica-gallery-v3__arrow vehica-gallery-v3__arrow--right" @click="gallery.onNext">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

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

                                        <button class="pswp__button pswp__button--close"
                                                title="Close (Esc)"></button>

                                        <button class="pswp__button pswp__button--fs"
                                                title="Toggle fullscreen"></button>

                                        <button class="pswp__button pswp__button--zoom"
                                                title="Zoom in/out"></button>

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

                                    <button class="pswp__button pswp__button--arrow--left"
                                            title="Previous (arrow left)">
                                    </button>

                                    <button class="pswp__button pswp__button--arrow--right"
                                            title="Next (arrow right)">
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
        </vehica-gallery-v3>
    </div>
</div>
