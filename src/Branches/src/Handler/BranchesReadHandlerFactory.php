<?php

declare(strict_types=1);

namespace Branches\Handler;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\ServerUrlHelper;

/**
 * Class BranchesReadHandlerFactory
 * @package Branches\Handler
 */
class BranchesReadHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return BranchesReadHandler
     */
    public function __invoke(ContainerInterface $container) : BranchesReadHandler
    {
        $entityManager = $container->get(EntityManager::class);

        $query = $entityManager->getRepository('Branches\Entity\Branch')
            ->createQueryBuilder('c')
            ->getQuery();

        $paginator  = new Paginator($query);

        $urlHelper = $container->get(ServerUrlHelper::class);

        return new BranchesReadHandler($paginator, $container->get('config')['page_size'], $urlHelper);
    }
}
