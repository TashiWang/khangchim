<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 50; $i++) {
            $user = User::create([
                'name' => 'Test ' . $i,
                'email' => 'test' . $i . '@test.com',
                'is_admin' => 0,
                'contact' => '77777777',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ]);
            $role = Role::where('id', 4)->first();

            $user->syncRoles($role);
        }
    }
}
