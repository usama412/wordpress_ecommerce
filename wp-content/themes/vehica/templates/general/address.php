<?php if (!empty(vehicaApp('address'))) : ?>
    <div class="vehica-address">
        <a href="https://maps.google.com/?q=<?php echo esc_attr(urlencode(vehicaApp('address'))); ?>" target="_blank">
            <span><?php echo esc_html(vehicaApp('address')); ?></span>
        </a>
    </div>
<?php endif; ?>