<?php
/* @var \Vehica\Widgets\Car\Single\DescriptionSingleCarWidget $vehicaCurrentWidget */
/* @var \Vehica\Model\Post\Car $vehicaCar */
global $vehicaCurrentWidget, $vehicaCar;

if (!$vehicaCar || !$vehicaCar->hasContent()) {
    return;
}
?>
<div class="vehica-app">
    <?php if ($vehicaCurrentWidget->showLabel()) : ?>
        <h3 class="vehica-section-label vehica-section-label--description">
            <?php echo esc_html($vehicaCurrentWidget->getLabel()); ?>
        </h3>
    <?php endif; ?>

    <?php if ($vehicaCurrentWidget->limitLength()) : ?>
        <vehica-show>
            <div slot-scope="props" class="vehica-car-description">
                <div v-if="!props.show" class="vehica-car-description__inner">
                    <?php $vehicaCurrentWidget->showTeaser(); ?>
                </div>

                <template v-if="props.show">
                    <?php $vehicaCar->displayContent(); ?>
                </template>

                <div class="vehica-color-text-primary">
                    <button
                            v-if="!props.show"
                            class="vehica-show-more"
                            @click.prevent="props.onClick"
                    >
                        <?php echo esc_html(vehicaApp('show_more_string')); ?>
                    </button>

                    <template>
                        <button
                                v-if="props.show"
                                class="vehica-show-more"
                                @click.prevent="props.onClick"
                        >
                            <?php echo esc_html(vehicaApp('show_less_string')); ?>
                        </button>
                    </template>
                </div>
            </div>
        </vehica-show>
    <?php else : ?>
        <div class="vehica-car-description">
            <?php if (vehicaApp('settings_config')->getDescriptionType() === 'rich') : ?>
                <?php $vehicaCar->displayContent(); ?>
            <?php else : ?>
                <?php echo wp_kses_post(nl2br($vehicaCar->getContent())); ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
