<?php

namespace Intaro\RuleEngineBundle\Manager;

interface RuleManagerInterface
{
    /**
     * Returns rules array related to the event
     *
     * @access public
     * @param mixed $name
     * @return array
     */
    public function findRulesByEventName($name);
}