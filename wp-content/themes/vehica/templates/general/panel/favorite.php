<?php
/* @var \Vehica\Widgets\General\PanelGeneralWidget $vehicaCurrentWidget */

global $vehicaCurrentWidget, $vehicaCarCard;
$vehicaCarCard = new \Vehica\Components\Card\Car\CardV3();
$vehicaFavoriteCars = $vehicaCurrentWidget->getFavoriteCars();
if ($vehicaFavoriteCars->isEmpty()) :?>
    <div class="vehica-panel-list vehica-container">
        <div class="vehica-panel-list__elements">
            <div class="vehica-panel__car-list__cars">
                <h2 class="vehica-panel-list-no-found">
                    <?php echo esc_html(vehicaApp('no_favorites_yet_string')); ?>
                </h2>
            </div>
        </div>
    </div>
<?php else : ?>
    <template>
        <div
                style="display: none;"
                id="vehica-favorite-is-empty"
                class="vehica-panel-list vehica-container"
        >
            <div class="vehica-panel-list__elements">
                <div class="vehica-panel__car-list__cars">
                    <h2 class="vehica-panel-list-no-found">
                        <?php echo esc_html(vehicaApp('no_favorites_yet_string')); ?>
                    </h2>
                </div>
            </div>
        </div>
    </template>

    <div class="vehica-panel-list vehica-container">
        <div class="vehica-panel-list__elements">
            <div id="vehica-favorite-cars" class="vehica-panel__car-list__cars">
                <?php
                foreach ($vehicaFavoriteCars as $vehicaCar) :
                    $vehicaCarCard->loadTemplate($vehicaCar);
                endforeach;
                ?>
            </div>
        </div>
    </div>
<?php
endif;