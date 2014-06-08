<?php

namespace Intaro\RuleEngineBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Intaro\RuleEngineBundle\Event\Mapper\EventsMap;

class IntaroRuleEngineBundle extends Bundle
{
    /**
     * Register cause events handling
     */
    public function boot()
    {
        $mapper     = $this->container->get('intaro.rule_engine.mapper');
        $listener   = $this->container->get('intaro.rule_engine.rule_listener');
        $dispatcher = $this->container->get('event_dispatcher');

        $eventMaps = $mapper->getEventsMaps(EventsMap::CLASS_CAUSE);

        foreach ($eventMaps as $eventMap) {
            $dispatcher->addListener($eventMap->getName(), array($listener, 'handleEvent'));
        }
    }
}
