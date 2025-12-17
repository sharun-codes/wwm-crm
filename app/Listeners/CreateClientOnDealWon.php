<?php

namespace App\Listeners;

use App\Events\DealWon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Client;

class CreateClientOnDealWon
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DealWon $event): void
    {
        $deal = $event->deal;

        Client::firstOrCreate([
            'name' => $deal->lead->name,
            'email' => $deal->lead->email,
            'phone' => $deal->lead->phone,
        ]);
    }
}
