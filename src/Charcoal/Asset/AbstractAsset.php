<?php

namespace Charcoal\Asset;

use \Charcoal\Asset\AssetInterface as AssetInterface;

use \Charcoal\Cache\CacheableInterface as CacheableInterface;
use \Charcoal\Cache\CacheableTrait as CacheableTrait;

use \Charcoal\Loader\LoadableInterface as LoadableInterface;
use \Charcoal\Loader\LoadableTrait as LoadableTrait;

abstract class AbstractAsset implements
    AssetInterface,
    CacheableInterface,
    LoadableInterface
{
    use CacheableTrait;
    use LoadableTrait;
}
