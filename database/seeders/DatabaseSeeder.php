<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call([
        //     RoleSeeder::class,
        //     FaqSeeder::class
        // ]);

        // $users = [
        //     [
        //         'first_name' => 'Test', 
        //         'last_name' => 'user', 
        //         'email' => 'test@example.com', 
        //     ],
        //     [
        //         'first_name' => 'Admin',
        //         'email' => 'admin@admin.com',
        //         'password' => Hash::make('admin@123'),
        //         'is_admin' => true,
        //     ],
        // ];

        // foreach ($users as $user) {
        //     User::updateOrCreate(
        //         ['email' => $user['email']],
        //         $user
        //     );
        // }
    }
}
