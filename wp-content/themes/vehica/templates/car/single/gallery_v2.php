<?php
/* @var \Vehica\Widgets\Car\Single\GalleryV2SingleCarWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget, $vehicaCar;

if ( ! $vehicaCurrentWidget || ! $vehicaCar) {
    return;
}
?>
<div class="vehica-app">
    <vehica-gallery-v2
            title="<?php echo esc_attr($vehicaCar->getName()); ?>"
        <?php if ($vehicaCurrentWidget->hasImages()) : ?>
            :images="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getImageData())); ?>"
        <?php endif; ?>
    >
        <div slot-scope="gallery">
            <div
                    class="vehica-gallery-v2"
                <?php if ($vehicaCurrentWidget->hasImages()) : ?>
                    style="background-image: url('<?php echo esc_url($vehicaCurrentWidget->getMainImageUrl()); ?>');"
                <?php endif; ?>
            >
                <div>
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
            </div>

            <div class="vehica-gallery-v2-link" @click="gallery.onOpen"></div>
        </div>
    </vehica-gallery-v2>
</div>