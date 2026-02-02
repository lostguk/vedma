<?php

declare(strict_types=1);

use Tests\TestCase;

uses(TestCase::class);

it('has russian labels for filament edit and delete actions', function () {
    expect(trans('filament.actions.edit.label'))->toBe('Изменить');
    expect(trans('filament.actions.delete.label'))->toBe('Удалить');
});
