<?php

declare(strict_types=1);

namespace Banks\Handler;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\ServerUrlHelper;

/**
 * Class BanksViewHandlerFactory
 * @package Banks\Handler
 */
class BanksViewHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return BanksViewHandler
     */
    public function __invoke(ContainerInterface $container) : BanksViewHandler
    {
        $entityManager = $container->get(EntityManager::class);

        $urlHelper = $container->get(ServerUrlHelper::class);

        $entityRepository = $entityManager->getRepository('Banks\Entity\Bank');

        return new BanksViewHandler($entityRepository, $urlHelper);
    }
}
