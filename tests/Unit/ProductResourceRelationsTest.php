<?php

declare(strict_types=1);

use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductResource\Pages\EditProduct;
use App\Filament\Resources\ProductResource\RelationManagers\RelatedRelationManager;
use App\Models\Product;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('registers related products relation manager', function () {
    expect(ProductResource::getRelations())
        ->toContain(RelatedRelationManager::class);
});

it('shows attach action for related products', function () {
    $user = User::factory()->state(['is_admin' => true])->create();
    $product = Product::factory()->create();
    Product::factory()->create();

    Filament::setCurrentPanel(Filament::getPanel('admin'));

    $this->actingAs($user);

    Livewire::test(RelatedRelationManager::class, [
        'ownerRecord' => $product,
        'pageClass' => EditProduct::class,
    ])->assertTableActionExists('attach');
});
