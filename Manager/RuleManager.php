<?php

namespace Intaro\RuleEngineBundle\Manager;

use Intaro\RuleEngineBundle\Model\RuleInterface;

/**
 * The simple in-memory rule manager
 */
class RuleManager implements RuleManagerInterface
{
    private $rules;

    public function __construct(array $rules = array())
    {
        $this->rules = $rules;
    }

    public function addRule(RuleInterface $rule)
    {
        if (!isset($this->rules[$rule->getEvent()])) {
            $this->rules[$rule->getEvent()] = array();
        }

        $this->rules[$rule->getEvent()][] = $rule;
    }

    public function findRulesByEventName($name)
    {
        return isset($this->rules[$name]) ? $this->rules[$name] : null;
    }
}