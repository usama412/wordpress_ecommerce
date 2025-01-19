<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Post\Page;

/**
 * Class TaxonomyUrlManager
 * @package Vehica\Managers
 */
class TaxonomyUrlManager extends Manager
{

    public function boot()
    {
        add_action('init', [$this, 'rewriteRules']);
    }

    public function rewriteRules()
    {
        if (!vehicaApp('settings_config') || !vehicaApp('pretty_urls_enabled')) {
            return;
        }

        $data = [
            [
                'regex' => vehicaApp('vehicle_archive_rewrite') . '/',
                'query' => 'index.php?post_type=' . Car::POST_TYPE,
            ],
        ];

        foreach (vehicaApp('settings_config')->getCustomArchivePages() as $page) {
            /* @var Page $page */
            $data[] = [
                'regex' => $page->getSlug() . '/',
                'query' => 'index.php?pagename=' . $page->getSlug()
            ];
        }

        foreach ($data as $d) {
            $regex = $d['regex'];
            $query = $d['query'];
            $counter = 1;

            foreach (vehicaApp('taxonomy_url') as $taxonomy) {
                $regex .= '([^/]+)/';
                $query .= '&' . $taxonomy->getKey() . '=$matches[' . $counter . ']';

                /* @var Taxonomy $taxonomy */
                add_rewrite_rule(
                    $regex . '?$',
                    $query,
                    'top'
                );

                $counter++;
            }
        }
    }

}