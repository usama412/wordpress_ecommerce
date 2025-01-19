<?php /* @var \Vehica\Model\Post\Field\LocationField $vehicaField */ ?>
<div class="vehica-edit__field">
    <input
            name="<?php echo esc_attr(\Vehica\Model\Post\Field\Field::IS_REQUIRED); ?>"
            id="<?php echo esc_attr(\Vehica\Model\Post\Field\Field::IS_REQUIRED); ?>"
            type="checkbox"
            value="1"
        <?php if ($vehicaField->isRequired()) : ?>
            checked
        <?php endif; ?>
    >

    <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\Field::IS_REQUIRED); ?>">
        <?php esc_html_e('Required', 'vehica-core'); ?>
    </label>
</div>

<div class="vehica-edit__field">
    <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\LocationField::MAP_TYPE); ?>">
        <?php esc_html_e('Map Type', 'vehica-core'); ?>
    </label>

    <select
            id="<?php echo esc_attr(\Vehica\Model\Post\Field\LocationField::MAP_TYPE); ?>"
            name="<?php echo esc_attr(\Vehica\Model\Post\Field\LocationField::MAP_TYPE); ?>"
            class="vehica-selectize"
    >
        <option
                value="<?php echo esc_attr(\Vehica\Model\Post\Field\LocationField::MAP_TYPE_ROADMAP); ?>"
            <?php if ($vehicaField->isMapType(\Vehica\Model\Post\Field\LocationField::MAP_TYPE_ROADMAP)) : ?>
                selected
            <?php endif; ?>
        >
            <?php esc_html_e('Roadmap', 'vehica-core'); ?>
        </option>

        <option
                value="<?php echo esc_attr(\Vehica\Model\Post\Field\LocationField::MAP_TYPE_SATELLITE); ?>"
            <?php if ($vehicaField->isMapType(\Vehica\Model\Post\Field\LocationField::MAP_TYPE_SATELLITE)) : ?>
                selected
            <?php endif; ?>
        >
            <?php esc_html_e('Satellite', 'vehica-core'); ?>
        </option>

        <option
                value="<?php echo esc_attr(\Vehica\Model\Post\Field\LocationField::MAP_TYPE_HYBRID); ?>"
            <?php if ($vehicaField->isMapType(\Vehica\Model\Post\Field\LocationField::MAP_TYPE_HYBRID)) : ?>
                selected
            <?php endif; ?>
        >
            <?php esc_html_e('Hybrid', 'vehica-core'); ?>
        </option>

        <option
                value="<?php echo esc_attr(\Vehica\Model\Post\Field\LocationField::MAP_TYPE_TERRAIN); ?>"
            <?php if ($vehicaField->isMapType(\Vehica\Model\Post\Field\LocationField::MAP_TYPE_TERRAIN)) : ?>
                selected
            <?php endif; ?>
        >
            <?php esc_html_e('Terrain', 'vehica-core'); ?>
        </option>
    </select>
</div>

<div class="vehica-edit__field">
    <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\LocationField::SEARCH_TYPES); ?>">
        <?php esc_html_e('Search Types', 'vehica-core'); ?>
    </label>

    <select
            id="<?php echo esc_attr(\Vehica\Model\Post\Field\LocationField::SEARCH_TYPES); ?>"
            name="<?php echo esc_attr(\Vehica\Model\Post\Field\LocationField::SEARCH_TYPES); ?>"
            class="vehica-selectize"
    >
        <option
                value="(regions)"
            <?php if ($vehicaField->isSearchTypeSelected('(regions)')) : ?>
                selected
            <?php endif; ?>
        >
            <?php esc_html_e('(regions)', 'vehica-core'); ?>
        </option>

        <option
                value="(cities)"
            <?php if ($vehicaField->isSearchTypeSelected('(cities)')) : ?>
                selected
            <?php endif; ?>
        >
            <?php esc_html_e('(cities)', 'vehica-core'); ?>
        </option>

        <option
                value="address"
            <?php if ($vehicaField->isSearchTypeSelected('address')) : ?>
                selected
            <?php endif; ?>
        >
            <?php esc_html_e('address', 'vehica-core'); ?>
        </option>
    </select>
</div>

<div class="vehica-edit__field">
    <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\LocationField::INPUT_TYPE); ?>">
        <?php esc_html_e('Input Types', 'vehica-core'); ?>
    </label>

    <select
            id="<?php echo esc_attr(\Vehica\Model\Post\Field\LocationField::INPUT_TYPE); ?>"
            name="<?php echo esc_attr(\Vehica\Model\Post\Field\LocationField::INPUT_TYPE); ?>"
            class="vehica-selectize"
    >
        <option
                value="geocode"
            <?php if ($vehicaField->isInputTypeSelected('geocode')) : ?>
                selected
            <?php endif; ?>
        >
            <?php esc_html_e('geocode', 'vehica-core'); ?>
        </option>

        <option
                value="address"
            <?php if ($vehicaField->isInputTypeSelected('address')) : ?>
                selected
            <?php endif; ?>
        >
            <?php esc_html_e('address', 'vehica-core'); ?>
        </option>

        <option
                value="establishment"
            <?php if ($vehicaField->isInputTypeSelected('establishment')) : ?>
                selected
            <?php endif; ?>
        >
            <?php esc_html_e('establishment', 'vehica-core'); ?>
        </option>

        <option
                value="(regions)"
            <?php if ($vehicaField->isInputTypeSelected('(regions)')) : ?>
                selected
            <?php endif; ?>
        >
            <?php esc_html_e('(regions)', 'vehica-core'); ?>
        </option>

        <option
                value="(cities)"
            <?php if ($vehicaField->isInputTypeSelected('(cities)')) : ?>
                selected
            <?php endif; ?>
        >
            <?php esc_html_e('(cities)', 'vehica-core'); ?>
        </option>
    </select>
</div>

<div class="vehica-edit__field">
    <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\LocationField::COUNTRIES); ?>">
        <?php esc_html_e('Country Restrictions (max 5)', 'vehica-core'); ?>
    </label>

    <select
            id="<?php echo esc_attr(\Vehica\Model\Post\Field\LocationField::COUNTRIES); ?>"
            name="<?php echo esc_attr(\Vehica\Model\Post\Field\LocationField::COUNTRIES); ?>[]"
            class="vehica-selectize"
            placeholder="<?php esc_attr_e('All Countries', 'vehica-core'); ?>"
            multiple
    >
        <?php foreach (\Vehica\Model\Post\Field\LocationField::getCountries() as $vehicaCountry) : ?>
            <option
                    value="<?php echo esc_attr($vehicaCountry['alpha-2']); ?>"
                <?php if ($vehicaField->isCountrySelected($vehicaCountry['alpha-2'])) : ?>
                    selected
                <?php endif; ?>
            >
                <?php echo esc_html($vehicaCountry['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>