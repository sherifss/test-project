<?php

namespace App\Http\Controllers;

use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function store(Request $request)
    {
        return $this->postService->createPost($request->all());
    }

    public function show($id)
    {
        return $this->postService->getPostById($id);
    }

    public function delete($id)
    {
        return $this->postService->deletePost($id);
    }

    public function getAll(Request $request)
    {
        $filters = $request->only(['id', 'topic', 'created_at', 'updated_at', 'comment']);
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $sortField = $request->input('sort', 'id');
        $direction = $request->input('direction', 'asc');

        return $this->postService->getAllPosts($filters, $page, $limit, $sortField, $direction);
    }
}
