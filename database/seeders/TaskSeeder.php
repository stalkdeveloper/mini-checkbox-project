<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;

use Faker\Factory as Faker;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seedData = Faker::create();
        
        for ($i = 0; $i < 15; $i++) {
            Task::insert([
                'name'          =>      $seedData->name,
                'description' =>      $seedData->paragraph,
                'is_marked'   =>      '0',
            ]);
        }
    }
}
