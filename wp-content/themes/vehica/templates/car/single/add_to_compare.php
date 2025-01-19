<?php
/* @var \Vehica\Widgets\Car\Single\AddToCompareSingleCarWidget $vehicaCurrentWidget */
/* @var \Vehica\Model\Post\Car $vehicaCar */
global $vehicaCurrentWidget, $vehicaCar;

if (!$vehicaCar || !$vehicaCurrentWidget) {
    return;
}
?>
<div class="vehica-app">
    <vehica-add-to-compare :car-id="<?php echo esc_attr($vehicaCar->getId()); ?>">
        <div slot-scope="addToCompare">
            <div v-if="addToCompare.compareMode" :class="{'vehica-car-card--is-compare': addToCompare.isAdded}">
                <div class="vehica-compare-add vehica-compare-add--solo">
                    <div
                            class="vehica-checkbox"
                            @click.prevent="addToCompare.set"
                    >
                        <input
                                id="vehica-compare--<?php echo esc_attr($vehicaCar->getId()); ?>"
                                type="checkbox"
                                :checked="addToCompare.isAdded"
                        >

                        <label for="vehica-compare--<?php echo esc_attr($vehicaCar->getId()); ?>">
                            <span v-if="!addToCompare.isAdded"><?php echo esc_html(vehicaApp('add_to_compare_string')); ?></span>
                            <template>
                                <span v-if="addToCompare.isAdded"><?php echo esc_html(vehicaApp('added_to_compare_string')); ?></span>
                            </template>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </vehica-add-to-compare>
</div>