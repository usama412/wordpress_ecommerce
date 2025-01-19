<?php
/* @var \Vehica\Widgets\Car\Single\GalleryV4SingleCarWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget, $vehicaCar;

if (!$vehicaCurrentWidget || !$vehicaCar) {
    return;
}

$vehicaMainImage = $vehicaCurrentWidget->getMainImage();
$vehicaThumbnails = $vehicaCurrentWidget->getThumbnails();
if (!$vehicaMainImage && $vehicaThumbnails->isEmpty()) {
    return;
}
?>
<div class="vehica-app">
    <vehica-gallery-v4
            title="<?php echo esc_attr($vehicaCar->getName()); ?>"
        <?php if ($vehicaCurrentWidget->hasImages()) : ?>
            :images="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getImageData())); ?>"
        <?php endif; ?>
    >
        <div slot-scope="gallery">
            <div class="vehica-gallery-v4 vehica-gallery-v4__count--<?php echo esc_attr($vehicaCurrentWidget->getCount()); ?>">
                <div class="vehica-gallery-v4__images">
                    <?php if ($vehicaMainImage) : ?>
                        <div
                                @click="gallery.onOpen(0)"
                                class="vehica-gallery-v4__image-big"
                        >
                            <img
                                    src="<?php echo esc_url($vehicaMainImage->getUrl('large')); ?>"
                                <?php if ($vehicaMainImage->hasAlt()) : ?>
                                    alt="<?php echo esc_attr($vehicaMainImage->getAlt()); ?>"
                                <?php else : ?>
                                    alt="<?php echo esc_attr($vehicaCar->getName()); ?>"
                                <?php endif; ?>
                                    class="vehica-gallery-v4__image"
                            >
                        </div>
                    <?php endif; ?>

                    <div class="vehica-gallery-v4__image-small-wrapper">
                        <?php foreach ($vehicaThumbnails as $vehicaIndex => $vehicaImage) :
                            /* @var \Vehica\Model\Post\Image $vehicaImage */
                            ?>
                            <div
                                    @click="gallery.onOpen(<?php echo esc_attr($vehicaIndex + 1); ?>)"
                                    class="vehica-gallery-v4__image-small"
                            >
                                <img
                                        src="<?php echo esc_url($vehicaImage->getUrl('large')); ?>"
                                    <?php if ($vehicaImage->hasAlt()) : ?>
                                        alt="<?php echo esc_attr($vehicaImage->getAlt()); ?>"
                                    <?php else : ?>
                                        alt="<?php echo esc_attr($vehicaCar->getName()); ?>"
                                    <?php endif; ?>
                                        class="vehica-gallery-v4__image"
                                >
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

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
        </div>
    </vehica-gallery-v4>
</div>
