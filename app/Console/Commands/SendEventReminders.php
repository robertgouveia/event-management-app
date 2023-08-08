<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Notifications\EventReminderNotification;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends an event reminder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //fetch all events with the users, only events where the time is from now to a days time.
        $events = Event::with('attendees.user')->whereBetween('start_time', [now(), now()->addDay()]);
        $eventCount = $events->count();
        $eventLabel = Str::plural('event', $eventCount);
        $this->info("Found {$eventCount} {$eventLabel}.");

        //over all the events, each event get the attendees and each attendee echo their user id
        $events->each(fn ($event) => $event->attendees->each(fn ($attendee) => $attendee->user->notify(new EventReminderNotification(
            $event
        ))));

        $this->info('Reminder notification sent Successfully!');
    }
}
