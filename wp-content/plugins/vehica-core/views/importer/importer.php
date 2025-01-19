<div class="vehica-app vehica-app--styles">
    <?php if (isset($_GET['state']) && $_GET['state'] === 'demoLoaded') : ?>
        <vehica-set-homepage :initial-page-id="13767">
            <div
                    slot-scope="props"
                    class="vehica-import-finished"
            >
                <form
                        action="<?php echo esc_url(admin_url('admin-post.php?action=vehica/settings/setHomepage')); ?>"
                        method="POST"
                >
                    <input
                            name="pageId"
                            :value="props.pageId"
                            type="hidden"
                    >

                    <h1>Last Step: Select your favorite homepage and click "Next"</h1>

                    <p>No worry, you can change it letter in the Vehica Panel</p>

                    <button class="vehica-button mr-5 vehica-button-import-finished-next">NEXT <i
                                class="fas fa-arrow-right"></i></button>

                    <div class="vehica-importer__choose">

                        <div
                                class="vehica-importer__choose__single"
                                :class="{'vehica-importer__choose__single--active': props.pageId === 13767}"
                                @click.prevent="props.setPage(13767)"
                        >
                            <img src="https://files.vehica.com/demo-importer-img/mosaic.png">
                            <div class="vehica-importer__choose__single__selected">Selected</div>
                            <h3>Mosaic</h3>
                        </div>

                        <div
                                class="vehica-importer__choose__single"
                                :class="{'vehica-importer__choose__single--active': props.pageId === 18101}"
                                @click.prevent="props.setPage(18101)"
                        >
                            <img src="https://files.vehica.com/demo-importer-img/car-dealer.png">
                            <div class="vehica-importer__choose__single__selected">Selected</div>
                            <h3>Car Dealer</h3>
                        </div>

                        <div
                                class="vehica-importer__choose__single"
                                :class="{'vehica-importer__choose__single--active': props.pageId === 17482}"
                                @click.prevent="props.setPage(17482)"
                        >
                            <img src="https://files.vehica.com/demo-importer-img/location.png">
                            <div class="vehica-importer__choose__single__selected">Selected</div>
                            <h3>Location</h3>
                        </div>

                        <div
                                class="vehica-importer__choose__single"
                                :class="{'vehica-importer__choose__single--active': props.pageId === 13210}"
                                @click.prevent="props.setPage(13210)"
                        >
                            <img src="https://files.vehica.com/demo-importer-img/modern.png">
                            <div class="vehica-importer__choose__single__selected">Selected</div>
                            <h3>Modern</h3>
                        </div>

                        <div
                                class="vehica-importer__choose__single"
                                :class="{'vehica-importer__choose__single--active': props.pageId === 11872}"
                                @click.prevent="props.setPage(11872)"
                        >
                            <img src="https://files.vehica.com/demo-importer-img/classic.png">
                            <div class="vehica-importer__choose__single__selected">Selected</div>
                            <h3>Classic</h3>
                        </div>

                        <div
                                class="vehica-importer__choose__single"
                                :class="{'vehica-importer__choose__single--active': props.pageId === 16475}"
                                @click.prevent="props.setPage(16475)"
                        >
                            <img src="https://files.vehica.com/demo-importer-img/video.png">
                            <div class="vehica-importer__choose__single__selected">Selected</div>
                            <h3>Video</h3>
                        </div>

                        <div
                                class="vehica-importer__choose__single"
                                :class="{'vehica-importer__choose__single--active': props.pageId === 17178}"
                                @click.prevent="props.setPage(17178)"
                        >
                            <img src="https://files.vehica.com/demo-importer-img/slideshow.png">
                            <div class="vehica-importer__choose__single__selected">Selected</div>
                            <h3>Slideshow</h3>
                        </div>
                    </div>
                </form>
            </div>
        </vehica-set-homepage>
    <?php elseif (isset($_GET['state']) && $_GET['state'] === 'finish') : ?>
        <div class="vehica-importer">
            <h1><?php esc_html_e('The demo import has finished', 'vehica-core'); ?></h1>
            <a
                    href="<?php echo esc_url(site_url()); ?>"
                    title="<?php esc_attr_e('Visit Homepage', 'vehica-core'); ?>"
                    target="_blank"
                    class="vehica-button mr-5"
            >
                <?php esc_html_e('Visit Homepage', 'vehica-core'); ?>
            </a>

            <a
                    href="<?php echo esc_url(admin_url('admin.php?page=vehica_panel')); ?>"
                    title="<?php esc_attr_e('Basic Settings', 'vehica-core'); ?>"
                    class="vehica-button vehica-button--accent mr-5"
            >
                <?php esc_html_e('Visit Theme Settings', 'vehica-core'); ?>
            </a>
        </div>
    <?php else : ?>
        <vehica-demo-importer
                request-url="<?php echo esc_url(admin_url('admin-post.php')); ?>"
                demo-url="<?php echo esc_url(\Vehica\Managers\DemosManager::DEMO_SOURCE); ?>"
        >
            <div slot-scope="importerProps">
                <div class="vehica-importer">
                    <h1 class="vehica-title"><?php esc_html_e('Vehica Demo Importer', 'vehica-core'); ?></h1>
                    <template v-if="importerProps.showProgress">
                        <div class="mb-5">
                            <?php esc_html_e('Importing demo content. Please wait', 'vehica-core'); ?>
                        </div>
                        <div class="vehica-importer__progress">
                            <i class="fas fa-spinner fa-spin mr-3"></i>
                            <strong>{{ importerProps.progress }} %</strong>
                        </div>
                    </template>
                    <div v-if="!importerProps.showProgress">
                        <div class="mb-6">
                            <?php esc_html_e('Here you can import homepages, sample cars, templates.',
                                'vehica-core'); ?>
                        </div>

                        <?php if (!function_exists('curl_version') && !ini_get('allow_url_fopen')) : ?>
                            <div style="margin: 30px 0px; background: #eeeeee; padding: 20px; border-radius: 10px;">
                                <h3 style="color: rgb(204,51,0);">
                                    <span class="dashicons dashicons-warning" style="position:relative;top:2px;"></span>
                                    There can be problem connecting to demo database</h3>
                                <div>curl extension is not installed on your hosting or allow_url_fopen setting is not
                                    enabled. One of this must be fixed to correctly connect to the demo content
                                    database.<br>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!extension_loaded('mbstring')) : ?>
                            <div style="margin: 30px 0; background: #eeeeee; padding: 20px; border-radius: 10px;">
                                <h3 style="color: rgb(204,51,0);">
                                    <span class="dashicons dashicons-warning" style="position:relative;top:2px;"></span>
                                    MB String not installed</h3>
                                <div>PHP mbstring extension is not installed on your server. It is required to run
                                    almost any
                                    modern WordPress theme or plugin. Please contact your server provider how to turn it
                                    "on".
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!function_exists('gd_info') || !extension_loaded('gd')) : ?>
                            <div style="margin: 30px 0; background: #eeeeee; padding: 20px; border-radius: 10px;">
                                <h3 style="color: rgb(204,51,0);">
                                    <span class="dashicons dashicons-warning" style="position:relative;top:2px;"></span>
                                    PHP GD extension is not installed on your server</h3>
                                <div>It is required to generate image thumbnails by any WordPress Theme.</div>
                            </div>
                        <?php endif; ?>

                        <div>
                            <u><?php esc_html_e('Please note that loading a new demo will delete all of your WordPress database content.',
                                    'vehica-core'); ?></u>
                        </div>
                        <div class="vehica-importer__demos">
                            <?php foreach (vehicaApp('demos') as $vehicaDemo) :
                                /* @var \Vehica\Core\Demo $vehicaDemo */
                                ?>
                                <div style="margin:30px 0;">
                                    <button
                                            @click.prevent="importerProps.onImport('<?php echo esc_attr($vehicaDemo->getKey()); ?>')"
                                            class="vehica-button vehica-button--accent"
                                    >
                                        <i class="fas fa-file-download mr-2" aria-hidden="true"></i>
                                        <?php esc_html_e('Import', 'vehica-core'); ?>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div style="margin:30px 0">
                            If you have any problem with demo import please contact us via <a
                                    target="_blank"
                                    style="color:#0073aa!important; font-weight:bold;"
                                    href="https://support.vehica.com/support/tickets/new">Support Contact Form</a>.
                            We will do our
                            best to help you.
                            Please send us:
                            <ul style="list-style: disc!important; padding-left: 40px;">
                                <li>WordPress link and admin login and password</li>
                                <li>Server Panel (e.g. cPanel - url / login / password) or some kind of FTP / SFTP
                                    credentials.
                                </li>
                            </ul>
                            Please consider you also contact us with any other question, problem or feedback. We are
                            ready to help you!
                        </div>

                    </div>
                </div>
            </div>
        </vehica-demo-importer>
    <?php endif; ?>
</div>