<?php
/**
 * Class Cache | Cache.php
 * @package Faulancer\Cache
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Cache;

/**
 * Class Cache
 */
class Cache
{

    /** @var CacheableInterface[] */
    protected $resources = [];

    public function handle(CacheableInterface $class)
    {

        $this->resources[] = [
            'name' => $class
        ];

    }

}