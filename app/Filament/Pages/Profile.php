<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;

class Profile extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected string $view = 'filament.pages.profile';

    protected static bool $shouldRegisterNavigation = false;

    public string $currentPassword = '';
    public string $newPassword = '';
    public string $newPasswordConfirmation = '';

    public ?array $formData = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(12)
            ->schema([
                Section::make('Change Password')->columnSpan(7)->columnStart(3)
                ->schema([
                    TextInput::make('current_password')
                        ->password()
                        ->required()
                        ->label('Current Password')->columnSpan([
                            '@sm' => 2,
                            '@md' => 2,
                            '@xl' => 3,
                        ]),

                    TextInput::make('password')
                        ->password()
                        ->required()
                        ->minLength(8)
                        ->label('New Password'),

                    TextInput::make('password_confirmation')
                        ->password()
                        ->same('password')
                        ->required()
                        ->label('Confirm New Password'),
                ]),
            ]),
        ];
    }

    protected function getFormStatePath(): string
    {
        return 'formData';
    }

    public function updatePassword(): void
    {
        $data = $this->form->getState();
        $user = auth()->user();

        if (! Hash::check($data['current_password'], $user->password)) {
            Notification::make()
                ->title('Current password is incorrect')
                ->danger()
                ->send();
            return;
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        $this->form->fill();

        Notification::make()
            ->title('Password updated successfully')
            ->success()
            ->send();
    }
}
