<?php
/**
 * This file is used with the Doctrine CLI console, which is accessed from the application root.
 *
 * $ php vendor/bin/doctrine list
 * 
 * Or if using Docker container gain shell access first.
 */

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Helper\HelperSet;

$container = require __DIR__ . '/container.php';

return new HelperSet([
    'em' => new EntityManagerHelper(
        $container->get(EntityManager::class) // Set in App/ConfigProvider.php
    ),
]);
