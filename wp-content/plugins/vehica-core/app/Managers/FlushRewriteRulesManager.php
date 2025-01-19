<?php


namespace Vehica\Managers;

use Elementor\Plugin;
use Vehica\Core\Manager;

/**
 * Class FlushRewriteRulesManager
 * @package Vehica\Managers
 */
class FlushRewriteRulesManager extends Manager
{
    const OPTION_KEY = 'vehica_reset_rewrites';

    public function boot()
    {
        add_action('init', [$this, 'check']);
        add_action('vehica_flush_rewrites', [$this, 'scheduleFlush']);
    }

    public function scheduleFlush()
    {
        update_option(self::OPTION_KEY, 1, true);
    }

    public function check()
    {
        if ($this->rewritesNeedFlush()) {
            $this->flushRewrites();

            $this->setRewritesFlushed();
        }
    }

    /**
     * @return bool
     */
    private function rewritesNeedFlush()
    {
        $resetRewrites = get_option(self::OPTION_KEY);
        return !empty($resetRewrites);
    }

    private function flushRewrites()
    {
        flush_rewrite_rules();

        Plugin::instance()->files_manager->clear_cache();
    }

    private function setRewritesFlushed()
    {
        update_option(self::OPTION_KEY, 0);
    }

}