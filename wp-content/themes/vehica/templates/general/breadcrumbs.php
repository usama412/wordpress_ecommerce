<?php
/* @var \Vehica\Widgets\General\BreadcrumbsGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;

?>
<div class="vehica-app">
    <?php if (!is_post_type_archive(\Vehica\Model\Post\Car::POST_TYPE)) : ?>
        <div class="vehica-breadcrumbs-wrapper" v-dragscroll.pass="true">
            <div class="vehica-breadcrumbs">
                <?php foreach ($vehicaCurrentWidget->getBreadcrumbs() as $vehicaBreadcrumb) :
                    /* @var \Vehica\Components\Breadcrumb $vehicaBreadcrumb */
                    if (!$vehicaBreadcrumb->isLast()) :
                        ?>
                        <div class="vehica-breadcrumbs__single">
                            <a
                                    class="vehica-breadcrumbs__link"
                                    href="<?php echo esc_url($vehicaBreadcrumb->getUrl()); ?>"
                                    title="<?php echo esc_attr($vehicaBreadcrumb->getName()); ?>"
                            >
                                <?php echo esc_html($vehicaBreadcrumb->getName()); ?>
                            </a>
                            <span class="vehica-breadcrumbs__separator"></span>
                        </div>
                    <?php else : ?>
                        <span class="vehica-breadcrumbs__last">
                    <?php echo esc_html($vehicaBreadcrumb->getName()); ?>
                </span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else : ?>
        <vehica-breadcrumbs
                :taxonomies="<?php echo htmlspecialchars(json_encode(vehicaApp('settings_config')->getCarBreadcrumbs())); ?>"
        >
            <div slot-scope="props" class="vehica-breadcrumbs-wrapper" v-dragscroll.pass="true">
                <div class="vehica-breadcrumbs">
                    <div class="vehica-breadcrumbs__single">
                        <a
                                class="vehica-breadcrumbs__link"
                                href="<?php echo esc_url(site_url()); ?>"
                                title="<?php echo esc_attr(vehicaApp('homepage_string')); ?>"
                        >
                            <?php echo esc_html(vehicaApp('homepage_string')); ?>
                        </a>
                        <span class="vehica-breadcrumbs__separator"></span>
                    </div>

                    <div class="vehica-breadcrumbs__single">
                        <span v-if="props.breadcrumbs.length === 0" class="vehica-breadcrumbs__last">
                            <?php echo esc_html(vehicaApp('inventory_string')); ?>
                        </span>
                        <template v-if="props.breadcrumbs.length > 0">
                            <a
                                    class="vehica-breadcrumbs__link"
                                    href="<?php echo esc_url(get_post_type_archive_link(\Vehica\Model\Post\Car::POST_TYPE)); ?>"
                                    title="<?php echo esc_attr(vehicaApp('inventory_string')); ?>"
                            >
                                <?php echo esc_html(vehicaApp('inventory_string')); ?>
                            </a>
                            <span class="vehica-breadcrumbs__separator"></span>
                        </template>
                    </div>

                    <template>
                        <div v-for="(breadcrumbs, mainIndex) in props.breadcrumbs" class="vehica-breadcrumbs__single">
                            <span v-for="(breadcrumb, index) in breadcrumbs" :key="breadcrumb.name">
                                <template v-if="index > 0">,</template>
                                <template v-if="mainIndex + 1 < props.breadcrumbs.length">
                                    <a
                                            class="vehica-breadcrumbs__link"
                                            :href="breadcrumb.link"
                                            :title="breadcrumb.name"
                                    >
                                        {{ breadcrumb.name }}
                                    </a>
                                </template>

                                <template v-if="mainIndex + 1 >= props.breadcrumbs.length">
                                    <span>
                                        {{ breadcrumb.name }}
                                    </span>
                                </template>
                            </span>
                            <span
                                    v-if="mainIndex + 1 < props.breadcrumbs.length"
                                    class="vehica-breadcrumbs__separator"
                            ></span>
                        </div>
                    </template>
                </div>
            </div>
        </vehica-breadcrumbs>
    <?php endif; ?>
</div>