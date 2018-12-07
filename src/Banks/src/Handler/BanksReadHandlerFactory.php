<?php

declare(strict_types=1);

namespace Banks\Handler;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\ServerUrlHelper;

/**
 * Class BanksReadHandlerFactory
 * @package Banks\Handler
 */
class BanksReadHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return BanksReadHandler
     */
    public function __invoke(ContainerInterface $container) : BanksReadHandler
    {
        $entityManager = $container->get(EntityManager::class);

        $query = $entityManager->getRepository('Banks\Entity\Bank')
            ->createQueryBuilder('c')
            ->getQuery();

        $paginator  = new Paginator($query);

        $urlHelper = $container->get(ServerUrlHelper::class);

        return new BanksReadHandler($paginator, $container->get('config')['page_size'], $urlHelper);
    }
}
