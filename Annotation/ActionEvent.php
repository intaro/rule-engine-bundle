<?php

namespace Intaro\RuleEngineBundle\Annotation;

/**
* @Annotation
* @Target({"CLASS"})
* @Attributes({
*   @Attribute("name", type = "string")
* })
*/
class ActionEvent
{
    private $name;

    public function __construct(array $values)
    {
        if (!isset($values['name'])) {
            throw new \InvalidArgumentException('Annotation ActionEvent must contain "name" property.');
        }

        $this->name = $values['name'];
    }

    public function getName()
    {
        return $this->name;
    }
}