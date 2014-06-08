<?php

namespace Intaro\RuleEngineBundle\Event\Dispatcher;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * The simple dispatcher of action events
 */
class ActionEventDispatcher implements ActionEventDispatcherInterface
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Dispatches an event with or without delay
     *
     * @param string $eventName
     * @param Event $event
     * @param int $delay
     * @return Event
     */
    public function dispatch($eventName, Event $event, $delay = 0)
    {
        if ($delay) {
            sleep((int) $delay);
        }

        $this->dispatcher->dispatch($eventName, $event);

        return $event;
    }
}