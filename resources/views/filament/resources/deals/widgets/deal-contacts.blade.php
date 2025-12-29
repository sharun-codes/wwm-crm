<x-filament::card>
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold">
            Contacts
        </h3>

        <x-filament::button
            wire:click="$dispatch('openAddContactModal')"
            size="sm"
        >
            Add Contact
        </x-filament::button>
    </div>

    @if($this->contacts?->count())
        <div class="space-y-2">
            @foreach($this->contacts as $contact)
                <div class="p-3 border rounded flex justify-between">
                    <div>
                        <div class="font-medium">
                            {{ $contact->name }}
                            @if($contact->is_primary)
                                <span class="text-xs text-primary-600">(Primary)</span>
                            @endif
                        </div>

                        <div class="text-sm text-gray-600">
                            {{ $contact->designation }}
                        </div>

                        <div class="text-sm">
                            {{ $contact->email }} · {{ $contact->mobile }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500">
            No contacts added yet.
        </p>
    @endif
</x-filament::card>
