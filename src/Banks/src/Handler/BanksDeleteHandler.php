<?php

declare(strict_types=1);

namespace Banks\Handler;

use Doctrine\ORM\ORMException;
use Zend\Expressive\Helper\ServerUrlHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class BanksDeleteHandler
 * @package Banks\Handler
 */
class BanksDeleteHandler implements RequestHandlerInterface
{
    protected $entityManager;
    protected $entityRepository;
    protected $urlHelper;

    /**
     * BanksDeleteHandler constructor.
     * @param EntityManager $entityManager
     * @param EntityRepository $entityRepository
     * @param ServerUrlHelper $urlHelper
     */
    public function __construct(
        EntityManager $entityManager,
        EntityRepository $entityRepository,
        ServerUrlHelper $urlHelper
    ) {
        $this->entityManager = $entityManager;
        $this->entityRepository = $entityRepository;
        $this->urlHelper = $urlHelper;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $result = [];
        $record = $this->entityRepository->find($request->getAttribute('id'));

        if (empty($record)) {
            $result['_error']['error'] = 'not_found';
            $result['_error']['error_description'] = 'Record not found.';

            return new JsonResponse($result, 404);
        }

        try {
            $this->entityManager->remove($record);
            $this->entityManager->flush();
        } catch(ORMException $e) {
            $result['_error']['error'] = 'not_removed';
            $result['_error']['error_description'] = $e->getMessage();

            return new JsonResponse($result, 400);
        }

        // add hypermedia links
        $result['Result']['_links']['self'] = $this->urlHelper->generate('/banks/'.$request->getAttribute('id'));
        $result['Result']['_links']['create'] = $this->urlHelper->generate('/banks/');
        $result['Result']['_links']['read'] = $this->urlHelper->generate('/banks/');

        $result['Result']['_embedded']['Bank'] = ['deleted_id' => $request->getAttribute('id')];

        return new JsonResponse($result);
    }
}
