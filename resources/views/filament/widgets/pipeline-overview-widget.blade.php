<x-filament::card>
    <h2 class="text-lg font-bold mb-4">Pipeline Overview</h2>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        @foreach($stages as $stage)
            <div class="rounded-lg bg-gray-100 dark:bg-gray-800 p-4 text-center">
                <div class="text-sm text-gray-500">
                    {{ $stage->name }}
                </div>
                <div class="text-2xl font-bold">
                    {{ $stage->deals_count }}
                </div>
            </div>
        @endforeach
    </div>
</x-filament::card>
