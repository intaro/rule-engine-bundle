<?php

namespace Intaro\RuleEngineBundle\Annotation;

/**
* @Annotation
* @Target({"CLASS"})
* @Attributes({
*   @Attribute("name", type = "string")
* })
*/
class CauseEvent
{
    private $name;

    public function __construct(array $values)
    {
        if (!isset($values['name'])) {
            throw new \InvalidArgumentException('Annotation CauseEvent must contain "name" property.');
        }

        $this->name = $values['name'];
    }

    public function getName()
    {
        return $this->name;
    }
}