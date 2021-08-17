<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        DB::table('test_types')->insert([
            ['name' => 'Linear Test', 'code' => 'LA', 'slug' => 'line-test', 'description' => ''],
            ['name' => 'CAT', 'code' => 'CAT', 'slug' => 'cat', 'description' => ''],
        ]);

        DB::table('question_types')->insert([
            ['name' => 'Single Choice', 'code' => 'SC', 'type' => 'objective', 'description' => 'Single Choice', 'is_active' => 1],
            ['name' => 'True or False', 'code' => 'TOF', 'type' => 'objective', 'description' => 'Yes/No, Correct/Incorrect, and Agree/Disagree.', 'is_active' => 1],
            ['name' => 'Consecutive Input', 'code' => 'CI', 'type' => 'objective', 'description' => 'Consecutive Input', 'is_active' => 1],
            ['name' => 'Matrix', 'code' => 'MT', 'type' => 'objective', 'description' => 'Matrix', 'is_active' => 1],
        ]);
    }
}
