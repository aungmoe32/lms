<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(2)->create();
        // Post::factory(4)->create();

        $this->call(RoleSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(InstructionLevelTableSeeder::class);

    }
}
