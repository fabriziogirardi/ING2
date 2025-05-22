<?php

namespace Tests\Feature\Manager;

use App\Mail\Manager\TokenGeneratedMail;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JsonException;
use Mail;
use Random\RandomException;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Manager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = Manager::factory()->create();
    }

    /**
     * A basic feature test example.
     */
    public function test_manager_can_request_token_with_correct_credentials(): void
    {
        Mail::fake();

        $response = $this->post(route('manager.login.post'), [
            'email'    => $this->manager->person->email,
            'password' => 'password',
        ]);

        $response->assertStatus(302);

        $this->assertGuest('manager')
            ->assertDatabaseHas('manager_tokens', [
                'manager_id' => $this->manager->id,
            ]);

        Mail::assertSent(TokenGeneratedMail::class);
    }

    public function test_manager_cannot_request_token_with_incorrect_credentials(): void
    {
        $response = $this->post(route('manager.login.post'), [
            'email'    => $this->manager->person->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['incorrect_credentials']);

        $this->assertGuest('manager')
            ->assertDatabaseEmpty('manager_tokens');
    }

    /**
     * @throws JsonException
     * @throws RandomException
     */
    public function test_manager_is_logged_in_after_verify_token(): void
    {
        $this->manager->createToken();

        $response = $this->post(route('manager.verify-token', [
            'email' => $this->manager->person->email,
            'token' => $this->manager->token->token,
        ]));

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $response->assertRedirect(route('manager.dashboard'));
        $this->assertAuthenticatedAs($this->manager, 'manager');
    }

    /**
     * @throws RandomException
     */
    public function test_manager_cannot_verify_token_with_incorrect_token(): void
    {
        $this->manager->createToken();

        $response = $this->post(route('manager.verify-token', [
            'email' => $this->manager->person->email,
            'token' => '123123',
        ]));

        $response->assertSessionHasErrors(['incorrect_token']);
        $response->assertStatus(302);
        $this->assertGuest('manager');
    }

    /**
     * @throws RandomException
     */
    public function test_manager_token_should_be_a_number(): void
    {
        $this->manager->createToken();

        $response = $this->post(route('manager.verify-token', [
            'email' => $this->manager->person->email,
            'token' => 'string',
        ]));

        $response->assertSessionHasErrors(['token']);
        $response->assertStatus(302);
        $this->assertGuest('manager');
    }

    /**
     * @throws RandomException
     */
    public function test_token_expires_after_two_minutes(): void
    {
        $this->manager->createToken();

        $this->travel(3)->minutes();

        $response = $this->post(route('manager.verify-token', [
            'email' => $this->manager->person->email,
            'token' => $this->manager->token->token,
        ]));

        $response->assertSessionHasErrors(['incorrect_token']);
        $response->assertStatus(302);
        $this->assertGuest('manager');
    }

    /**
     * @throws RandomException
     * @throws JsonException
     */
    public function test_manager_cannot_login_twice_with_same_token(): void
    {
        $this->manager->createToken();

        $response = $this->post(route('manager.verify-token', [
            'email' => $this->manager->person->email,
            'token' => $this->manager->token->token,
        ]));

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $response->assertRedirect(route('manager.dashboard'));

        $response = $this->post(route('manager.verify-token', [
            'email' => $this->manager->person->email,
            'token' => $this->manager->token->token,
        ]));

        $response->assertSessionHasErrors(['incorrect_token']);
        $response->assertStatus(302);
    }

    public function test_manager_can_logout(): void
    {
        $this->actingAs($this->manager, 'manager');

        $response = $this->get(route('manager.logout'));

        $response->assertStatus(302);
        $response->assertRedirect(route('manager.login'));
        $this->assertGuest('manager');
    }
}
