<?php

declare(strict_types=1);

namespace Announcements\Handler;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;

/**
 * Class AnnouncementsViewHandlerFactory
 * @package Announcements\Handler
 */
class AnnouncementsViewHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return AnnouncementsViewHandler
     */
    public function __invoke(ContainerInterface $container) : AnnouncementsViewHandler
    {
        return new AnnouncementsViewHandler(
            $container->get(EntityManager::class),
            $container->get(HalResponseFactory::class),
            $container->get(ResourceGenerator::class)
        );
    }
}
