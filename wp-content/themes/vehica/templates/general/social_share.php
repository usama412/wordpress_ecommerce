<?php
/* @var \Vehica\Widgets\General\SocialShareGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
?>
<div class="vehica-social-share">
    <?php if ($vehicaCurrentWidget->showFacebook()) : ?>
        <a
                class="vehica-social-share__icon vehica-social-share__icon--facebook"
                href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url($vehicaCurrentWidget->getCurrentUrl()); ?>"
                target="_blank"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                <path d="M512 256C512 114.6 397.4 0 256 0S0 114.6 0 256C0 376 82.7 476.8 194.2 504.5V334.2H141.4V256h52.8V222.3c0-87.1 39.4-127.5 125-127.5c16.2 0 44.2 3.2 55.7 6.4V172c-6-.6-16.5-1-29.6-1c-42 0-58.2 15.9-58.2 57.2V256h83.6l-14.4 78.2H287V510.1C413.8 494.8 512 386.9 512 256h0z"/>
            </svg>

            <?php echo esc_html(vehicaApp('share_string')); ?>
        </a>
    <?php endif; ?>

    <?php if ($vehicaCurrentWidget->showTwitter()) : ?>
        <a
                class="vehica-social-share__icon vehica-social-share__icon--twitter"
                href="https://twitter.com/share?url=<?php echo esc_url($vehicaCurrentWidget->getCurrentUrl()); ?>"
                target="_blank"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/>
            </svg>

            <?php echo esc_html(vehicaApp('tweet_string')); ?>
        </a>
    <?php endif; ?>
</div>
