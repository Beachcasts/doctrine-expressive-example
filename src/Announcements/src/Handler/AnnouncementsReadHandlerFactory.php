<?php

declare(strict_types=1);

namespace Announcements\Handler;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;

/**
 * Class AnnouncementsReadHandlerFactory
 * @package Announcements\Handler
 */
class AnnouncementsReadHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return AnnouncementsReadHandler
     */
    public function __invoke(ContainerInterface $container) : AnnouncementsReadHandler
    {
        $entityManager = $container->get(EntityManager::class);

        $resourceGenerator = $container->get(ResourceGenerator::class);
        $responseFactory = $container->get(HalResponseFactory::class);

        return new AnnouncementsReadHandler($entityManager, $container->get('config')['page_size'], $responseFactory, $resourceGenerator);
    }
}
