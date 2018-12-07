<?php

declare(strict_types=1);

namespace Banks\Handler;

use Doctrine\ORM\EntityManager;
use Banks\Entity\Bank;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\ServerUrlHelper;

/**
 * Class BanksUpdateHandlerFactory
 * @package Banks\Handler
 */
class BanksUpdateHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return BanksUpdateHandler
     */
    public function __invoke(ContainerInterface $container) : BanksUpdateHandler
    {
        $entityManager = $container->get(EntityManager::class);

        $entityRepository = $entityManager->getRepository('Banks\Entity\Bank');

        $entity = new Bank();

        $urlHelper = $container->get(ServerUrlHelper::class);

        return new BanksUpdateHandler($entityManager, $entityRepository, $entity, $urlHelper);
    }
}
