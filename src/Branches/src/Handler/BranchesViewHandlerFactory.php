<?php

declare(strict_types=1);

namespace Branches\Handler;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\ServerUrlHelper;

/**
 * Class BranchesViewHandlerFactory
 * @package Branches\Handler
 */
class BranchesViewHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return BranchesViewHandler
     */
    public function __invoke(ContainerInterface $container) : BranchesViewHandler
    {
        $entityManager = $container->get(EntityManager::class);

        $entityRepository = $entityManager->getRepository('Branches\Entity\Branch');

        $urlHelper = $container->get(ServerUrlHelper::class);

        return new BranchesViewHandler($entityRepository, $urlHelper);
    }
}
