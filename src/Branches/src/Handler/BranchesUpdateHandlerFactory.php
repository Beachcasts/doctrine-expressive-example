<?php

declare(strict_types=1);

namespace Branches\Handler;

use Doctrine\ORM\EntityManager;
use Branches\Entity\Branch;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\ServerUrlHelper;

/**
 * Class BranchesUpdateHandlerFactory
 * @package Branches\Handler
 */
class BranchesUpdateHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return BranchesUpdateHandler
     */
    public function __invoke(ContainerInterface $container) : BranchesUpdateHandler
    {
        $entityManager = $container->get(EntityManager::class);

        $entityRepository = $entityManager->getRepository('Branches\Entity\Branch');

        $entity = new Branch();

        $urlHelper = $container->get(ServerUrlHelper::class);

        return new BranchesUpdateHandler($entityManager, $entityRepository, $entity, $urlHelper);
    }
}
