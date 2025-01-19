<?php foreach (vehicaApp('template_types') as $vehicaTemplateType) : ?>
    <div class="vehica-section vehica-section--full" id="<?php echo esc_attr($vehicaTemplateType['type']); ?>">
        <div class="vehica-section__left vehica-section__left--small">
            <div class="vehica-section__left__inner">
                <h2>
                    <i class="<?php echo esc_attr($vehicaTemplateType['icon']); ?>"></i>
                    <?php echo esc_html($vehicaTemplateType['name']); ?>
                </h2>
            </div>
        </div>
        <div class="vehica-section__right">
            <div class="vehica-section__right__inner">
                <vehica-templates-list
                        template-type="<?php echo esc_attr($vehicaTemplateType['type']); ?>"
                        :initial-active-template="<?php echo esc_attr($vehicaTemplateType['templateId']); ?>"
                        :initial-templates="<?php echo htmlspecialchars(json_encode(vehicaApp($vehicaTemplateType['type'] . '_templates')->all())); ?>"
                        create-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_template_create')); ?>"
                        delete-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_template_delete')); ?>"
                        set-active-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_template_set')); ?>"
                        creating-template-text="<?php esc_attr_e('Creating template', 'vehica-core'); ?>"
                        template-name-text="<?php echo esc_attr($vehicaTemplateType['name']); ?>"
                        create-success-text="<?php esc_attr_e('Template created', 'vehica-core'); ?>"
                        delete-success-text="<?php esc_attr_e('Template deleted', 'vehica-core'); ?>"
                        error-text="<?php esc_attr_e('Something went wrong', 'vehica-core'); ?>"
                        are-you-sure-title-text="<?php esc_attr_e('Are you sure?', 'vehica-core'); ?>"
                        are-you-sure-msg-text="<?php esc_attr_e('You won\'t be able to revert this!', 'vehica-core'); ?>"
                        confirm-text="<?php esc_attr_e('Yes', 'vehica-core'); ?>"
                        cancel-text="<?php esc_attr_e('Cancel', 'vehica-core'); ?>"
                        saving-changes-text="<?php esc_attr_e('Saving changes', 'vehica-core'); ?>"
                        changes-saved-text="<?php esc_attr_e('Changes saved successfully', 'vehica-core'); ?>"
                        duplicate-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_duplicate_post')); ?>"
                        duplicate-in-progress-text="<?php esc_attr_e('Duplicate in progress', 'vehica-core'); ?>"
                        duplicate-success-text="<?php esc_attr_e('Duplicate success', 'vehica-core'); ?>"
                >
                    <div slot-scope="templatesListProps">
                        <div class="vehica-table">
                            <div class="vehica-table__head">
                                <div class="vehica-table__cell vehica-table__cell--state">
                                    <?php esc_html_e('State', 'vehica-core') ?>
                                </div>
                                <div class="vehica-table__cell vehica-table__cell--name">
                                    <?php esc_html_e('Name', 'vehica-core'); ?>
                                </div>
                                <div class="vehica-table__cell vehica-table__cell--edit">
                                    <?php esc_html_e('Actions', 'vehica-core'); ?>
                                </div>
                            </div>

                            <vehica-row
                                    v-for="template in templatesListProps.templates"
                                    :key="template.id"
                                    :row-id="template.id"
                            >
                                <div
                                        slot-scope="rowProps"
                                        class="vehica-table__row"
                                        :class="{'vehica-table-row--active': rowProps.isEdited}"
                                >

                                    <div class="vehica-table__cell vehica-table__cell--state">
                                        <div
                                                v-if="templatesListProps.activeTemplate === template.id"
                                                class="vehica-table__active"
                                        >
                                            <i class="material-icons vehica-checked">
                                                radio_button_checked
                                            </i>
                                            <span><?php esc_html_e('Active', 'vehica-core'); ?></span>
                                        </div>
                                        <div
                                                v-if="templatesListProps.activeTemplate !== template.id"
                                                @click.prevent="templatesListProps.setActiveTemplate(template)"
                                                class="vehica-table__not-active"
                                        >
                                            <i class="material-icons vehica-checked">
                                                radio_button_checked
                                            </i>
                                            <i class="material-icons vehica-unchecked">
                                                radio_button_unchecked
                                            </i>
                                            <span>Activate</span>
                                        </div>
                                    </div>
                                    <div class="vehica-table__cell vehica-table__cell--name">
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

                                    <div class="vehica-table__cell vehica-table__cell--edit">
                                        <a
                                                class="vehica-micro-button"
                                                :href="template.elementorEditUrl"
                                                target="_blank"
                                                title="<?php esc_attr_e('Click to edit using page builder', 'vehica-core'); ?>"
                                        >
                                            <i class="fas fa-cog"></i>
                                            <span><?php esc_html_e('Edit', 'vehica-core'); ?></span>
                                        </a>
                                        <a
                                                :href="template.url"
                                                target="_blank"
                                                title="<?php esc_attr_e('Preview', 'vehica-core'); ?>"
                                        >
                                            <i class="fas fa-eye vehica-action"></i>
                                        </a>
                                        <span title="Duplicate" @click.prevent="templatesListProps.duplicate(template)">
                                            <i class="fas fa-clone vehica-action"></i>
                                        </span>
                                        <span
                                                v-if="templatesListProps.templates.length > 1"
                                                @click.prevent="templatesListProps.onDeleteTemplate(template)"
                                                title="<?php esc_attr_e('Remove', 'vehica-core'); ?>"
                                        >
                                            <i class="fas fa-trash vehica-action"></i>
                                        </span>
                                    </div>

                                </div>
                            </vehica-row>

                        </div>
                        <button class="vehica-button vehica-button--add-new mt-5"
                                @click.prevent="templatesListProps.createTemplate">
                            <i class="fas fa-plus-circle"></i> <?php esc_html_e('Add new', 'vehica-core'); ?>
                        </button>
                    </div>
                </vehica-templates-list>
            </div>
        </div>
    </div>
<?php endforeach; ?>
