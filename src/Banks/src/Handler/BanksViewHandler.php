<?php

declare(strict_types=1);

namespace Banks\Handler;

use Zend\Expressive\Helper\ServerUrlHelper;
use Doctrine\ORM\EntityRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class BanksViewHandler
 * @package Banks\Handler
 */
class BanksViewHandler implements RequestHandlerInterface
{
    protected $entityRepository;
    protected $urlHelper;

    /**
     * BanksViewHandler constructor.
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

        if (empty($return)) {
            $result['_error']['error'] = 'not_found';
            $result['_error']['error_description'] = 'Record not found.';

            return new JsonResponse($result, 404);
        }

        // add hypermedia links
        $result['Result']['_links']['self'] = $this->urlHelper->generate('/banks/'.$return->getId());
        $result['Result']['_links']['create'] = $this->urlHelper->generate('/banks/');
        $result['Result']['_links']['read'] = $this->urlHelper->generate('/banks/');
        $result['Result']['_links']['update'] = $this->urlHelper->generate('/banks/'.$return->getId());
        $result['Result']['_links']['delete'] = $this->urlHelper->generate('/banks/'.$return->getId());

        $result['Result']['_embedded']['Bank'] = $return->getBank(true, true, true);

        return new JsonResponse($result);
    }
}
