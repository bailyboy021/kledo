<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExpenseApprovalTest extends TestCase
{
    /**
     * Test adding an approver.
     */
    public function test_add_approver()
    {
        $response = $this->postJson('/api/approvers', [
            'name' => 'Patrick22'
        ]);

        $response->assertStatus(201); // Pastikan respons statusnya 201 Created
        $response->assertJson([
            'status' => true,
            'data' => [
                'name' => 'Patrick22',
            ],
        ]);
    }

    /**
     * Test adding an approval stage.
     */
    public function test_add_approval_stage()
    {
        $response = $this->postJson('/api/approval-stages', [
            'approver_id' => 1,
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'status' => true,
            'data' => [
                'approver_id' => 1,
            ],
        ]);
    }

    /**
     * Test adding an expense.
     */
    public function test_add_expense()
    {
        $response = $this->postJson('/api/expense', [
            'amount' => 12000
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'status' => true,
            'data' => [
                'amount' => 12000,
                'status_id' => 1,
            ]
        ]);
    }

}
