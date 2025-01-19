<?php /** @noinspection PhpIncludeInspection */


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\Post\Field\Field;

/**
 * Class AdminPanelMenuManager
 * @package Vehica\Managers
 */
class AdminPanelMenuManager extends Manager
{
    public function boot()
    {
        add_action('admin_menu', [$this, 'create']);
    }

    public function create()
    {
        if (!$this->currentUserCanManageOptions()) {
            return;
        }

        $this->panelPage();

        $this->basicSetup();

        $this->userPanel();

        $this->layoutsAndTemplates();

        $this->carFields();

        $this->monetization();

        $this->maps();

        $this->notifications();

        $this->translateAndRename();

        $this->advanced();
    }

    private function userPanel()
    {
        add_submenu_page(
            'vehica_panel',
            esc_html__('User Panel', 'vehica-core'),
            esc_html__('User Panel', 'vehica-core'),
            'manage_options',
            'vehica_panel_user_panel',
            static function () {
                require vehicaApp('views_path') . 'admin/user_panel.php';
            }
        );
    }

    private function panelPage()
    {
        add_menu_page(
            esc_html__('Vehica Panel', 'vehica-core'),
            esc_html__('Vehica Panel', 'vehica-core'),
            'manage_options',
            'vehica_panel',
            static function () {
            },
            '',
            2
        );
    }

    private function basicSetup()
    {
        add_submenu_page(
            'vehica_panel',
            esc_html__('Basic Setup', 'vehica-core'),
            esc_html__('Basic Setup', 'vehica-core'),
            'manage_options',
            'vehica_panel',
            static function () {
                require vehicaApp('views_path') . 'admin/basic_setup.php';
            }
        );
    }

    private function layoutsAndTemplates()
    {
        add_submenu_page(
            'vehica_panel',
            esc_html__('Layouts & Templates', 'vehica-core'),
            esc_html__('Layouts & Templates', 'vehica-core'),
            'manage_options',
            'vehica_panel_layouts_and_templates',
            static function () {
                require vehicaApp('views_path') . 'admin/layouts_and_templates.php';
            }
        );
    }

    private function carFields()
    {
        add_submenu_page(
            'vehica_panel',
            esc_html__('Custom Fields', 'vehica-core'),
            esc_html__('Custom Fields', 'vehica-core'),
            'manage_options',
            'vehica_panel_car_fields',
            static function () {
                require vehicaApp('views_path') . 'admin/car_fields.php';
            }
        );

        vehicaApp('car_fields')->each(static function ($field) {
            /* @var Field $field */
            add_submenu_page(
                'vehica_panel_car_fields',
                $field->getName(),
                $field->getName(),
                'manage_options',
                $field->getKey(),
                static function () {
                    /** @noinspection PhpUnusedLocalVariableInspection */
                    $vehicaField = Field::getByKey($_GET['page']);
                    require vehicaApp('views_path') . 'admin/field.php';
                }
            );
        });
    }

    private function translateAndRename()
    {
        add_submenu_page(
            'vehica_panel',
            esc_html__('Translate & Rename', 'vehica-core'),
            esc_html__('Translate & Rename', 'vehica-core'),
            'manage_options',
            'vehica_panel_rename_and_translate',
            static function () {
                require vehicaApp('views_path') . 'admin/translate_and_rename.php';
            }
        );
    }

    public function monetization()
    {
        add_submenu_page(
            'vehica_panel',
            esc_html__('Monetization', 'vehica-core'),
            esc_html__('Monetization', 'vehica-core'),
            'manage_options',
            'vehica_panel_monetization',
            static function () {
                require vehicaApp('views_path') . 'admin/monetization.php';
            }
        );
    }

    public function advanced()
    {
        add_submenu_page(
            'vehica_panel',
            esc_html__('Advanced', 'vehica-core'),
            esc_html__('Advanced', 'vehica-core'),
            'manage_options',
            'vehica_panel_advanced',
            static function () {
                require vehicaApp('views_path') . 'admin/advanced.php';
            }
        );
    }

    public function maps()
    {
        add_submenu_page(
            'vehica_panel',
            esc_html__('Google Maps', 'vehica-core'),
            esc_html__('Google Maps', 'vehica-core'),
            'manage_options',
            'vehica_panel_maps',
            static function () {
                require vehicaApp('views_path') . 'admin/maps.php';
            }
        );
    }

    public function notifications()
    {
        add_submenu_page(
            'vehica_panel',
            esc_html__('Notifications', 'vehica-core'),
            esc_html__('Notifications', 'vehica-core'),
            'manage_options',
            'vehica_panel_notifications',
            static function () {
                require vehicaApp('views_path') . 'admin/notifications.php';
            }
        );
    }

}