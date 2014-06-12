<?php

namespace Intaro\RuleEngineBundle\Annotation;

/**
* @Annotation
* @Target({"METHOD"})
* @Attributes({
*   @Attribute("field", type = "string"),
*   @Attribute("type", type = "string"),
*   @Attribute("data", type = "bool")
* })
*/
class Getter
{
    private $field;
    private $type = 'string';
    private $data = true;

    public function __construct(array $values)
    {
        if (isset($values['field'])) {
            $this->field = $values['field'];
        }
        if (isset($values['type'])) {
            $this->type = $values['type'];
        }
        if (isset($values['data'])) {
            $this->data = (bool) $values['data'];
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

    public function isData()
    {
        return $this->data;
    }
}