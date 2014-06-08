<?php

namespace Intaro\RuleEngineBundle\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class RuleExpressionLanguage extends ExpressionLanguage
{
    protected function registerFunctions()
    {
        parent::registerFunctions(); // do not forget to also register core functions
    }
}