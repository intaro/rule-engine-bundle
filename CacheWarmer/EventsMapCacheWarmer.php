<?php

namespace Intaro\RuleEngineBundle\CacheWarmer;

use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Intaro\RuleEngineBundle\Event\Mapper\EventMapper;

class EventsMapCacheWarmer implements CacheWarmerInterface
{
    protected $mapper;

    public function __construct(EventMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * Warms up the cache.
     *
     * @param string $cacheDir The cache directory
     */
    public function warmUp($cacheDir)
    {
        if ($this->mapper instanceof WarmableInterface) {
            $this->mapper->warmUp($cacheDir);
        }
    }

    /**
     * Checks whether this warmer is optional or not.
     *
     * Optional warmers can be ignored on certain conditions.
     *
     * A warmer should return true if the cache can be
     * generated incrementally and on-demand.
     *
     * @return Boolean true if the warmer is optional, false otherwise
     */
    public function isOptional()
    {
        return true;
    }
}