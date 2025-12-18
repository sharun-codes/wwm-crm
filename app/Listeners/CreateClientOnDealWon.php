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

        // Find or create client (email = identity)
        $client = Client::firstOrCreate(
            [
                'email' => $deal->lead->email ?? 'phone:' . $deal->lead->phone,
            ],
            [
                'name' => $deal->lead->name,
                'phone' => $deal->lead->phone,
            ]
        );

        \Log::warning("deal won");
        \Log::debug($deal);
        \Log::debug($client);
        \Log::warning("--------");

        // Link deal to client (idempotent)
        if ($deal->client_id !== $client->id) {
            $deal->update([
                'client_id' => $client->id,
            ]);
        }
    }
}
