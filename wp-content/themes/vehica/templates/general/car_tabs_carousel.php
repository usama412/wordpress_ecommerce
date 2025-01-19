<?php
/* @var \Vehica\Widgets\General\CarTabsCarouselGeneralWidget $vehicaCurrentWidget */
/* @var \Vehica\Components\Card\Car\CardV1 */
global $vehicaCurrentWidget, $vehicaCarCard;
$vehicaCarCard = $vehicaCurrentWidget->getCardV1();
$vehicaCars = $vehicaCurrentWidget->getCars();
?>
<div class="vehica-app vehica-<?php echo esc_attr($vehicaCurrentWidget->get_id_int()); ?>">
    <vehica-car-tabs
            widget-id="<?php echo esc_attr($vehicaCurrentWidget->get_id()); ?>"
            :tabs="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getTabs())); ?>"
            :featured="<?php echo esc_attr($vehicaCurrentWidget->featured() ? 'true' : 'false'); ?>"
            :include-excluded="<?php echo esc_attr($vehicaCurrentWidget->includeExcluded() ? 'true' : 'false'); ?>"
            :card-config="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getCardV1Config())); ?>"
            request-url="<?php echo esc_url(admin_url('admin-ajax.php?action=vehica_car_carousel')); ?>"
            :limit="<?php echo esc_attr($vehicaCurrentWidget->getCarsNumber()); ?>"
            sort-by="<?php echo esc_attr($vehicaCurrentWidget->getSortBy()); ?>"
            sort-by-rewrite="<?php echo esc_attr(vehicaApp('sort_by_rewrite')); ?>"
            content-class="vehica-swiper-wrapper"
    >
        <div
                slot-scope="carTabs"
                class="vehica-car-tabs-carousel vehica-car-tabs-carousel__arrows-<?php echo esc_attr($vehicaCurrentWidget->getArrowsPosition()); ?>"
            <?php if (!$vehicaCurrentWidget->includeExcluded()) : ?>
                :class="'vehica-carousel-v1--cars-' + carTabs.viewAllCount"
            <?php endif; ?>
        >
            <div class="vehica-tabs-top-v2">
                <h3 class="vehica-tabs-top-v2__heading">
                    <?php echo esc_html($vehicaCurrentWidget->getHeadingText()); ?>
                </h3>

                <?php if ($vehicaCurrentWidget->hasTabs()) : ?>
                    <div class="vehica-tabs-top-v2__tabs">
                        <div v-dragscroll.x="true" class="vehica-tabs-wrapper">
                            <div class="vehica-tabs">
                                <?php foreach ($vehicaCurrentWidget->getTabs() as $vehicaTabKey => $vehicaTab) :
                                    /* @var \Vehica\Model\Term\Term $vehicaTab */ ?>
                                    <div
                                            class="vehica-tab vehica-tab--big vehica-tab--bg-white"
                                            :class="{'vehica-tab--active': carTabs.isActive('<?php echo esc_attr($vehicaTab->getKey()); ?>')}"
                                            @click="carTabs.setTab('<?php echo esc_attr($vehicaTab->getKey()); ?>')"
                                    >
                                        <div class="vehica-tab__title">
                                            <?php echo esc_html($vehicaTab->getName()); ?>
                                        </div>

                                        <div class="vehica-tab__subtitle">
                                            <?php echo esc_html($vehicaTab->getCount($vehicaCurrentWidget->includeExcluded())); ?>
                                            <?php echo esc_html(vehicaApp('vehicles_string')); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <div class="vehica-carousel-v1__tab-ghost"></div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="vehica-carousel-v1">
                <vehica-swiper
                        :config="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getSwiperConfig())); ?>"
                        widget-id="<?php echo esc_attr($vehicaCurrentWidget->get_id()); ?>"
                        :breakpoints="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getBreakpoints())) ?>"
                >
                    <div slot-scope="swiperProps">
                        <div class="vehica-carousel__swiper">
                            <div class="vehica-swiper-container vehica-swiper-container-horizontal">
                                <div class="vehica-swiper-wrapper">
                                    <?php
                                    global $vehicaCurrentCar;
                                    foreach ($vehicaCars as $vehicaCurrentCar) : ?>
                                        <?php get_template_part('templates/shared/car_carousel'); ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <template>
                            <div class="vehica-carousel-v1__arrows">
                                <button
                                        class="vehica-carousel__arrow vehica-carousel__arrow--left"
                                        @click.prevent="swiperProps.prevSlide"
                                >
                                </button>

                                <button
                                        class="vehica-carousel__arrow vehica-carousel__arrow--right"
                                        @click.prevent="swiperProps.nextSlide"
                                >
                                </button>
                            </div>
                        </template>
                    </div>
                </vehica-swiper>
            </div>

            <?php if ($vehicaCurrentWidget->showElement('view_all_button')) : ?>
                <div class="vehica-carousel-v1-button">
                    <a class="vehica-button" :href="carTabs.viewAllUrl" :title="carTabs.viewAllTitle">
                        <?php echo esc_html(vehicaApp('view_string')); ?>
                        <template>{{ carTabs.viewAllCount }} {{ carTabs.viewAllTitle }}</template>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </vehica-car-tabs>
</div>