<?php

declare(strict_types=1);

namespace Announcements\Handler;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\ServerUrlHelper;

/**
 * Class AnnouncementsDeleteHandlerFactory
 * @package Announcements\Handler
 */
class AnnouncementsDeleteHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return AnnouncementsDeleteHandler
     */
    public function __invoke(ContainerInterface $container) : AnnouncementsDeleteHandler
    {
        $entityManager = $container->get(EntityManager::class);

        $urlHelper = $container->get(ServerUrlHelper::class);

        return new AnnouncementsDeleteHandler($entityManager, $urlHelper);
    }
}
