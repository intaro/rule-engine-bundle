<?php

namespace Intaro\RuleEngineBundle\Annotation;

/**
* @Annotation
* @Target({"METHOD"})
* @Attributes({
*   @Attribute("field", type = "string"),
*   @Attribute("type", type = "string")
* })
*/
class Getter
{
    private $field;
    private $type = 'string';

    public function __construct(array $values)
    {
        if (isset($values['field'])) {
            $this->field = $values['field'];
        }
        if (isset($values['type'])) {
            $this->type = $values['type'];
        }
    }

    public function getField()
    {
        return $this->field;
    }

    public function getType()
    {
        return $this->type;
    }
}