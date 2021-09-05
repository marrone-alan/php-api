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
}
