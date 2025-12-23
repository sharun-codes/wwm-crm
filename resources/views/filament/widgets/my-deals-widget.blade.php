<x-filament::card>
    <h2 class="text-lg font-bold mb-4">My Recent Deals</h2>

    <ul class="space-y-2">
        @foreach($deals as $deal)
            <li class="flex justify-between">
                <span>{{ $deal->company?->name ?? '—' }}</span>
                <span class="text-sm text-gray-500">
                    ₹{{ number_format($deal->value ?? 0) }}
                </span>
            </li>
        @endforeach
    </ul>
</x-filament::card>
