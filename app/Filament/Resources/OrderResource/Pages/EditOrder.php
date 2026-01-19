<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Repositories\PaymentRepository;
use App\Services\Payment\PaymentService;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('refreshPaymentStatus')
                ->label('Проверить оплату')
                ->action(function (PaymentRepository $paymentRepository, PaymentService $paymentService): void {
                    /** @var Order $order */
                    $order = $this->record;
                    $payment = $paymentRepository->findLatestForOrder($order->id);

                    if (! $payment) {
                        Notification::make()
                            ->title('Платеж не найден')
                            ->warning()
                            ->send();

                        return;
                    }

                    if (! $payment->external_order_id) {
                        Notification::make()
                            ->title('Нет данных для проверки')
                            ->body('У платежа отсутствует orderId платежного шлюза.')
                            ->warning()
                            ->send();

                        return;
                    }

                    try {
                        $paymentService->refreshStatus($payment);
                    } catch (\RuntimeException $exception) {
                        Notification::make()
                            ->title('Ошибка проверки оплаты')
                            ->body($exception->getMessage())
                            ->danger()
                            ->send();

                        return;
                    } catch (\Throwable) {
                        Notification::make()
                            ->title('Ошибка проверки оплаты')
                            ->body('Платежный шлюз недоступен.')
                            ->danger()
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->title('Статус платежа обновлен')
                        ->success()
                        ->send();
                }),
            Actions\Action::make('refundPayment')
                ->label('Возврат оплаты')
                ->requiresConfirmation()
                ->form([
                    TextInput::make('amount')
                        ->label('Сумма возврата')
                        ->numeric()
                        ->minValue(1)
                        ->placeholder('Полная сумма'),
                ])
                ->action(function (array $data, PaymentRepository $paymentRepository, PaymentService $paymentService): void {
                    /** @var Order $order */
                    $order = $this->record;
                    $payment = $paymentRepository->findLatestForOrder($order->id);

                    if (! $payment) {
                        Notification::make()
                            ->title('Платеж не найден')
                            ->warning()
                            ->send();

                        return;
                    }

                    $paymentService->refund($payment, $data['amount'] ?? null);

                    Notification::make()
                        ->title('Возврат выполнен')
                        ->success()
                        ->send();
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
