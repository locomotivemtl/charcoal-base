<?php

use \Charcoal\Charcoal;

// Composer autoloader for Charcoal's psr4-compliant Unit Tests
$autoloader = require __DIR__.'/../vendor/autoload.php';
$autoloader->add('Charcoal\\', __DIR__.'/src/');
$autoloader->add('Charcoal\\Tests\\', __DIR__);


$config = new \Charcoal\CharcoalConfig();
Charcoal::init([
    'config'=>$config
]);
