<?php


namespace Vehica\Managers;


use Elementor\Core\Base\Document;
use Elementor\Plugin;
use Elementor\TemplateLibrary\Source_Local;
use Elementor\Utils;
use Exception;
use Vehica\Core\App;
use Vehica\Core\Demo;
use Vehica\Core\Manager;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\GalleryField;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Post\Post;
use WP_Query;

/**
 * Class DemosManager
 *
 * @package Vehica\Managers
 */
class DemosManager extends Manager
{
    const DEMO_SOURCE = 'https://files.vehica.com';

    /**
     * @var bool
     */
    protected $officialDemo = false;

    public function boot()
    {
        if (!current_user_can('manage_options') || vehicaApp('hide_importer')) {
            return;
        }

        add_action('admin_post_vehica_importer_prepare', [$this, 'prepare']);
        add_action('admin_post_vehica_importer_posts', [$this, 'addPosts']);
        add_action('admin_post_vehica_importer_terms', [$this, 'addTerms']);
        add_action('admin_post_vehica_importer_term_taxonomy', [$this, 'addTermTaxonomy']);
        add_action('admin_post_vehica_importer_term_relationships', [$this, 'addTermRelationships']);
        add_action('admin_post_vehica_importer_term_meta', [$this, 'addTermMeta']);
        add_action('admin_post_vehica_importer_comments', [$this, 'addComments']);
        add_action('admin_post_vehica_importer_options', [$this, 'addOptions']);
        add_action('admin_post_vehica_importer_users', [$this, 'addUsers']);
        add_action('admin_post_vehica_importer_media', [$this, 'addMedia']);
        add_action('admin_post_vehica_importer_cache', [$this, 'reset']);
        add_action('admin_menu', static function () {
            add_menu_page(
                esc_html__('Demo Importer', 'vehica-core'),
                esc_html__('Demo Importer', 'vehica-core'),
                'manage_options',
                'vehica_demo_importer',
                static function () {
                    /** @noinspection PhpIncludeInspection */
                    require vehicaApp('views_path') . 'importer/importer.php';
                },
                'dashicons-migrate',
                2
            );
        });

        add_action('admin_init', [$this, 'refreshTermsCount']);

        add_filter('wp_generate_attachment_metadata', static function ($metadata, $attachment_id) {
            $attachment_post = get_post($attachment_id);
            $type = $attachment_post->post_mime_type;
            if ($type === 'image/svg+xml' && empty($metadata)) {
                $upload_dir = wp_upload_dir();
                $base_name = basename($attachment_post->guid);
                $metadata = [
                    'file' => $upload_dir['subdir'] . '/' . $base_name
                ];
            }
            return $metadata;
        }, 1, 2);
    }

    public function reset()
    {
        $this->clearCache();

        update_option('vehica_refresh_terms_count', 1);

        $id = wp_insert_post([
            'post_title' => esc_html__('Default Kit', 'vehica-core'),
            'post_type' => Source_Local::CPT,
            'post_status' => 'publish',
            'meta_input' => [
                '_elementor_edit_mode' => 'builder',
                Document::TYPE_META_KEY => 'kit',
            ],
        ]);

        update_option(\Elementor\Core\Kits\Manager::OPTION_ACTIVE, $id);

        $kit = Plugin::instance()->kits_manager->get_active_kit_for_frontend();
        $kit->set_settings('container_width', [
            'size' => 1428,
            'unit' => 'px',
            'sizes' => []
        ]);

        $kit->set_settings('space_between_widgets', [
            "column" => "0",
            "row" => "0",
            "isLinked" => true,
            "unit" => "px",
            "size" => 0,
            "sizes" => []
        ]);

        $kit->set_settings('viewport_md', 899);
        $kit->set_settings('viewport_lg', 1199);
        $kit->set_settings('viewport_mobile', 899);
        $kit->set_settings('viewport_tablet', 1199);
        $kit->save(['settings' => $kit->get_settings()]);
    }

    /** @noinspection SqlNoDataSourceInspection */
    public function prepare()
    {
        $this->clearCache();

        update_option(App::APP_STATUS, App::APP_STATUS_DEMO_INSTALLATION);

        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->posts} ");
        $wpdb->query("DELETE FROM {$wpdb->postmeta} ");
        $wpdb->query("DELETE FROM {$wpdb->commentmeta} ");
        $wpdb->query("DELETE FROM {$wpdb->comments} ");
        $wpdb->query("DELETE FROM {$wpdb->terms} ");
        $wpdb->query("DELETE FROM {$wpdb->term_taxonomy} ");
        $wpdb->query("DELETE FROM {$wpdb->term_relationships} ");
        $wpdb->query("DELETE FROM {$wpdb->termmeta} ");
        $wpdb->query("DELETE FROM {$wpdb->users} WHERE ID != 1 AND ID != " . get_current_user_id());
        $wpdb->query("DELETE FROM {$wpdb->usermeta} WHERE user_id != 1 AND user_id != " . get_current_user_id());
    }

    /**
     * @return string
     */
    private function getSourceUrl()
    {
        if (!isset($_POST['demoKey'])) {
            return '';
        }

        return self::DEMO_SOURCE . '/' . $_POST['demoKey'];
    }

    /**
     * @return string
     */
    private function getDemoUrl()
    {
        $demo = $this->getDemo();

        if (!$demo) {
            return '';
        }

        return $demo->getUrl();
    }

    public function addPosts()
    {
        if (!isset($_POST['start'], $_POST['limit'], $_POST['demoKey'])) {
            return;
        }

        $start = (int)$_POST['start'];
        $end = (int)$_POST['limit'];

        $posts = get_option('vehica_demo_posts');
        if (empty($posts)) {
            $file = $this->getSourceUrl() . '/posts.json?time=' . time();
            $posts = $this->getData($file);
            update_option('vehica_demo_posts', $posts);
        }

        global $wpdb;

        $exclude = ['7612', '7827', '7608', '7828', '7829', '7962', '7609', '8655', '18981'];

        for ($i = $start; $i < $end; $i++) {
            $post = $posts[$i]['post'];
            $post_meta = $posts[$i]['post_meta'];

            if (in_array($post['ID'], $exclude, true)) {
                continue;
            }

            if (!$this->officialDemo) {
                $post['post_author'] = get_current_user_id();
            }

            $wpdb->insert($wpdb->posts, $post);

            if (is_array($post_meta)) {
                foreach ($post_meta as $key => $meta) {
                    if ($meta['meta_key'] === '_menu_item_url' && $post['post_type'] === 'nav_menu_item') {
                        $meta['meta_value'] = str_replace($this->getDemoUrl(), site_url(), $meta['meta_value']);
                    }

                    if ($meta['meta_key'] === 'vehica_6673' && !empty($meta['meta_value'])) {
                        $gallery = explode(',', $meta['meta_value']);
                        $meta['meta_value'] = implode(',', array_unique([
                            $gallery[0],
                            15691,
                            15566,
                            15361,
                            15359,
                            15342,
                            15696
                        ]));
                    }

                    $check = get_post_meta_by_id($meta['meta_id']);
                    if (!$check) {
                        $wpdb->insert(
                            $wpdb->postmeta,
                            $meta
                        );
                    }
                }
            }
        }
    }

    public function addTerms()
    {
        if (isset($_POST['start'], $_POST['limit'])) {
            $start = (int)$_POST['start'];
            $end = (int)$_POST['limit'];

            $terms = get_option('vehica_demo_terms');
            if (empty($terms)) {
                $file = $this->getSourceUrl() . '/terms.json';
//                $terms = json_decode(file_get_contents($file), true);
                $terms = $this->getData($file);
                update_option('vehica_demo_terms', $terms);
            }

            global $wpdb;

            $excluded = ['2318', ''];

            for ($i = $start; $i < $end; $i++) {
                if (in_array($terms[$i]['term_id'], $excluded, true)) {
                    continue;
                }

                $wpdb->insert($wpdb->terms, $terms[$i]);
            }
        }
    }

    public function addTermTaxonomy()
    {
        if (isset($_POST['start'], $_POST['limit'])) {
            $start = (int)$_POST['start'];
            $end = (int)$_POST['limit'];

            $termTaxonomy = get_option('vehica_demo_term_taxonomy');
            if (empty($termTaxonomy)) {
                $file = $this->getSourceUrl() . '/term_taxonomy.json';
//                $termTaxonomy = json_decode(file_get_contents($file), true);
                $termTaxonomy = $this->getData($file);
                update_option('vehica_demo_term_taxonomy', $termTaxonomy);
            }

            global $wpdb;
            for ($i = $start; $i < $end; $i++) {
                $wpdb->insert($wpdb->term_taxonomy, $termTaxonomy[$i]);
            }
        }
    }

    public function addTermRelationships()
    {
        if (isset($_POST['start'], $_POST['limit'])) {
            $start = (int)$_POST['start'];
            $end = (int)$_POST['limit'];

            $termRelationships = get_option('vehica_demo_term_relationships');
            if (empty($termRelationships)) {
                $file = $this->getSourceUrl() . '/term_relationships.json';
//                $termRelationships = json_decode(file_get_contents($file), true);
                $termRelationships = $this->getData($file);
                update_option('vehica_demo_term_relationships', $termRelationships);
            }

            global $wpdb;
            for ($i = $start; $i < $end; $i++) {
                $wpdb->insert($wpdb->term_relationships, $termRelationships[$i]);
            }
        }
    }

    public function addTermMeta()
    {
        if (isset($_POST['start'], $_POST['limit'])) {
            $start = (int)$_POST['start'];
            $end = (int)$_POST['limit'];

            $termMeta = get_option('vehica_demo_term_meta');
            if (empty($termMeta)) {
                $file = $this->getSourceUrl() . '/term_meta.json';
//                $termMeta = json_decode(file_get_contents($file), true);
                $termMeta = $this->getData($file);
                update_option('vehica_demo_term_meta', $termMeta);
            }

            global $wpdb;
            for ($i = $start; $i < $end; $i++) {
                $wpdb->insert($wpdb->termmeta, $termMeta[$i]);
            }
        }
    }

    public function addComments()
    {
        if (!$this->officialDemo) {
            return;
        }

        if (isset($_POST['start'], $_POST['limit'])) {
            $start = (int)$_POST['start'];
            $end = (int)$_POST['limit'];
            $file = $this->getSourceUrl() . '/comments.json';
            $comments = $this->getData($file);

            global $wpdb;
            for ($i = $start; $i < $end; $i++) {
                $comment = $comments[$i]['comment'];
                $comment_meta = $comments[$i]['comment_meta'];
                $wpdb->insert($wpdb->comments, $comment);
                if (is_array($comment_meta)) {
                    foreach ($comment_meta as $meta) {
                        $wpdb->insert($wpdb->commentmeta, $meta);
                    }
                }
            }
        }
    }

    /** @noinspection SqlNoDataSourceInspection */
    public function addOptions()
    {
        if (isset($_POST['start'], $_POST['limit'])) {
            $start = (int)$_POST['start'];
            $end = (int)$_POST['limit'];
            $file = $this->getSourceUrl() . '/options.json';
//            $options = json_decode(file_get_contents($file), true);
            $options = $this->getData($file);

            global $wpdb;
            for ($i = $start; $i < $end; $i++) {
                $option = $options[$i];

                if ($option['option_name'] === 'theme_mods_vehica-child' || $option['option_name'] === 'theme_mods_vehica') {
                    $theme = get_option('stylesheet');
                    $option['option_name'] = 'theme_mods_' . $theme;
                }

                $wpdb->query("
                    DELETE FROM {$wpdb->options}
                    WHERE option_name = '" . $option['option_name'] . "'
                ");
                $wpdb->insert(
                    $wpdb->options,
                    array(
                        'option_name' => $option['option_name'],
                        'option_value' => $option['option_value'],
                        'autoload' => $option['autoload']
                    )
                );
            }
        }
    }

    public function addUsers()
    {
        if (isset($_POST['start'], $_POST['limit'])) {
            $start = (int)$_POST['start'];
            $end = (int)$_POST['limit'];
            $file = $this->getSourceUrl() . '/users.json';
//            $users = json_decode(file_get_contents($file), true);
            $users = $this->getData($file);
            $current_user = wp_get_current_user();

            global $wpdb;
            for ($i = $start; $i < $end; $i++) {
                $user = $users[$i]['user'];
                if ($user['user_login'] === 'admin'
                    || $user['ID'] === get_current_user_id()
                    || $user['ID'] === 1
                    || $user['ID'] === '1'
                ) {
                    continue;
                }

                $user['user_pass'] = $current_user->data->user_pass;
                $user_meta = $users[$i]['user_meta'];
                $wpdb->insert($wpdb->users, $user);
                foreach ($user_meta as $meta) {
                    $test = get_metadata_by_mid('user', $meta['umeta_id']);
                    if ($test !== false) {
                        continue;
                    }

                    $wpdb->insert($wpdb->usermeta, $meta);
                    if (strpos($meta['meta_key'], '_capabilities') !== false) {
                        if (strpos($meta['meta_value'], 'vehica_user') !== false) {
                            $role = 'vehica_user';
                        } elseif (strpos($meta['meta_value'], 'editor') !== false) {
                            $role = 'editor';
                        } elseif (strpos($meta['meta_value'], 'subscriber') !== false) {
                            $role = 'subscriber';
                        }

                        if (isset($role)) {
                            wp_update_user(['ID' => $user['ID'], 'role' => $role]);
                        }
                    }
                }
            }
        }
    }

    /**
     * @return string
     */
    private function getDemoKey()
    {
        if (!isset($_POST['demoKey'])) {
            return '';
        }

        return $_POST['demoKey'];
    }

    /**
     * @return Demo|false
     */
    private function getDemo()
    {
        return vehicaApp('demos')->find(function ($demo) {
            /* @var Demo $demo */
            return $demo->getKey() === $this->getDemoKey();
        });
    }

    public function addMedia()
    {
        $demo = $this->getDemo();
        if (!$demo) {
            return;
        }

        $upload_dir = wp_upload_dir();
        $save_path = $upload_dir['basedir'] . '/';

        if (isset($_POST['start'], $_POST['limit'])) {
            $start = (int)$_POST['start'];
            $end = (int)$_POST['limit'];

            $media = get_option('vehica_demo_media');
            if (empty($media)) {
                $file = $this->getSourceUrl() . '/media.json';
//                $media = json_decode(file_get_contents($file), true);
                $media = $this->getData($file);
                update_option('vehica_demo_media', $media);
            }

            global $wpdb;
            for ($i = $start; $i < $end; $i++) {
                $attachment = $media[$i]['attachment'];
                $attachment_meta = $media[$i]['attachment_meta'];
                $check = $wpdb->insert($wpdb->posts, $attachment);
                if (!$check) {
                    echo $wpdb->last_error;
                    continue;
                }

                foreach ($attachment_meta as $meta) {
                    if ($meta['meta_key'] === '_wp_attached_file') {
                        $name = $save_path . $meta['meta_value'];
                        $source = $demo->getMediaSource() . $meta['meta_value'];

                        $dir = dirname($name);
                        if (!is_dir($dir)) {
                            mkdir($dir, 0777, true);
                        }

                        $response = wp_remote_get($source, [
                            'timeout' => 60
                        ]);

                        if (is_wp_error($response)) {
                            echo $response->get_error_message();
                        }

                        $file = $response['body'];
                        file_put_contents($name, $file);


                        if ($attachment['post_mime_type'] !== 'image/svg+xml') {
                            $metadata = wp_generate_attachment_metadata($attachment['ID'], $name);
                            if (!empty($metadata)) {
                                wp_update_attachment_metadata($attachment['ID'], $metadata);
                            }
                        }
                    }

                    $check = get_post_meta_by_id($meta['meta_id']);
                    if (!$check && $attachment['post_mime_type']) {
                        $wpdb->insert($wpdb->postmeta, $meta);
                    }
                }
            }
        }
    }

    /** @noinspection SqlNoDataSourceInspection */
    public function clearCache()
    {
        global $wp_rewrite;
        $wp_rewrite->set_permalink_structure('/%postname%/');
        update_option('rewrite_rules', false);
        $wp_rewrite->flush_rules(true);

        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%transient_%' ");
        $wpdb->query("
            DELETE t, tm FROM {$wpdb->terms} t INNER JOIN {$wpdb->termmeta} tm ON t.term_id = tm.term_id
            WHERE tm.meta_key = 'vehica_name' AND tm.meta_value = 'EUR' 
            ");

        wp_load_alloptions();
        wp_cache_delete('alloptions', 'options');

        $options = get_option('theme_mods_vehica');
        update_option('theme_mods_vehica', $options);
        update_option(App::APP_STATUS, '0');
        update_option('vehica_reset_rewrites', 1);

        update_option('vehica_demo_posts', '0');
        update_option('vehica_demo_terms', '0');
        update_option('vehica_demo_term_taxonomy', '0');
        update_option('vehica_demo_term_relationships', '0');
        update_option('vehica_demo_term_meta', '0');
        update_option('vehica_demo_media', '0');
        update_option('vehica_demo', '1');

        $this->updateContactForms();

        $kit = Plugin::instance()->kits_manager->get_active_kit_for_frontend();
        $kit->set_settings('container_width', [
            'size' => 1428,
            'unit' => 'px',
            'sizes' => []
        ]);

        $kit->set_settings('viewport_md', 900);
        $kit->set_settings('viewport_lg', 1200);
        $kit->set_settings('viewport_mobile', 900);
        $kit->set_settings('viewport_tablet', 1200);
        $kit->save(['settings' => $kit->get_settings()]);

        $vehica4wp = get_option('mc4wp');
        $vehica4wp['api_key'] = '';
        update_option('mc4wp', $vehica4wp);

        try {
            Utils::replace_urls($this->getDemoUrl(), site_url());
        } catch (Exception $e) {
        }
        Plugin::instance()->files_manager->clear_cache();

        $this->updateAttachments();
    }

    private function updateAttachments()
    {
        $query = new WP_Query([
            'post_type' => 'vehica_car',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ]);

        foreach ($query->posts as $post) {
            update_post_meta($post->ID, 'vehica_18820', '18819');
        }
    }

    protected function updateContactForms()
    {
        $query = new WP_Query([
            'post_type' => 'wpcf7_contact_form',
            'posts_per_page' => -1
        ]);

        if (isset($_SERVER['SERVER_NAME'])) {
            $domain = $_SERVER['SERVER_NAME'];
        } else {
            $domain = 'tangibledesign.net';
        }

        foreach ($query->posts as $post) {
            $user = _wp_get_current_user();
            if ($user) {
                $adminMail = $user->user_email;
            } else {
                $adminMail = get_option('admin_email');
            }

            if ($post->ID === 18047) {
                $cf = new Post($post);
                $mail = $cf->getMeta('_mail');
                $mail['sender'] = 'info@' . $domain;
                $mail['recipient'] = $adminMail;
                $cf->setMeta('_mail', $mail);
            } elseif ($post->ID === 6201) {
                $cf = new Post($post);
                $mail = $cf->getMeta('_mail');
                $mail['sender'] = 'info@' . $domain;
                $mail['recipient'] = '[_post_author_email]';
                $cf->setMeta('_mail', $mail);
            }
        }
    }

    public function refreshTermsCount()
    {
        $check = get_option('vehica_refresh_terms_count');
        if (empty($check)) {
            return;
        }

        $taxonomies = vehicaApp('taxonomies');
        if (!$taxonomies) {
            return;
        }

        $taxonomies = $taxonomies->map(static function ($taxonomy) {
            /* @var Taxonomy $taxonomy */
            return $taxonomy->getKey();
        })->all();

        $taxonomies[] = 'category';
        $taxonomies[] = 'post_tag';

        foreach ($taxonomies as $taxonomy) {
            $args = [
                'taxonomy' => $taxonomy,
                'fields' => 'ids',
                'hide_empty' => false,
            ];

            wp_update_term_count_now(get_terms($args), $taxonomy);
        }

        update_option('vehica_refresh_terms_count', '0');

        $this->checkGalleries();
    }

    private function checkGalleries()
    {
        $galleryField = vehicaApp('gallery_fields')->first();
        if (!$galleryField instanceof GalleryField) {
            return;
        }

        foreach (Car::getAll() as $car) {
            $gallery = $galleryField->getValue($car);

            foreach ($gallery as $key => $imageId) {
                $post = get_post($imageId);
                if (!$post) {
                    unset($gallery[$key]);
                }
            }

            $galleryField->save($car, implode(',', $gallery));
        }
    }

    /**
     * @param string $url
     * @return mixed
     */
    private function getData($url)
    {
        $response = wp_remote_get($url, [
            'timeout' => 60
        ]);

        if (!is_wp_error($response)) {
            return json_decode($response['body'], true);
        }

        if (ini_get('allow_url_fopen')) {
            return json_decode(file_get_contents($url), true);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

}