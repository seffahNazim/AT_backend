<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employe;
use App\Models\Admin;
use App\Models\Permission;
use App\Models\Pointing;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Employe::factory(5)->create();
        Permission::factory(1)->create();
        Admin::factory(5)->create();
        Pointing::factory(5)->create();
    }
}
