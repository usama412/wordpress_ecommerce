<?php
/* @var \Vehica\Widgets\General\CompareGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;

$vehicaCompareFields = $vehicaCurrentWidget->getFields();
$vehicaIsEditMode = \Elementor\Plugin::instance()->editor->is_edit_mode();
?>
<div class="vehica-app">
    <vehica-compare-nav
            redirect-url="<?php echo esc_url(vehicaApp('car_archive_url')); ?>"
            :initial-cars="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getCarsData())); ?>"
            :redirect="<?php echo esc_attr((\Elementor\Plugin::instance()->editor->is_edit_mode() || is_front_page()) ? 'false' : 'true'); ?>"
    >
        <div slot-scope="compareNav">
            <?php if (!$vehicaIsEditMode) : ?>
                <template>
                    <div v-if="!compareNav.count">
                        <div class="vehica-compare__loader">
                            <div class="vehica-compare__loader-inner">
                                <svg width="120" height="30" viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg"
                                     fill="#fff">
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
                            </div>
                        </div>
                    </div>
                </template>
            <?php endif; ?>

            <div
                <?php if (!$vehicaIsEditMode) : ?>
                    v-if="compareNav.count > 0"
                <?php endif; ?>
                    class="vehica-compare"
                    :class="'vehica-compare--count-' + compareNav.count"
            >
                <div class="vehica-compare__data">
                    <div class="vehica-compare__row">
                        <div class="vehica-compare__column">
                            <h1 class="vehica-compare__heading">
                                <?php echo esc_html(vehicaApp('compare_string')); ?>
                                <template>({{ compareNav.count }})</template>
                            </h1>

                            <div class="vehica-compare__buttons">
                                <div class="vehica-compare__back-to-search">
                                    <a class="vehica-button vehica-button--outline"
                                       href="<?php echo esc_url(vehicaApp('car_archive_url')); ?>?compare=1">
                                        <?php echo esc_html(vehicaApp('back_to_search_string')); ?>
                                    </a>
                                </div>
                                <div class="vehica-compare__arrows">
                                    <template>
                                        <div
                                                class="vehica-carousel__arrow vehica-carousel__arrow--left"
                                                @click.prevent="compareNav.prev"
                                        >
                                        </div>

                                        <div
                                                class="vehica-carousel__arrow vehica-carousel__arrow--right"
                                                @click.prevent="compareNav.next"
                                        >
                                        </div>
                                    </template>
                                </div>
                            </div>

                        </div>

                        <template>
                            <div
                                    v-for="car in compareNav.cars"
                                    :key="car.id"
                                    class="vehica-compare__column"
                                    :class="'vehica-compare__column vehica-compare__column--' + car.id"
                            >
                                <div class="vehica-compare__image-wrapper">
                                    <a
                                            class="vehica-compare__image"
                                            :href="car.url"
                                    >
                                        <img
                                                v-if="car.image"
                                                :src="car.image"
                                                :alt="car.name"
                                        >

                                        <div
                                                v-if="!car.image"
                                                class="vehica-compare__image-wrapper__icon"
                                        ></div>
                                    </a>

                                    <div
                                            class="vehica-compare__remove"
                                            @click.prevent="compareNav.remove(car.id)"
                                    >
                                        <i class="fas fa-times"></i>
                                    </div>

                                    <button
                                            class="vehica-compare__lock"
                                            @click.prevent="compareNav.setLock(car.id)"
                                    >
                                        <span v-if="compareNav.lockedCarId !== car.id"><i
                                                    class="fas fa-unlock"></i> <?php echo esc_html(vehicaApp('lock_string')); ?></span>

                                        <span v-if="compareNav.lockedCarId === car.id"><i
                                                    class="fas fa-lock"></i> <?php echo esc_html(vehicaApp('locked_string')); ?></span>
                                    </button>
                                </div>

                                <h3 class="vehica-compare__name">
                                    <a :href="car.url">
                                        {{ car.name }}
                                    </a>
                                </h3>
                            </div>

                            <div class="vehica-compare__column">
                                <a
                                        href="<?php echo esc_url(vehicaApp('car_archive_url')); ?>?compare=1"
                                        class="vehica-compare__image-placeholder"
                                >
                                    <div class="vehica-compare__image-placeholder__inner">
                                        <div>
                                            <i class="fa fa-plus"></i>
                                        </div>

                                        <div>
                                            <?php echo esc_html(vehicaApp('add_another_listing_string')); ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </template>
                    </div>

                    <?php foreach ($vehicaCompareFields as $vehicaCompareField) :
                        /* @var array $vehicaCompareField */
                        /* @var \Vehica\Attribute\SimpleTextAttribute $vehicaField */
                        $vehicaField = $vehicaCompareField['field'];

                        if (!$vehicaCompareField['multiple']) :?>
                            <div
                                    class="vehica-compare__row"
                                <?php if ($vehicaCurrentWidget->hideEmptyRows()) : ?>
                                    v-if="compareNav.someCarHasValue(<?php echo esc_attr($vehicaField->getId()); ?>)"
                                <?php endif; ?>
                            >
                                <div
                                    <?php if (!empty($vehicaCompareField['featured'])) : ?>
                                        class="vehica-compare__column vehica-compare__column--label vehica-compare__column--featured-label"
                                    <?php else : ?>
                                        class="vehica-compare__column vehica-compare__column--label"
                                    <?php endif; ?>
                                >
                                    <?php echo esc_html($vehicaField->getName()); ?>
                                </div>

                                <template>
                                    <div
                                            v-for="car in compareNav.cars"
                                            :key="car.id"
                                        <?php if ($vehicaCompareField['featured']) : ?>
                                            class="vehica-compare__column vehica-compare__column--featured"
                                        <?php else : ?>
                                            class="vehica-compare__column"
                                        <?php endif; ?>
                                            :class="'vehica-compare__column--' + car.id"
                                    >
                                        {{ car[<?php echo esc_html($vehicaField->getId()); ?>] }}
                                    </div>

                                    <div class="vehica-compare__column">
                                        -
                                    </div>
                                </template>
                            </div>
                        <?php else : ?>
                            <?php
                            $vehicaTerms = $vehicaField->getTerms();
                            /* @var \Vehica\Model\Post\Field\Taxonomy\Taxonomy $vehicaField */
                            foreach ($vehicaTerms as $vehicaTerm) :
                                /* @var \Vehica\Model\Term\Term $vehicaTerm */
                                ?>
                                <div
                                    <?php if (isset($vehicaCompareField['hide_empty']) && $vehicaCompareField['hide_empty']) : ?>
                                        v-if="compareNav.someCarNotEmpty(<?php echo esc_attr($vehicaField->getId()); ?>, <?php echo esc_attr($vehicaTerm->getId()); ?>)"
                                    <?php endif; ?>
                                        class="vehica-compare__row"
                                >
                                    <div class="vehica-compare__column vehica-compare__column--label">
                                        <?php echo esc_html($vehicaTerm->getName()); ?>
                                    </div>

                                    <template>
                                        <div
                                                v-for="car in compareNav.cars"
                                            <?php if ($vehicaCompareField['featured']) : ?>
                                                class="vehica-compare__column vehica-compare__column--featured"
                                            <?php else : ?>
                                                class="vehica-compare__column"
                                            <?php endif; ?>
                                                :class="'vehica-compare__column--' + car.id"
                                        >
                                            <span
                                                    v-if="car[<?php echo esc_attr($vehicaField->getId()); ?>].indexOf(<?php echo esc_attr($vehicaTerm->getId()); ?>) !== -1"
                                                    class="vehica-compare__yes"
                                            ><?php echo esc_html(vehicaApp('yes_string')); ?></span>

                                            <span
                                                    v-if="car[<?php echo esc_attr($vehicaField->getId()); ?>].indexOf(<?php echo esc_attr($vehicaTerm->getId()); ?>) === -1"
                                                    class="vehica-compare__no"
                                            ><?php echo esc_html(vehicaApp('no_string')); ?></span>
                                        </div>

                                        <div class="vehica-compare__column">
                                            -
                                        </div>
                                    </template>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <div class="vehica-compare__row">
                        <div class="vehica-compare__column"></div>

                        <template>
                            <div
                                    v-for="car in compareNav.cars"
                                    :key="car.id"
                                    class="vehica-compare__column"
                                    :class="'vehica-compare__column--' + car.id"
                            >
                                <div class="vehica-compare__button">
                                    <a
                                            class="vehica-button"
                                            :href="car.url"
                                            :title="car.name"
                                            target="_blank"
                                    >
                                        <?php echo esc_html(vehicaApp('view_string')); ?>
                                    </a>
                                </div>
                            </div>

                            <div class="vehica-compare__column"></div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </vehica-compare-nav>
</div>