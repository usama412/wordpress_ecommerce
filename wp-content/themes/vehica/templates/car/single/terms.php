<?php
/* @var \Vehica\Widgets\Car\Single\TermsSingleCarWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
/* @var \Vehica\Model\Post\Car $vehicaCar */
global $vehicaCar;

if (!$vehicaCar) {
    return;
}

$vehicaTaxonomy = $vehicaCurrentWidget->getTaxonomy();
if (!$vehicaTaxonomy) {
    esc_html_e('Taxonomy not found', 'vehica');
    return;
}

$vehicaTerms = $vehicaCar->getTerms($vehicaTaxonomy);

if ($vehicaTerms->isNotEmpty()) :?>
    <div>
        <h3 class="vehica-section-label vehica-section-label--list">
            <?php echo esc_html($vehicaTaxonomy->getName()); ?>
        </h3>
        <div class="vehica-grid vehica-car-list">
            <?php foreach ($vehicaTerms as $vehicaTerm) :/* @var \Vehica\Model\Term\Term $vehicaTerm */ ?>
                <div class="vehica-car-list__element vehica-grid__element vehica-grid__element--1of3 vehica-grid__element--mobile-1of1 vehica-car-list__element">
                    <span class="vehica-car-list__element__dot">•</span>
                    <span class="vehica-car-list__element__inner">
                        <?php echo esc_html($vehicaTerm->getName()); ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
