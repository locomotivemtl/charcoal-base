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

    /**
    * @return string
    */
    abstract public function relative_url();

    /**
    * @return string
    */
    abstract public function absolute_url();

    /**
    * @return string
    */
    abstract public function relative_path();

    /**
    * @return string
    */
    abstract public function absolute_path();

    /**
    * @return AbstractAsset Chainable
    */
    public function cache_data()
    {
        return $this;
    }

    /**
    * @param array $data Optional
    * @return AssetLoader
    */
    public function create_loader(array $data = null)
    {
        $loader = new AssetLoader();
        if (is_array($data)) {
            $loader->set_data($data);
        }
        return $loader;
    }
}
