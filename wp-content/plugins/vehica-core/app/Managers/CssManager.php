<?php

namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Widgets\General\PanelGeneralWidget;

/**
 * Class CssManager
 *
 * @package Vehica\Managers
 */
class CssManager extends Manager
{

    public function boot()
    {
        add_filter('body_class', function ($classes) {
            $classes[] = $this->getVersionClass();

            if ($this->isComparePage()) {
                $classes[] = 'vehica-compare-page';
            }

            if (
                isset($_GET[PanelGeneralWidget::ACTION_TYPE])
                && $_GET[PanelGeneralWidget::ACTION_TYPE] === PanelGeneralWidget::ACTION_TYPE_RESET_PASSWORD
            ) {
                $classes[] = 'vehica-reset-password';
            }

            return $classes;
        });

        add_action('wp_enqueue_scripts', [$this, 'css'], 20);
    }

    /**
     * @return bool
     */
    private function isComparePage()
    {
        global $post;
        if (!$post) {
            return false;
        }

        return $post->ID === vehicaApp('settings_config')->getComparePageId();
    }

    public function css()
    {
        ob_start();

        $this->utilities();

        $this->fonts();

        $this->colors();

        wp_add_inline_style('vehica', $this->minify(ob_get_clean()));
    }

    private function utilities()
    {
        ?>

        <?php
    }

    private function fonts()
    {
        $this->textFont();

        $this->headingFont();
    }

    private function headingFont()
    {
        if (empty(vehicaApp('heading_font'))) {
            return;
        }
        ?>
        h1, h2, h3, h4, h5, h6 {
        font-family: '<?php echo vehicaApp('heading_font'); ?>', Arial,Helvetica,sans-serif;
        }
        <?php
    }

    private function textFont()
    {
        if (empty(vehicaApp('text_font'))) {
            return;
        }
        ?>
        body, textarea, input, button {
        font-family: '<?php echo vehicaApp('text_font') ?>', Arial,Helvetica,sans-serif!important;
        }
        <?php
    }

    private function colors()
    {
        ?>
        :root {
        --primary: <?php echo esc_html(vehicaApp('primary_color')); ?>;
        <?php if (mb_strtolower(vehicaApp('primary_color')) === '#ff4605') : ?>
        --primary-light: #fff0eb;
    <?php else : ?>
        --primary-light: <?php echo esc_html($this->hex2rgba(vehicaApp('primary_color'), 0.1)); ?>;
    <?php endif; ?>
        }
        <?php
    }

    /**
     * @param string $css
     *
     * @return string
     */
    public function minify($css)
    {
        $css = preg_replace('/\/\*((?!\*\/).)*\*\//', '', $css);
        $css = preg_replace('/\s{2,}/', ' ', $css);
        $css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
        $css = preg_replace('/;}/', '}', $css);

        return $css;
    }

    /**
     * @return string
     */
    private function getVersionClass()
    {
        if (!defined('VEHICA_VERSION')) {
            return 'vehica-version-1.0.0';
        }

        return 'vehica-version-' . VEHICA_VERSION;
    }

    /**
     * @param string $color
     * @param float|false $opacity
     * @return string
     */
    private function hex2rgba($color, $opacity = false)
    {
        $default = 'rgb(0,0,0)';

        if (empty($color)) {
            return $default;
        }

        if ($color[0] === '#') {
            $color = substr($color, 1);
        }

        if (strlen($color) === 6) {
            $hex = [$color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]];
        } elseif (strlen($color) === 3) {
            $hex = [$color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]];
        } else {
            return $default;
        }

        $rgb = array_map('hexdec', $hex);

        if ($opacity) {
            if (abs($opacity) > 1) {
                $opacity = 1.0;
            }
            $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(",", $rgb) . ')';
        }

        return $output;
    }

}