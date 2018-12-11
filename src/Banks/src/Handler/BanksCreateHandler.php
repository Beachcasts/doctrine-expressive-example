<?php

declare(strict_types=1);

namespace Banks\Handler;

use Doctrine\ORM\ORMException;
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
     * @throws \Exception
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $result = [];
        $requestBody = $request->getParsedBody()['Request']['Banks'];

        if (empty($requestBody)) {
            $result['_error']['error'] = 'missing_request';
            $result['_error']['error_description'] = 'No request body sent.';

            return new JsonResponse($result, 400);
        }

        // Need to get the Parent Bank this Bank will be associated with
        $bank = $this->entityManager->find(Bank::class, $requestBody['parent_id']);

        if (empty($bank)) {
            $result['_error']['error'] = 'bank_missing';
            $result['_error']['error_description'] = 'Bank specified in request does not exist.';

            return new JsonResponse($result, 400);
        }

        try {
            $this->entity->setParent($bank);
            $this->entity->setBank($requestBody);
            $this->entity->setCreated(new \DateTime("now"));

            $this->entityManager->persist($this->entity);
            $this->entityManager->flush();
        } catch(ORMException $e) {
            $result['_error']['error'] = 'not_created';
            $result['_error']['error_description'] = $e->getMessage();

            return new JsonResponse($result, 400);
        }

        // add hypermedia links
        $result['Result']['_links']['self'] = $this->urlHelper->generate('/banks/'.$this->entity->getId());
        $result['Result']['_links']['read'] = $this->urlHelper->generate('/banks/');
        $result['Result']['_links']['update'] = $this->urlHelper->generate('/banks/'.$this->entity->getId());
        $result['Result']['_links']['delete'] = $this->urlHelper->generate('/banks/'.$this->entity->getId());

        $result['Result']['_embedded']['Bank'] = $this->entity->getBank();

        if (empty($result['Result']['_embedded']['Bank'])) {
            $result['_error']['error'] = 'not_created';
            $result['_error']['error_description'] = 'Not Created.';

            return new JsonResponse($result, 400);
        }

        return new JsonResponse($result, 201);
    }
}
