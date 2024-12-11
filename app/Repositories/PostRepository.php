<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository
{
    public function create(array $data): Post
    {
        return Post::create($data);
    }

    public function findById(int $id): ?Post
    {
        return Post::find($id);
    }

    public function delete(Post $post): void
    {
        $post->delete();
    }

    public function getAll(array $filters, int $page, int $limit, string $sortField, string $direction)
    {
        $query = Post::query();

        foreach ($filters as $field => $value) {
            if ($field === 'id') {
                $query->where($field, $value);
            } elseif ($field !== 'comment') { // Skip the `comment` field here
                $query->where($field, 'like', '%' . $value . '%');
            }
        }

        if (isset($filters['comment'])) {
            $commentContent = $filters['comment'];
            $query->whereHas('comments', function ($q) use ($commentContent) {
                $q->where('content', 'like', '%' . $commentContent . '%');
            });
        }

        return $query->orderBy($sortField, $direction)->paginate($limit, ['*'], 'page', $page);
    }
}
