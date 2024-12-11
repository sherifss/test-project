<?php

namespace Database\Factories;
use App\Models\Post;
use App\Models\Comment;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'post_id' => Post::factory(),
            'content' => $this->faker->sentence,
            'abbreviation' => $this->faker->word,
        ];
    }
}
