<?php

namespace App\Listeners;

use App\Events\DealWon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Client;
use App\Models\Company;

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

         // Resolve or create Company
         $company = Company::firstOrCreate(
            ['name' => $deal->lead?->company ?? $deal->client?->company ?? $deal->lead?->name],
        );

        // Resolve or create Client (contact)
        $client = Client::firstOrCreate(
            ['email' => $deal->lead?->email ?? $deal->client?->email],
            [
                'name' => $deal->lead?->name ?? $deal->client?->name,
                'phone' => $deal->lead?->phone ?? $deal->client?->phone,
                'company_id' => $company->id,
            ]
        );

        //Link Deal -> Client + Company (idempotent)
        $deal->update([
            'client_id' => $client->id,
            'company_id' => $company->id,
        ]);

    }
}
