<x-filament::page>
    <form wire:submit.prevent="updatePassword" class="space-y-6">
        {{ $this->form }}

        <div class="flex justify-center">
            <x-filament::button type="submit">
                Update Password
            </x-filament::button>
        </div>
    </form>
</x-filament::page>
