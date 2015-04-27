<?php

namespace Charcoals\Tests\Asset;

use \Charcoal\Asset\Asset as Asset;

/**
* Test the AbstractAsset methods, through concrete `Asset` class.
*/
class AbstractAssetTest extends \PHPUnit_Framework_Testcase
{
    public function testConstructor()
    {
        $obj = new Asset();
        $this->assertInstanceOf('Charcoal\Asset\Asset', $obj);
    }
}
