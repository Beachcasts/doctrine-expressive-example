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
class AnnouncementsReadHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return AnnouncementsReadHandler
     */
    public function __invoke(ContainerInterface $container) : AnnouncementsReadHandler
    {
        return new AnnouncementsReadHandler(
            $container->get(EntityManager::class),
            $container->get(HalResponseFactory::class),
            $container->get(ResourceGenerator::class)
        );
    }
}
