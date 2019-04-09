<?php

declare(strict_types=1);

namespace Announcements\Handler;

use Announcements\Entity\Announcement;
use Doctrine\ORM\ORMException;
use Zend\Expressive\Helper\ServerUrlHelper;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class AnnouncementsDeleteHandler implements RequestHandlerInterface
{
    protected $entityManager;
    protected $urlHelper;

    /**
     * AnnouncementsDeleteHandler constructor.
     * @param EntityManager $entityManager
     * @param ServerUrlHelper $urlHelper
     */
    public function __construct(
        EntityManager $entityManager,
        ServerUrlHelper $urlHelper
    ) {
        $this->entityManager = $entityManager;
        $this->urlHelper = $urlHelper;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $result = [];

        $entityRepository = $this->entityManager->getRepository(Announcement::class);

        $entity = $entityRepository->find($request->getAttribute('id'));

        if (empty($entity)) {
            $result['_error']['error'] = 'not_found';
            $result['_error']['error_description'] = 'Record not found.';

            return new JsonResponse($result, 404);
        }

        try {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        } catch(ORMException $e) {
            $result['_error']['error'] = 'not_removed';
            $result['_error']['error_description'] = $e->getMessage();

            return new JsonResponse($result, 400);
        }

        // add hypermedia links
        $result['Result']['_links']['self'] = $this->urlHelper->generate('/announcements/'.$request->getAttribute('id'));
        $result['Result']['_links']['create'] = $this->urlHelper->generate('/announcements/');
        $result['Result']['_links']['read'] = $this->urlHelper->generate('/announcements/');

        $result['Result']['_embedded']['Announcement'] = ['deleted_id' => $request->getAttribute('id')];

        return new JsonResponse($result);
    }
}
