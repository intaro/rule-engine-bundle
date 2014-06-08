<?php

namespace Intaro\RuleEngineBundle\Model;

class Rule implements RuleInterface
{
    private $filter;
    private $rule;
    private $event;
    private $actionEvents;

    public function __construct($event, $filter, $rule, array $actionEvents = array())
    {
        $this->event = $event;
        $this->filter = $filter;
        $this->rule = $rule;
        $this->actionEvents = $actionEvents;
    }

    /**
     * Return filter expression
     *
     * @access public
     * @return string
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Return rule expression
     *
     * @access public
     * @return void
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * Return handled event name
     *
     * @access public
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Return array of action events
     *
     * @access public
     * @return array
     */
    public function getActionEvents()
    {
        return $this->actionEvents;
    }
}