<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_a_booking_successfully()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $res = $this->postJson('/api/bookings', [
            'title' => 'Kickoff',
            'description' => 'Initial call',
            'start_time' => '2025-08-05 10:00:00',
            'end_time'   => '2025-08-05 11:00:00',
            'user_id'    => $user->id,
            'client_id'  => $client->id,
        ]);

        $res->assertCreated();
        $this->assertDatabaseHas('bookings',['title'=>'Kickoff']);
    }

    public function test_prevents_overlapping_bookings()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        Booking::create([
            'title'=>'First','start_time'=>'2025-08-05 10:00',
            'end_time'=>'2025-08-05 11:00','user_id'=>$user->id,'client_id'=>$client->id,
        ]);

        $res = $this->postJson('/api/bookings', [
            'title'=>'Overlap','start_time'=>'2025-08-05 10:30',
            'end_time'=>'2025-08-05 10:45','user_id'=>$user->id,'client_id'=>$client->id,
        ]);

        $res->assertStatus(422)->assertJson(['error'=>'Overlapping booking detected']);
    }

    public function test_retrieves_weekly_bookings()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        Booking::create([
            'title'=>'Meeting','start_time'=>'2025-08-05 09:00',
            'end_time'=>'2025-08-05 10:00','user_id'=>$user->id,'client_id'=>$client->id,
        ]);

        $res = $this->getJson('/api/bookings?week=2025-08-05');
        $res->assertOk()->assertJsonFragment(['title'=>'Meeting']);
    }
}
