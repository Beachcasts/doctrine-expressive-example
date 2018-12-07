<?php

declare(strict_types=1);

namespace Banks\Handler;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\ServerUrlHelper;

/**
 * Class BanksDeleteHandlerFactory
 * @package Banks\Handler
 */
class BanksDeleteHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return BanksDeleteHandler
     */
    public function __invoke(ContainerInterface $container) : BanksDeleteHandler
    {
        $entityManager = $container->get(EntityManager::class);

        $entityRepository = $entityManager->getRepository('Banks\Entity\Bank');

        $urlHelper = $container->get(ServerUrlHelper::class);

        return new BanksDeleteHandler($entityManager, $entityRepository, $urlHelper);
    }
}
