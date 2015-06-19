<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$file = __DIR__.'/../vendor/autoload.php';
if (!file_exists($file)) {
    throw new RuntimeException('Install dependencies to run test suite.');
}

$autoload = require_once $file;

use Doctrine\Common\Annotations\AnnotationRegistry;
AnnotationRegistry::registerLoader(function ($class) {
    if (strpos($class, 'Manhattan\Bundle\ConsoleBundle\Controller\\') === 0) {
        $path = __DIR__.'/../'.str_replace('\\', '/', substr($class, strlen('Manhattan\Bundle\ConsoleBundle\\'))).'.php';
        require_once $path;
    }

    return class_exists($class, false);
});
