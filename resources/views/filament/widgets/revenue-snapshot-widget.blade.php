<x-filament::card>
    <h2 class="text-lg font-bold mb-4">Revenue Snapshot</h2>

    <div class="grid grid-cols-3 gap-4">
        <div>
            <div class="text-sm text-gray-500">Total</div>
            <div class="text-xl font-bold">₹{{ number_format($total) }}</div>
        </div>

        <div>
            <div class="text-sm text-gray-500">Open</div>
            <div class="text-xl font-bold text-warning-600">
                ₹{{ number_format($open) }}
            </div>
        </div>

        <div>
            <div class="text-sm text-gray-500">Won</div>
            <div class="text-xl font-bold text-success-600">
                ₹{{ number_format($won) }}
            </div>
        </div>
    </div>
</x-filament::card>
