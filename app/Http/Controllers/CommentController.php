<?php

namespace App\Http\Controllers;

use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Exception;

class CommentController extends Controller
{
    protected CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'post_id' => 'required|exists:posts,id',
                'content' => 'required|string',
                'abbreviation' => 'required|string|unique:comments,abbreviation',
            ]);

            $comment = $this->commentService->createComment($validated);

            return response()->json([
                'message' => 'Comment created successfully.',
                'result' => $comment,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $comment = $this->commentService->getCommentById($id);

            return response()->json([
                'result' => $comment,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            $this->commentService->deleteComment($id);

            return response()->json([
                true
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function getAll(Request $request): JsonResponse
    {
        $filters = $request->only(['id', 'post_id', 'content', 'abbreviation', 'created_at', 'updated_at']);
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $sortField = $request->input('sort', 'id');
        $direction = $request->input('direction', 'asc');

        $with = [];
        if ($request->has('with')) {
            $relations = explode(',', $request->input('with'));
            $with = array_filter($relations, function ($relation) {
                return method_exists(\App\Models\Comment::class, $relation);
            });
        }

        $comments = $this->commentService->getAllComments($filters, $page, $limit, $sortField, $direction, $with);

        return response()->json([
            'result' => $comments->items(),
            'count' => $comments->total(),
            'current_page' => $comments->currentPage(),
            'total_pages' => $comments->lastPage(),
        ]);
    }
}
