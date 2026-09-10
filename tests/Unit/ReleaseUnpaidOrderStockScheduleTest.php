<?php

declare(strict_types=1);

uses(Tests\TestCase::class);

it('планировщик регистрирует ночной возврат склада', function (): void {
    putenv('COLUMNS=200');
    $_ENV['COLUMNS'] = '200';
    $_SERVER['COLUMNS'] = '200';

    $this->artisan('schedule:list')
        ->expectsOutputToContain('orders:release-unpaid-stock --execute --include-pending --min-age=12')
        ->assertSuccessful();
});
