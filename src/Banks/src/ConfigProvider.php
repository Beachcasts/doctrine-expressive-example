<?php

declare(strict_types=1);

namespace Banks;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;

/**
 * The configuration provider for the Banks module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
            'doctrine'     => $this->getDoctrineEntities(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'delegators' => [
                \Zend\Expressive\Application::class => [
                    RoutesDelegator::class,
                ],
            ],
            'invokables' => [
            ],
            'factories'  => [
                Handler\BanksReadHandler::class => Handler\BanksReadHandlerFactory::class,
                Handler\BanksViewHandler::class => Handler\BanksViewHandlerFactory::class,
                Handler\BanksCreateHandler::class => Handler\BanksCreateHandlerFactory::class,
                Handler\BanksUpdateHandler::class => Handler\BanksUpdateHandlerFactory::class,
                Handler\BanksDeleteHandler::class => Handler\BanksDeleteHandlerFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                'banks'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }

    public function getDoctrineEntities() : array
    {
        return [
            'driver' => [
                'orm_default' => [
                    'class' => MappingDriverChain::class,
                    'drivers' => [
                        'Banks\Entity' => 'banks_entity',
                    ],
                ],
                'banks_entity' => [
                    'class' => AnnotationDriver::class,
                    'cache' => 'array',
                    'paths' => [__DIR__ . '/Entity'],
                ],
            ],
        ];
    }
}
