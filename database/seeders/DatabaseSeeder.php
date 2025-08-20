<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'John Doe',
            'username' => 'john_doe',
            'email' => 'johndoe@example.com',
        ]);

        $categories = [
            'JavaScript',
            'CSS',
            'HTML',
            'Laravel',
            'React',
            'Python'
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
            ]);
        }
        // Post::factory(100)->create();
    }
}
