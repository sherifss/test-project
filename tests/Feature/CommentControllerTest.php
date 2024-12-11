<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Response;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $post;

    public function setUp(): void
    {
        parent::setUp();
        // Create the post that will be associated with comments
        $this->post = Post::factory()->create();
    }

    public function test_store_valid_comment()
    {
        $data = [
            'post_id' => $this->post->id,
            'content' => 'This is a comment',
            'abbreviation' => 'TAC',
        ];

        $response = $this->postJson('/api/comments', $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'message' => 'Comment created successfully.',
        ]);
        $this->assertDatabaseHas('comments', $data);
    }

    public function test_store_invalid_comment()
    {
        $data = [
            'post_id' => $this->post->id,
            'content' => '',
            'abbreviation' => 'TAC',
        ];

        $response = $this->postJson('/api/comments', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors',
        ]);
    }

    public function test_show_comment()
    {
        $comment = Comment::factory()->create([
            'post_id' => $this->post->id,
            'content' => 'This is a comment',
            'abbreviation' => 'TAC',
        ]);

        $response = $this->getJson("/api/comments/{$comment->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'result' => [
                'id' => $comment->id,
                'post_id' => $comment->post_id,
                'content' => $comment->content,
                'abbreviation' => $comment->abbreviation,
            ]
        ]);
    }

    public function test_show_non_existent_comment()
    {
        $response = $this->getJson("/api/comments/999");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson([
            'message' => 'Comment not found.',
        ]);
    }

    public function test_delete_comment()
    {
        $comment = Comment::factory()->create([
            'post_id' => $this->post->id,
            'content' => 'This is a comment',
            'abbreviation' => 'TAC',
        ]);

        $response = $this->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([true]);

        $this->assertDeleted($comment);
    }

    public function test_delete_non_existent_comment()
    {
        $response = $this->deleteJson("/api/comments/999");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson([
            'message' => 'Comment not found.',
        ]);
    }

    public function test_get_all_comments_with_filters()
    {
        $comment1 = Comment::factory()->create([
            'post_id' => $this->post->id,
            'content' => 'one commentTest',
            'abbreviation' => 'oc',
        ]);

        $comment2 = Comment::factory()->create([
            'post_id' => $this->post->id,
            'content' => 'another comment',
            'abbreviation' => 'ac',
        ]);

        $response = $this->getJson('/api/comments?content=commentTest&limit=1&page=1');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(1, 'result');
        $response->assertJson([
            'result' => [
                [
                    'id' => $comment1->id,
                    'content' => $comment1->content,
                    'abbreviation' => $comment1->abbreviation,
                ]
            ]
        ]);
    }
}
