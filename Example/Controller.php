<?php

namespace AntCMS\Plugins\Example;

use AntCMS\AbstractPlugin;
use AntCMS\HookController;
use AntCMS\Twig;
use Flight;

class Controller extends AbstractPlugin
{
    public function __construct()
    {
        // Register a hook and sets the description for it
        HookController::registerHook('myCoolHook', 'The helpful description of my hook');

        /**
         * Register a callback for the hook you just created
         * You could also directly to this without calling `registerHook`, which will register the hook without a description
         * If `registerHook` is later called, the description of the hook will be updated
         */
        HookController::registerCallback('myCoolHook', [$this, 'hookCallback']);

        // Register a route
        Flight::route("GET /example", function (): void {
            // We can fire that hook we made when this page is loaded
            HookController::fire('myCoolHook', ['some', 'data', 'in', 'an', 'array']);

            // Here we get a complete list of hooks
            $hooks = HookController::getHookList();

            // And pass that list to the example template
            echo Twig::render('example.html.twig', [
                'hooks' => $hooks,
                'AntCMSTitle' => 'Example Page',
            ]);
        });

        // Add a new sitemap entry
        $this->appendSitemap('/example');

        // Disallow it from being indexed via the robots.txt file
        $this->addDisallow('/example');

        // Or we could explicitly allow indexing it
        //$this->addAllow('/hi');
    }

    public function hookCallback(array $data): void
    {
        error_log(print_r($data, true));
    }
}
