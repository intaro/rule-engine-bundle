<?php

namespace Intaro\RuleEngineBundle\Annotation;

/**
* @Annotation
* @Target({"METHOD"})
* @Attributes({
*   @Attribute("field", type = "string"),
*   @Attribute("type", type = "string"),
*   @Attribute("data", type = "bool"),
*   @Attribute("tags", type = "array")
* })
*/
class Getter
{
    private $field;
    private $type = 'string';
    private $data = true;
    private $tags = [];

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
        if (isset($values['tags'])) {
            $this->tags = $values['tags'];
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

    public function getTags()
    {
        if (!is_array($this->tags)) {
            $this->tags = [];
        }

        return $this->tags;
    }
}
