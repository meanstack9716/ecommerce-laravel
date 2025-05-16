<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Address;
use App\Models\Seller;
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
        $this->call([
            RoleSeeder::class,
            FaqSeeder::class,
            CategorySeeder::class,
            ProductBrandSeeder::class
        ]);

        $users = [
            [
                'first_name' => 'Admin',
                'last_name' => 'Inno',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin@123'),
                'is_admin' => true,
                'phone_number' => '+919876543211',
                'role' => 'Seller',
                'business_details' => [
                    'business_name' =>  'InnoSales',
                    'business_type' => 'Corporation',
                    'business_email' => 'innoSales@gmail.com',
                    'business_mobile' => '+919876543211',
                    'gst_num' => '07ASS1234567890',
                    'status' => 'Approved'
                ],
                'address' => [
                    'type' => "Office",
                    'line1' => "MPV Tower",
                    'line2' => "Sec-10",
                    'city' => 'Noida',
                    'state' => 'Uttar Pradesh',
                    'postal_code' => '123456',
                    'country' => 'India',
                ]
            ],
        ];

        foreach ($users as $user) {
            $role = Role::where('name', $user['role'])->first();
            $adminUser = User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'email' => $user['email'],
                    'password' => $user['password'],
                    'phone_number' => $user['phone_number'],
                    'is_admin' => $user['is_admin'],
                    'role_id' => $role ? $role['id'] : null
                ]
            );

            Seller::updateOrCreate(
                ['user_id' => $adminUser['id']],
                [
                    'business_name' => $user['business_details']['business_name'],
                    'business_type' => $user['business_details']['business_type'],
                    'business_email' => $user['business_details']['business_email'],
                    'business_mobile' => $user['business_details']['business_mobile'],
                    'gst_num' => $user['business_details']['gst_num'],
                    'status' => $user['business_details']['status'],
                ]
            );

            Address::create([
                'user_id' => $adminUser['id'],
                'type' => $user['address']['type'],
                'line1' => $user['address']['line1'],
                'line2' => $user['address']['line2'],
                'city' => $user['address']['city'],
                'state' => $user['address']['state'],
                'postal_code' => $user['address']['postal_code'],
                'country' => $user['address']['country'],
            ]);
        }
    }
}
