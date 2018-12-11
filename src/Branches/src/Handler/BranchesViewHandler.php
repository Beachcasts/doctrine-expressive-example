<?php

declare(strict_types=1);

namespace Branches\Handler;

use Zend\Expressive\Helper\ServerUrlHelper;
use Doctrine\ORM\EntityRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class BranchesViewHandler
 * @package Branches\Handler
 */
class BranchesViewHandler implements RequestHandlerInterface
{
    protected $entityRepository;
    protected $urlHelper;

    /**
     * BranchesViewHandler constructor.
     * @param EntityRepository $entityRepository
     * @param ServerUrlHelper $urlHelper
     */
    public function __construct(
        EntityRepository $entityRepository,
        ServerUrlHelper $urlHelper
    ) {
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
        $return = $this->entityRepository->find($request->getAttribute('id'));

        if ($return === null) {
            $result['_error']['error'] = 'not_found';
            $result['_error']['error_description'] = 'Record not found.';

            return new JsonResponse($result, 404);
        }

        // add hypermedia links
        $result['Result']['_links']['self'] = $this->urlHelper->generate('/branches/'.$return->getId());
        $result['Result']['_links']['create'] = $this->urlHelper->generate('/branches/');
        $result['Result']['_links']['read'] = $this->urlHelper->generate('/branches/');
        $result['Result']['_links']['update'] = $this->urlHelper->generate('/branches/'.$return->getId());
        $result['Result']['_links']['delete'] = $this->urlHelper->generate('/branches/'.$return->getId());

        $result['Result']['_embedded']['Branch'] = $return->getBranch(true);

        return new JsonResponse($result);
    }
}
