<?php
if (!defined('ABSPATH')) {
    exit;
}

if (is_singular(\Vehica\Model\Post\Template\Template::POST_TYPE)) {
    global $post;
    $vehicaTemplate = \Vehica\Model\Post\Template\Template::getByPost($post);
    $vehicaContext = $vehicaTemplate->getType();
} else {
    $vehicaContext = 'general';
}
?>
<vehica-templates-manager
        id="vehica-templates-manager"
        source-url="<?php echo esc_attr(\Vehica\Managers\Elementor\TemplateSource::SOURCE_URL); ?>"
        context="<?php echo esc_attr($vehicaContext); ?>"
>
    <div slot-scope="manager">
        <template>
            <div v-if="manager.showModal" class="dialog-type-lightbox elementor-templates-modal">
                <div class="vehica-add-modal">
                    <div class="vehica-add-modal__brand">
                        <?php esc_html_e('Vehica Content Importer', 'vehica-core'); ?>
                    </div>
                    <div class="vehica-add-modal__head">
                        <div
                                :class="{'vehica-add-modal__tab-active': manager.type === 'block' }"
                                @click="manager.setType('block')"
                        >
                            <?php esc_html_e('Blocks', 'vehica-core'); ?>
                        </div>
                        <div
                                :class="{'vehica-add-modal__tab-active': manager.type === 'template' }"
                                @click="manager.setType('template')"
                        >
                            <?php esc_html_e('Ready Pages', 'vehica-core'); ?>
                        </div>
                    </div>
                    <div class="vehica-add-modal__exit" @click="manager.setShowModal">
                        <i class="eicon-close"></i>
                    </div>

                    <div class="vehica-add-modal__search">
                        <input
                                :value="manager.keyword"
                                @input="manager.setKeyword($event.target.value)"
                                type="text"
                                id="elementor-template-library-filter-text"
                                placeholder="<?php esc_attr_e('Search by name', 'vehica-core'); ?>"
                        >
                    </div>

                    <div class="vehica-add-modal__content">
                        <div
                                v-if="manager.showModal && !manager.isLoading && !manager.isImporting"
                        >
                            <div class="vehica-add-modal__elements">
                                <div
                                        v-for="template in manager.templates"
                                        :key="template.id"
                                        class="vehica-add-modal__element"
                                >
                                    <div class="vehica-add-modal__element__inner">
                                        <div class="vehica-add-modal__element__image">
                                            <img v-if="template.image" :src="template.image" alt="">
                                        </div>
                                        <div class="vehica-add-modal__element__add"
                                             @click="manager.importTemplate(template.id)">
                                            <div class="vehica-add-modal__element__add__inner">
                                                <?php esc_html_e('+ Insert', 'vehica-core'); ?>
                                            </div>
                                        </div>
                                        <div class="vehica-add-modal__element__title">
                                            {{ template.title }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="vehica-add-modal__information" v-if="manager.showModal && manager.isImporting">
                            <?php esc_html_e('Importing, please wait', 'vehica-core'); ?>
                        </div>

                        <div class="vehica-add-modal__information" v-if="manager.showModal && manager.isLoading">
                            <?php esc_html_e('Loading...', 'vehica-core'); ?>
                        </div>
                    </div>

                </div>
            </div>
        </template>
    </div>
</vehica-templates-manager>