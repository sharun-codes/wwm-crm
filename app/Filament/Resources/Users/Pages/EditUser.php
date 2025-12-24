<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            Actions\Action::make('resetPassword')
                ->label('Reset Password')
                ->icon('heroicon-o-key')
                ->color('warning')
                ->visible(fn () => auth()->user()->hasRole('super-admin'))
                ->modalHeading('Reset User Password')
                ->modalDescription('This will overwrite the user’s current password.')
                ->modalSubmitActionLabel('Reset Password')
                ->requiresConfirmation()
                ->form([
                    \Filament\Forms\Components\TextInput::make('password')
                        ->password()
                        ->required()
                        ->minLength(8)
                        ->label('New Password'),

                    \Filament\Forms\Components\TextInput::make('password_confirmation')
                        ->password()
                        ->same('password')
                        ->required()
                        ->label('Confirm Password'),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'password' => Hash::make($data['password']),
                    ]);
                })
                ->successNotificationTitle('Password reset successfully'),
            DeleteAction::make(),
        ];
    }
}
