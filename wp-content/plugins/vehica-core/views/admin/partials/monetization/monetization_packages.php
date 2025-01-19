<?php
if (!vehicaApp('settings_config')->isPaymentEnabled()) {
    return;
}
?>
<div>
    <h3><?php esc_html_e('Payment Packages', 'vehica-core'); ?></h3>
</div>

<div class="vehica-section vehica-app">
    <div>
        <vehica-payment-packages
                :initial-packages="<?php echo htmlspecialchars(json_encode(vehicaApp('payment_packages'))); ?>"
                delete-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_delete_payment_package')); ?>"
        >
            <div class="vehica-package-list" slot-scope="props">
                <div v-for="package in props.packages" class="vehica-package-list__row">
                    <div class="vehica-package-list__row__name">{{ package.name }}</div>
                    <div class="vehica-package-list__row__remove">
                        <i @click.prevent="props.onDelete(package.id)" title="Remove"
                           class="fas fa-trash vehica-action"></i>
                    </div>
                    <vehica-edit-payment-package
                            :initial-package="package"
                            request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_update_payment_package')); ?>"
                    >
                        <div slot-scope="editProps" class="vehica-package-list__row__form">
                            <div v-if="!editProps.showForm" class="vehica-package-list__row__edit">
                                <button
                                        @click.prevent="editProps.onEdit"
                                        class="vehica-micro-button vehica-micro-button--edit-package">
                                    <i class="fas fa-cog"></i> <?php esc_html_e('Edit', 'vehica-core'); ?>
                                </button>
                            </div>
                            <div v-if="editProps.showForm">
                                <form @submit.prevent="editProps.onSave">
                                    <div class="vehica-package-list__row__form__edit-row">
                                        <div class="vehica-package-list__row__form__label">
                                            <?php esc_html_e('Name', 'vehica-core'); ?>
                                        </div>
                                        <div class="vehica-package-list__row__form__input">
                                            <input
                                                    @input="editProps.setName($event.target.value)"
                                                    :value="editProps.package.name"
                                                    type="text"
                                            >
                                        </div>
                                    </div>
                                    <div class="vehica-package-list__row__form__edit-row">
                                        <div class="vehica-package-list__row__form__label">
                                            <?php esc_html_e('Label', 'vehica-core'); ?>
                                        </div>
                                        <div class="vehica-package-list__row__form__input">
                                            <input
                                                    @input="editProps.setLabel($event.target.value)"
                                                    :value="editProps.package.label"
                                                    type="text"
                                            >
                                        </div>
                                    </div>
                                    <div class="vehica-package-list__row__form__edit-row">
                                        <div class="vehica-package-list__row__form__label">
                                            <?php esc_html_e('Price (integer e.g. 10)', 'vehica-core'); ?>
                                        </div>
                                        <div class="vehica-package-list__row__form__input">
                                            <input
                                                    @input="editProps.setPrice($event.target.value)"
                                                    :value="editProps.package.price"
                                                    type="text"
                                            >
                                        </div>
                                    </div>
                                    <div class="vehica-package-list__row__form__edit-row">
                                        <div class="vehica-package-list__row__form__label">
                                            <?php esc_html_e('Display Price (Format number as currency e.g. $10.00)', 'vehica-core'); ?>
                                        </div>
                                        <div class="vehica-package-list__row__form__input">
                                            <input
                                                    @input="editProps.setDisplayPrice($event.target.value)"
                                                    :value="editProps.package.displayPrice"
                                                    type="text"
                                            >
                                        </div>
                                    </div>
                                    <div class="vehica-package-list__row__form__edit-row">
                                        <div class="vehica-package-list__row__form__label">
                                            <?php esc_html_e('Listings:', 'vehica-core'); ?>
                                        </div>
                                        <div class="vehica-package-list__row__form__input">
                                            <input
                                                    @input="editProps.setNumber($event.target.value)"
                                                    :value="editProps.package.number"
                                                    type="text"
                                            >
                                        </div>
                                    </div>
                                    <div class="vehica-package-list__row__form__edit-row">
                                        <div class="vehica-package-list__row__form__label">
                                            <?php esc_html_e('Duration (days)', 'vehica-core'); ?>
                                        </div>
                                        <div class="vehica-package-list__row__form__input">
                                            <input
                                                    @input="editProps.setExpire($event.target.value)"
                                                    :value="editProps.package.expire"
                                                    type="text"
                                            >
                                        </div>
                                    </div>
                                    <div class="vehica-package-list__row__form__edit-row">
                                        <div class="vehica-package-list__row__form__label">
                                            <?php esc_html_e('Featured (days)', 'vehica-core'); ?>
                                        </div>
                                        <div class="vehica-package-list__row__form__input">
                                            <input
                                                    @input="editProps.setFeaturedExpire($event.target.value)"
                                                    :value="editProps.package.featuredExpire"
                                                    type="text"
                                            >
                                        </div>
                                    </div>
                                    <div class="vehica-package-list__row__form__edit-row">
                                        <button class="vehica-flat-button vehica-flat-button--cyan">
                                            Save
                                        </button>
                                        <button @click.prevent="editProps.onCancel"
                                                class="vehica-flat-button vehica-flat-button--transparent"
                                        >
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </vehica-edit-payment-package>
                </div>
            </div>
        </vehica-payment-packages>
    </div>
</div>

<h3><?php esc_html_e('Add new', 'vehica-core'); ?></h3>
<div class="vehica-section vehica-app">
    <div>
        <vehica-create-payment-package
                request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_create_payment_package')); ?>"
                vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_create_payment_package')); ?>"
        >
            <div slot-scope="props" class="vehica-package-new">
                <form @submit.prevent="props.onCreate">
                    <div class="vehica-package-new__name">
                        <label><?php esc_html_e('New Package Name', 'vehica-core'); ?></label>
                        <input
                                @input="props.setName($event.target.value)"
                                :value="props.name"
                                type="text"
                                placeholder="<?php esc_attr_e('Basic Plan', 'vehica-core'); ?>"
                        >
                    </div>
                    <div class="vehica-package-new__label">
                        <label><?php esc_html_e('Label', 'vehica-core'); ?></label>
                        <input
                                @input="props.setLabel($event.target.value)"
                                :value="props.label"
                                type="text"
                                placeholder=""
                        >
                    </div>
                    <div class="vehica-package-new__price">
                        <label><?php esc_html_e('Display Price (e.g. $10.00)', 'vehica-core'); ?></label>
                        <input
                                @input="props.setDisplayPrice($event.target.value)"
                                :value="props.displayPrice"
                                type="text"
                                placeholder="$10"
                        >
                    </div>
                    <div class="vehica-package-new__price">
                        <label><?php esc_html_e('Price (integer e.g. 10)', 'vehica-core'); ?></label>
                        <input
                                @input="props.setPrice($event.target.value)"
                                :value="props.price"
                                type="text"
                                placeholder="10"
                        >
                    </div>
                    <div class="vehica-package-new__max-listings">
                        <?php esc_html_e('Listings: ', 'vehica-core'); ?>
                        <input
                                @input="props.setNumber($event.target.value)"
                                :value="props.number"
                                type="number"
                        >
                    </div>
                    <div class="vehica-package-new__expire-total">
                        <?php esc_html_e('Duration', 'vehica-core'); ?>
                        <input
                                @input="props.setExpire($event.target.value)"
                                :value="props.expire"
                                type="number"
                        >
                        <?php esc_html_e('days.', 'vehica-core'); ?>
                    </div>
                    <div class="vehica-package-new__expire-featured">
                        <?php esc_html_e('Days Featured:', 'vehica-core'); ?>
                        <input
                                @input="props.setFeaturedExpire($event.target.value)"
                                :value="props.featuredExpire"
                                type="number"
                        >
                        <?php esc_html_e('days.', 'vehica-core'); ?>
                    </div>
                    <button class="vehica-button vehica-button--accent">
                        <?php esc_html_e('Create', 'vehica-core'); ?>
                    </button>
                </form>
            </div>
        </vehica-create-payment-package>
    </div>
</div>
