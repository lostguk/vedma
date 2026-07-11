<?php

declare(strict_types=1);

use Illuminate\Support\Facades\URL;
use Tests\TestCase;

uses(TestCase::class);

it('stores livewire temporary uploads on the public disk', function (): void {
    expect(config('livewire.temporary_file_upload.disk'))->toBe('public')
        ->and(config('livewire.temporary_file_upload.directory'))->toBe('livewire-tmp');
});

it('disables media library session affinity by default', function (): void {
    expect(config('media-library.enable_temporary_uploads_session_affinity'))->toBeFalse();
});

it('disables temporary upload thumbnails by default', function (): void {
    expect(config('media-library.generate_thumbnails_for_temporary_uploads'))->toBeFalse();
});

it('forces generated urls from app url', function (): void {
    URL::forceRootUrl('https://vedminozelie.ru');
    URL::forceScheme('https');

    expect(URL::to('/livewire/upload-file'))->toBe('https://vedminozelie.ru/livewire/upload-file');
});
