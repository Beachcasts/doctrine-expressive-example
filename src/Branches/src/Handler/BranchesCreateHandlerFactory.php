<?php

declare(strict_types=1);

namespace Branches\Handler;

use Doctrine\ORM\EntityManager;
use Branches\Entity\Branch;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\ServerUrlHelper;

/**
 * Class BranchesCreateHandlerFactory
 * @package Branches\Handler
 */
class BranchesCreateHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return BranchesCreateHandler
     */
    public function __invoke(ContainerInterface $container) : BranchesCreateHandler
    {
        $entityManager = $container->get(EntityManager::class);

        $entity = new Branch();

        $urlHelper = $container->get(ServerUrlHelper::class);

        return new BranchesCreateHandler($entityManager, $entity, $urlHelper);
    }
}
