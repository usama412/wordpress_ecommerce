<?php

global $vehicaCarList;
$vehicaCars = $vehicaCarList->getCars();

if ($vehicaCars->isEmpty()) : ?>
    <div class="vehica-panel-list vehica-container">
        <div class="vehica-panel-list__elements">
            <div class="vehica-panel__car-list__cars">
                <h2 class="vehica-panel-list-no-found">
                    <?php echo esc_html(vehicaApp('no_cars_found_in_string')); ?>
                    <span class="vehica-panel-list-no-found__highlight"><?php echo esc_html($vehicaCarList->getStatusLabel()); ?></span>
                </h2>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="vehica-panel-list__elements">
        <div class="vehica-panel__car-list__cars">
            <?php
            global $vehicaCar;
            foreach ($vehicaCarList->getCars() as $vehicaCar) :
                get_template_part('templates/general/panel/car_card');
            endforeach;
            ?>
        </div>

        <template>
            <vehica-pagination
                    :total-items="<?php echo esc_attr($vehicaCarList->getPaginationCarsNumber()); ?>"
                    :current-page="<?php echo esc_attr($vehicaCarList->getCurrentPage()); ?>"
                    :page-size="<?php echo esc_attr(apply_filters('vehica/panel/list/limit', 10)); ?>"
                    :max-pages="7"
            >
                <div
                        slot-scope="pagination"
                        v-if="pagination.pages.length > 1"
                >
                    <div class="vehica-panel-pagination">
                        <a
                                v-if="pagination.currentPage > 1"
                                class="vehica-panel-pagination__element vehica-panel-pagination__element--arrow"
                                href="<?php echo esc_url($vehicaCarList->getPaginationPrev()); ?>"
                        >
                            <i class="fas fa-chevron-left"></i>
                        </a>

                        <a
                                v-if="pagination.startPage > 1"
                                class="vehica-panel-pagination__element"
                                :href="'<?php echo esc_attr($vehicaCarList->getPageUrl()); ?>1'"
                        >
                            <span>1</span>
                        </a>

                        <div class="vehica-panel-pagination__element" v-if="pagination.startPage > 2">
                            <span>...</span>
                        </div>

                        <a
                                v-for="page in pagination.pages"
                                class="vehica-panel-pagination__element"
                                :class="{'vehica-panel-pagination__element--active': page === pagination.currentPage}"
                                :href="'<?php echo esc_attr($vehicaCarList->getPageUrl()); ?>' + page"
                        >
                            <span>{{ page }}</span>
                        </a>

                        <a
                                v-if="pagination.currentPage < pagination.endPage"
                                class="vehica-panel-pagination__element vehica-panel-pagination__element--arrow"
                                href="<?php echo esc_url($vehicaCarList->getPaginationNext()); ?>"
                        >
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>

                    <div class="vehica-pagination-mobile vehica-pagination-mobile--panel">
                        <a
                                class="vehica-pagination-mobile__arrow vehica-pagination-mobile__arrow--left"
                                :class="{'vehica-pagination-mobile__arrow--disabled': pagination.currentPage <= 1}"
                                href="<?php echo esc_url($vehicaCarList->getPaginationPrev()); ?>"
                        >
                            <i class="fas fa-chevron-left"></i>
                        </a>

                        <span class="vehica-pagination-mobile__start">
                            <?php echo esc_html($vehicaCarList->getCurrentPage()); ?>
                        </span>

                        <span class="vehica-pagination-mobile__middle">/</span>

                        <span class="vehica-pagination-mobile__end">
                            {{ pagination.totalPages }}
                        </span>

                        <a
                                class="vehica-pagination-mobile__arrow vehica-pagination-mobile__arrow--right"
                                :class="{'vehica-pagination-mobile__arrow--disabled': pagination.currentPage >= pagination.endPage}"
                                href="<?php echo esc_url($vehicaCarList->getPaginationNext()); ?>"
                        >
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
            </vehica-pagination>
        </template>
    </div>
<?php
endif;