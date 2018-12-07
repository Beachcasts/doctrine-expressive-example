<?php

declare(strict_types=1);

namespace Announcements\Handler;

use Doctrine\ORM\EntityManager;
use Announcements\Entity\Announcement;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\ServerUrlHelper;

/**
 * Class AnnouncementsCreateHandlerFactory
 * @package Announcements\Handler
 */
class AnnouncementsCreateHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return AnnouncementsCreateHandler
     */
    public function __invoke(ContainerInterface $container) : AnnouncementsCreateHandler
    {
        $entityManager = $container->get(EntityManager::class);

        $entity = new Announcement();

        $urlHelper = $container->get(ServerUrlHelper::class);

        return new AnnouncementsCreateHandler($entityManager, $entity, $urlHelper);
    }
}
