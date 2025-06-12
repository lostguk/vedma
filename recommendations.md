# Рекомендации по улучшению архитектуры проекта

## 1. Структура слоев приложения

### Текущее состояние
Проект имеет хорошую базовую структуру с разделением на контроллеры, сервисы, репозитории и модели. Используется паттерн Repository для работы с данными.

### Рекомендации
1. **Внедрить слой DTO (Data Transfer Objects)**
   - Создать отдельные объекты для передачи данных между слоями
   - Отделить структуры данных API от внутренних моделей
   - Пример: `app/DTO/OrderDTO.php` для передачи данных о заказе

2. **Добавить слой фасадов**
   - Создать фасады для упрощения доступа к сложным подсистемам
   - Пример: `app/Facades/PaymentFacade.php` для работы с различными платежными системами

3. **Выделить слой доменной логики**
   - Создать папку `app/Domain` для бизнес-логики, не зависящей от фреймворка
   - Перенести ключевую бизнес-логику из сервисов в доменные классы

## 2. Разделение ответственности

### Текущее состояние
Сервисы содержат бизнес-логику, репозитории отвечают за доступ к данным. Однако некоторые сервисы (например, OrderService) содержат слишком много ответственности.

### Рекомендации
1. **Разделить крупные сервисы на более мелкие**
   - Выделить `PaymentService` из `OrderService`
   - Создать `DeliveryService` для логики доставки

2. **Использовать CQRS (Command Query Responsibility Segregation)**
   - Разделить операции чтения и записи
   - Создать структуру `app/Commands` и `app/Queries`
   - Пример: `app/Commands/CreateOrderCommand.php` и `app/Queries/GetUserOrdersQuery.php`

3. **Внедрить медиаторы для обработки команд**
   - Создать `app/Mediator/CommandBus.php` для маршрутизации команд к обработчикам
   - Уменьшить связанность между компонентами системы

## 3. Применение паттернов проектирования

### Текущее состояние
Проект уже использует некоторые паттерны (Repository, Service). Однако можно расширить применение паттернов для улучшения архитектуры.

### Рекомендации
1. **Внедрить паттерн Стратегия**
   - Для различных алгоритмов расчета стоимости заказа
   - Пример: `app/Strategies/PricingStrategy/RegularPricingStrategy.php`, `PromotionalPricingStrategy.php`

2. **Использовать паттерн Наблюдатель для событий**
   - Создать систему событий для заказов
   - Пример: `app/Events/OrderCreated.php`, `app/Listeners/SendOrderConfirmation.php`

3. **Применить паттерн Состояние для заказов**
   - Моделировать различные состояния заказа как отдельные классы
   - Пример: `app/States/OrderState/NewOrderState.php`, `ProcessingOrderState.php`

4. **Внедрить паттерн Спецификация**
   - Для инкапсуляции бизнес-правил и условий
   - Пример: `app/Specifications/OrderCanBeCancelled.php`

## 4. Организация бизнес-логики

### Текущее состояние
Бизнес-логика распределена между сервисами и контроллерами. Некоторые бизнес-правила встроены непосредственно в код.

### Рекомендации
1. **Выделить бизнес-правила в отдельные классы**
   - Создать `app/Rules` для бизнес-правил
   - Пример: `app/Rules/OrderDiscountRule.php`

2. **Внедрить Value Objects для бизнес-понятий**
   - Создать `app/ValueObjects` для таких понятий как Money, Address, Email
   - Пример: `app/ValueObjects/Money.php` с валидацией и операциями

3. **Использовать доменные события**
   - Создать `app/DomainEvents` для событий бизнес-домена
   - Отделить их от событий фреймворка
   - Пример: `app/DomainEvents/OrderShipped.php`

4. **Внедрить агрегаты для сложных доменных объектов**
   - Создать `app/Aggregates` для объектов, управляющих группой связанных сущностей
   - Пример: `app/Aggregates/OrderAggregate.php`

## 5. Масштабируемость архитектуры

### Текущее состояние
Проект имеет хорошую базовую структуру, но может столкнуться с проблемами при масштабировании.

### Рекомендации
1. **Внедрить модульную архитектуру**
   - Разделить приложение на модули по бизнес-доменам
   - Создать структуру `app/Modules/Orders`, `app/Modules/Catalog` и т.д.
   - Каждый модуль содержит свои контроллеры, сервисы, репозитории

2. **Использовать очереди для асинхронных операций**
   - Перенести тяжелые операции в очереди
   - Пример: обработка заказов, отправка email, генерация отчетов

3. **Внедрить кэширование на уровне репозиториев**
   - Добавить декораторы для репозиториев с кэшированием
   - Пример: `app/Repositories/Cache/CachedProductRepository.php`

4. **Подготовить архитектуру к микросервисам**
   - Четко определить границы доменов
   - Минимизировать зависимости между доменами
   - Использовать API Gateway для коммуникации между потенциальными микросервисами

## 6. Примеры реализации

### Пример Value Object
```php
namespace App\ValueObjects;

final class Money
{
    private float $amount;
    private string $currency;

    public function __construct(float $amount, string $currency = 'RUB')
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Amount cannot be negative');
        }
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function add(Money $money): self
    {
        if ($this->currency !== $money->currency) {
            throw new \InvalidArgumentException('Cannot add money with different currencies');
        }
        return new self($this->amount + $money->amount, $this->currency);
    }

    public function multiply(float $multiplier): self
    {
        return new self($this->amount * $multiplier, $this->currency);
    }

    public function format(): string
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }
}
```

### Пример DTO
```php
namespace App\DTO;

final class OrderDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $status,
        public readonly float $totalPrice,
        public readonly string $customerName,
        public readonly string $customerEmail,
        public readonly ?string $customerPhone,
        public readonly array $items,
        public readonly ?string $promoCode = null,
        public readonly ?string $deliveryType = null,
        public readonly ?float $deliveryPrice = null,
    ) {}

    public static function fromModel(\App\Models\Order $order): self
    {
        return new self(
            id: $order->id,
            status: $order->status,
            totalPrice: $order->total_price,
            customerName: $order->first_name . ' ' . $order->last_name,
            customerEmail: $order->email,
            customerPhone: $order->phone,
            items: $order->items->map(fn($item) => [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'count' => $item->count,
                'total' => $item->total,
            ])->toArray(),
            promoCode: $order->promoCode?->code,
            deliveryType: $order->delivery_type,
            deliveryPrice: $order->delivery_price,
        );
    }
}
```

### Пример Command и Handler
```php
namespace App\Commands;

final class CreateOrderCommand
{
    public function __construct(
        public readonly array $items,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly ?string $phone = null,
        public readonly ?string $promoCode = null,
        public readonly ?string $deliveryType = null,
    ) {}
}

namespace App\Handlers;

use App\Commands\CreateOrderCommand;
use App\Services\OrderService;

final class CreateOrderHandler
{
    public function __construct(
        private OrderService $orderService,
    ) {}

    public function handle(CreateOrderCommand $command): int
    {
        $orderData = [
            'items' => $command->items,
            'first_name' => $command->firstName,
            'last_name' => $command->lastName,
            'email' => $command->email,
            'phone' => $command->phone,
            'promo_code' => $command->promoCode,
            'delivery_type' => $command->deliveryType,
        ];

        $order = $this->orderService->createOrder($orderData);
        return $order->id;
    }
}
```

## 7. План внедрения

1. **Краткосрочные улучшения (1-2 недели)**
   - Внедрить DTO для API ответов
   - Выделить Value Objects для основных бизнес-понятий
   - Добавить события для ключевых бизнес-операций

2. **Среднесрочные улучшения (1-2 месяца)**
   - Разделить крупные сервисы на более мелкие
   - Внедрить CQRS для основных операций
   - Добавить кэширование на уровне репозиториев

3. **Долгосрочные улучшения (3+ месяцев)**
   - Перейти к модульной архитектуре
   - Подготовить инфраструктуру для микросервисов
   - Внедрить асинхронную обработку для тяжелых операций
