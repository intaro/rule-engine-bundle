<?php

namespace Intaro\RuleEngineBundle\Event\Mapper;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Intaro\RuleEngineBundle\Model\ActionEventInterface;

class EventMapper implements WarmableInterface
{
    private $loader;
    private $map;

    public function __construct(LoaderInterface $loader, array $options = array())
    {
        $this->loader = $loader;
        $this->setOptions($options);
    }

    /**
     * Get event objects
     *
     * @access public
     * @param Event $event
     * @return array
     */
    public function getEventObjects(Event $event)
    {
        $eventMap = $this->getEventMap($event->getName());

        $objects = array();
        foreach ($eventMap->getGetters() as $method => $getterMeta) {
            if (method_exists($event, $method)) {
                $objects[$getterMeta['field']] = $event->$method();
            }
        }

        return $objects;
    }

    /**
     * Get event getters objects names and types
     *
     * @param $name
     * @return array
     */
    public function getEventContext($name, $onlyData = false)
    {
        $eventMap = $this->getEventMap($name);

        $names = array();
        foreach ($eventMap->getGetters() as $method => $getterMeta) {
            if ($onlyData && !$getterMeta['data']) {
                continue;
            }
            $names[$getterMeta['field']] = $getterMeta['type'];
        }

        return $names;
    }

    public function getEventClass($name)
    {
        return $this->getEventMap($name)->getClass();
    }

    /**
     * Build action event with objects mapped from cause event
     *
     * @access public
     * @param mixed $name
     * @param Event $mappedEvent (default: null)
     * @param array $mappedObjects (default: array())
     * @return Event
     */
    public function buildActionEvent(ActionEventInterface $actionEvent, Event $mappedEvent)
    {
        $mappedObjects = $actionEvent->getObjects();

        if (!$mappedEventMap = $this->getEventMap($mappedEvent->getName())) {
            throw new \InvalidArgumentException('Undefined mapped event "' . $mappedEvent->getName() . '"');
        }
        if (!$eventMap = $this->getEventMap($actionEvent->getName())) {
            throw new \InvalidArgumentException('Undefined action event "' . $actionEvent->getName() . '"');
        }

        $eventClass = $eventMap->getClass();
        $event = new $eventClass;
        foreach ($eventMap->getSetters() as $method => $setterMeta) {
            $field = $setterMeta['field'];

            $value = null;
            if (isset($mappedObjects[$field])) {
                $value = $mappedObjects[$field];
            }
            else {
                foreach ($mappedEventMap->getGetters() as $getterMethod => $getterMeta) {
                    if ($setterMeta['field'] == $getterMeta['field']) {
                        $value = $mappedEvent->$getterMethod();
                        break;
                    }
                }
            }

            if (!is_null($value)) {
                $isFunction = 'is_' . $setterMeta['type'];
                $validObject = false;
                if (function_exists($isFunction) && $isFunction($value)) {
                    $validObject = true;
                } elseif ($value instanceof $setterMeta['type']) {
                    $validObject = true;
                }

                if ($validObject) {
                    $event->$method($value);
                    continue;
                }
            }

            if ($setterMeta['required']) {
                throw new \UnexpectedValueException(
                    sprintf(
                        'Not found object for method "%s" of the event "%s" (with event name "%s")',
                        $method,
                        $eventClass,
                        $actionEvent->getName()
                    )
                );
            }
        }

        return $event;
    }

    public function getEventsMaps($type = null)
    {
        if (!$this->map) {
            $this->loadMap();
        }

        return $this->map->getEventMaps($type);
    }

    public function getEventMap($name)
    {
        if (!$this->map) {
            $this->loadMap();
        }

        if (!$eventMap = $this->map->getEventMap($name)) {
            throw new \InvalidArgumentException('Not found information about event with name "' . $name . '"');
        }

        return $eventMap;
    }

    private function loadMap()
    {
        if (null === $this->options['cache_dir'] || null === $this->options['cache_filename']) {
            throw new \RuntimeException('Options "cache_dir" and "cache_filename" must be defined.');
        }

        $cache = new ConfigCache(
            $this->options['cache_dir'] . '/' . $this->options['cache_filename'] . '.php',
            $this->options['debug']
        );

        if (!$cache->isFresh()) {
            $map = new EventsMap();

            foreach ($this->options['bundles'] as $bundle) {
                $refl = new \ReflectionClass($bundle);

                $dir = dirname($refl->getFileName());
                if (file_exists($dir) && is_dir($dir)) {
                    $map->merge($this->loader->load($dir));
                }
            }

            $dumper = new $this->options['dumper_class']();
            $cache->write($dumper->dump($map), $map->getResources());
        }

        $this->map = include $cache;
    }

    public function setOptions(array $options)
    {
        $this->options = array(
            'cache_dir'      => null,
            'cache_filename' => 'IntaroRuleEngineEventMap',
            'dumper_class'   => null,
            'bundles'        => array(),
            'debug'          => false,
        );

        // check option names and live merge, if errors are encountered Exception will be thrown
        $invalid = array();
        foreach ($options as $key => $value) {
            if (array_key_exists($key, $this->options)) {
                $this->options[$key] = $value;
            } else {
                $invalid[] = $key;
            }
        }

        if ($invalid) {
            throw new \InvalidArgumentException(sprintf('The EventMapper does not support the following options: "%s".', implode('\', \'', $invalid)));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function warmUp($cacheDir)
    {
        $currentDir = $this->options['cache_dir'];

        // force cache generation
        $this->options['cache_dir'] = $cacheDir;
        $this->loadMap();

        $this->options['cache_dir'] = $currentDir;
    }
}
