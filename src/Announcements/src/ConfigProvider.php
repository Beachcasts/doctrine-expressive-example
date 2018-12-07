<?php

declare(strict_types=1);

namespace Announcements;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;

/**
 * The configuration provider for the Announcements module
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
                Handler\AnnouncementsReadHandler::class => Handler\AnnouncementsReadHandlerFactory::class,
                Handler\AnnouncementsViewHandler::class => Handler\AnnouncementsViewHandlerFactory::class,
                Handler\AnnouncementsCreateHandler::class => Handler\AnnouncementsCreateHandlerFactory::class,
                Handler\AnnouncementsUpdateHandler::class => Handler\AnnouncementsUpdateHandlerFactory::class,
                Handler\AnnouncementsDeleteHandler::class => Handler\AnnouncementsDeleteHandlerFactory::class,
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
                'announcements'    => [__DIR__ . '/../templates/'],
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
                        'Announcements\Entity' => 'announcement_entity',
                    ],
                ],
                'announcement_entity' => [
                    'class' => AnnotationDriver::class,
                    'cache' => 'array',
                    'paths' => [__DIR__ . '/Entity'],
                ],
            ],
        ];
    }
}
