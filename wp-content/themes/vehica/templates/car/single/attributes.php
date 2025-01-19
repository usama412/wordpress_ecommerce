<?php
/* @var \Vehica\Widgets\Car\Single\AttributesSingleCarWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
global $vehicaCar;

if (!$vehicaCar) {
    return;
}

$vehicaAttributes = $vehicaCurrentWidget->getAttributes();
if ($vehicaAttributes->isNotEmpty()) :?>
    <div class="vehica-app vehica-car-attributes">
        <?php if ($vehicaCurrentWidget->showTeaser()) : ?>
            <vehica-show>
                <div slot-scope="props">
                    <div v-if="!props.show" class="vehica-car-attributes-grid vehica-grid">
                        <?php foreach ($vehicaCurrentWidget->getTeaserAttributes() as $vehicaAttribute) : ?>
                            <div <?php $vehicaCurrentWidget->print_render_attribute_string('column'); ?>>
                                <div class="vehica-grid">
                                    <div class="vehica-car-attributes__name vehica-grid__element--1of2">
                                        <?php echo esc_html($vehicaAttribute['name']); ?><?php esc_html_e(':', 'vehica'); ?>
                                    </div>
                                    <div class="vehica-car-attributes__values vehica-grid__element--1of2">
                                        <?php foreach ($vehicaAttribute['values'] as $vehicaValueKey => $vehicaValue) :
                                            if ($vehicaValueKey) {
                                                echo ', ';
                                            }
                                            echo esc_html($vehicaValue);
                                        endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <template>
                        <div v-if="props.show" class="vehica-car-attributes-grid vehica-grid">
                            <?php foreach ($vehicaAttributes as $vehicaAttribute) : ?>
                                <div <?php $vehicaCurrentWidget->print_render_attribute_string('column'); ?>>
                                    <div class="vehica-grid">
                                        <div class="vehica-car-attributes__name vehica-grid__element--1of2">
                                            <?php echo esc_html($vehicaAttribute['name']); ?><?php esc_html_e(':', 'vehica'); ?>
                                        </div>
                                        <div class="vehica-car-attributes__values vehica-grid__element--1of2">
                                            <?php foreach ($vehicaAttribute['values'] as $vehicaValueKey => $vehicaValue) :
                                                if ($vehicaValueKey) {
                                                    echo ', ';
                                                }
                                                echo esc_html($vehicaValue);
                                            endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </template>

                    <div class="vehica-car-attributes__name vehica-color-text-primary">
                        <button class="vehica-show-more" v-if="!props.show" @click.prevent="props.onClick">
                            <?php echo esc_html(vehicaApp('show_more_string')); ?>
                        </button>
                        <template>
                            <button class="vehica-show-more" v-if="props.show" @click.prevent="props.onClick">
                                <?php echo esc_html(vehicaApp('show_less_string')); ?>
                            </button>
                        </template>
                    </div>
                </div>
            </vehica-show>

        <?php else : ?>
            <div class="vehica-car-attributes-grid vehica-grid">
                <?php foreach ($vehicaAttributes as $vehicaAttribute) : ?>
                    <div <?php $vehicaCurrentWidget->print_render_attribute_string('column'); ?>>
                        <div class="vehica-grid">
                            <div class="vehica-car-attributes__name vehica-grid__element--1of2">
                                <?php echo esc_html($vehicaAttribute['name']); ?><?php esc_html_e(':', 'vehica'); ?>
                            </div>
                            <div class="vehica-car-attributes__values vehica-grid__element--1of2">
                                <?php foreach ($vehicaAttribute['values'] as $vehicaValueKey => $vehicaValue) :
                                    if ($vehicaValueKey) {
                                        echo ', ';
                                    }
                                    echo esc_html($vehicaValue);
                                endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

<?php elseif (\Elementor\Plugin::instance()->editor->is_edit_mode()) : ?>
    <?php esc_html_e('List of attributes will be displayed here', 'vehica'); ?>
<?php
endif;
