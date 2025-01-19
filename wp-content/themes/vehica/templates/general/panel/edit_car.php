<?php
/* @var \Vehica\Widgets\General\PanelGeneralWidget $vehicaCurrentWidget */

global $vehicaCurrentWidget;

$vehicaCar = $vehicaCurrentWidget->getEditCar();

if (!$vehicaCar) {
    return;
}
?>

<div class="vehica-car-form">
    <div class="vehica-car-form__inner">
        <h3 class="vehica-car-form__heading">
            <?php echo esc_attr(vehicaApp('edit_colon_string')); ?><?php echo esc_html($vehicaCar->getName()); ?>
        </h3>

        <vehica-car-form
                request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_update_car')) ?>"
                redirect-url="<?php echo esc_url($vehicaCurrentWidget->getCreateCarRedirectUrl()); ?>"
                vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_update_car')); ?>"
                vehica-checkout-nonce="<?php echo esc_attr(wp_create_nonce('vehica_buy_package')); ?>"
                checkout-url="<?php echo esc_url(admin_url('admin-ajax.php?action=vehica/woocommerce/checkout')); ?>"
                error-msg="<?php echo esc_attr(vehicaApp('error_required_fields_string')); ?>"
                :is-logged="true"
                confirmation-text="<?php echo esc_attr(vehicaApp('ok_string')); ?>"
                success-text="<?php echo esc_attr(vehicaApp('added_string')); ?>"
                order-not-paid-text="<?php echo esc_attr(vehicaApp('order_not_paid_string')); ?>"
                choose-package-text="<?php echo esc_attr(vehicaApp('choose_package_string')); ?>"
                :initial-car="<?php echo htmlspecialchars(json_encode($vehicaCar)); ?>"
                :show-thank-you-modal="<?php echo esc_attr($vehicaCurrentWidget->showThankYouModal() ? 'true' : 'false'); ?>"
                :re-captcha="false"
                :require-description="<?php echo esc_attr(vehicaApp('settings_config')->isDescriptionRequired() ? 'true' : 'false'); ?>"
                :woocommerce-mode="<?php echo esc_attr(vehicaApp('woocommerce_mode') ? 'true' : 'false'); ?>"
            <?php if (vehicaApp('auto_title_fields')->isEmpty()) : ?>
                :require-name="true"
            <?php else : ?>
                :require-name="false"
            <?php endif; ?>
            <?php if (!$vehicaCar->hasExpireDate() && $vehicaCurrentWidget->requireSelectPackage() && !$vehicaCar->hasPendingPackage()) : ?>
                :payment-enabled="true"
            <?php else : ?>
                :payment-enabled="false"
            <?php endif; ?>
        >
            <div slot-scope="carForm">
                <form @submit.prevent="carForm.onUpdate">
                    <div class="vehica-car-form__section vehica-car-form__section--edit-car">
                        <div class="vehica-car-form__grid-wrapper">
                            <div class="vehica-car-form__grid">

                                <?php if (vehicaApp('auto_title_fields')->isEmpty()) : ?>
                                    <div class="vehica-car-form__grid-element vehica-car-form__grid-element--row">
                                        <?php
                                        $vehicaNameField = $vehicaCurrentWidget->getNameField();
                                        $vehicaNameField->loadTemplate(); ?>
                                    </div>
                                <?php endif; ?>

                                <?php foreach ($vehicaCurrentWidget->getSingleValueFields() as $vehicaPanelField) :
                                    /* @var \Vehica\Panel\PanelField\PanelField $vehicaPanelField */
                                    if ($vehicaPanelField instanceof \Vehica\Panel\PanelField\DateTimePanelField || $vehicaPanelField instanceof \Vehica\Panel\PanelField\PricePanelField) :
                                        $vehicaPanelField->loadTemplate();
                                    else :?>
                                        <div class="vehica-car-form__grid-element vehica-relation-field vehica-car-form-field__<?php echo esc_attr($vehicaPanelField->getKey()); ?>">
                                            <?php echo esc_html($vehicaPanelField->loadTemplate()); ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php
                        $vehicaDescriptionPanelField = $vehicaCurrentWidget->getDescriptionField();
                        if ($vehicaDescriptionPanelField) : ?>
                            <div class="vehica-car-form__grid-element vehica-car-form__grid-element--row vehica-car-form-field__description">
                                <?php $vehicaDescriptionPanelField->loadTemplate(); ?>
                            </div>
                        <?php endif; ?>

                        <?php foreach ($vehicaCurrentWidget->getEmbedFields() as $vehicaPanelField) : ?>
                            <div class="vehica-car-form__grid-element vehica-car-form__grid-element--row vehica-relation-field vehica-car-form-field__<?php echo esc_attr($vehicaPanelField->getKey()); ?>">
                                <?php $vehicaPanelField->loadTemplate(); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php foreach ($vehicaCurrentWidget->getGalleryFields() as $vehicaPanelField) : ?>
                        <?php $vehicaPanelField->loadTemplate(); ?>
                    <?php endforeach; ?>

                    <?php foreach ($vehicaCurrentWidget->getAttachmentsFields() as $vehicaPanelField) : ?>
                        <?php $vehicaPanelField->loadTemplate(); ?>
                    <?php endforeach; ?>

                    <?php foreach ($vehicaCurrentWidget->getLocationFields() as $vehicaPanelField) : ?>
                        <?php $vehicaPanelField->loadTemplate(); ?>
                    <?php endforeach; ?>

                    <?php foreach ($vehicaCurrentWidget->getMultiValueFields() as $vehicaPanelField) : ?>
                        <?php $vehicaPanelField->loadTemplate(); ?>
                    <?php endforeach; ?>

                    <?php if (!$vehicaCar->hasExpireDate() && $vehicaCurrentWidget->showSelectPackages() && !$vehicaCar->hasPendingPackage()) : ?>
                        <?php get_template_part('templates/general/panel/select_package'); ?>
                    <?php endif; ?>

                    <div class="vehica-car-form__save-submit">
                        <?php if ($vehicaCar->isPending() && current_user_can('manage_options')) : ?>
                            <div class="vehica-car-form__save-submit__admin-buttons">
                                <a
                                        class="vehica-button vehica-button--approve"
                                        href="<?php echo esc_url($vehicaCar->getApproveUrl()); ?>">
                                    <i class="fas fa-check"></i>
                                    <?php echo esc_html(vehicaApp('approve_string')); ?>
                                </a>
                                <a class="vehica-button vehica-button--decline"
                                   href="<?php echo esc_url($vehicaCar->getDeclineUrl()); ?>">
                                    <i class="fas fa-times"></i>
                                    <?php echo esc_html(vehicaApp('decline_string')); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ($vehicaCurrentWidget->showFeaturedCheckbox()) : ?>
                            <div class="vehica-checkbox vehica-checkbox--featured-big">
                                <input
                                        type="checkbox"
                                        id="vehica-checkbox-featured"
                                        @change="carForm.setFeatured"
                                        :checked="carForm.car.featured"
                                >

                                <label for="vehica-checkbox-featured">
                                    <?php echo esc_html(vehicaApp('featured_string')); ?>
                                </label>
                            </div>
                        <?php endif; ?>

                        <div class="vehica-car-form__save-submit__save-changes">
                            <button
                                    class="vehica-button vehica-button--with-progress-animation"
                                    :class="{'vehica-button--with-progress-animation--active': carForm.disabled, 'vehica-button--with-progress-animation--gallery-in-progress': carForm.inProgress}"
                                    :disabled="carForm.disabled || carForm.inProgress"
                            >
                                <span class="vehica-button__text-initial"><?php echo esc_html(vehicaApp('save_changes_string')); ?></span>
                                <span class="vehica-button__text-disabled"><i
                                            class="fas fa-file-import"></i> <?php echo esc_html(vehicaApp('uploading_files_please_wait_string')); ?>
                                </span>

                                <template>
                                    <svg
                                            v-if="carForm.disabled"
                                            width="120"
                                            height="30"
                                            wviewBox="0 0 120 30"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="#fff"
                                    >
                                        <circle cx="15" cy="15" r="15">
                                            <animate attributeName="r" from="15" to="15"
                                                     begin="0s" dur="0.8s"
                                                     values="15;9;15" calcMode="linear"
                                                     repeatCount="indefinite"/>
                                            <animate attributeName="fill-opacity" from="1" to="1"
                                                     begin="0s" dur="0.8s"
                                                     values="1;.5;1" calcMode="linear"
                                                     repeatCount="indefinite"/>
                                        </circle>
                                        <circle cx="60" cy="15" r="9" fill-opacity="0.3">
                                            <animate attributeName="r" from="9" to="9"
                                                     begin="0s" dur="0.8s"
                                                     values="9;15;9" calcMode="linear"
                                                     repeatCount="indefinite"/>
                                            <animate attributeName="fill-opacity" from="0.5" to="0.5"
                                                     begin="0s" dur="0.8s"
                                                     values=".5;1;.5" calcMode="linear"
                                                     repeatCount="indefinite"/>
                                        </circle>
                                        <circle cx="105" cy="15" r="15">
                                            <animate attributeName="r" from="15" to="15"
                                                     begin="0s" dur="0.8s"
                                                     values="15;9;15" calcMode="linear"
                                                     repeatCount="indefinite"/>
                                            <animate attributeName="fill-opacity" from="1" to="1"
                                                     begin="0s" dur="0.8s"
                                                     values="1;.5;1" calcMode="linear"
                                                     repeatCount="indefinite"/>
                                        </circle>
                                    </svg>
                                </template>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </vehica-car-form>
    </div>
</div>