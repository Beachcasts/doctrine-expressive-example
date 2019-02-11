<?php

namespace Announcements;

use Announcements\Handler;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\ProblemDetails\ProblemDetailsMiddleware;

class RoutesDelegator
{
    /**
     * @param ContainerInterface $container
     * @param string $serviceName Name of the service being created.
     * @param callable $callback Creates and returns the service.
     * @return Application
     */
    public function __invoke(ContainerInterface $container, $serviceName, callable $callback)
    {
        /** @var $app Application */
        $app = $callback();

        // Setup routes:
        $app->post('/announcements[/]', Handler\AnnouncementsCreateHandler::class, 'announcements.create');

        $app->get('/announcements/{id:[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}}[/]', [
                ProblemDetailsMiddleware::class,
                Handler\AnnouncementsViewHandler::class,
            ], 'announcements.view');

        $app->get('/announcements/[?page={page:\d+}]', Handler\AnnouncementsReadHandler::class, 'announcements.read');

        $app->put('/announcements/{id:[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}}[/]', Handler\AnnouncementsUpdateHandler::class, 'announcements.update');

        $app->delete('/announcements/{id:[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}}[/]', Handler\AnnouncementsDeleteHandler::class, 'announcements.delete');

        return $app;
    }
}
