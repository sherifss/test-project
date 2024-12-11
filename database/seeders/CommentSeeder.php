<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $randomWords = ["cool", "strange", "funny", "laughing", "nice", "awesome", "great", "horrible", "beautiful", "php", "vegeta", "italy", "joost"];
        $posts = Post::all()->pluck('id')->toArray();

        // Generate all combinations
        $combinations = $this->generateCombinations($randomWords);

        // Insert comments in batches
        $batchSize = 500;
        $batch = [];
        foreach ($combinations as $combination) {
            $content = implode(' ', $combination);
            $abbreviation = $this->generateAbbreviation($combination);

            $batch[] = [
                'post_id' => $posts[array_rand($posts)],
                'content' => $content,
                'abbreviation' => $abbreviation,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) >= $batchSize) {
                DB::table('comments')->insert($batch);
                $batch = [];
            }
        }

        // Insert remaining records
        if (!empty($batch)) {
            DB::table('comments')->insert($batch);
        }
    }

    /**
     * Generate all unique combinations of the given words.
     */
    private function generateCombinations(array $words): array
    {
        $combinations = [];
    
        // recursively generate combinations
        $generate = function($currentCombination, $startIndex) use ($words, &$combinations, &$generate) {
            // Add the current combination to the result if it's not empty
            if (!empty($currentCombination)) {
                $combinations[] = $currentCombination;
            }
    
            // Generate combinations starting from the next word
            for ($i = $startIndex; $i < count($words); $i++) {
                $generate(array_merge($currentCombination, [$words[$i]]), $i + 1);
            }
        };
    
        // Start generating combinations
        $generate([], 0);
    
        return $combinations;
    }

    /**
     * Generate an abbreviation based on content.
     */
    private function generateAbbreviation(array $words): string
    {
        return implode('', array_map(fn($word) => $word[0], $words));
    }
}