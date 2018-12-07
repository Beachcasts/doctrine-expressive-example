<?php

declare(strict_types=1);

namespace Branches;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;

/**
 * The configuration provider for the Branches module
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
                Handler\BranchesReadHandler::class => Handler\BranchesReadHandlerFactory::class,
                Handler\BranchesViewHandler::class => Handler\BranchesViewHandlerFactory::class,
                Handler\BranchesCreateHandler::class => Handler\BranchesCreateHandlerFactory::class,
                Handler\BranchesUpdateHandler::class => Handler\BranchesUpdateHandlerFactory::class,
                Handler\BranchesDeleteHandler::class => Handler\BranchesDeleteHandlerFactory::class,
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
                'branches'    => [__DIR__ . '/../templates/'],
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
                        'Branches\Entity' => 'branch_entity',
                    ],
                ],
                'branch_entity' => [
                    'class' => AnnotationDriver::class,
                    'cache' => 'array',
                    'paths' => [__DIR__ . '/Entity'],
                ],
            ],
        ];
    }
}
