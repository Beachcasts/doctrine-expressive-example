<?php

declare(strict_types=1);

namespace Announcements\Handler;

use Doctrine\ORM\ORMException;
use Zend\Expressive\Helper\ServerUrlHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Announcements\Entity\Announcement;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class AnnouncementsUpdateHandler
 *
 * Example request body to update can be found in /data/announcement_update.json
 *
 * @package Announcements\Handler
 */
class AnnouncementsUpdateHandler implements RequestHandlerInterface
{
    protected $entityManager;
    protected $entityRepository;
    protected $entity;
    protected $urlHelper;

    /**
     * AnnouncementsUpdateHandler constructor.
     * @param EntityManager $entityManager
     * @param EntityRepository $entityRepository
     * @param Announcement $entity
     * @param ServerUrlHelper $urlHelper
     */
    public function __construct(
        EntityManager $entityManager,
        EntityRepository $entityRepository,
        Announcement $entity,
        ServerUrlHelper $urlHelper
    ) {
        $this->entityManager = $entityManager;
        $this->entityRepository = $entityRepository;
        $this->entity = $entity;
        $this->urlHelper = $urlHelper;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Exception
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $result = [];
        $requestBody = $request->getParsedBody()['Request']['Announcements'];

        if (empty($requestBody)) {
            $result['_error']['error'] = 'missing_request';
            $result['_error']['error_description'] = 'No request body sent.';

            return new JsonResponse($result, 400);
        }

        $this->entity = $this->entityRepository->find($request->getAttribute('id'));

        if (empty($this->entity)) {
            $result['_error']['error'] = 'not_found';
            $result['_error']['error_description'] = 'Record not found.';

            return new JsonResponse($result, 404);
        }

        try {
            $this->entity->setAnnouncement($requestBody);
            
            $this->entityManager->merge($this->entity);
            $this->entityManager->flush();
        } catch(ORMException $e) {
            $result['_error']['error'] = 'not_updated';
            $result['_error']['error_description'] = $e->getMessage();

            return new JsonResponse($result, 400);
        }

        // add hypermedia links
        $result['Result']['_links']['self'] = $this->urlHelper->generate('/announcements/'.$this->entity->getId());
        $result['Result']['_links']['read'] = $this->urlHelper->generate('/announcements/');
        $result['Result']['_links']['delete'] = $this->urlHelper->generate('/announcements/'.$this->entity->getId());
        $result['Result']['_links']['view'] = $this->urlHelper->generate('/announcements/'.$this->entity->getId());

        $result['Result']['_embedded']['Announcement'] = $this->entity->getAnnouncement();

        if (empty($result['Result']['_embedded']['Announcement'])) {
            $result['_error']['error'] = 'not_found';
            $result['_error']['error_description'] = 'Not Found.';

            return new JsonResponse($result, 404);
        }

        return new JsonResponse($result);
    }
}
