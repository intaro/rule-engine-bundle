<?php

namespace Intaro\RuleEngineBundle\Event\Mapper;

/**
 * Map of the event
 */
class EventMap
{
    private $name;
    private $class;
    private $type;
    private $getters;
    private $setters;

    public function __construct(
        $name = null,
        $class = null,
        $type = EventsMap::CLASS_CAUSE,
        array $getters = array(),
        array $setters = array()
    )
    {
        $this->name = $name;
        $this->class = $class;
        $this->type = $type;
        $this->setGetters($getters);
        $this->setSetters($setters);
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setClass($class)
    {
        $this->class = $class;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setGetters(array $getters = array())
    {
        $this->getters = $getters;
    }

    public function getGetters()
    {
        return $this->getters;
    }

    public function setSetters(array $setters = array())
    {
        $this->setters = $setters;
    }

    public function getSetters()
    {
        return $this->setters;
    }
}