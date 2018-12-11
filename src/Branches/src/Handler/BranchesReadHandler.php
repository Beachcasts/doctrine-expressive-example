<?php

declare(strict_types=1);

namespace Branches\Handler;

use Zend\Expressive\Helper\ServerUrlHelper;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class BranchesReadHandler
 * @package Branches\Handler
 */
class BranchesReadHandler implements RequestHandlerInterface
{
    protected $paginator;
    protected $pageCount;
    protected $urlHelper;

    /**
     * BranchesReadHandler constructor.
     * @param Paginator $paginator
     * @param $pageCount
     * @param ServerUrlHelper $urlHelper
     */
    public function __construct(
        Paginator $paginator,
        $pageCount,
        ServerUrlHelper $urlHelper
    ) {
        $this->paginator = $paginator;
        $this->pageCount = $pageCount;
        $this->urlHelper = $urlHelper;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $result = [];
        $totalItems = count($this->paginator);
        $currentPage = ($request->getAttribute('page')) ?: 1;
        $totalPagesCount = ceil($totalItems / $this->pageCount);
        $nextPage = (($currentPage < $totalPagesCount) ? $currentPage + 1 : $totalPagesCount);
        $previousPage = (($currentPage > 1) ? $currentPage - 1 : 1);

        $records = $this->paginator
            ->getQuery()
            ->setFirstResult($this->pageCount * ($currentPage-1)) // set the offset
            ->setMaxResults($this->pageCount) // set the limit
            ->getResult(Query::HYDRATE_ARRAY);

        // add hypermedia links
        $result['Result']['_links']['self'] = $this->urlHelper->generate('/branches/page/'.$currentPage);
        $result['Result']['_links']['previous'] = $this->urlHelper->generate('/branches/page/'.$previousPage);
        $result['Result']['_links']['next'] = $this->urlHelper->generate('/branches/page/'.$nextPage);
        $result['Result']['_links']['last'] = $this->urlHelper->generate('/branches/page/'.$totalPagesCount);
        $result['Result']['_links']['create'] = $this->urlHelper->generate('/branches/');
        $result['Result']['_links']['read'] = $this->urlHelper->generate('/branches/');
        $result['Result']['total'] = $totalItems;
        $result['Result']['per_page'] = $this->pageCount;
        $result['Result']['page'] = $currentPage;
        $result['Result']['total_pages'] = $totalPagesCount;

        // add record specific hypermedia links
        foreach ($records as $key => $value) {
            $records[$key]['_links']['self'] = $this->urlHelper->generate('/branches/'.$value['id']);
            $records[$key]['_links']['update'] = $this->urlHelper->generate('/branches/'.$value['id']);
            $records[$key]['_links']['delete'] = $this->urlHelper->generate('/branches/'.$value['id']);
        }

        $result['Result']['_embedded']['Branches'] = $records;

        return new JsonResponse($result);
    }
}
