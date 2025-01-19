<template>
    <div class="vehica-title">
        <?php esc_html_e('Custom Fields', 'vehica-core'); ?>
    </div>

    <div class="vehica-add-new-field">
        <vehica-create-car-field
                request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_field_car_create')); ?>"
                creating-new-field-text="<?php esc_attr_e('Creating new field', 'vehica-core'); ?>"
                error-text="<?php esc_attr_e('Something went wrong', 'vehica-core'); ?>"
                success-text="<?php esc_attr_e('Field added to the bottom of a table', 'vehica-core'); ?>"
        >
            <div slot-scope="createCarFieldProps" class="vehica-add-new-field__inner">
                <h3><?php esc_html_e('Add new field', 'vehica-core'); ?></h3>

                <div>
                    <input
                            :value="createCarFieldProps.fieldName"
                            @input="createCarFieldProps.setFieldName($event.target.value)"
                            type="text"
                            placeholder="<?php esc_attr_e('Name', 'vehica-core'); ?>"
                            required
                    >
                </div>

                <div>
                    <select
                            :value="createCarFieldProps.fieldType"
                            @change="createCarFieldProps.setFieldType($event.target.value)"
                    >
                        <option value="">
                            <?php esc_html_e('Field Type', 'vehica-core'); ?>
                        </option>
                        <?php foreach (vehicaApp('field_types') as $vehicaFieldType) :
                            /* @var \Vehica\Field\FieldType $vehicaFieldType */
                            ?>
                            <option value="<?php echo esc_attr($vehicaFieldType->getKey()); ?>">
                                <?php echo esc_html($vehicaFieldType->getName()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <button
                            class="vehica-button vehica-button--accent mt-2 mb-0"
                            @click.prevent="createCarFieldProps.createField"
                            :disabled="createCarFieldProps.inProgress"
                    >
                        <i class="fas fa-plus-circle"></i>
                        <?php esc_html_e('Add new field', 'vehica-core'); ?>
                    </button>

                    <div class="vehica-add-new-field__info">
                        <a href="https://support.vehica.com/support/solutions/articles/101000377008" target="_blank">
                            <i class="material-icons">videocam</i>
                            <?php esc_html_e('Click to watch how to display new field on the search form and single car page', 'vehica-core'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </vehica-create-car-field>
    </div>

    <div>
        <a
                class="vehica-doc-link vehica-doc-link--full-width"
                target="_blank"
                href="https://support.vehica.com/support/solutions/articles/101000377008">
            <i class="fas fa-info-circle"></i> <span><?php esc_html_e('Click here to learn about "Custom Fields" and how to add field on the single ling page or inventory', 'vehica-core'); ?></span>
        </a>
    </div>

    <div class="vehica-table mt-7">
        <div class="vehica-table__head">
            <div class="vehica-table__cell vehica-table__cell--field-name">
                <?php esc_html_e('Name', 'vehica-core'); ?>
            </div>
            <div class="vehica-table__cell vehica-table__cell--field-type">
                <?php esc_html_e('Type', 'vehica-core'); ?>
            </div>
            <div class="vehica-table__cell vehica-table__cell--field-slug">
                <?php esc_html_e('Slug', 'vehica-core'); ?>
            </div>
            <div class="vehica-table__cell vehica-table__cell--actions">
                <?php esc_html_e('Actions', 'vehica-core'); ?>
            </div>
        </div>

        <vehica-draggable :list="carFieldsProps.fields" handle=".vehica-drag">
            <vehica-row v-for="field in carFieldsProps.fields" :key="field.key" :row-id="field.id">
                <div
                        slot-scope="rowProps"
                        class="vehica-table__row"
                        :class="{'vehica-table-row--active': rowProps.isEdited}"
                >
                    <div class="vehica-table__cell vehica-table__cell--field-name">
                        <vehica-car-field-name
                                :field="field"
                                request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_field_car_update_name')); ?>"
                                saving-changes-text="<?php esc_attr_e('Saving changes', 'vehica-core'); ?>"
                                something-went-wrong-text="<?php esc_attr_e('Something went wrong', 'vehica-core'); ?>"
                        >
                            <div slot-scope="editCarFieldNameProps">
                                <template v-if="!editCarFieldNameProps.showEditField">
                                    <i title="Drag and drop to re-order"
                                       class="material-icons vehica-drag">drag_indicator</i>
                                    <span>{{ field.name }}</span>
                                    <i class="material-icons vehica-action vehica-action--left"
                                       @click="editCarFieldNameProps.onShowEditField"
                                       title="Rename"
                                    >edit</i>
                                </template>

                                <template v-if="editCarFieldNameProps.showEditField">
                                    <input
                                            :value="editCarFieldNameProps.name"
                                            @input="editCarFieldNameProps.setName($event.target.value)"
                                            type="text"
                                            @keyup.prevent.enter="editCarFieldNameProps.onSave"
                                    >

                                    <button
                                            class="vehica-flat-button vehica-flat-button--cyan"
                                            @click="editCarFieldNameProps.onSave"
                                    >
                                        <?php esc_html_e('Save', 'vehica-core'); ?>
                                    </button>

                                    <button
                                            class="vehica-flat-button vehica-flat-button--transparent"
                                            @click="editCarFieldNameProps.onCancel"
                                    >
                                        <i class="fas fa-times"></i>
                                    </button>
                                </template>
                            </div>
                        </vehica-car-field-name>
                    </div>

                    <div class="vehica-table__cell vehica-table__cell--field-type">
                        {{ field.prettyType }}
                    </div>

                    <div class="vehica-table__cell vehica-table__cell--field-slug">
                        <vehica-car-field-rewrite
                                :field="field"
                                request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_field_car_update_rewrite')); ?>"
                                success-text="<?php esc_attr_e('Slug changed successfully', 'vehica-core'); ?>"
                                in-progress-text="<?php esc_attr_e('Saving changes', 'vehica-core'); ?>"
                                error-text="<?php esc_attr_e('Something went wrong', 'vehica-core'); ?>"
                        >
                            <div slot-scope="editCarFieldRewrite">
                                <template v-if="field.hasOwnProperty('rewrite')">
                                    <div v-if="!editCarFieldRewrite.showEditField">
                                        {{ field.rewrite }}
                                        <i class="material-icons vehica-action vehica-action--left"
                                           @click.prevent="editCarFieldRewrite.onShowEditField"
                                           title="Change slug (part of a URL)"
                                        >edit</i>
                                    </div>

                                    <div v-if="editCarFieldRewrite.showEditField">
                                        <input
                                                @input="editCarFieldRewrite.setRewrite($event.target.value)"
                                                :value="editCarFieldRewrite.rewrite"
                                                @keyup.prevent.enter="editCarFieldRewrite.onSave"
                                                type="text"
                                        >

                                        <button @click.prevent="editCarFieldRewrite.onSave"
                                                class="vehica-flat-button vehica-flat-button--cyan"
                                        >
                                            <?php esc_html_e('Save', 'vehica-core'); ?>
                                        </button>

                                        <button @click.prevent="editCarFieldRewrite.onCancel"
                                                class="vehica-flat-button vehica-flat-button--transparent">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </vehica-car-field-rewrite>
                    </div>
                    <div class="vehica-table__cell vehica-table__cell--actions">

                        <a :href="field.editLink" class="vehica-micro-button" title="Edit the field settings">
                            <i class="fas fa-cog"></i> EDIT
                        </a>

                        <vehica-delete-car-field
                                :field="field"
                                request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_field_car_delete')); ?>"
                                success-title-text="<?php esc_attr_e('Field deleted successfully', 'vehica-core'); ?>"
                                deleting-field-text="<?php esc_attr_e('Deleting field', 'vehica-core'); ?>"
                                are-you-sure-title-text="<?php esc_attr_e('Are you sure?', 'vehica-core'); ?>"
                                are-you-sure-msg-text="<?php esc_attr_e('You won\'t be able to revert this!', 'vehica-core'); ?>"
                                confirm-text="<?php esc_attr_e('Yes', 'vehica-core'); ?>"
                                cancel-text="<?php esc_attr_e('Cancel', 'vehica-core'); ?>"
                        >
                            <div slot-scope="deleteFieldProps" class="vehica-inline">
                                <i
                                        @click.prevent="deleteFieldProps.deleteField"
                                        class="fas fa-trash vehica-action"
                                        title="Remove"
                                ></i>
                            </div>
                        </vehica-delete-car-field>

                    </div>
                </div>
            </vehica-row>
        </vehica-draggable>
    </div>

</template>