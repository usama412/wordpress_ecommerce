<?php
/* @var \Vehica\Widgets\General\MenuGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
?>
<header class="vehica-app <?php echo esc_attr($vehicaCurrentWidget->getClass()); ?>">
    <?php if ($vehicaCurrentWidget->isTransparent()) : ?>
        <div class="vehica-menu__transparent-wrapper">
            <div class="vehica-menu__transparent-container">
                <div class="vehica-hide-mobile vehica-hide-tablet">
                    <?php
                    if ($vehicaCurrentWidget->showTopBar()) :
                        get_template_part('templates/general/menu/top-bar');
                    endif;

                    get_template_part('templates/general/menu/menu-desktop');
                    ?>
                </div>

                <div class="vehica-hide-desktop">
                    <?php get_template_part('templates/general/menu/menu-mobile'); ?>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="vehica-hide-mobile vehica-hide-tablet">
            <?php
            if ($vehicaCurrentWidget->showTopBar()) :
                get_template_part('templates/general/menu/top-bar');
            endif;

            get_template_part('templates/general/menu/menu-desktop');
            ?>
        </div>

        <div class="vehica-hide-desktop">
            <?php get_template_part('templates/general/menu/menu-mobile'); ?>
        </div>
    <?php endif; ?>
</header>