<?php
if (!defined('ABSPATH')) {
    exit;
}
/* @var \Vehica\Model\Term\Term $vehicaTerm */
?>
<tr>
    <td colspan="2">
        <div class="flex flex-wrap">
            <div class="w-1/3">
                <table>
                    <tr>
                        <th colspan="2">
                            <h2><?php echo esc_html(vehicaApp('car_config')->getName()); ?></h2>
                        </th>
                    </tr>
                    <?php foreach ($vehicaTerm->getTaxonomy()->getRelations($vehicaTerm) as $dFieldRelation) :
                        /* @var \Vehica\Model\Term\Relation\FieldRelation $dFieldRelation */
                        ?>
                        <tr>
                            <th>
                                <label for="<?php echo esc_attr($dFieldRelation->getKey()); ?>">
                                    <?php echo esc_html($dFieldRelation->getName()); ?>
                                </label>
                            </th>
                            <td>
                                <input
                                        id="<?php echo esc_attr($dFieldRelation->getKey()); ?>"
                                        name="<?php echo esc_attr($dFieldRelation->getKey()); ?>"
                                        value="<?php echo esc_attr(\Vehica\Model\Term\Relation\Relation::DEFAULT_VALUE); ?>"
                                        type="checkbox"
                                    <?php if ($dFieldRelation->isFieldRequired()) : ?>
                                        checked
                                        disabled
                                    <?php elseif ($dFieldRelation->isChecked()) : ?>
                                        checked
                                    <?php endif; ?>
                                >
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </td>
</tr>