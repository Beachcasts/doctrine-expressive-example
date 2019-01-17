<?php

declare(strict_types=1);

namespace Announcements\Handler;

use Doctrine\ORM\EntityManager;
use Zend\Expressive\Helper\ServerUrlHelper;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class AnnouncementsReadHandler
 * @package Announcements\Handler
 */
class AnnouncementsReadHandler implements RequestHandlerInterface
{
    protected $entityManager;
    protected $pageCount;
    protected $urlHelper;

    /**
     * AnnouncementsReadHandler constructor.
     * @param EntityManager $entityManager
     * @param $pageCount
     * @param ServerUrlHelper $urlHelper
     */
    public function __construct(
        EntityManager $entityManager,
        $pageCount,
        ServerUrlHelper $urlHelper
    ) {
        $this->entityManager = $entityManager;
        $this->pageCount = $pageCount;
        $this->urlHelper = $urlHelper;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $query = $this->entityManager->getRepository('Announcements\Entity\Announcement')
            ->createQueryBuilder('c')
            ->getQuery();

        $paginator  = new Paginator($query);

        $result = [];
        $totalItems = count($paginator);
        $currentPage = ($request->getAttribute('page')) ?: 1;
        $totalPagesCount = ceil($totalItems / $this->pageCount);
        $nextPage = (($currentPage < $totalPagesCount) ? $currentPage + 1 : $totalPagesCount);
        $previousPage = (($currentPage > 1) ? $currentPage - 1 : 1);

        $records = $paginator
            ->getQuery()
            ->setFirstResult($this->pageCount * ($currentPage-1)) // set the offset
            ->setMaxResults($this->pageCount) // set the limit
            ->getResult(Query::HYDRATE_ARRAY);

        // add hypermedia links
        $result['Result']['_links']['self'] = $this->urlHelper->generate('/announcements/page/'.$currentPage);
        $result['Result']['_links']['previous'] = $this->urlHelper->generate('/announcements/page/'.$previousPage);
        $result['Result']['_links']['next'] = $this->urlHelper->generate('/announcements/page/'.$nextPage);
        $result['Result']['_links']['last'] = $this->urlHelper->generate('/announcements/page/'.$totalPagesCount);
        $result['Result']['_links']['create'] = $this->urlHelper->generate('/announcements/');
        $result['Result']['_links']['read'] = $this->urlHelper->generate('/announcements/');
        $result['Result']['_per_page'] = $this->pageCount;
        $result['Result']['_page'] = $currentPage;
        $result['Result']['_total'] = $totalItems;
        $result['Result']['_total_pages'] = $totalPagesCount;

        // add record specific hypermedia links
        foreach ($records as $key => $value) {
            $records[$key]['_links']['self'] = $this->urlHelper->generate('/announcements/'.$value['id']);
            $records[$key]['_links']['update'] = $this->urlHelper->generate('/announcements/'.$value['id']);
            $records[$key]['_links']['delete'] = $this->urlHelper->generate('/announcements/'.$value['id']);
        }

        $result['Result']['_embedded']['Announcements'] = $records;

        return new JsonResponse($result);
    }
}
