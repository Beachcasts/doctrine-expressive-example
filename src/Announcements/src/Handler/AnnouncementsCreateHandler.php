<?php

declare(strict_types=1);

namespace Announcements\Handler;

use Zend\Expressive\Helper\ServerUrlHelper;
use Doctrine\ORM\EntityManager;
use Announcements\Entity\Announcement;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class AnnouncementsCreateHandler
 *
 * Example request body to create can be found in /data/announcement_create.json
 *
 * @package Announcements\Handler
 */
class AnnouncementsCreateHandler implements RequestHandlerInterface
{
    protected $entityManager;
    protected $entity;
    protected $urlHelper;

    /**
     * AnnouncementsCreateHandler constructor.
     * @param EntityManager $entityManager
     * @param Announcement $entity
     * @param ServerUrlHelper $urlHelper
     */
    public function __construct(
        EntityManager $entityManager,
        Announcement $entity,
        ServerUrlHelper $urlHelper
    ) {
        $this->entityManager = $entityManager;
        $this->entity = $entity;
        $this->urlHelper = $urlHelper;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $result = [];
        $requestBody = $request->getParsedBody()['Request']['Announcements'];

        if (empty($requestBody)) {
            $result['error'] = 'missing_request';
            $result['error_description'] = 'No request body sent.';

            return new JsonResponse($result, 400);
        }

        try {
            $this->entity->setAnnouncement($requestBody);
            $this->entity->setCreated(new \DateTime("now"));

            $this->entityManager->persist($this->entity);
            $this->entityManager->flush();
        } catch(\Exception $e) {
            $result['error'] = 'not_created';
            $result['error_description'] = $e->getMessage();

            return new JsonResponse($result, 400);
        }

        // add hypermedia links
        $result['Result']['_links']['self'] = $this->urlHelper->generate('/announcements/'.$this->entity->getId());
        $result['Result']['_links']['read'] = $this->urlHelper->generate('/announcements/');
        $result['Result']['_links']['update'] = $this->urlHelper->generate('/announcements/'.$this->entity->getId());
        $result['Result']['_links']['delete'] = $this->urlHelper->generate('/announcements/'.$this->entity->getId());

        $result['Result']['Announcements'] = $this->entity->getAnnouncement();

        if (empty($result['Result']['Announcements'])) {
            $result['error'] = 'not_created';
            $result['error_description'] = 'Not Created.';

            return new JsonResponse($result, 400);
        }

        return new JsonResponse($result, 201);
    }
}
