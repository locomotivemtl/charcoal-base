<?php

namespace Charcoal\Asset;

interface AssetInterface
{
    /**
    * @return string
    */
    public function relative_url();

    /**
    * @return string
    */
    public function absolute_url();

    /**
    * @return string
    */
    public function relative_path();

    /**
    * @return string
    */
    public function absolute_path();
}
