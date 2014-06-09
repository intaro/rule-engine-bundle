<?php

namespace Intaro\RuleEngineBundle\EventListener;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\EventDispatcher\Event;
use Intaro\RuleEngineBundle\Model\RuleInterface;
use Intaro\RuleEngineBundle\Event\Mapper\EventMapper;
use Intaro\RuleEngineBundle\Event\Dispatcher\ActionEventDispatcherInterface;
use Intaro\RuleEngineBundle\Manager\RuleManagerInterface;

class RuleListener implements RuleListenerInterface
{
    private $evaluator;
    private $mapper;
    private $dispatcher;
    private $ruleManager;

    public function __construct(
        ExpressionLanguage $evaluator,
        EventMapper $mapper,
        ActionEventDispatcherInterface $dispatcher,
        RuleManagerInterface $ruleManager
    )
    {
        $this->evaluator = $evaluator;
        $this->mapper = $mapper;
        $this->dispatcher = $dispatcher;
        $this->ruleManager = $ruleManager;
    }

    public function handleEvent(Event $event)
    {
        $rules = $this->ruleManager->findRulesByEventName($event->getName());

        if (sizeof($rules)) {
            foreach ($rules as $rule) {
                if ($this->evaluateRule($rule, $event)) {
                    $this->dispatchActionEvents($rule, $event);
                }
            }
        }

        return $event;
    }

    private function evaluateRule(RuleInterface $rule, Event $event)
    {
        $objects = $this->mapper->getEventObjects($event);

        // check rule filter
        if ( (bool) $this->evaluator->evaluate($rule->getFilter(), $objects) ) {
            //check rule
            return (bool) $this->evaluator->evaluate($rule->getRule(), $objects);
        }

        return false;
    }

    private function dispatchActionEvents(RuleInterface $rule, Event $event)
    {
        foreach ($rule->getActionEvents() as $item) {
            $actionEvent = $this->mapper->buildActionEvent($item, $event);

            if ($actionEvent) {
                $this->dispatcher->dispatch($item->getName(), $actionEvent, $item->getDelay());
            }
        }
    }
}