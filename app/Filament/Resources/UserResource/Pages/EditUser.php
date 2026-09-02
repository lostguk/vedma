<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Throwable;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('verifyEmail')
                ->label('Подтвердить email')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn (): bool => $this->record instanceof User && ! $this->record->hasVerifiedEmail())
                ->requiresConfirmation()
                ->modalHeading('Подтвердить email')
                ->modalDescription('Пользователь сможет войти без перехода по ссылке из письма.')
                ->action(function (): void {
                    if (! $this->record instanceof User) {
                        return;
                    }

                    $this->record->markEmailAsVerified();
                    $this->record->refresh();

                    Notification::make()
                        ->title('Email подтверждён')
                        ->success()
                        ->send();
                }),
            Actions\Action::make('resendVerification')
                ->label('Отправить письмо')
                ->icon('heroicon-o-envelope')
                ->visible(fn (): bool => $this->record instanceof User && ! $this->record->hasVerifiedEmail())
                ->action(function (): void {
                    if (! $this->record instanceof User) {
                        return;
                    }

                    try {
                        $this->record->sendEmailVerificationNotification();

                        Notification::make()
                            ->title('Письмо отправлено')
                            ->success()
                            ->send();
                    } catch (Throwable $exception) {
                        report($exception);

                        Notification::make()
                            ->title('Не удалось отправить письмо')
                            ->danger()
                            ->send();
                    }
                }),
            Actions\DeleteAction::make()
                ->label('Удалить'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
