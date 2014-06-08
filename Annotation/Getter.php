<?php

namespace Intaro\RuleEngineBundle\Annotation;

/**
* @Annotation
* @Target({"METHOD"})
* @Attributes({
*   @Attribute("field", type = "string")
* })
*/
class Getter
{
    private $field;

    public function __construct(array $values)
    {
        if (isset($values['field'])) {
            $this->field = $values['field'];
        }
    }

    public function getField()
    {
        return $this->field;
    }
}