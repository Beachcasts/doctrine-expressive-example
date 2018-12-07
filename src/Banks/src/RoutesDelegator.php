<?php

namespace Banks;

use Banks\Handler;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;

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
        $app->post('/banks[/]', Handler\BanksCreateHandler::class, 'banks.create');

        $app->get('/banks/{id:\d+}[/]', Handler\BanksViewHandler::class, 'banks.view');
        $app->get('/banks/[page/{page:\d+}]', Handler\BanksReadHandler::class, 'banks.read');

        $app->put('/banks/{id:\d+}[/]', Handler\BanksUpdateHandler::class, 'banks.update');

        $app->delete('/banks/{id:\d+}[/]', Handler\BanksDeleteHandler::class, 'banks.delete');

        return $app;
    }
}
