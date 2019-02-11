<?php

declare(strict_types=1);

namespace Announcements\Handler;

use Announcements\Entity\Announcement;
use Announcements\Entity\AnnouncementCollection;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;

/**
 * Class AnnouncementsReadHandler
 * @package Announcements\Handler
 */
class AnnouncementsReadHandler implements RequestHandlerInterface
{
    protected $entityManager;
    protected $pageCount;
    protected $responseFactory;
    protected $resourceGenerator;

    /**
     * AnnouncementsReadHandler constructor.
     * @param EntityManager $entityManager
     * @param $pageCount
     * @param HalResponseFactory $responseFactory
     * @param ResourceGenerator $resourceGenerator
     */
    public function __construct(
        EntityManager $entityManager,
        $pageCount,
        HalResponseFactory $responseFactory,
        ResourceGenerator $resourceGenerator
    ) {
        $this->entityManager = $entityManager;
        $this->pageCount = $pageCount;
        $this->responseFactory = $responseFactory;
        $this->resourceGenerator = $resourceGenerator;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $repository = $this->entityManager->getRepository(Announcement::class);

        $query = $repository
            ->createQueryBuilder('a')
            ->addOrderBy('a.sort', 'asc')
            ->setMaxResults($this->pageCount)
            ->getQuery();

        $paginator = new AnnouncementCollection($query);
        $resource  = $this->resourceGenerator->fromObject($paginator, $request);
        return $this->responseFactory->createResponse($request, $resource);
    }
}
