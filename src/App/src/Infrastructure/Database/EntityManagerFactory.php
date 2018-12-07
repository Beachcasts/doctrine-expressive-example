<?php

declare(strict_types=1);

namespace App\Infrastructure\Database;

use Psr\Container\ContainerInterface;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class EntityManagerFactoryShouldNotBeUses
{
    public function __invoke(ContainerInterface $container) : EntityManager
    {
//        $files = array(
//            __DIR__."/Entity"
//        );
//
//        $isDevMode = false;
//        $simpleAnnotationReader = false;
//
//        $config = $container->get('config');
//        $connectionParams = $config['db'];
//
//        $ormConfig = Setup::createAnnotationMetadataConfiguration($files, $isDevMode, null, null, $simpleAnnotationReader);
//
//        return $entityManager = EntityManager::create($connectionParams, $ormConfig);
    }
}
