<?php

namespace Intaro\RuleEngineBundle\Annotation;

/**
* @Annotation
* @Target({"METHOD"})
* @Attributes({
*   @Attribute("field", type = "string"),
*   @Attribute("type", type = "string"),
*   @Attribute("required", type = "bool")
* })
*/
class Setter
{
    private $field;
    private $type = 'string';
    private $required = true;

    public function __construct(array $values)
    {
        if (isset($values['field'])) {
            $this->field = $values['field'];
        }
        if (isset($values['type'])) {
            $this->type = $values['type'];
        }
        if (isset($values['required'])) {
            $this->required = (bool) $values['required'];
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

    public function isRequired()
    {
        return $this->required;
    }
}