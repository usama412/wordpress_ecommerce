<?php
/* @var \Vehica\Widgets\General\PanelGeneralWidget $vehicaCurrentWidget */

global $vehicaCurrentWidget, $vehicaCarList;

$vehicaCarList = \Vehica\Components\Panel\CarList::make($_GET);
$vehicaAllCarsNumber = $vehicaCarList->getAllCarsNumber();
$vehicaActiveCarsNumber = $vehicaCarList->getActiveCarsNumber();
$vehicaPendingCarsNumber = $vehicaCarList->getPendingCarsNumber();
$vehicaDraftCarsNumber = $vehicaCarList->getDraftCarsNumber();
?>
<vehica-panel-car-list
        request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_car_list&vehica_status=' . $vehicaCarList->getStatus())); ?>"
        initial-keyword="<?php echo esc_attr($vehicaCarList->getKeyword()); ?>"
        base-url="<?php echo esc_url($vehicaCarList->getBaseUrl()); ?>"
>
    <div slot-scope="carList">
        <div class="vehica-panel-list vehica-container">
            <div class="vehica-panel-list__top">
                <div class="vehica-panel-list__statuses-wrapper">
                    <div class="vehica-panel-list__statuses">
                        <a
                            <?php if ($vehicaCarList->isAnyStatus()) : ?>
                                class="vehica-panel-list__status vehica-panel-list__status--active"
                            <?php else : ?>
                                class="vehica-panel-list__status"
                            <?php endif; ?>
                                href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getCarListPageUrl()); ?>"
                        >
                            <?php echo esc_html(vehicaApp('all_string')); ?>
                            <span>(<?php echo esc_html($vehicaAllCarsNumber) ?>)</span>
                        </a>
                        <a
                            <?php if ($vehicaCarList->isPublishStatus()) : ?>
                                class="vehica-panel-list__status vehica-panel-list__status--active"
                            <?php else : ?>
                                class="vehica-panel-list__status"
                            <?php endif; ?>
                                href="<?php echo esc_url($vehicaCarList->getActiveCarListPageUrl()); ?>"
                        >
                            <?php echo esc_html(vehicaApp('active_string')); ?>
                            <span>(<?php echo esc_html($vehicaActiveCarsNumber) ?>)</span>
                        </a>

                        <a
                            <?php if ($vehicaCarList->isPendingStatus()) : ?>
                                class="vehica-panel-list__status vehica-panel-list__status--active"
                            <?php else : ?>
                                class="vehica-panel-list__status"
                            <?php endif; ?>
                                href="<?php echo esc_url($vehicaCarList->getPendingCarListPageUrl()); ?>"
                        >
                            <?php echo esc_html(vehicaApp('pending_string')); ?>
                            <span>(<?php echo esc_html($vehicaPendingCarsNumber) ?>)</span>
                        </a>

                        <a
                            <?php if ($vehicaCarList->isDraftStatus()) : ?>
                                class="vehica-panel-list__status vehica-panel-list__status--active"
                            <?php else : ?>
                                class="vehica-panel-list__status"
                            <?php endif; ?>
                                href="<?php echo esc_url($vehicaCarList->getDraftCarListPageUrl()); ?>"
                        >
                            <?php echo esc_html(vehicaApp('draft_string')); ?>
                            <span>(<?php echo esc_html($vehicaDraftCarsNumber) ?>)</span>
                        </a>
                    </div>
                </div>

                <div class="vehica-panel-list__keyword">
                    <input
                            type="text"
                            placeholder="<?php echo esc_attr(vehicaApp('search_string')); ?>"
                            @input="carList.setKeyword($event.target.value)"
                            :value="carList.keyword"
                    >
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <div v-if="!carList.cars">
                <?php get_template_part('templates/general/panel/car_list_partial'); ?>
            </div>

            <template v-if="carList.cars">
                <div id="vehica-panel-car-list"></div>
            </template>
        </div>
    </div>
</vehica-panel-car-list>