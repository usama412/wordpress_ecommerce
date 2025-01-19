<?php
/* @var \Vehica\Model\Post\Car $vehicaCar */
/* @var \Vehica\Widgets\General\PanelGeneralWidget $vehicaCurrentWidget */
global $vehicaCar, $vehicaCurrentWidget;
$vehicaUser = $vehicaCar->getUser();
$vehicaCardFeatures = $vehicaCar->getSimpleTextValues(vehicaApp('panel_card_features'));
?>
<div class="vehica-panel-card vehica-panel-card--<?php echo esc_attr($vehicaCar->getStatus()); ?>">
    <?php if ($vehicaCar->isPublished()) : ?>
        <a
                class="vehica-panel-card__image"
                href="<?php echo esc_url($vehicaCar->getUrl()); ?>"
                title="<?php echo esc_html($vehicaCar->getName()); ?>"
                target="_blank"
        >
            <?php
            $vehicaCarLabel = $vehicaCar->getLabel();
            if ($vehicaCarLabel) : ?>
                <div
                        class="vehica-car-card__featured"
                        style="background-color: <?php echo esc_attr($vehicaCarLabel->getBackgroundColor()); ?>;"
                >
                    <div
                            class="vehica-car-card__featured__inner"
                            style="color: <?php echo esc_attr($vehicaCarLabel->getColor()); ?>;"
                    >
                        <?php echo esc_html($vehicaCarLabel->getText()); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($vehicaCar->hasImageUrl()) : ?>
                <img
                        src="<?php echo esc_url($vehicaCar->getImageUrl()); ?>"
                        alt="<?php echo esc_attr($vehicaCar->getName()); ?>"
                >
            <?php else: ?>
                <div class="vehica-panel-card__image-no-photo"></div>
            <?php endif; ?>
        </a>
    <?php else : ?>
        <div class="vehica-panel-card__image">
            <?php
            $vehicaCarLabel = $vehicaCar->getLabel();
            if ($vehicaCarLabel) : ?>
                <div
                        class="vehica-car-card__featured"
                        style="background-color: <?php echo esc_attr($vehicaCarLabel->getBackgroundColor()); ?>;"
                >
                    <div
                            class="vehica-car-card__featured__inner"
                            style="color: <?php echo esc_attr($vehicaCarLabel->getColor()); ?>;"
                    >
                        <?php echo esc_html($vehicaCarLabel->getText()); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($vehicaCar->hasImageUrl()) : ?>
                <img
                        src="<?php echo esc_url($vehicaCar->getImageUrl()); ?>"
                        alt="<?php echo esc_attr($vehicaCar->getName()); ?>"
                >
            <?php else: ?>
                <div class="vehica-panel-card__image-no-photo"></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="vehica-panel-card__details">
        <h3 class="vehica-panel-card__title">
            <?php if ($vehicaCar->isPublished() || current_user_can('manage_options')) : ?>
                <a
                        href="<?php echo esc_url($vehicaCar->getUrl()); ?>"
                        title="<?php echo esc_attr($vehicaCar->getName()); ?>"
                        target="_blank"
                >
                    <div
                        <?php if ($vehicaCar->isPublished()) : ?>
                            class="vehica-panel-card__status vehica-panel-card__status--active"
                        <?php elseif ($vehicaCar->isPending()) : ?>
                            class="vehica-panel-card__status vehica-panel-card__status--pending"
                        <?php elseif ($vehicaCar->isDraft()) : ?>
                            class="vehica-panel-card__status vehica-panel-card__status--draft"
                        <?php endif; ?>
                    >
                        <?php echo esc_html($vehicaCar->getStatusText()); ?>
                    </div>
                    <?php echo esc_html($vehicaCar->getName()); ?>
                </a>
            <?php else : ?>
                <div>
                    <div
                        <?php if ($vehicaCar->isPending()) : ?>
                            class="vehica-panel-card__status vehica-panel-card__status--pending"
                        <?php elseif ($vehicaCar->isDraft()) : ?>
                            class="vehica-panel-card__status vehica-panel-card__status--draft"
                        <?php endif; ?>
                    >
                        <?php echo esc_html($vehicaCar->getStatusText()); ?>
                    </div>
                    <?php echo esc_html($vehicaCar->getName()); ?>
                </div>
            <?php endif; ?>
        </h3>

        <div class="vehica-panel-card__top">
            <?php if ($vehicaUser && current_user_can('manage_options')) : ?>
                <div class="vehica-panel-card__user">
                    <a href="<?php echo esc_url($vehicaUser->getUrl()); ?>" target="_blank">
                        <?php if ($vehicaUser->hasImageUrl('vehica_100_100')) : ?>
                            <img
                                    src="<?php echo esc_url($vehicaUser->getImageUrl('vehica_100_100')); ?>"
                                    alt="<?php echo esc_attr($vehicaUser->getName()); ?>"
                            >
                        <?php else : ?>
                            <i class="fa fa-user"></i>
                        <?php endif; ?>
                        <span><?php echo esc_html($vehicaUser->getName()); ?></span>
                    </a>
                </div>
            <?php endif; ?>

            <div class="vehica-panel-card__dates">
                <?php if ($vehicaCar->isPublished()) : ?>
                    <div class="vehica-panel-card__date-total">
                        <i class="far fa-calendar"></i>
                        <?php if ($vehicaCar->hasExpireDate() && !$vehicaCar->isExpired()) : ?>
                            <span class="vehica-panel-card__dates__label"><?php echo esc_html(vehicaApp('expire_string')) ?>
                                :</span> <?php echo esc_html($vehicaCar->getExpireDateText()); ?>
                        <?php else : ?>
                            <span class="vehica-panel-card__dates__label"><?php echo esc_html(vehicaApp('published_string')) ?>
                                :</span> <?php echo esc_html($vehicaCar->getPublishDate()); ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if (!$vehicaCar->isPublished()) : ?>
                    <div class="vehica-panel-card__date-total">
                        <i class="far fa-calendar"></i>
                        <span class="vehica-panel-card__dates__label"><?php echo esc_html(vehicaApp('last_update_string')) ?>
                            :</span> <?php echo esc_html($vehicaCar->getLastUpdateDate()); ?>
                    </div>
                <?php endif; ?>

                <?php if ($vehicaCar->hasFeaturedExpireDate()) : ?>
                    <div class="vehica-panel-card__date-featured-expired">
                        <i class="fas fa-hourglass-end"></i>
                        <span class="vehica-panel-card__dates__label"><?php echo esc_html(vehicaApp('featured_expire_string')) ?>
                            :</span> <?php echo esc_html($vehicaCar->getFeaturedExpireDateText()); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($vehicaCardFeatures->isNotEmpty()) : ?>
            <div class="vehica-panel-card__features">
                <?php foreach ($vehicaCardFeatures as $vehicaIndex => $vehicaCardFeature) : ?>
                    <div class="vehica-panel-card__feature">
                        <?php echo esc_html($vehicaCardFeature); ?><span
                                class="vehica-panel-card__feature__comma">,</span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="vehica-panel-card__stats">
            <div class="vehica-panel-card__stat">
                <i class="far fa-eye"></i>
                <strong><?php echo esc_html($vehicaCar->getViews()); ?></strong>
                <?php echo esc_html(vehicaApp('views_string')); ?>
            </div>
            <div class="vehica-panel-card__stat">
                <i class="fas fa-phone-alt"></i>
                <strong><?php echo esc_html($vehicaCar->getPhoneClickNumber()); ?></strong>
                <?php echo esc_html(vehicaApp('phone_reveal_clicks_string')); ?>
            </div>
            <div class="vehica-panel-card__stat">
                <i class="fas fa-star"></i> <strong><?php echo esc_html($vehicaCar->getFavoriteNumber()); ?></strong>
                <?php echo esc_html(vehicaApp('favorite_string')); ?>
            </div>
        </div>

        <div class="vehica-panel-card__actions">
            <?php if ($vehicaCar->isPending() && current_user_can('manage_options')) : ?>
                <span class="vehica-panel-card__action">
                    <a href="<?php echo esc_url($vehicaCar->getApproveUrl()); ?>">
                        <i class="fas fa-check"></i>
                        <?php echo esc_html(vehicaApp('approve_string')); ?>
                    </a>
                </span>

                <span class="vehica-panel-card__action">
                    <a href="<?php echo esc_url($vehicaCar->getDeclineUrl()); ?>">
                        <i class="fas fa-times"></i>
                        <?php echo esc_html(vehicaApp('decline_string')); ?>
                    </a>
                </span>
            <?php endif; ?>

            <?php if (
                $vehicaCar->isDraft()
                && !$vehicaCar->hasExpireDate()
                && $vehicaCar->isCurrentUserOwner()
                && vehicaApp('settings_config')->isPaymentEnabled()
                && (!vehicaApp('woocommerce_mode') || vehicaApp('current_user')->hasPackages())
            ) : ?>
                <span class="vehica-panel-card__action">
                    <a href="<?php echo esc_url($vehicaCar->getFrontendEditUrl('#vehica-select-package')); ?>">
                        <i class="fas fa-check"></i>
                        <?php echo esc_html(vehicaApp('publish_string')); ?>
                    </a>
                </span>
            <?php endif; ?>

            <?php if (
                $vehicaCar->isDraft()
                && !$vehicaCar->hasExpireDate()
                && $vehicaCar->isCurrentUserOwner()
                && vehicaApp('settings_config')->isPaymentEnabled()
                && (vehicaApp('woocommerce_mode') && !vehicaApp('current_user')->hasPackages())
            ) : ?>
                <span class="vehica-panel-card__action">
                    <a href="<?php echo esc_url(admin_url('admin-post.php?action=vehica_assign_car_and_buy_package&carId='.$vehicaCar->getId())); ?>">
                        <i class="fas fa-check"></i>
                        <?php echo esc_html(vehicaApp('publish_string')); ?>
                    </a>
                </span>
            <?php endif; ?>

            <?php if ($vehicaCar->isDraft() && !vehicaApp('settings_config')->isPaymentEnabled()) : ?>
                <span class="vehica-panel-card__action">
                    <a href="<?php echo esc_url($vehicaCar->getFrontendPublishUrl()); ?>">
                        <i class="fas fa-check"></i>
                        <?php echo esc_html(vehicaApp('publish_string')); ?>
                    </a>
                </span>
            <?php endif; ?>

            <span class="vehica-panel-card__action">
                <a href="<?php echo esc_url($vehicaCar->getFrontendEditUrl()); ?>">
                    <i class="fas fa-pencil-alt"></i>
                    <?php echo esc_html(vehicaApp('edit_string')); ?>
                </a>
            </span>

            <vehica-panel-delete-car
                    :car-id="<?php echo esc_attr($vehicaCar->getId()); ?>"
                    vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_delete_car_'.$vehicaCar->getId())); ?>"
                    request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_delete_car')); ?>"
                    confirm-string="<?php echo esc_attr(vehicaApp('confirm_string')); ?>"
                    success-string="<?php echo esc_attr(vehicaApp('success_string')); ?>"
                    error-string="<?php echo esc_attr(vehicaApp('error_string')); ?>"
                    in-progress-string="<?php echo esc_attr(vehicaApp('in_progress_string')); ?>"
                    cancel-string="<?php echo esc_attr(vehicaApp('cancel_string')); ?>"
                    message-string="<?php echo esc_attr(vehicaApp('are_you_sure_string')); ?>"
            >
                <span
                        slot-scope="props"
                        @click.prevent="props.onDelete"
                        class="vehica-panel-card__action"
                >
                    <span class="vehica-panel-card__action__del">
                        <i class="fas fa-trash"></i>
                        <?php echo esc_html(vehicaApp('delete_string')); ?>
                    </span>
                </span>
            </vehica-panel-delete-car>
        </div>

        <?php if ($vehicaCar->isPublished() || current_user_can('manage_options')) : ?>
            <div class="vehica-panel-card__view-button">
                <a
                        href="<?php echo esc_url($vehicaCar->getUrl()); ?>"
                        title="<?php echo esc_attr($vehicaCar->getName()); ?>"
                        target="_blank"
                        class="vehica-button"
                >
                    <?php echo esc_html(vehicaApp('view_string')); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
