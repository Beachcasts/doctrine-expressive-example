<?php

declare(strict_types=1);

namespace Announcements\Handler;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\ServerUrlHelper;

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
        $entityManager = $container->get(EntityManager::class);

        $urlHelper = $container->get(ServerUrlHelper::class);

        return new AnnouncementsViewHandler($entityManager, $urlHelper);
    }
}
