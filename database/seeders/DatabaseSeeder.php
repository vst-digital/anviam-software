<?php

namespace Database\Seeders;
use App\Models\User;
use App\Models\UserRole;
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
        User::create([
            'first_name' => 'Anviam',
            'last_name' => '',
            'email' => 'superadmin@anviam.com',
            'phone_number' => '1234567890',
            'password' => bcrypt('secret@123'),
            'email_verified_at' => now(),
            'role' => 1,
            'status' => 1,
        ]);
		UserRole::create([
            'name' => 'Superadmin'
        ]);
		UserRole::create([
            'name' => 'Company'
        ]);
		UserRole::create([
            'name' => 'Role'
        ]);
    }
}
