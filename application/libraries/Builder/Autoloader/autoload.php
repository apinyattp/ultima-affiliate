<?php

namespace Builder\Autoloader;

require_once 'Psr4Autoloader.php';

/**
 * @var string $srcBaseDirectory
 * Full path to "src/Spout" which is what we want "Builder" to map to.
 */
$srcBaseDirectory = dirname(dirname(__FILE__));

$loader = new Psr4Autoloader();
$loader->register();
$loader->addNamespace('Builder', $srcBaseDirectory);
