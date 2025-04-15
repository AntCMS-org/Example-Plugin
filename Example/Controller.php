<?php

namespace AntCMS\Plugins\Example;

use AntCMS\AbstractPlugin;
use AntCMS\Event;
use AntCMS\HookController;
use AntCMS\Twig;
use Flight;

class Controller extends AbstractPlugin
{
    public function __construct()
    {
        // Register a hook and sets the description for it
        HookController::registerHook('myCoolHook', 'The helpful description of my hook', true);

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

    public function hookCallback(Event $event): Event
    {
        if ($event->isDefaultPrevented()) {
            return $event;
        }

        // The AntCMS\Event object holds important info about a hook that has been fired.
        $data = $event->getParameters();
        $firedAt = $event->firedAt(); // DateTime object
        $name = $event->getHookName();
        $readCount = $event->getReadCount(); // The total number of times the parameters for a hook were read.
        $updateCount = $event->getUpdateCount(); // The total number of times the parameters for a hook were updated.

        if ($event->isDone()) {
            $event->timeElapsed(); // Time elapsed in nano seconds
        }

        // We don't actually want to blindly do this due to potential breakage, but some hooks can have their default behavior prevented.
        if ($event->isDefaultPreventable()) {
            //$event->preventDefault();
        }

        // We can update the parameters of a hook, in which case we **have** to return the Event object. Otherwise, no return is needed.
        $event->setParameters(['some', 'values']);
        return $event;
    }
}
