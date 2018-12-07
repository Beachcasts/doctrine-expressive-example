<?php

declare(strict_types=1);

namespace Announcements\Handler;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\ServerUrlHelper;

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

        $query = $entityManager->getRepository('Announcements\Entity\Announcement')
            ->createQueryBuilder('c')
            ->getQuery();

        $paginator  = new Paginator($query);

        $urlHelper = $container->get(ServerUrlHelper::class);

        return new AnnouncementsReadHandler($paginator, $container->get('config')['page_size'], $urlHelper);
    }
}
