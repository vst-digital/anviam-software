<?php

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Class UserTableSeeder.
 */
class UserSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        // Add the master administrator, user id of 1
        User::create([
            'first_name' => 'Anviam',
            'last_name' => '',
            'email' => 'superadmin@anviam.com',
            'phone_number' => '1234567890',
            'password' => 'secret@123',
            'email_verified_at' => now(),
            'role' => 1,
            'status' => 1,
        ]);

        $this->enableForeignKeys();
    }
}
