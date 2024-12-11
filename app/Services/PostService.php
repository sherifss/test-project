<?php

namespace App\Services;

use App\Repositories\PostRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class PostService
{
    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function createPost(array $data): JsonResponse
    {
        try {
            $validated = validator($data, [
                'topic' => 'required|string|unique:posts,topic|max:255',
            ])->validate();

            $post = $this->postRepository->create($validated);

            return response()->json([
                'message' => 'Post created successfully.',
                'result' => $post,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function getPostById(int $id): JsonResponse
    {
        $post = $this->postRepository->findById($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found.',
            ], 404);
        }

        return response()->json([
            'result' => $post,
        ]);
    }

    public function deletePost(int $id): JsonResponse
    {
        $post = $this->postRepository->findById($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found.',
            ], 404);
        }

        $this->postRepository->delete($post);

        return response()->json([true], 200);
    }

    public function getAllPosts(array $filters, int $page, int $limit, string $sortField, string $direction): JsonResponse
    {
        $posts = $this->postRepository->getAll($filters, $page, $limit, $sortField, $direction);

        return response()->json([
            'result' => $posts->items(),
            'count' => $posts->total(),
            'current_page' => $posts->currentPage(),
            'total_pages' => $posts->lastPage(),
        ]);
    }
}