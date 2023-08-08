<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //generating users
        \App\Models\User::factory(1000)->create();

        //calling based off the user to the events and attendees to assign the user to the event as an attendee
        $this->call(EventSeeder::class);
        $this->call(AttendeeSeeder::class);
    }
}
