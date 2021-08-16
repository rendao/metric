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
    }
}
