<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_корзина_требует_авторизацию(): void
    {
        $this->getJson('/api/v1/cart')->assertUnauthorized();
        $this->putJson('/api/v1/cart', ['items' => []])->assertUnauthorized();
        $this->postJson('/api/v1/cart/merge', ['items' => []])->assertUnauthorized();
        $this->deleteJson('/api/v1/cart')->assertUnauthorized();
    }

    public function test_сохраняет_корзину_и_отдаёт_актуальную_цену(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->withStock(8)->create([
            'name' => 'Свеча',
            'price' => 250,
            'old_price' => 300,
        ]);
        Sanctum::actingAs($user);

        $this->putJson('/api/v1/cart', [
            'items' => [
                ['id' => $product->id, 'quantity' => 2],
            ],
        ])->assertOk()
            ->assertJsonPath('data.items.0.id', $product->id)
            ->assertJsonPath('data.items.0.qty', 2)
            ->assertJsonPath('data.items.0.name', 'Свеча')
            ->assertJsonPath('data.items.0.price', 250)
            ->assertJsonPath('data.removed', []);

        $product->update(['price' => 270]);

        $this->getJson('/api/v1/cart')
            ->assertOk()
            ->assertJsonPath('data.items.0.price', 270)
            ->assertJsonPath('data.items.0.qty', 2);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_замена_убирает_позиции_которых_нет_в_запросе(): void
    {
        $user = User::factory()->create();
        $first = Product::factory()->withStock(5)->create();
        $second = Product::factory()->withStock(5)->create();
        Sanctum::actingAs($user);

        $this->putJson('/api/v1/cart', [
            'items' => [
                ['id' => $first->id, 'quantity' => 1],
                ['id' => $second->id, 'quantity' => 1],
            ],
        ])->assertOk();

        $this->putJson('/api/v1/cart', [
            'items' => [
                ['id' => $second->id, 'quantity' => 3],
            ],
        ])->assertOk()
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.id', $second->id)
            ->assertJsonPath('data.items.0.qty', 3);

        $this->assertDatabaseMissing('cart_items', [
            'product_id' => $first->id,
        ]);
    }

    public function test_слияние_складывает_количество_и_обрезает_по_остатку(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->withStock(3)->create();
        $other = Product::factory()->withStock(4)->create();
        Sanctum::actingAs($user);

        $this->putJson('/api/v1/cart', [
            'items' => [
                ['id' => $product->id, 'quantity' => 2],
            ],
        ])->assertOk();

        $this->postJson('/api/v1/cart/merge', [
            'items' => [
                ['id' => $product->id, 'quantity' => 2],
                ['id' => $other->id, 'quantity' => 1],
            ],
        ])->assertOk()
            ->assertJsonPath('data.items.0.qty', 3)
            ->assertJsonPath('data.items.1.id', $other->id)
            ->assertJsonPath('data.items.1.qty', 1);
    }

    public function test_недоступный_товар_не_сохраняется(): void
    {
        $user = User::factory()->create();
        $gone = Product::factory()->withStock(0)->create();
        $ok = Product::factory()->unlimitedStock()->create();
        Sanctum::actingAs($user);

        $missingId = $ok->id + 1000;

        $this->putJson('/api/v1/cart', [
            'items' => [
                ['id' => $gone->id, 'quantity' => 1],
                ['id' => $missingId, 'quantity' => 1],
                ['id' => $ok->id, 'quantity' => 2],
            ],
        ])->assertOk()
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.id', $ok->id)
            ->assertJsonPath('data.items.0.qty', 2)
            ->assertJsonFragment(['id' => $gone->id, 'reason' => 'unavailable'])
            ->assertJsonFragment(['id' => $missingId, 'reason' => 'unavailable']);

        $this->assertDatabaseMissing('cart_items', [
            'product_id' => $gone->id,
        ]);
    }

    public function test_чтение_убирает_товар_который_закончился(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->withStock(5)->create();
        Sanctum::actingAs($user);

        $this->putJson('/api/v1/cart', [
            'items' => [
                ['id' => $product->id, 'quantity' => 4],
            ],
        ])->assertOk();

        $product->update(['stock' => 0]);

        $this->getJson('/api/v1/cart')
            ->assertOk()
            ->assertJsonPath('data.items', [])
            ->assertJsonFragment(['id' => $product->id, 'reason' => 'unavailable']);

        $this->assertDatabaseMissing('cart_items', [
            'product_id' => $product->id,
        ]);
    }

    public function test_корзины_пользователей_изолированы(): void
    {
        $first = User::factory()->create();
        $second = User::factory()->create();
        $product = Product::factory()->withStock(5)->create();

        Sanctum::actingAs($first);
        $this->putJson('/api/v1/cart', [
            'items' => [
                ['id' => $product->id, 'quantity' => 2],
            ],
        ])->assertOk();

        Sanctum::actingAs($second);
        $this->getJson('/api/v1/cart')
            ->assertOk()
            ->assertJsonPath('data.items', []);
    }

    public function test_очистка_корзины(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->withStock(5)->create();
        Sanctum::actingAs($user);

        $this->putJson('/api/v1/cart', [
            'items' => [
                ['id' => $product->id, 'quantity' => 1],
            ],
        ])->assertOk();

        $this->deleteJson('/api/v1/cart')
            ->assertOk()
            ->assertJsonPath('data.items', []);

        $this->assertSame(0, CartItem::query()->count());
    }

    public function test_заказ_авторизованного_пользователя_очищает_только_его_корзину(): void
    {
        $buyer = User::factory()->create();
        $other = User::factory()->create();
        $product = Product::factory()->withStock(10)->create(['price' => 100]);
        $category = Category::factory()->create(['exclude_from_shipping' => true]);
        $product->categories()->attach($category->id);

        Sanctum::actingAs($other);
        $this->putJson('/api/v1/cart', [
            'items' => [
                ['id' => $product->id, 'quantity' => 1],
            ],
        ])->assertOk();

        Sanctum::actingAs($buyer);
        $this->putJson('/api/v1/cart', [
            'items' => [
                ['id' => $product->id, 'quantity' => 2],
            ],
        ])->assertOk();

        $this->postJson('/api/v1/order', [
            'items' => [
                ['id' => $product->id, 'count' => 2],
            ],
            'register' => false,
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => $buyer->email,
            'delivery_type' => 'PostOffice',
            'address' => 'Москва, ул. Пример, д. 1',
        ])->assertCreated();

        $this->getJson('/api/v1/cart')
            ->assertOk()
            ->assertJsonPath('data.items', []);

        Sanctum::actingAs($other);
        $this->getJson('/api/v1/cart')
            ->assertOk()
            ->assertJsonPath('data.items.0.qty', 1);
    }

    public function test_некорректное_количество_отклоняется(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->putJson('/api/v1/cart', [
            'items' => [
                ['id' => 1, 'quantity' => 0],
            ],
        ])->assertUnprocessable();
    }
}
