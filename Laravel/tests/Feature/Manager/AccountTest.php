<?php

namespace Tests\Feature\Manager;

use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     */
    public function test_manager_can_login_with_valid_credentials_and_token(): void
    {
        $manager = $this->createManager();
        
        $this->post(route('manager.login'), [
                    'email' => $manager->email,
                    'password' => 'password',
                ])
            ->assertStatus(200);
        
        $this->assertDatabaseHas('manager_tokens', [
            'manager_id' => $manager->id,
        ]);
        
        $this->assertGuest('manager');
        
    }
    
    private function createManager()
    {
        return Manager::factory()->create();
    }
}
