<?php

namespace App\Services;

use App\Repositories\CommentRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentService
{
    protected CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function getAllComments(array $filters, int $page, int $limit, string $sortField, string $direction, array $with = [])
    {
        return $this->commentRepository->all($filters, $page, $limit, $sortField, $direction, $with);
    }

    public function getCommentById(int $id)
    {
        $comment = $this->commentRepository->find($id);
        if (!$comment) {
            throw new ModelNotFoundException('Comment not found.');
        }

        return $comment;
    }

    public function createComment(array $data)
    {
        return $this->commentRepository->create($data);
    }

    public function deleteComment(int $id)
    {
        if (!$this->commentRepository->delete($id)) {
            throw new ModelNotFoundException('Comment not found.');
        }

        return true;
    }
}
