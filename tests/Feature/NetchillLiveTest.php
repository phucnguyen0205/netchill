<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Movie;
use App\Models\Watchlist;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NetchillLiveTest extends TestCase
{
    use DatabaseTransactions;

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
