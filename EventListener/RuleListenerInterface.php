<?php

namespace Intaro\RuleEngineBundle\EventListener;

use Symfony\Component\EventDispatcher\Event;

interface RuleListenerInterface
{
    /**
     * Handle the event
     *
     * @param Event $event
     * @return Event
     */
    public function handleEvent(Event $event);
}