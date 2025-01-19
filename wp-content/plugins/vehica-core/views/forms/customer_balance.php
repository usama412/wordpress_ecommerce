<?php

if ( ! defined('ABSPATH')) {
    exit;
}
/* @var \Vehica\Model\User\User $vehicaUser */
?>
<div class="vehica-app">

    <h2><?php esc_html_e('Customer Packages', 'vehica-core'); ?></h2>

    <?php if ($vehicaUser->hasPackages()) : ?>
        <?php foreach ($vehicaUser->getPackages() as $vehicaIndex => $vehicaPackage) :
            /* @var \Vehica\Panel\Package $vehicaPackage */
            ?>
            <h3><?php esc_html_e('Package', 'vehica-core'); ?> #<?php echo esc_html($vehicaIndex + 1); ?></h3>
            <table class="form-table">
                <tr>
                    <th>
                        <label for="<?php echo esc_attr(\Vehica\Panel\Package::NUMBER); ?>">
                            <?php esc_html_e('Listing Number', 'vehica-core'); ?>
                        </label>
                    </th>
                    <td>
                        <input
                                id="<?php echo esc_attr(\Vehica\Panel\Package::NUMBER); ?>"
                                name="packages[<?php echo esc_attr($vehicaIndex); ?>][<?php echo esc_attr(\Vehica\Panel\Package::NUMBER); ?>]"
                                type="text"
                                value="<?php echo esc_attr($vehicaPackage->getNumber()); ?>"
                        >
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="<?php echo esc_attr(\Vehica\Panel\Package::EXPIRE); ?>">
                            <?php esc_html_e('Listing expire after X days', 'vehica-core'); ?>
                        </label>
                    </th>
                    <td>
                        <input
                                id="<?php echo esc_attr(\Vehica\Panel\Package::EXPIRE); ?>"
                                name="packages[<?php echo esc_attr($vehicaIndex); ?>][<?php echo esc_attr(\Vehica\Panel\Package::EXPIRE); ?>]"
                                type="text"
                                value="<?php echo esc_attr($vehicaPackage->getExpire()); ?>"
                        >
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="<?php echo esc_attr(\Vehica\Panel\Package::FEATURED_EXPIRE); ?>">
                            <?php esc_html_e('Featured expire after X days', 'vehica-core'); ?>
                        </label>
                    </th>
                    <td>
                        <input
                                id="<?php echo esc_attr(\Vehica\Panel\Package::FEATURED_EXPIRE); ?>"
                                name="packages[<?php echo esc_attr($vehicaIndex); ?>][<?php echo esc_attr(\Vehica\Panel\Package::FEATURED_EXPIRE); ?>]"
                                type="text"
                                value="<?php echo esc_attr($vehicaPackage->getFeaturedExpire()); ?>"
                        >
                    </td>
                </tr>
            </table>
        <?php endforeach; ?>
    <?php endif; ?>

    <h2><?php esc_html_e('Add Package', 'vehica-core'); ?></h2>

    <table class="form-table">
        <tr>
            <th>
                <label for="<?php echo esc_attr(\Vehica\Panel\Package::NUMBER); ?>">
                    <?php esc_html_e('Listing Number', 'vehica-core'); ?>
                </label>
            </th>
            <td>
                <input
                        id="<?php echo esc_attr(\Vehica\Panel\Package::NUMBER); ?>"
                        name="package[<?php echo esc_attr(\Vehica\Panel\Package::NUMBER); ?>]"
                        type="text"
                >
            </td>
        </tr>
        <tr>
            <th>
                <label for="<?php echo esc_attr(\Vehica\Panel\Package::EXPIRE); ?>">
                    <?php esc_html_e('Listing expire after X days', 'vehica-core'); ?>
                </label>
            </th>
            <td>
                <input
                        id="<?php echo esc_attr(\Vehica\Panel\Package::EXPIRE); ?>"
                        name="package[<?php echo esc_attr(\Vehica\Panel\Package::EXPIRE); ?>]"
                        type="text"
                >
            </td>
        </tr>
        <tr>
            <th>
                <label for="<?php echo esc_attr(\Vehica\Panel\Package::FEATURED_EXPIRE); ?>">
                    <?php esc_html_e('Featured expire after X days', 'vehica-core'); ?>
                </label>
            </th>
            <td>
                <input
                        id="<?php echo esc_attr(\Vehica\Panel\Package::FEATURED_EXPIRE); ?>"
                        name="package[<?php echo esc_attr(\Vehica\Panel\Package::FEATURED_EXPIRE); ?>]"
                        type="text"
                >
            </td>
        </tr>
    </table>
</div>