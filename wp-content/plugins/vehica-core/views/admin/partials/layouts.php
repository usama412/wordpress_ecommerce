<vehica-templates-list
        template-type="<?php echo esc_attr(\Vehica\Model\Post\Template\Template::TYPE_LAYOUT); ?>"
        :initial-templates="<?php echo htmlspecialchars(json_encode(vehicaApp('layouts')->all())); ?>"
        create-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_template_create')); ?>"
        delete-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_template_delete')); ?>"
        creating-template-text="<?php esc_attr_e('Creating layout', 'vehica-core'); ?>"
        template-name-text="<?php esc_attr_e('New Layout', 'vehica-core'); ?>"
        create-success-text="<?php esc_attr_e('Layout created', 'vehica-core'); ?>"
        delete-success-text="<?php esc_attr_e('Layout deleted', 'vehica-core'); ?>"
        error-text="<?php esc_attr_e('Something went wrong', 'vehica-core'); ?>"
        are-you-sure-title-text="<?php esc_attr_e('Are you sure?', 'vehica-core'); ?>"
        are-you-sure-msg-text="<?php esc_attr_e('You won\'t be able to revert this!', 'vehica-core'); ?>"
        confirm-text="<?php esc_attr_e('Yes', 'vehica-core'); ?>"
        cancel-text="<?php esc_attr_e('Cancel', 'vehica-core'); ?>"
        :initial-global-layout-id="<?php echo esc_attr(vehicaApp('global_layout_id')); ?>"
        set-global-layout-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_set_global_layout')); ?>"
        saving-changes-text="<?php esc_attr_e('Saving changes', 'vehica-core'); ?>"
        changes-saved-text="<?php esc_attr_e('Changes saved successfully', 'vehica-core'); ?>"
        duplicate-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_duplicate_post')); ?>"
        duplicate-in-progress-text="<?php esc_attr_e('Duplicate in progress', 'vehica-core'); ?>"
        duplicate-success-text="<?php esc_attr_e('Duplicate success', 'vehica-core'); ?>"
>
    <div slot-scope="layoutsListProps" v-scroll-spy="{time: 1000}">

        <div class="vehica-section vehica-section--full">
            <div class="vehica-section__left vehica-section__left--small">
                <h2 id="layouts">
                    <?php esc_html_e('Page layouts', 'vehica-core'); ?>
                </h2>
                <div class="vehica-section__description">
                    <?php esc_html_e('Header & footer combinations', 'vehica-core'); ?>
                </div>
            </div>
            <div class="vehica-section__right">
                <div class="vehica-section__right__inner">

                    <div class="vehica-table">
                        <div class="vehica-table__head">
                            <div class="vehica-table__cell vehica-table__cell--state">
                                <?php esc_html_e('State', 'vehica-core'); ?>
                            </div>
                            <div class="vehica-table__cell vehica-table__cell--name">
                                <?php esc_html_e('Name', 'vehica-core'); ?>
                            </div>
                            <div class="vehica-table__cell vehica-table__cell--edit">
                                <?php esc_html_e('Actions', 'vehica-core'); ?>
                            </div>
                        </div>

                        <vehica-row
                                v-for="template in layoutsListProps.templates"
                                :key="template.id"
                                :row-id="template.id"
                        >
                            <div
                                    slot-scope="rowProps"
                                    class="vehica-table__row"
                                    :class="{'vehica-table-row--not-valid': !template.isValid}"
                                    :class="{'vehica-table-row--active': rowProps.isEdited}"
                            >

                                <div class="vehica-table__cell vehica-table__cell--state">
                                    <template v-if="template.isValid">
                                        <div
                                                v-if="template.id === layoutsListProps.globalLayoutId"
                                                class="vehica-table__active"
                                        >
                                            <i class="material-icons vehica-checked">
                                                radio_button_checked
                                            </i>
                                            <span><?php esc_html_e('Default', 'vehica-core'); ?></span>
                                        </div>
                                        <div
                                                v-if="template.id !== layoutsListProps.globalLayoutId"
                                                class="vehica-table__not-active"
                                                @click="layoutsListProps.setGlobalLayout(template)"
                                        >
                                            <i class="material-icons vehica-checked">
                                                radio_button_checked
                                            </i>
                                            <i class="material-icons vehica-unchecked">
                                                radio_button_unchecked
                                            </i>
                                            <span><?php esc_html_e('Default', 'vehica-core'); ?></span>
                                        </div>
                                    </template>
                                    <template v-if="!template.isValid">
                                        <div class="vehica-valid">
                                            <div class="vehica-valid__icon">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </div>
                                    </template>
                                </div>

                                <div class="vehica-table__cell vehica-table__cell--name">
                                    <div>
                                        <vehica-template-name
                                                :template="template"
                                                request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_template_set_name')); ?>"
                                                saving-changes-text="<?php esc_attr_e('Saving changes', 'vehica-core'); ?>"
                                                success-text="<?php esc_attr_e('Changes saved successfully', 'vehica-core'); ?>"
                                                something-went-wrong-text="<?php esc_attr_e('Something went wrong', 'vehica-core'); ?>"
                                        >
                                            <div slot-scope="editTemplateNameProps">
                                                <template v-if="!editTemplateNameProps.showEditField">
                                                    <div class="vehica-table__name">
                                                        {{ template.name }}
                                                        <template v-if="!template.isValid">
                                                            <div class="vehica-valid">
                                                                <div class="vehica-valid__text">
                                                                    <strong><?php esc_attr_e('Layout not valid', 'vehica-core'); ?></strong>
                                                                    <br>
                                                                    <?php esc_attr_e('"Template Content" elements was removed', 'vehica-core'); ?>
                                                                </div>
                                                        </template>
                                                    </div>
                                                </template>

                                                <template v-if="editTemplateNameProps.showEditField">
                                                    <input
                                                            @input="editTemplateNameProps.setName($event.target.value)"
                                                            :value="editTemplateNameProps.name"
                                                            type="text"
                                                            @keyup.prevent.enter="editTemplateNameProps.onSave"
                                                    >
                                                    <button
                                                            class="vehica-flat-button vehica-flat-button--cyan"
                                                            @click.prevent="editTemplateNameProps.onSave"
                                                    >
                                                        <?php esc_attr_e('Save', 'vehica-core'); ?>
                                                    </button>
                                                    <button
                                                            class="vehica-flat-button vehica-flat-button--transparent"
                                                            @click.prevent="editTemplateNameProps.onCancel"
                                                    >
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </template>
                                            </div>
                                        </vehica-template-name>
                                    </div>
                                </div>
                                <div class="vehica-table__cell vehica-table__cell--edit">
                                    <a
                                            class="vehica-micro-button"
                                            :href="template.elementorEditUrl"
                                            target="_blank"
                                            title="<?php esc_attr_e('Edit using page builder', 'vehica-core'); ?>"
                                    >
                                        <i class="fas fa-cog"></i>
                                        <span>
                                            <?php esc_html_e('Edit', 'vehica-core'); ?>
                                        </span>
                                    </a>
                                    <a
                                            :href="template.url"
                                            target="_blank"
                                            title="<?php esc_attr_e('Preview', 'vehica-core'); ?>"
                                    >
                                        <i class="fas fa-eye vehica-action"></i>
                                    </a>
                                    <span
                                            @click.prevent="layoutsListProps.duplicate(template)"
                                            title="<?php esc_attr_e('Duplicate', 'vehica-core'); ?>"
                                    >
                                        <i class="fas fa-clone vehica-action"></i>
                                    </span>
                                    <span
                                            v-if="layoutsListProps.templates.length > 1"
                                            @click.prevent="layoutsListProps.onDeleteTemplate(template)"
                                            title="<?php esc_attr_e('Remove', 'vehica-core'); ?>"
                                    >
                                        <i class="fas fa-trash vehica-action"></i>
                                    </span>
                                </div>
                            </div>
                        </vehica-row>
                    </div>

                </div>

                <button class="vehica-button vehica-button--add-new mt-5" @click.prevent="layoutsListProps.createTemplate">
                    <i class="fas fa-plus-circle"></i> <?php esc_html_e('Add new', 'vehica-core'); ?>
                </button>

            </div>
        </div>

        <?php require 'template_types.php'; ?>
</vehica-templates-list>
