<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Repositories\PaymentRepository;
use App\Services\Export\OrderExcelExporter;
use App\Services\Payment\PaymentService;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportExcel')
                ->label('Выгрузить в Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    /** @var Order $order */
                    $order = $this->record;
                    $exporter = app(OrderExcelExporter::class);
                    $filePath = $exporter->export($order);
                    $fileName = "order-{$order->id}.xlsx";

                    return response()->download($filePath, $fileName, [
                        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ])->deleteFileAfterSend();
                }),
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
                ->visible(function (): bool {
                    /** @var Order $order */
                    $order = $this->record;

                    return $order->status === 'paid';
                })
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

                    if (! $payment->external_order_id) {
                        Notification::make()
                            ->title('Нет данных для возврата')
                            ->body('У платежа отсутствует orderId платежного шлюза.')
                            ->warning()
                            ->send();

                        return;
                    }

                    try {
                        $paymentService->refund($payment, $data['amount'] ?? null);
                    } catch (\RuntimeException $exception) {
                        Notification::make()
                            ->title('Ошибка возврата')
                            ->body($exception->getMessage())
                            ->danger()
                            ->send();

                        return;
                    } catch (\Throwable) {
                        Notification::make()
                            ->title('Ошибка возврата')
                            ->body('Платежный шлюз недоступен.')
                            ->danger()
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->title('Возврат выполнен')
                        ->success()
                        ->send();
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
