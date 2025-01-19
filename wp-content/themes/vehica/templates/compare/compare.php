<?php
$vehicaInitialCars = \Vehica\Managers\CompareManager::getCars();
?>
<div class="vehica-app">
    <template>
        <vehica-compare-area
                request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica/compare/update')); ?>"
                :init-compare="<?php echo esc_attr(!empty($_GET['compare']) || vehicaApp('compare_mode') === 2 ? 'true' : 'false'); ?>"
            <?php if (!empty($vehicaInitialCars)) : ?>
                :initial-cars="<?php echo htmlspecialchars(json_encode($vehicaInitialCars)); ?>"
            <?php endif; ?>
        >
            <div slot-scope="compareArea">
                <div
                        v-if="compareArea.count > 0"
                        class="vehica-compare-mobile-wrapper"
                        :class="{'vehica-compare-mobile-wrapper--open': compareArea.open}"
                >
                    <a
                            v-if="compareArea.count > 1"
                            class="vehica-compare-mobile-wrapper__inner"
                            href="<?php echo esc_url(vehicaApp('settings_config')->getComparePageUrl()); ?>"
                    >
                        <?php echo esc_html(vehicaApp('compare_string')); ?>

                        <template>
                            ({{compareArea.cars.length}})
                        </template>
                    </a>

                    <span
                            v-if="compareArea.count === 1"
                            class="vehica-compare-mobile-wrapper__inner"
                    ><?php echo esc_html(vehicaApp('select_one_more_string')); ?></span>
                </div>

                <transition name="vehica-compare-area-wrapper">
                    <div
                            class="vehica-compare-area-wrapper"
                            :class="{'vehica-compare-area-wrapper--open': compareArea.open}"
                            v-if="compareArea.cars.length > 0"
                    >
                        <div class="vehica-compare-area-inner">
                            <div
                                    class="vehica-compare-area"
                                    :class="'vehica-compare-area--count-' + compareArea.count"
                            >
                                <div class="vehica-compare-area__top">
                                    <div
                                            class="vehica-compare-area__label"
                                            :class="{'vehica-compare-area__label--open': compareArea.open}"
                                            @click.prevent="compareArea.setOpen"
                                    >
                                        <i class="fas fa-exchange-alt"></i> <?php echo esc_html(vehicaApp('compare_string')); ?>

                                        ({{compareArea.cars.length}})
                                    </div>
                                </div>

                                <div
                                        class="vehica-compare-area__inner"
                                        :class="{'vehica-compare-area__inner--open': compareArea.open}"
                                >
                                    <div class="vehica-compare-area__listings">
                                        <div
                                                v-for="(car, index) in compareArea.cars"
                                                class="vehica-compare-area__listing vehica-compare-area__listing--filled"
                                        >
                                            <a
                                                    class="vehica-compare-area__image"
                                                    :href="car.url"
                                            >
                                                <img
                                                        v-if="car.image"
                                                        :src="car.image"
                                                        :alt="car.name"
                                                >

                                                <div
                                                        v-if="!car.image"
                                                        class="vehica-compare-area__icon"
                                                ></div>
                                            </a>

                                            <div
                                                    class="vehica-compare-area__remove"
                                                    @click.prevent="compareArea.remove(car.id)"
                                            >
                                                <i class="fas fa-times"></i>
                                            </div>

                                            <a
                                                    class="vehica-compare-area__name"
                                                    :href="car.url"
                                            >
                                                {{ car.name }}
                                            </a>
                                        </div>

                                        <div class="vehica-compare-area__listing">
                                            <div class="vehica-compare-area__image vehica-compare-area__image--placeholder"></div>
                                        </div>

                                        <div class="vehica-compare-area__listing">
                                            <div class="vehica-compare-area__image vehica-compare-area__image--placeholder"></div>
                                        </div>

                                        <div class="vehica-compare-area__listing">
                                            <div class="vehica-compare-area__image vehica-compare-area__image--placeholder"></div>
                                        </div>
                                    </div>

                                    <div class="vehica-compare-area__buttons">
                                        <div v-if="compareArea.cars.length > 1" class="vehica-compare-area__button">
                                            <a
                                                    class="vehica-button vehica-button--icon vehica-button--icon--compare"
                                                    href="<?php echo esc_url(vehicaApp('settings_config')->getComparePageUrl()); ?>"
                                            >
                                                <?php echo esc_html(vehicaApp('compare_string')); ?>
                                            </a>
                                        </div>

                                        <div class="vehica-compare-area__one-more"
                                             v-if="compareArea.cars.length <= 1">
                                            <?php echo esc_html(vehicaApp('compare_one_more_listing_string')); ?>
                                        </div>

                                        <div class="vehica-compare-area__carousel-buttons">
                                            <div
                                                    class="vehica-compare-area__prev"
                                                    @click.prevent="compareArea.prev"
                                            >
                                                <i class="fas fa-chevron-left"></i>
                                            </div>

                                            <div
                                                    class="vehica-compare-area__next"
                                                    @click.prevent="compareArea.next"
                                            >
                                                <i class="fas fa-chevron-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </vehica-compare-area>
    </template>
</div>