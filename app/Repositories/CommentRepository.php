<?php

namespace App\Repositories;

use App\Models\Comment;

class CommentRepository
{
    public function all(array $filters, int $page, int $limit, string $sortField, string $direction, array $with = [])
    {
        $query = Comment::query();

        foreach ($filters as $field => $value) {
            if ($field === 'id') {
                $query->where($field, $value);
            } else {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }

        if (in_array($sortField, ['id', 'post_id', 'content', 'abbreviation', 'created_at', 'updated_at'])) {
            $query->orderBy($sortField, $direction);
        }
        
        if (!empty($with)) {
            $query->with($with);
        }

        return $query->paginate($limit, ['*'], 'page', $page);
    }

    public function find(int $id)
    {
        return Comment::find($id);
    }

    public function create(array $data)
    {
        return Comment::create($data);
    }

    public function delete(int $id)
    {
        $comment = $this->find($id);
        if ($comment) {
            $comment->delete();
            return true;
        }

        return false;
    }
}
