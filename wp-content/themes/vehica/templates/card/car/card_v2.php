<?php
/* @var \Vehica\Model\Post\Car $vehicaCurrentCar */
/* @var \Vehica\Components\Card\Car\CardV2 $vehicaCarCard */
global $vehicaCurrentCar, $vehicaCarCard;

if (!$vehicaCurrentCar) {
    return;
}

$vehicaPrice = $vehicaCarCard->getPrice($vehicaCurrentCar);
$vehicaFeaturedImageId = $vehicaCurrentCar->getFeaturedImageId();
$vehicaImageSrcset = wp_get_attachment_image_srcset($vehicaFeaturedImageId, vehicaApp('card_image_size'));
$vehicaImageCount = $vehicaCarCard->getImageCount($vehicaCurrentCar);
$vehicaAttributes = $vehicaCarCard->getAttributes($vehicaCurrentCar);
?>
<div
        id="vehica-car-<?php echo esc_attr($vehicaCurrentCar->getId()); ?>"
        data-id="<?php echo esc_attr($vehicaCurrentCar->getId()); ?>"
        class="vehica-car-card vehica-car vehica-car-card-v2 <?php if (vehicaApp('card_multiline_features')) { ?>vehica-car-card__info-multiple-lines<?php } ?>"
>
    <div class="vehica-car-card__inner">
        <a class="vehica-car-card-link" href="<?php echo esc_url($vehicaCurrentCar->getUrl()); ?>">
            <div
                    class="vehica-car-card__image"
                    style="padding-top: <?php echo esc_attr(vehicaApp('card_image_ratio_padding')); ?>;"
            >
                <?php if (vehicaApp('is_compare_enabled')) : ?>
                    <template>
                        <vehica-add-to-compare
                                :car-id="<?php echo esc_attr($vehicaCurrentCar->getId()); ?>"
                                :add-class="true"
                        >
                            <div slot-scope="addToCompare">
                                <transition name="vehica-car-card__compare">
                                    <div
                                            v-if="addToCompare.compareMode"
                                            class="vehica-car-card__compare"
                                    >
                                        <div class="vehica-compare-add">
                                            <div
                                                    class="vehica-checkbox"
                                                    @click.prevent="addToCompare.set"
                                            >
                                                <input
                                                        id="vehica-compare--<?php echo esc_attr($vehicaCurrentCar->getId()); ?>"
                                                        type="checkbox"
                                                        :checked="addToCompare.isAdded"
                                                >

                                                <label for="vehica-compare--<?php echo esc_attr($vehicaCurrentCar->getId()); ?>">
                                                    <?php echo esc_html(vehicaApp('compare_string')); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </transition>
                            </div>
                        </vehica-add-to-compare>
                    </template>
                <?php endif; ?>

                <?php if (vehicaApp('show_favorite')) : ?>
                    <vehica-add-to-favorite
                            :car-id="<?php echo esc_attr($vehicaCurrentCar->getId()); ?>"
                            request-url="<?php echo esc_url(admin_url('admin-ajax.php?action=vehica_favorite')); ?>"
                            redirect-url="<?php echo esc_url(vehicaApp('login_page_url')); ?>"
                            vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_favorite')); ?>"
                        <?php if (vehicaApp('current_user')) : ?>
                            :is-logged="true"
                            :initial-is-favorite="<?php echo esc_attr(vehicaApp('current_user')->isFavorite($vehicaCurrentCar) ? 'true' : 'false'); ?>"
                        <?php else : ?>
                            :is-logged="false"
                            :initial-is-favorite="false"
                        <?php endif; ?>
                    >
                        <div slot-scope="props">
                            <div
                                    class="vehica-car-card__image__favorite"
                                    v-if="!props.isFavorite"
                                    @click.prevent="props.onAdd"
                            >
                                <i class="far fa-star"></i>
                            </div>

                            <template>
                                <div
                                        class="vehica-car-card__image__favorite vehica-car-card__image__favorite--is-favorite"
                                        v-if="props.isFavorite"
                                        @click.prevent="props.onAdd"
                                >
                                    <i class="fas fa-star"></i>
                                </div>
                            </template>
                        </div>
                    </vehica-add-to-favorite>
                <?php endif; ?>
            </div>
        </a>

        <?php if ($vehicaCarCard->showLabels()) : ?>
            <?php if (vehicaApp('card_label_type') === 'single') :
                $vehicaLabel = $vehicaCarCard->getLabel($vehicaCurrentCar);
                if (!empty($vehicaLabel)) : ?>
                    <div
                            class="vehica-car-card__featured"
                            style="background-color: <?php echo esc_attr($vehicaLabel->getBackgroundColor()); ?>;"
                    >
                        <div
                                class="vehica-car-card__featured__inner"
                                style="color: <?php echo esc_attr($vehicaLabel->getColor()); ?>;"
                        >
                            <?php echo esc_html($vehicaLabel->getText()); ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php elseif (vehicaApp('card_label_type') === 'multiple') :
                $vehicaLabels = $vehicaCarCard->getLabels($vehicaCurrentCar);
                if (!empty($vehicaLabels)) :?>
                    <div class="vehica-car-card__labels">
                        <?php foreach ($vehicaLabels as $vehicaLabel) : ?>
                            <div class="vehica-car-card__label">
                                <div class="vehica-car-card__label__inner">
                                    <?php echo esc_html($vehicaLabel); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>

        <div class="vehica-car-card__image-bg">
            <div
                    class="vehica-car-card__image"
                    style="padding-top: <?php echo esc_attr(vehicaApp('card_image_ratio_padding')); ?>;"
            >
                <?php if (!empty($vehicaFeaturedImageId)) : ?>
                    <img
                        <?php if (!empty($vehicaImageSrcset)) : ?>
                            class="lazyload"
                            src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                            data-srcset="<?php echo esc_attr($vehicaImageSrcset); ?>"
                            data-sizes="auto"
                        <?php else : ?>
                            src="<?php echo esc_url(wp_get_attachment_image_url($vehicaFeaturedImageId, 'full')); ?>"
                        <?php endif; ?>
                            alt="<?php echo esc_html($vehicaCurrentCar->getName()); ?>"
                    >
                <?php else: ?>
                    <div class="vehica-car-card__image__icon"></div>
                <?php endif; ?>

                <?php if ($vehicaImageCount > 0 && vehicaApp('show_photo_count')) : ?>
                    <div class="vehica-car-card__image-info">
                        <span class="vehica-car-card__image-info__photos">
                            <i class="far fa-images"></i>
                            <span><?php echo esc_html($vehicaImageCount); ?></span>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="vehica-car-card__content">
            <a
                    class="vehica-car-card__name"
                    href="<?php echo esc_url($vehicaCurrentCar->getUrl()); ?>"
                    title="<?php echo esc_html($vehicaCurrentCar->getName()); ?>"
            >
                <?php echo esc_html($vehicaCurrentCar->getName()); ?>
            </a>

            <?php if (!empty($vehicaPrice)) : ?>
                <div class="vehica-car-card__price">
                    <?php echo esc_html($vehicaPrice); ?>
                </div>
            <?php elseif (vehicaApp('show_contact_for_price')) : ?>
                <div class="vehica-car-card__price">
                    <?php echo esc_html(vehicaApp('contact_for_price_string')); ?>
                </div>
            <?php endif; ?>

            <?php if ($vehicaAttributes->count()) : ?>
                <div class="vehica-car-card__separator"></div>

                <div class="vehica-car-card__info">
                    <?php foreach ($vehicaAttributes as $vehicaAttribute) : ?>
                        <div class="vehica-car-card__info__single">
                            <?php echo esc_html($vehicaAttribute); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>