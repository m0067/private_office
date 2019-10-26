<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Transfer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class TransferTest
 * @package Tests\Feature
 */
class TransferTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var User
     */
    private $admin;

    /**
     * @var User
     */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\UsersTableSeeder::class);
        $this->admin = User::find(\UsersTableSeeder::ADMIN_ID);
        $this->user  = User::find(\UsersTableSeeder::USER_ID);
    }

    public function testIndexTransfer(): void
    {
        factory(Transfer::class, 3)->create();

        $response = $this->getJson('/api/transfer', ['Authorization' => 'Bearer '.$this->admin->api_token]);
        $response->assertOk();
        $response->assertJsonCount(3);
    }

    public function testIndexTransferByManager(): void
    {
        factory(Transfer::class, 10)->create();
        $manager = User::find(\UsersTableSeeder::MANAGER_ID);

        $response = $this->getJson('/api/transfer', ['Authorization' => 'Bearer '.$manager->api_token]);
        $response->assertOk();

        $transfers = json_decode($response->content(), true);
        $this->assertTrue(! empty($transfers));

        foreach ($transfers as $transfer) {
            $this->assertTrue(
                in_array(\UsersTableSeeder::USER_ID, [$transfer['sender_id'], $transfer['recipient_id']])
            );
        }
    }

    public function testIndexTransferByUser(): void
    {
        factory(Transfer::class, 10)->create();

        $response = $this->getJson('/api/transfer', ['Authorization' => 'Bearer '.$this->user->api_token]);
        $response->assertOk();

        $transfers = json_decode($response->content(), true);
        $this->assertTrue(! empty($transfers));

        foreach ($transfers as $transfer) {
            $this->assertTrue(
                in_array(\UsersTableSeeder::USER_ID, [$transfer['sender_id'], $transfer['recipient_id']])
            );
        }
    }

    /**
     * @dataProvider data
     *
     * @param  array  $data
     */
    public function testStoreTransfer(array $data): void
    {
        $response = $this->postJson('/api/transfer', $data, ['Authorization' => 'Bearer '.$this->user->api_token]);
        $response
            ->assertStatus(201)
            ->assertJson($data);

        $this->assertDatabaseHas('notifications', ['notifiable_id' => \UsersTableSeeder::USER_ID]);
        $this->assertDatabaseHas('notifications', ['notifiable_id' => \UsersTableSeeder::USER2_ID]);
        $this->assertDatabaseHas('notifications', ['notifiable_id' => \UsersTableSeeder::MANAGER_ID]);
        $this->assertDatabaseHas('notifications', ['notifiable_id' => \UsersTableSeeder::MANAGER2_ID]);
    }

    public function testShowTransfer(): void
    {
        $transfer = factory(Transfer::class)->create(['sender_id' => $this->user->id]);

        $response = $this->getJson('/api/transfer/'.$transfer->id, [
            'Authorization' => 'Bearer '.$this->user->api_token,
        ]);
        $response
            ->assertOk()
            ->assertJson($transfer->toArray());
    }

    public function testShowManagerTransfer(): void
    {
        $manager  = User::find(\UsersTableSeeder::MANAGER_ID);
        $transfer = factory(Transfer::class)->create(['sender_id' => $this->user->id]);

        $response = $this->getJson('/api/transfer/'.$transfer->id, [
            'Authorization' => 'Bearer '.$manager->api_token,
        ]);
        $response
            ->assertOk()
            ->assertJson($transfer->toArray());
    }

    public function testCantShowManagerTransfer(): void
    {
        $manager  = User::find(\UsersTableSeeder::MANAGER2_ID);
        $transfer = factory(Transfer::class)->create([
            'sender_id'    => $this->user->id,
            'recipient_id' => $this->admin->id,
        ]);

        $response = $this->getJson('/api/transfer/'.$transfer->id, [
            'Authorization' => 'Bearer '.$manager->api_token,
        ]);
        $response->assertStatus(403);
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [
            'transfer 1' => [
                [
                    'recipient_id' => \UsersTableSeeder::USER2_ID,
                    'amount'       => 1000,
                ],
            ],
        ];
    }
}
