<?php
/* @var \Vehica\Widgets\Car\Single\PhoneSingleCarWidget $vehicaCurrentWidget */
/* @var \Vehica\Model\Post\Car $vehicaCar */
global $vehicaCurrentWidget, $vehicaCar;

if (!$vehicaCar || !$vehicaCurrentWidget) {
    return;
}

$vehicaUser = $vehicaCar->getUser();
if (!$vehicaUser || !$vehicaUser->hasPhone() || $vehicaUser->hidePhone()) {
    return;
}
?>
<div class="vehica-app">
    <div class="vehica-phone-show-number">
        <vehica-phone
                user-id="<?php echo esc_attr($vehicaCar->getUserId()); ?>"
                request-url="<?php echo esc_url(admin_url('admin-ajax.php?action=vehica_phone')); ?>"
                :car-id="<?php echo esc_attr($vehicaCar->getId()); ?>"
        >
            <div slot-scope="props">
                <button
                        v-if="props.showPlaceholder"
                        @click.prevent="props.onShow"
                >
                    <i class="fas fa-phone-alt"></i>
                    <?php echo esc_html($vehicaCurrentWidget->getNumberPlaceholder($vehicaCar)); ?>
                    <?php echo esc_html(vehicaApp('show_number_string')); ?>
                </button>

                <template>
                    <a
                            v-if="!props.showPlaceholder"
                            :href="props.url"
                    >
                        <i class="fas fa-phone-alt"></i>
                        {{ props.label }}
                    </a>
                </template>
            </div>
        </vehica-phone>
    </div>
</div>
