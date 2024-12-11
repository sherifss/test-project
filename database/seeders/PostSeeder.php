<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Factory::create();

        $topics = [];
        
        for ($i = 0; $i < 5; $i++) {
            $topics[] = $faker->unique()->words(3, true);
        }

        foreach ($topics as $topic) {
            DB::table('posts')->insert([
                'topic' => $topic,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
