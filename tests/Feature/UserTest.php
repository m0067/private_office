<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Class UserTest
 * @package Tests\Feature
 */
class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var User
     */
    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\UsersTableSeeder::class);
        $this->admin = User::find(\UsersTableSeeder::ADMIN_ID);
    }

    public function testIndexUser(): void
    {
        $response = $this->getJson('/api/user/', [
            'Authorization' => 'Bearer '.$this->admin->api_token,
        ]);
        $response->assertOk();
        $response->assertJsonCount(6);
    }

    /**
     * @dataProvider newUser
     *
     * @param  array  $user
     */
    public function testStoreUser(array $user): void
    {
        $response = $this->postJson('/api/user', $user, ['Authorization' => 'Bearer '.$this->admin->api_token]);
        $response
            ->assertStatus(201)
            ->assertJson(['name' => $user['name']]);
        $createdUser = json_decode($response->content(), true);

        $this->assertDatabaseHas('wallets', ['user_id' => $createdUser['id']]);
    }

    /**
     * @dataProvider newUser
     *
     * @param  array  $user
     */
    public function testStoreUserByManager(array $user): void
    {
        $manager = User::find(\UsersTableSeeder::MANAGER_ID);

        $response = $this->postJson('/api/user', $user, ['Authorization' => 'Bearer '.$manager->api_token]);
        $response
            ->assertStatus(201)
            ->assertJson(['name' => $user['name']]);
        $createdUser = json_decode($response->content(), true);

        $this->assertDatabaseHas('wallets', ['user_id' => $createdUser['id']]);
    }

    /**
     * @dataProvider newUser
     *
     * @param  array  $user
     */
    public function testCantStoreAdminByManager(array $user): void
    {
        $user    += ['role' => User::ROLES['ADMIN']];
        $manager = User::find(\UsersTableSeeder::MANAGER_ID);

        $response = $this->postJson('/api/user', $user, ['Authorization' => 'Bearer '.$manager->api_token]);
        $response->assertStatus(403);
    }

    /**
     * @dataProvider newUser
     *
     * @param  array  $user
     */
    public function testCantStoreBlockedUserByManager(array $user): void
    {
        $user    += ['is_blocked' => true];
        $manager = User::find(\UsersTableSeeder::MANAGER_ID);

        $response = $this->postJson('/api/user', $user, ['Authorization' => 'Bearer '.$manager->api_token]);
        $response->assertStatus(403);
    }

    /**
     * @dataProvider newUser
     *
     * @param  array  $newUser
     */
    public function testCantStoreUserByUser(array $newUser): void
    {
        $user = User::find(\UsersTableSeeder::USER_ID);

        $response = $this->postJson('/api/user', $newUser, ['Authorization' => 'Bearer '.$user->api_token]);
        $response->assertStatus(403);
    }

    public function testShowUser(): void
    {
        $response = $this->getJson('/api/user/'.\UsersTableSeeder::ADMIN_ID, [
            'Authorization' => 'Bearer '.$this->admin->api_token,
        ]);
        $response->assertOk();
    }

    public function testCantShowBlockedUser(): void
    {
        $user = User::find(\UsersTableSeeder::BLOCKED_USER_ID);

        $response = $this->getJson('/api/user/'.\UsersTableSeeder::BLOCKED_USER_ID, [
            'Authorization' => 'Bearer '.$user->api_token,
        ]);
        $response->assertStatus(403);
    }

    public function testShowNotFoundUser(): void
    {
        $response = $this->getJson('/api/user/1212152121', ['Authorization' => 'Bearer '.$this->admin->api_token]);
        $response->assertNotFound();
    }

    public function testUpdateUser(): void
    {
        $data = ['name' => 'Viktor'];

        $response = $this->putJson('/api/user/'.\UsersTableSeeder::ADMIN_ID, $data, [
            'Authorization' => 'Bearer '.$this->admin->api_token,
        ]);
        $response
            ->assertOk()
            ->assertJson($data);
    }

    public function testDestroyUser(): void
    {
        $response = $this->deleteJson('/api/user/'.\UsersTableSeeder::ADMIN_ID, [], [
            'Authorization' => 'Bearer '.$this->admin->api_token,
        ]);
        $response->assertOk();
    }

    /**
     * @return array
     */
    public function newUser(): array
    {
        return [
            'Liza' => [
                [
                    'name'                  => 'Liza',
                    'email'                 => Str::random(10).'@gmail.com',
                    'password'              => 'Liza1234',
                    'password_confirmation' => 'Liza1234',
                ],
            ],
        ];
    }
}
