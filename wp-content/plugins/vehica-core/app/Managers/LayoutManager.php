<?php

namespace Vehica\Managers;

use Vehica\Core\Manager;
use Vehica\Model\Post\BasePost;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Page;
use Vehica\Model\Post\Template\CarArchiveTemplate;
use Vehica\Model\Post\Template\CarSingleTemplate;
use Vehica\Model\Post\Template\Layout;
use Vehica\Model\Post\Template\PostArchiveTemplate;
use Vehica\Model\Post\Template\PostSingleTemplate;
use Vehica\Model\Post\Template\Template;
use Vehica\Model\Post\Post;
use Vehica\Model\Term\Term;
use Vehica\Model\User\User;
use WP_Post;

/**
 * Class LayoutManager
 * @package Vehica\Managers
 */
class LayoutManager extends Manager
{

    public function boot()
    {
        add_action('vehica/layouts/single/template', [$this, 'loadSingleTemplate']);

        add_action('vehica/layouts/single/post', [$this, 'loadSinglePost']);
        add_action('vehica/layouts/single/user', [$this, 'loadSingleUser']);
        add_action('vehica/layouts/single/car', [$this, 'loadSingleCar']);

        add_action('vehica/layouts/archive/car', [$this, 'loadArchiveCar']);
        add_action('vehica/layouts/archive/post', [$this, 'loadArchivePost']);

        add_action('vehica/layouts/page', [$this, 'loadPageLayout']);
        add_action('vehica/layouts/attachment', [$this, 'loadAttachmentLayout']);

        add_action('vehica/layouts/404', [$this, 'load404Layout']);

        add_action('vehica/layouts/user/prepareCss', [$this, 'prepareUserCss']);
        add_action('vehica/layouts/postSingle/prepareCss', [$this, 'preparePostSingleCss']);
        add_action('vehica/layouts/postArchive/prepareCss', [$this, 'preparePostArchiveCss']);
        add_action('vehica/layouts/carSingle/prepareCss', [$this, 'prepareCarSingleCss']);
        add_action('vehica/layouts/carArchive/prepareCss', [$this, 'prepareCarArchiveCss']);
        add_action('vehica/layouts/preparePageCss', [$this, 'preparePageCss']);
        add_action('vehica/layouts/prepareAttachmentCss', [$this, 'prepareAttachmentCss']);
        add_action('vehica/layouts/template/prepareCss', [$this, 'prepareTemplateCss']);
        add_action('vehica/layouts/404/prepareCss', [$this, 'prepare404Css']);

        add_filter('vehica/car/template', [$this, 'carTemplateByTerm'], 10, 2);
    }

    /**
     * @param Template $template
     * @param Car $car
     * @return Template|false
     */
    public function carTemplateByTerm($template, $car)
    {
        if (!vehicaApp('settings_config')->customTemplatesEnabled()) {
            return $template;
        }

        if (!$template instanceof Template || !$car instanceof Car) {
            return $template;
        }

        foreach (vehicaApp('taxonomies') as $taxonomy) {
            foreach ($car->getTerms($taxonomy) as $term) {
                /* @var Term $term */
                $customTemplate = $term->getCarSingleCustomTemplate();
                if ($customTemplate) {
                    return $customTemplate;
                }
            }
        }

        return $template;
    }

    public function prepareUserCss()
    {
        if (!vehicaApp('user_template') instanceof Template) {
            add_action('wp_enqueue_scripts', static function () {
                wp_enqueue_style('vehica-blog', get_template_directory_uri() . '/assets/css/style-static.css',
                    ['vehica']);
            });

            return;
        }

        $this->loadPostCss(vehicaApp('user_template'));

        global $vehicaLayout;
        $vehicaLayout = vehicaApp('user_template')->getLayout();
        if ($vehicaLayout instanceof Layout) {
            $this->loadPostCss($vehicaLayout);
        }
    }

    public function prepare404Css()
    {
        if (vehicaApp('page_layout') instanceof Layout) {
            $this->loadPostCss(vehicaApp('page_layout'));
        }

        if (vehicaApp('404_page') instanceof Page) {
            $this->loadPostCss(vehicaApp('404_page'));
        }
    }

    public function load404Layout()
    {
        if (!vehicaApp('page_layout') instanceof Layout) {
            return;
        }

        vehicaApp('page_layout')->display();
    }

    public function prepareCarArchiveCss()
    {
        if (!vehicaApp('car_archive_template') instanceof Template) {
            add_action('wp_enqueue_scripts', static function () {
                wp_enqueue_style('vehica-blog', get_template_directory_uri() . '/assets/css/style-static.css',
                    ['vehica']);
            });

            return;
        }
        $this->loadPostCss(vehicaApp('car_archive_template'));

        global $vehicaLayout;
        $vehicaLayout = vehicaApp('car_archive_template')->getLayout();
        if ($vehicaLayout instanceof Layout) {
            $this->loadPostCss($vehicaLayout);
        }
    }

    public function preparePostArchiveCss()
    {
        if (!vehicaApp('post_archive_template') instanceof Template) {
            add_action('wp_enqueue_scripts', static function () {
                wp_enqueue_style('vehica-blog', get_template_directory_uri() . '/assets/css/style-static.css',
                    ['vehica']);
            });

            return;
        }

        $this->loadPostCss(vehicaApp('post_archive_template'));

        global $vehicaLayout;
        $vehicaLayout = vehicaApp('post_archive_template')->getLayout();
        if ($vehicaLayout instanceof Layout) {
            $this->loadPostCss($vehicaLayout);
        }
    }

    public function preparePostSingleCss()
    {
        if (!vehicaApp('post_single_template') instanceof Template) {
            add_action('wp_enqueue_scripts', static function () {
                wp_enqueue_style('vehica-blog', get_template_directory_uri() . '/assets/css/style-static.css',
                    ['vehica']);
            });

            return;
        }

        $this->loadPostCss(vehicaApp('post_single_template'));

        global $vehicaLayout;
        $vehicaLayout = vehicaApp('post_single_template')->getLayout();
        if ($vehicaLayout instanceof Layout) {
            $this->loadPostCss($vehicaLayout);
        }
    }

    public function prepareCarSingleCss()
    {
        global $post;
        if ($post instanceof WP_Post && $post->post_type === Car::POST_TYPE) {
            $car = new Car($post);
            $template = apply_filters('vehica/car/template', vehicaApp('car_single_template'), $car);
        } else {
            $template = vehicaApp('car_single_template');
        }

        /* @var Template $template */
        if (!$template instanceof Template) {
            add_action('wp_enqueue_scripts', static function () {
                wp_enqueue_style('vehica-blog', get_template_directory_uri() . '/assets/css/style-static.css',
                    ['vehica']);
            });

            return;
        }

        $this->loadPostCss($template);

        global $vehicaLayout;
        $vehicaLayout = $template->getLayout();
        if ($vehicaLayout instanceof Layout) {
            $this->loadPostCss($vehicaLayout);
        }
    }

    public function loadSinglePost()
    {
        global $post;
        global $vehicaPost;
        $vehicaPost = Post::getByPost($post);

        global $vehicaTemplate;
        $vehicaTemplate = vehicaApp('post_config')->getSingleTemplate();

        if (!$vehicaTemplate instanceof PostSingleTemplate) {
            /** @noinspection ForgottenDebugOutputInspection */
            wp_die(
                esc_html__(
                    'Template not found (or selected) for: Post (single) ',
                    'vehica-core'
                ) . $this->getAdditionalErrorMsg()
            );
        }

        $vehicaTemplate->load();
    }

    public function loadArchivePost()
    {
        global $vehicaTemplate;
        $vehicaTemplate = vehicaApp('post_archive_template');

        if (!$vehicaTemplate instanceof PostArchiveTemplate) {
            /** @noinspection ForgottenDebugOutputInspection */
            wp_die(
                esc_html__(
                    'Template not found (or selected) for: Post (archive) ',
                    'vehica-core'
                ) . $this->getAdditionalErrorMsg()
            );
        }

        $vehicaTemplate->load();
    }

    public function loadSingleTemplate()
    {
        global $vehicaTemplate;
        global $post;
        $vehicaTemplate = Template::get($post);
        $vehicaTemplate->load();
    }

    public function loadSingleCar()
    {
        global $post, $vehicaCar;
        $vehicaCar = Car::getByPost($post);

        global $vehicaTemplate;
        $vehicaTemplate = apply_filters('vehica/car/template', vehicaApp('car_single_template'), $vehicaCar);

        if (!$vehicaTemplate instanceof CarSingleTemplate) {
            /** @noinspection ForgottenDebugOutputInspection */
            wp_die(
                esc_html__(
                    'Template not found (or selected) for: Car Single ',
                    'vehica-core'
                ) . $this->getAdditionalErrorMsg()
            );
        }

        $vehicaTemplate->load();
    }

    public function loadArchiveCar()
    {
        global $vehicaTemplate;
        $vehicaTemplate = vehicaApp('car_config')->getArchiveTemplate();

        if (!$vehicaTemplate instanceof CarArchiveTemplate) {
            /** @noinspection ForgottenDebugOutputInspection */
            wp_die(
                esc_html__(
                    'Template not found (or selected) for: Car Archive ',
                    'vehica-core'
                ) . $this->getAdditionalErrorMsg()
            );
        }

        $vehicaTemplate->load();
    }

    public function loadSingleUser()
    {
        $user = get_user_by('slug', get_query_var('author_name'));
        global $vehicaUser;
        $vehicaUser = new User($user);

        global $vehicaTemplate;
        $vehicaTemplate = vehicaApp('user_template');

        if (!$vehicaTemplate) {
            /** @noinspection ForgottenDebugOutputInspection */
            wp_die(
                esc_html__(
                    'Template not found (or selected) for: User ',
                    'vehica-core'
                ) . $this->getAdditionalErrorMsg()
            );
        }

        $vehicaTemplate->load();
    }

    private function getAdditionalErrorMsg()
    {
        ob_start();
        if (current_user_can('manage_options') && empty(get_option('vehica_demo') && !vehicaApp('hide_importer'))) {
            ?>
            <a href="<?php echo esc_url(admin_url('admin.php?page=vehica_demo_importer')); ?>">
                <?php esc_html_e('Click here to import demo', 'vehica-core'); ?>
            </a>
            <?php
        }
        ?>
        <?php
        return ob_get_clean();
    }

    public function loadPageLayout()
    {
        global $vehicaLayout;
        $vehicaLayout = vehicaApp('page_layout');

        if (!$vehicaLayout instanceof Layout) {
            the_content();

            return;
        }

        $vehicaLayout->display();
    }

    public function loadAttachmentLayout()
    {
        global $vehicaLayout;
        $vehicaLayout = vehicaApp('global_layout');

        if (!$vehicaLayout instanceof Layout) {
            the_content();

            return;
        }

        $vehicaLayout->display();
    }

    public function preparePageCss()
    {
        global $post, $vehicaLayout;
        $vehicaLayout = (new Page($post))->getLayout();

        if ($vehicaLayout instanceof Layout) {
            $this->loadPostCss($vehicaLayout);
        } else {
            add_action('wp_enqueue_scripts', static function () {
                wp_enqueue_style(
                    'vehica-blog', get_template_directory_uri() . '/assets/css/style-static.css',
                    ['vehica']
                );
            });
        }
    }

    public function prepareAttachmentCss()
    {
        global $vehicaLayout;
        $vehicaLayout = vehicaApp('global_layout');

        if ($vehicaLayout instanceof Layout) {
            $this->loadPostCss($vehicaLayout);
        }
    }

    public function prepareTemplateCss()
    {
        global $post;
        $template = Template::get($post);
        if (!$template instanceof Template) {
            return;
        }

        global $vehicaLayout;
        $vehicaLayout = $template->getLayout();
        if (!$vehicaLayout) {
            return;
        }

        $this->loadPostCss($vehicaLayout);
    }

    /**
     * @param BasePost $post
     */
    private function loadPostCss(BasePost $post)
    {
        $postCss = new \Elementor\Core\Files\CSS\Post($post->getId());
        $postCss->enqueue();
    }

}