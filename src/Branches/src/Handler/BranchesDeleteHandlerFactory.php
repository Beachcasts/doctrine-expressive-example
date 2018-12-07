<?php

declare(strict_types=1);

namespace Branches\Handler;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\ServerUrlHelper;

/**
 * Class BranchesDeleteHandlerFactory
 * @package Branches\Handler
 */
class BranchesDeleteHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return BranchesDeleteHandler
     */
    public function __invoke(ContainerInterface $container) : BranchesDeleteHandler
    {
        $entityManager = $container->get(EntityManager::class);

        $entityRepository = $entityManager->getRepository('Branches\Entity\Branch');

        $urlHelper = $container->get(ServerUrlHelper::class);

        return new BranchesDeleteHandler($entityManager, $entityRepository, $urlHelper);
    }
}
