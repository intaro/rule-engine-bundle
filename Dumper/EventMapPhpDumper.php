<?php

namespace Intaro\RuleEngineBundle\Dumper;

use Intaro\RuleEngineBundle\Event\Mapper\EventsMap;

class EventMapPhpDumper
{
    public function dump(EventsMap $map)
    {
        $result = "<?php\n";
        $result .= "use Intaro\RuleEngineBundle\Event\Mapper\EventMap;\n";
        $result .= "return new Intaro\RuleEngineBundle\Event\Mapper\EventsMap(\n";
        $result .= "    array(\n";

        foreach ($map->getEventMaps() as $eventMap) {
            $result .= "        '" . $eventMap->getName() . "' => new EventMap(";
            $result .= "'" . $eventMap->getName() . "','" . $eventMap->getClass() . "','"
                . $eventMap->getType() . "',array(";
            $i = 0;
            foreach ($eventMap->getGetters() as $method => $getterMeta) {
                $result .= ($i > 0 ? ', ' : '') . "'$method' => array(";
                $result .= "'field' => '" . $getterMeta['field'] . "'";
                $result .= ",'type' => '" . $getterMeta['type'] . "'";
                $result .= ",'data' => " . ($getterMeta['data'] ? 'true' : 'false') . "";
                $result .= ",'tags' => array(" . (!empty($getterMeta['tags'])
                    ? "'" . implode("','", $getterMeta['tags']) . "'"
                    : '') . ")";
                $result .= ")";
                $i++;
            }
            $result .= "), array(";
            $i = 0;
            foreach ($eventMap->getSetters() as $method => $setterMeta) {
                $result .= ($i > 0 ? ', ' : '') . "'$method' => array(";
                $result .= "'field' => '" . $setterMeta['field'] . "'";
                $result .= ",'type' => '" . $setterMeta['type'] . "'";
                $result .= ",'required' => " . ($setterMeta['required'] ? 'true' : 'false') . "";
                $result .= ",'tags' => array(" . (!empty($setterMeta['tags'])
                    ? "'" . implode("','", $setterMeta['tags']) . "'"
                    : '') . ")";
                $result .= ")";
                $i++;
            }
            $result .= ")),\n";
        }

        $result .= "    )\n";
        $result .= ");\n";

        return $result;
    }
}
