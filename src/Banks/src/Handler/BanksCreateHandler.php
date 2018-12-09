<?php

declare(strict_types=1);

namespace Banks\Handler;

use Zend\Expressive\Helper\ServerUrlHelper;
use Doctrine\ORM\EntityManager;
use Banks\Entity\Bank;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class BanksCreateHandler
 *
 * Example request body to create can be found in /data/bank_create.json
 *
 * @package Banks\Handler
 */
class BanksCreateHandler implements RequestHandlerInterface
{
    protected $entityManager;
    protected $entity;
    protected $urlHelper;

    /**
     * BanksCreateHandler constructor.
     * @param EntityManager $entityManager
     * @param Bank $entity
     * @param ServerUrlHelper $urlHelper
     */
    public function __construct(
        EntityManager $entityManager,
        Bank $entity,
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
        $requestBody = $request->getParsedBody()['Request']['Banks'];

        if (empty($requestBody)) {
            $result['error'] = 'missing_request';
            $result['error_description'] = 'No request body sent.';

            return new JsonResponse($result, 400);
        }

        try {
            // Need to get the Parent Bank this Bank will be associated with
            $bank = $this->entityManager->find(Bank::class, $requestBody['parent_id']);

            $this->entity->setParent($bank);
            $this->entity->setBank($requestBody);
            $this->entity->setCreated(new \DateTime("now"));

            $this->entityManager->persist($this->entity);
            $this->entityManager->flush();
        } catch(\Exception $e) {
            $result['error'] = 'not_created';
            $result['error_description'] = $e->getMessage();

            return new JsonResponse($result, 400);
        }

        // add hypermedia links
        $result['Result']['_links']['self'] = $this->urlHelper->generate('/banks/'.$this->entity->getId());
        $result['Result']['_links']['read'] = $this->urlHelper->generate('/banks/');
        $result['Result']['_links']['update'] = $this->urlHelper->generate('/banks/'.$this->entity->getId());
        $result['Result']['_links']['delete'] = $this->urlHelper->generate('/banks/'.$this->entity->getId());

        $result['Result']['Banks'] = $this->entity->getBank();

        if (empty($result['Result']['Banks'])) {
            $result['error'] = 'not_created';
            $result['error_description'] = 'Not Created.';

            return new JsonResponse($result, 400);
        }

        return new JsonResponse($result, 201);
    }
}
