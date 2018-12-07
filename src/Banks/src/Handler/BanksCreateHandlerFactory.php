<?php

declare(strict_types=1);

namespace Banks\Handler;

use Doctrine\ORM\EntityManager;
use Banks\Entity\Bank;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\ServerUrlHelper;

/**
 * Class BanksCreateHandlerFactory
 * @package Banks\Handler
 */
class BanksCreateHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return BanksCreateHandler
     */
    public function __invoke(ContainerInterface $container) : BanksCreateHandler
    {
        $entityManager = $container->get(EntityManager::class);

        $entity = new Bank();

        $urlHelper = $container->get(ServerUrlHelper::class);

        return new BanksCreateHandler($entityManager, $entity, $urlHelper);
    }
}
