<?php

namespace Intaro\RuleEngineBundle\Loader;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Config\Resource\FileResource;
use Intaro\RuleEngineBundle\Event\Mapper\EventsMap;
use Intaro\RuleEngineBundle\Event\Mapper\EventMap;

class AnnotationClassLoader implements LoaderInterface
{
    protected $reader;

    protected $annotationClassAction = EventsMap::CLASS_ACTION;
    protected $annotationClassCause = EventsMap::CLASS_CAUSE;
    protected $annotationMethodGetter = EventsMap::METHOD_GETTER;
    protected $annotationMethodSetter = EventsMap::METHOD_SETTER;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Loads from annotations from a class.
     *
     * @param string $class A class name
     * @param string $type  The resource type
     *
     * @return EventsMap A event map
     *
     * @throws \InvalidArgumentException When annotations can't be parsed
     */
    public function load($class, $type = null)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        $class = new \ReflectionClass($class);
        if ($class->isAbstract()) {
            throw new \InvalidArgumentException(sprintf('Annotations from class "%s" cannot be read as it is abstract.', $class));
        }

        $map = new EventsMap();
        $map->addResource(new FileResource($class->getFileName()));

        $eventMap = null;
        foreach($this->reader->getClassAnnotations($class) as $annot) {
            if ($annot instanceof $this->annotationClassAction) {
                $eventMap = new EventMap($annot->getName(), $class->getName(), $this->annotationClassAction);
                continue;
            }
            if ($annot instanceof $this->annotationClassCause) {
                $eventMap = new EventMap($annot->getName(), $class->getName(), $this->annotationClassCause);
                continue;
            }
        }
        if ($eventMap) {
            $getters = array();
            $setters = array();
            foreach ($class->getMethods() as $method) {
                foreach ($this->reader->getMethodAnnotations($method) as $annot) {
                    if ($annot instanceof $this->annotationMethodGetter) {
                        $getter = array(
                            'type' => $annot->getType(),
                        );
                        if ($annot->getField()) {
                            $getter['field'] = $annot->getField();
                        }
                        else {
                            $field = $method->getName();
                            if (strncmp($field, 'get', 3) === 0 && strlen($field) > 3) {
                                $field = lcfirst(substr($field, 3));
                            }
                            elseif (strncmp($field, 'is', 2) === 0 && strlen($field) > 2) {
                                $field = lcfirst(substr($field, 2));
                            }
                            $getter['field'] = $field;
                        }
                        $getters[$method->getName()] = $getter;
                    }
                    if ($annot instanceof $this->annotationMethodSetter) {
                        $setter = array(
                            'required' => $annot->isRequired(),
                            'type' => $annot->getType(),
                        );
                        if ($annot->getField()) {
                            $setter['field'] = $annot->getField();
                        }
                        else {
                            $field = $method->getName();
                            if (strncmp($field, 'set', 3) === 0 && strlen($field) > 3) {
                                $field = lcfirst(substr($field, 3));
                            }
                            $setter['field'] = $field;
                        }

                        $setters[$method->getName()] = $setter;
                    }
                }
            }
            $eventMap->setGetters($getters);
            $eventMap->setSetters($setters);
            $map->addEventMap($eventMap);
        }

        return $map;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && preg_match('/^(?:\\\\?[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)+$/', $resource) && (!$type || 'annotation' === $type);
    }

    /**
     * {@inheritdoc}
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getResolver()
    {
    }
}