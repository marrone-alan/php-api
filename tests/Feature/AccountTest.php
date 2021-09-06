<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Account;

class AccountTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Get balance for non-existing account
     *
     * @return void
     */
    public function testNonExistingAccount()
    {
        $response = $this->get('/api/balance?account_id=0');

        $response->assertStatus(404);
        $this->assertEquals(0, $response->getData());
    }

    /**
     * Get balance for existing account
     *
     * @return void
     */
    public function testExistingAccount()
    {
        $account = factory(Account::class)->create();

        $response = $this->get("/api/balance?account_id={$account->id}");

        $response->assertStatus(200);
        $this->assertEquals($account->balance, $response->getData());
    }

    /**
     * Create account with initial balance
     *
     * @return void
     */
    public function testCreateAccountInitialValue()
    {
        $response = $this->postJson('/api/event', [
            'type' => 'deposit',
            'destination' => '100',
            'amount' => 10,
        ]);

        $returnExpected = ["destination" => ["id" => "100", "balance" => 10]];

        $response
            ->assertStatus(201)
            ->assertExactJson($returnExpected);
    }

    /**
     * Deposit into existing account
     * 
     * @return void
     */
    public function testDepositExistingAccount()
    {
        $account = factory(Account::class)->create(['id' => '100', 'balance' => 10]);

        $response = $this->postJson('/api/event', [
            'type' => 'deposit',
            'destination' => '100',
            'amount' => 10,
        ]);

        $returnExpected = ["destination" => ["id" => "100", "balance" => 20]];

        $response
            ->assertStatus(201)
            ->assertExactJson($returnExpected);
    }

    /**
     * Withdraw from non-existing account
     * 
     * @return void
     */
    public function testWithdrawNonExistingAccount()
    {
        $response = $this->postJson('/api/event', [
            'type' => 'withdraw',
            'origin' => '200',
            'amount' => 10,
        ]);

        $response->assertStatus(404);
        $this->assertEquals(0, $response->getData());
    }

    /**
     * Withdraw from existing account
     * 
     * @return void
     */
    public function testWithdrawExistingAccount()
    {
        $account = factory(Account::class)->create(['id' => '100', 'balance' => 20]);

        $response = $this->postJson('/api/event', [
            'type' => 'withdraw',
            'origin' => '100',
            'amount' => 5,
        ]);

        $returnExpected = ["origin" => ["id" => "100", "balance" => 15]];

        $response
            ->assertStatus(201)
            ->assertExactJson($returnExpected);
    }

    /**
     * Transfer from non-existing account
     * 
     * @return void
     */
    public function testTransferNonExistingAccount()
    {
        $response = $this->postJson('/api/event', [
            'type' => 'transfer',
            'origin' => '200',
            'destination' => '300',
            'amount' => 15,
        ]);

        $response->assertStatus(404);
        $this->assertEquals(0, $response->getData());
    }

    /**
     * Transfer from existing account
     * 
     * @return void
     */
    public function testTransferExistingAccount()
    {
        $account = factory(Account::class)->create(['id' => '100', 'balance' => 15]);

        $response = $this->postJson('/api/event', [
            'type' => 'transfer',
            'origin' => '100',
            'destination' => '300',
            'amount' => 15,
        ]);

        $returnExpected = [
            "origin" => [
                "id" => "100",
                "balance" => 0
            ],
            "destination" => [
                "id" => "300",
                "balance" => 15
            ],
        ];

        $response
            ->assertStatus(201)
            ->assertExactJson($returnExpected);
    }

    /**
     * Reset account
     * 
     * @return void
     */
    public function testResetAccount()
    {
        factory(Account::class)->create();
        factory(Account::class)->create();
        factory(Account::class)->create();
        $this->assertEquals(count(Account::all()), 3);

        $response = $this->post('/api/reset');

        $this->assertEquals(count(Account::all()), 0);
        $this->assertEquals('OK', $response->getData());
    }
}
