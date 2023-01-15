<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    const ADMIN_ID = 999;
    const MANAGER_ID = 888;
    const USER_ID = 777;
    const BLOCKED_USER_ID = 666;
    const USER2_ID = 555;
    const MANAGER2_ID = 444;
    const USERS_ID = [
        UsersTableSeeder::ADMIN_ID,
        UsersTableSeeder::MANAGER_ID,
        UsersTableSeeder::MANAGER2_ID,
        UsersTableSeeder::USER_ID,
        UsersTableSeeder::USER2_ID,
        UsersTableSeeder::BLOCKED_USER_ID,
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id'        => UsersTableSeeder::ADMIN_ID,
            'name'      => Str::random(10),
            'email'     => Str::random(10).'@gmail.com',
            'password'  => Hash::make('password123'),
            'api_token' => Str::random(80),
            'role'      => User::ROLES['ADMIN'],
        ]);
        DB::table('users')->insert([
            'id'        => UsersTableSeeder::MANAGER_ID,
            'name'      => Str::random(10),
            'email'     => Str::random(10).'@gmail.com',
            'password'  => Hash::make('password123'),
            'api_token' => Str::random(80),
            'role'      => User::ROLES['MANAGER'],
        ]);
        DB::table('users')->insert([
            'id'        => UsersTableSeeder::MANAGER2_ID,
            'name'      => Str::random(10),
            'email'     => Str::random(10).'@gmail.com',
            'password'  => Hash::make('password123'),
            'api_token' => Str::random(80),
            'role'      => User::ROLES['MANAGER'],
        ]);
        DB::table('users')->insert([
            'id'        => UsersTableSeeder::USER_ID,
            'parent_id' => UsersTableSeeder::MANAGER_ID,
            'name'      => Str::random(10),
            'email'     => Str::random(10).'@gmail.com',
            'password'  => Hash::make('password123'),
            'api_token' => Str::random(80),
            'role'      => User::ROLES['USER'],
        ]);
        DB::table('users')->insert([
            'id'        => UsersTableSeeder::USER2_ID,
            'parent_id' => UsersTableSeeder::MANAGER2_ID,
            'name'      => Str::random(10),
            'email'     => Str::random(10).'@gmail.com',
            'password'  => Hash::make('password123'),
            'api_token' => Str::random(80),
            'role'      => User::ROLES['USER'],
        ]);
        DB::table('users')->insert([
            'id'         => UsersTableSeeder::BLOCKED_USER_ID,
            'parent_id'  => UsersTableSeeder::MANAGER2_ID,
            'name'       => Str::random(10),
            'email'      => Str::random(10).'@gmail.com',
            'password'   => Hash::make('password123'),
            'api_token'  => Str::random(80),
            'role'       => User::ROLES['USER'],
            'is_blocked' => true,
        ]);

        foreach (UsersTableSeeder::USERS_ID as $userId) {
            DB::table('wallets')->insert([
                'id'      => $userId,
                'user_id' => $userId,
                'balance' => 5000,
            ]);
        }
    }
}
