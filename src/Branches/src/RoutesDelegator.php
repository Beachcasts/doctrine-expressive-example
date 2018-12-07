<?php

namespace Branches;

use Branches\Handler;
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
        $app->post('/branches[/]', Handler\BranchesCreateHandler::class, 'branches.create');

        $app->get('/branches/{id:\d+}[/]', Handler\BranchesViewHandler::class, 'branches.view');
        $app->get('/branches/[page/{page:\d+}]', Handler\BranchesReadHandler::class, 'branches.read');

        $app->put('/branches/{id:\d+}[/]', Handler\BranchesUpdateHandler::class, 'branches.update');

        $app->delete('/branches/{id:\d+}[/]', Handler\BranchesDeleteHandler::class, 'branches.delete');

        return $app;
    }
}
