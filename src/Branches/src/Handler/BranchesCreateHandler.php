<?php

declare(strict_types=1);

namespace Branches\Handler;

use Doctrine\ORM\ORMException;
use Zend\Expressive\Helper\ServerUrlHelper;
use Doctrine\ORM\EntityManager;
use Branches\Entity\Branch;
use Banks\Entity\Bank;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class BranchesCreateHandler
 *
 * Example request body to create can be found in /data/branch_create.json
 *
 * @package Branches\Handler
 */
class BranchesCreateHandler implements RequestHandlerInterface
{
    protected $entityManager;
    protected $entity;
    protected $urlHelper;

    /**
     * BranchesCreateHandler constructor.
     * @param EntityManager $entityManager
     * @param Branch $entity
     * @param ServerUrlHelper $urlHelper
     */
    public function __construct(
        EntityManager $entityManager,
        Branch $entity,
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
        $requestBody = $request->getParsedBody()['Request']['Branches'];

        if (empty($requestBody)) {
            $result['_error']['error'] = 'missing_request';
            $result['_error']['error_description'] = 'No request body sent.';

            return new JsonResponse($result, 400);
        }

        try {
            // Need to get the Bank this Branch will be associated with
            $bank = $this->entityManager->find(Bank::class, $requestBody['bank_id']);

            $this->entity->setBank($bank);
            $this->entity->setBranch($requestBody);
            $this->entity->setCreated(new \DateTime("now"));

            $this->entityManager->persist($this->entity);
            $this->entityManager->flush();
        } catch(ORMException $e) {
            $result['_error']['error'] = 'not_created';
            $result['_error']['error_description'] = $e->getMessage();

            return new JsonResponse($result, 400);
        }

        // add hypermedia links
        $result['Result']['_links']['self'] = $this->urlHelper->generate('/branches/'.$this->entity->getId());
        $result['Result']['_links']['read'] = $this->urlHelper->generate('/branches/');
        $result['Result']['_links']['update'] = $this->urlHelper->generate('/branches/'.$this->entity->getId());
        $result['Result']['_links']['delete'] = $this->urlHelper->generate('/branches/'.$this->entity->getId());

        $result['Result']['_embedded']['Branch'] = $this->entity->getBranch();

        if (empty($result['Result']['_embedded']['Branch'])) {
            $result['_error']['error'] = 'not_created';
            $result['_error']['error_description'] = 'Not Created.';

            return new JsonResponse($result, 400);
        }

        return new JsonResponse($result, 201);
    }
}
