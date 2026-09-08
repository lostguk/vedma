<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\ProductStockService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

final class ReleaseUnpaidOrderStockCommand extends Command
{
    protected $signature = 'orders:release-unpaid-stock
                            {--execute : Фактически вернуть остатки. Без флага — только отчёт}
                            {--min-age=0 : Минимальный возраст заказа в часах (0 = все)}
                            {--include-pending : Включить заказы в статусе «ожидает оплату»}
                            {--ids= : Ограничить список id через запятую}';

    protected $description = 'Вернуть склад по неоплаченным заказам (по умолчанию dry-run)';

    public function handle(ProductStockService $productStockService): int
    {
        $execute = (bool) $this->option('execute');
        $minAgeHours = max(0, (int) $this->option('min-age'));
        $includePending = (bool) $this->option('include-pending');
        $ids = $this->parseIds((string) $this->option('ids'));
        $cutoff = $minAgeHours > 0 ? Carbon::now()->subHours($minAgeHours) : null;

        $this->info($execute
            ? 'Режим: выполнение'
            : 'Режим: dry-run (ничего не меняется). Для записи добавьте --execute');
        $this->line($cutoff === null
            ? 'Возраст: все подходящие заказы'
            : "Минимальный возраст: {$minAgeHours} ч (создан до {$cutoff->toDateTimeString()})");

        $releaseQuery = Order::query()
            ->whereNull('stock_released_at')
            ->when($cutoff !== null, fn ($query) => $query->where('created_at', '<=', $cutoff))
            ->where(function ($query) use ($includePending): void {
                $query->whereIn('status', ['new', 'payment_failed', 'cancelled']);

                if ($includePending) {
                    $query->orWhere('status', 'payment_pending');
                }
            })
            ->with('items');

        $refundedQuery = Order::query()
            ->where('status', 'refunded')
            ->whereNull('stock_released_at')
            ->when($cutoff !== null, fn ($query) => $query->where('created_at', '<=', $cutoff));

        if ($ids !== []) {
            $releaseQuery->whereIn('id', $ids);
            $refundedQuery->whereIn('id', $ids);
        }

        $toRelease = $releaseQuery->orderBy('id')->get();
        $toMark = $refundedQuery->orderBy('id')->get();

        if ($toRelease->isEmpty() && $toMark->isEmpty()) {
            $this->info('Подходящих заказов нет.');

            return self::SUCCESS;
        }

        if ($toRelease->isNotEmpty()) {
            $this->newLine();
            $this->info('Вернуть остатки и отменить:');
            $this->table(
                ['ID', 'Статус', 'Создан', 'Позиций'],
                $toRelease->map(fn (Order $order): array => [
                    $order->id,
                    $order->status,
                    $order->created_at?->toDateTimeString(),
                    $order->items->count(),
                ])->all(),
            );
        }

        if ($toMark->isNotEmpty()) {
            $this->newLine();
            $this->info('Refunded без флага — только проставить stock_released_at (склад уже возвращали):');
            $this->table(
                ['ID', 'Статус', 'Создан'],
                $toMark->map(fn (Order $order): array => [
                    $order->id,
                    $order->status,
                    $order->created_at?->toDateTimeString(),
                ])->all(),
            );
        }

        if (! $execute) {
            $this->newLine();
            $this->warn('Dry-run завершён. Чтобы применить: php artisan orders:release-unpaid-stock --execute');

            return self::SUCCESS;
        }

        $released = 0;
        $marked = 0;
        $failed = 0;

        foreach ($toRelease as $order) {
            try {
                $productStockService->cancelAndRelease($order);
                $released++;
            } catch (\Throwable $exception) {
                $failed++;
                $this->error("Заказ #{$order->id}: {$exception->getMessage()}");
            }
        }

        foreach ($toMark as $order) {
            $productStockService->markReleasedWithoutRestore($order);
            $marked++;
        }

        $this->newLine();
        $this->info("Отменено с возвратом склада: {$released}");
        $this->info("Проставлен флаг у refunded: {$marked}");

        if ($failed > 0) {
            $this->warn("Ошибок: {$failed}");
        }

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * @return list<int>
     */
    private function parseIds(string $raw): array
    {
        if (trim($raw) === '') {
            return [];
        }

        return array_values(array_filter(array_map(
            static fn (string $id): int => (int) trim($id),
            explode(',', $raw),
        ), static fn (int $id): bool => $id > 0));
    }
}
