<?php
/* @var \Vehica\Widgets\Car\Single\AddToFavoriteSingleCarWidget $vehicaCurrentWidget */
/* @var \Vehica\Model\Post\Car $vehicaCar */
global $vehicaCurrentWidget, $vehicaCar;

if (!$vehicaCar || !$vehicaCurrentWidget || !vehicaApp('show_favorite')) {
    return;
}
?>
<div class="vehica-app">
    <div class="vehica-car-add-to-favorite__wrapper">
        <vehica-add-to-favorite
                :car-id="<?php echo esc_attr($vehicaCar->getId()); ?>"
                request-url="<?php echo esc_url(admin_url('admin-ajax.php?action=vehica_favorite')); ?>"
                redirect-url="<?php echo esc_url(vehicaApp('login_page_url')); ?>"
                vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_favorite')); ?>"
            <?php if (vehicaApp('current_user')) : ?>
                :is-logged="true"
                :initial-is-favorite="<?php echo esc_attr(vehicaApp('current_user')->isFavorite($vehicaCar) ? 'true' : 'false'); ?>"
            <?php else : ?>
                :is-logged="false"
                :initial-is-favorite="false"
            <?php endif; ?>
        >
            <div slot-scope="props">
                <button
                        class="vehica-car-add-to-favorite"
                        :class="{'vehica-car-add-to-favorite--is-favorite': props.isFavorite}"
                        @click.prevent="props.onAdd"
                >
                    <?php if (is_user_logged_in() && vehicaApp('current_user')->isFavorite($vehicaCar)) : ?>
                        <span v-if="false" class="vehica-car-add-to-favorite__favorite">
                            <i class="fas fa-star"></i>
                        </span>
                    <?php else : ?>
                        <span v-if="false" class="vehica-car-add-to-favorite__add-to-favorite">
                            <i class="far fa-star"></i> <?php echo esc_attr(vehicaApp('add_to_favorite_string')); ?>
                        </span>
                    <?php endif; ?>

                    <template>
                        <span v-if="props.isFavorite" class="vehica-car-add-to-favorite__favorite">
                            <i class="fas fa-star"></i> <?php echo esc_attr(vehicaApp('added_to_favorite_string')); ?>
                        </span>

                        <span v-if="!props.isFavorite" class="vehica-car-add-to-favorite__add-to-favorite">
                            <i class="far fa-star"></i> <?php echo esc_attr(vehicaApp('add_to_favorite_string')); ?>
                        </span>
                    </template>
                </button>
            </div>
        </vehica-add-to-favorite>
    </div>
</div>
