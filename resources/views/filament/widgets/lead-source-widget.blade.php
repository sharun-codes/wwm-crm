<x-filament::card>
    <h2 class="text-lg font-bold mb-4">Lead Sources</h2>

    <ul class="space-y-2">
        @foreach($sources as $row)
            <li class="flex justify-between">
                <span>{{ ucfirst($row->source) }}</span>
                <span class="font-semibold">{{ $row->total }}</span>
            </li>
        @endforeach
    </ul>
</x-filament::card>
