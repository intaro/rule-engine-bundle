<?php

namespace Intaro\RuleEngineBundle\Model;

interface RuleInterface
{
    /**
     * Return filter expression
     *
     * @access public
     * @return string
     */
    public function getFilter();

    /**
     * Return rule expression
     *
     * @access public
     * @return void
     */
    public function getRule();

    /**
     * Return handled event name
     *
     * @access public
     * @return string
     */
    public function getEvent();

    /**
     * Return array of action events
     *
     * @access public
     * @return array
     */
    public function getActionEvents();
}