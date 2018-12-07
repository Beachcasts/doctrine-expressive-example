<?php

declare(strict_types=1);

namespace Banks\Handler;

use Zend\Expressive\Helper\ServerUrlHelper;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class BanksReadHandler
 * @package Banks\Handler
 */
class BanksReadHandler implements RequestHandlerInterface
{
    protected $paginator;
    protected $pageCount;
    protected $urlHelper;

    /**
     * BanksReadHandler constructor.
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
        $result['Result']['_links']['self'] = $this->urlHelper->generate('/banks/page/'.$currentPage);
        $result['Result']['_links']['previous'] = $this->urlHelper->generate('/banks/page/'.$previousPage);
        $result['Result']['_links']['next'] = $this->urlHelper->generate('/banks/page/'.$nextPage);
        $result['Result']['_links']['last'] = $this->urlHelper->generate('/banks/page/'.$totalPagesCount);
        $result['Result']['_links']['create'] = $this->urlHelper->generate('/banks/');
        $result['Result']['_links']['read'] = $this->urlHelper->generate('/banks/');
        $result['Result']['total'] = $totalItems;
        $result['Result']['per_page'] = $this->pageCount;
        $result['Result']['page'] = $currentPage;
        $result['Result']['total_pages'] = $totalPagesCount;

        // add record specific hypermedia links
        foreach ($records as $key => $value) {
            $records[$key]['_links']['self'] = $this->urlHelper->generate('/banks/'.$value['id']);
            $records[$key]['_links']['update'] = $this->urlHelper->generate('/banks/'.$value['id']);
            $records[$key]['_links']['delete'] = $this->urlHelper->generate('/banks/'.$value['id']);
        }

        $result['Result']['Banks'] = $records;

        return new JsonResponse($result);
    }
}
