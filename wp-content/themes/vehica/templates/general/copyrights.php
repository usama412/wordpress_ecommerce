<?php if (!empty(vehicaApp('copyrights_text'))) : ?>
    <div class="vehica-copyrights">
        <?php echo wp_kses_post(vehicaApp('copyrights_text')); ?>
    </div>
<?php endif; ?>