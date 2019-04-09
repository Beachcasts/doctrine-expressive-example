<?php

declare(strict_types=1);

namespace Announcements\Handler;

use Announcements\Entity\Announcement;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;
use Zend\ProblemDetails\Exception\CommonProblemDetailsExceptionTrait;
use Zend\ProblemDetails\Exception\ProblemDetailsExceptionInterface;

/**
 * Class AnnouncementsViewHandler
 * @package Announcements\Handler
 */
class AnnouncementsReadHandler implements RequestHandlerInterface
{
    protected $entityManager;
    protected $halResponseFactory;
    protected $resourceGenerator;

    /**
     * AnnouncementsViewHandler constructor.
     * @param EntityManager $entityManager
     * @param HalResponseFactory $halResponseFactory
     * @param ResourceGenerator $resourceGenerator
     */
    public function __construct(
        EntityManager $entityManager,
        HalResponseFactory $halResponseFactory,
        ResourceGenerator $resourceGenerator
    ) {
        $this->entityManager = $entityManager;
        $this->halResponseFactory = $halResponseFactory;
        $this->resourceGenerator = $resourceGenerator;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $id               = $request->getAttribute('id', null);
        $entityRepository = $this->entityManager->getRepository(Announcement::class);
        $entity           = $entityRepository->find($id);

        if (empty($entity)) {

            $problem = new class ($id) extends RuntimeException implements ProblemDetailsExceptionInterface {
                use CommonProblemDetailsExceptionTrait;

                public function __construct(?string $id)
                {
                    $this->detail = sprintf('Unable to find an announcement with ID "%s"', (string) $id);
                    $this->status = 404;
                    $this->title  = 'Record not found.';
                    $this->type = 'error.announcement.not-found';
                    parent::__construct($this->detail, $this->status);
                }
            };

            throw $problem;
        }

        $resource = $this->resourceGenerator->fromObject($entity, $request);
        return $this->halResponseFactory->createResponse($request, $resource);
    }
}
