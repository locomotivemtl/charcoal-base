<?php

namespace Charcoal\Asset;

interface AssetInterface
{
    public function relative_url();
    public function absolute_url();
    public function relative_path();
    public function absolute_path();
}
