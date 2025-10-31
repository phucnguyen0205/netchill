<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }

    #[Test]
    public function can_open_homepage(): void
    {
        $res = $this->get('/');
        $res->assertStatus(200);
    }

    #[Test]
    public function detai_page_loads_with_existing_movie(): void
    {
        $movie = Movie::query()->first();
        $this->assertNotNull($movie, 'Không tìm thấy Movie nào trong DB thật.');

        $res = $this->get(route('movies.detai', $movie->slug));
        $res->assertStatus(200);
    }

    #[Test]
    public function logged_user_can_add_movie_to_existing_watchlist(): void
    {
        $user = User::query()->first() ?? User::factory()->create([
            'password' => bcrypt('secret123'),
        ]);
        $this->actingAs($user);

        $movie = Movie::query()->first();
        $this->assertNotNull($movie, 'Không có movie trong DB.');

        $watchlist = Watchlist::query()->where('user_id', $user->id)->first()
            ?? Watchlist::create(['user_id' => $user->id, 'name' => 'Test WL']);

        $res = $this->postJson(route('watchlists.items.store', $watchlist), [
            'movie_id' => $movie->id,
        ]);

        $res->assertStatus(200)->assertJsonStructure(['message']);

        $this->assertDatabaseHas('movie_watchlist', [
            'watchlist_id' => $watchlist->id,
            'movie_id'     => $movie->id,
        ]);
    }
}
