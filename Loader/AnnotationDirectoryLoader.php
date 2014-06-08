<?php

namespace Intaro\RuleEngineBundle\Loader;

use Symfony\Component\Config\Resource\DirectoryResource;
use Intaro\RuleEngineBundle\Event\Mapper\EventsMap;

class AnnotationDirectoryLoader extends AnnotationFileLoader
{
    /**
     * Loads from annotations from a directory.
     *
     * @param string $path A directory path
     * @param string $type The resource type
     *
     * @return EventsMap A event map
     *
     * @throws \InvalidArgumentException When annotations can't be parsed
     */
    public function load($path, $type = null)
    {
        $dir = $this->locator->locate($path);

        $map = new EventsMap();
        $map->addResource(new DirectoryResource($dir, '/Event\.php$/'));
        $files = iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir), \RecursiveIteratorIterator::LEAVES_ONLY));
        usort($files, function (\SplFileInfo $a, \SplFileInfo $b) {
            return (string) $a > (string) $b ? 1 : -1;
        });

        foreach ($files as $file) {
            if (!$file->isFile() || 'Event.php' !== substr($file->getFilename(), -9)) {
                continue;
            }

            if ($class = $this->findClass($file)) {
                $refl = new \ReflectionClass($class);
                if ($refl->isAbstract()) {
                    continue;
                }
                if (!$refl->getParentClass() || $refl->getParentClass()->getName() != 'Symfony\Component\EventDispatcher\Event') {
                    continue;
                }

                $map->merge($this->loader->load($class, $type));
            }
        }

        return $map;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        try {
            $path = $this->locator->locate($resource);
        } catch (\Exception $e) {
            return false;
        }

        return is_string($resource) && is_dir($path) && (!$type || 'annotation' === $type);
    }
}
