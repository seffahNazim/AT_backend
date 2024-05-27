<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employe;
use App\Models\Admin;
use App\Models\Permission;
use App\Models\Pointing;
use App\Models\Notification1;
use App\Models\Notification2;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Permission::factory(1)->create();
        $numberOfAdmins = 5;
        $numberOfEmployes = 5;

        User::factory($numberOfAdmins)->create(['role' => 'admin'])->each(function ($user) {
            Admin::factory()->create(['user_id' => $user->id]);
        });

        User::factory($numberOfEmployes)->create(['role' => 'employe'])->each(function ($user) {
            Employe::factory()->create(['user_id' => $user->id]);
        });

        Pointing::factory(20)->create();
        // Notification1::factory(5)->create();
        // Notification2::factory(5)->create();
    }
}


// <?php

// namespace Database\Seeders;

// use App\Models\User;
// use App\Models\Employe;
// use App\Models\Admin;
// use App\Models\Permission;
// use App\Models\Pointing;
// use App\Models\Notification1;
// use App\Models\Notification2;
// // use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Illuminate\Database\Seeder;

// class DatabaseSeeder extends Seeder
// {
//     /**
//      * Seed the application's database.
//      */
//     public function run(): void
//     {
//         Permission::factory(1)->create();

//         $numberOfAdmins = 2;
//         $numberOfEmployes = 8;
//         // Créer des utilisateurs pour les admins
//         User::factory($numberOfAdmins)->create(['role' => 'admin'])->each(function ($user) {
//             Admin::factory()->create(['user_id' => $user->id]);
//         });

//         // Créer des utilisateurs pour les employés
//         User::factory($numberOfEmployes)->create(['role' => 'employe'])->each(function ($user) {
//             Employe::factory()->create(['user_id' => $user->id]);
//         });

//         // Créer des pointages et des notifications
//         Pointing::factory(5)->create();

//         Pointing::factory(5)->create();
//         Notification1::factory(5)->create();
//         Notification2::factory(5)->create();
//     }
// }

