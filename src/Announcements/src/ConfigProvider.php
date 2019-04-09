<?php

declare(strict_types=1);

namespace Announcements;

use Announcements\Entity\Announcement;
use Announcements\Entity\AnnouncementCollection;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Expressive\Application;
use Zend\Expressive\Hal\Metadata\MetadataMap;
use Zend\Expressive\Hal\Metadata\RouteBasedCollectionMetadata;
use Zend\Expressive\Hal\Metadata\RouteBasedResourceMetadata;
use Zend\Hydrator\ReflectionHydrator;

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
            MetadataMap::class => $this->getHalMetadataMap(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'delegators' => [
                Application::class => [
                    RoutesDelegator::class,
                ],
            ],
            'invokables' => [
            ],
            'factories'  => [
                Handler\AnnouncementsCreateHandler::class => Handler\AnnouncementsCreateHandlerFactory::class,
                Handler\AnnouncementsReadHandler::class => Handler\AnnouncementsReadHandlerFactory::class,
                Handler\AnnouncementsUpdateHandler::class => Handler\AnnouncementsUpdateHandlerFactory::class,
                Handler\AnnouncementsDeleteHandler::class => Handler\AnnouncementsDeleteHandlerFactory::class,
                Handler\AnnouncementsListHandler::class => Handler\AnnouncementsListHandlerFactory::class,
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

    public function getHalMetadataMap()
    {
        return [
            [
                '__class__' => RouteBasedResourceMetadata::class,
                'resource_class' => Announcement::class,
                'route' => 'announcements.read',
                'extractor' => ReflectionHydrator::class,
            ],
            [
                '__class__' => RouteBasedCollectionMetadata::class,
                'collection_class' => AnnouncementCollection::class,
                'collection_relation' => 'announcement',
                'route' => 'announcements.list',
            ],
        ];
    }
}
