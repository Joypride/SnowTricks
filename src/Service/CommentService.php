<?php

namespace App\Service;

use App\Entity\Trick;
use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CommentService 
{
    public function __construct(private RequestStack $requestStack, private CommentRepository $commentRepository, private PaginatorInterface $paginator)
    {
        
    }

    public function getPaginatedComments(?Trick $trick = null): PaginationInterface
    {
        $request = $this->requestStack->getMainRequest();
        $page = $request->query->getInt('page', 1);
        $limit = 5;

        $commentsQuery = $this->commentRepository->findForPagination($trick);

        return $this->paginator->paginate($commentsQuery, $page, $limit);
    }
}