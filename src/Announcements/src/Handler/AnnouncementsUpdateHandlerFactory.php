<?php

declare(strict_types=1);

namespace Announcements\Handler;

use Doctrine\ORM\EntityManager;
use Announcements\Entity\Announcement;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\ServerUrlHelper;

/**
 * Class AnnouncementsUpdateHandlerFactory
 * @package Announcements\Handler
 */
class AnnouncementsUpdateHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return AnnouncementsUpdateHandler
     */
    public function __invoke(ContainerInterface $container) : AnnouncementsUpdateHandler
    {
        $entityManager = $container->get(EntityManager::class);

        $entityRepository = $entityManager->getRepository('Announcements\Entity\Announcement');

        $entity = new Announcement();

        $urlHelper = $container->get(ServerUrlHelper::class);

        return new AnnouncementsUpdateHandler($entityManager, $entityRepository, $entity, $urlHelper);
    }
}
