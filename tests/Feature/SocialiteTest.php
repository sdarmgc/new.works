<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;

class SocialiteTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_google_redirect(): void
    {
        Socialite::fake('google');
    
        $response = $this->get('/login/google/redirect');
    
        $response->assertRedirect();
    }

    public function test_google_callback(): void
    {
        Socialite::fake('google', (new User)->map([
            'id' => 'google-123',
            'name' => 'Sean Kim',
            'email' => 'shskim7@gmail.com',
        ]));
    
        $response = $this->get('/login/google/callback');
    
        $response->assertRedirect('/dashboard');
    
        $this->assertDatabaseHas('users', [
            'id' => 'google-123',
            'name' => 'Sean Kim',
            'email' => 'shskim7@gmail.com',
        ]);
    }
}
