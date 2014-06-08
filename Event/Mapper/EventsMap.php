<?php

namespace Intaro\RuleEngineBundle\Event\Mapper;

use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * Contains map of events
 */
class EventsMap
{
    const CLASS_ACTION  = 'Intaro\\RuleEngineBundle\\Annotation\\ActionEvent';
    const CLASS_CAUSE   = 'Intaro\\RuleEngineBundle\\Annotation\\CauseEvent';
    const METHOD_GETTER = 'Intaro\\RuleEngineBundle\\Annotation\\Getter';
    const METHOD_SETTER = 'Intaro\\RuleEngineBundle\\Annotation\\Setter';

    private $events;
    private $resources;

    public function __construct(array $events = array(), array $resources = array())
    {
        $this->events = $events;
        $this->resources = $resources;
    }

    public function getEventMap($name)
    {
        return isset($this->events[$name]) ? $this->events[$name] : null;
    }

    public function addEventMap(EventMap $eventMap)
    {
        $this->events[$eventMap->getName()] = $eventMap;
    }

    public function getEventMaps($type = null)
    {
        if ($type !== null && $type != self::CLASS_ACTION && $type != self::CLASS_CAUSE) {
            throw new \InvalidArgumentException('Invalid event type "' . (string) $type . '"');
        }

        if (!$type) {
            return $this->events;
        }

        $events = array();
        foreach ($this->events as $event) {
            if ($event->getType() == $type) {
                $events[] = $event;
            }
        }

        return $events;
    }

    /**
     * Returns an array of resources loaded to build this rules.
     *
     * @return ResourceInterface[] An array of resources
     */
    public function getResources()
    {
        return array_unique($this->resources);
    }

    /**
     * Adds a resource for this rules.
     *
     * @param ResourceInterface $resource A resource instance
     */
    public function addResource(ResourceInterface $resource)
    {
        $this->resources[] = $resource;
    }

    public function merge(EventsMap $map)
    {
        $this->resources = array_merge($this->resources, $map->getResources());

        foreach ($map->getEventMaps() as $name => $eventMap) {
            $this->addEventMap($eventMap);
        }
    }
}