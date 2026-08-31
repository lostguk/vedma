<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PromoBannerResource\Pages;
use App\Models\PromoBanner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PromoBannerResource extends Resource
{
    protected static ?string $model = PromoBanner::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'Контент';

    protected static ?string $navigationLabel = 'Скидка на главной';

    protected static ?string $modelLabel = 'объявление о скидке';

    protected static ?string $pluralModelLabel = 'объявления о скидке';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Показ на сайте')
                    ->description('На главной виден только один активный баннер. Если включить этот — остальные выключатся. Пустые даты означают «без ограничений».')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Показывать на главной')
                            ->helperText('Выключите, чтобы скрыть блок, не удаляя текст')
                            ->default(true),
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label('Начало показа')
                            ->native(false)
                            ->seconds(false)
                            ->placeholder('Сразу'),
                        Forms\Components\DateTimePicker::make('ends_at')
                            ->label('Конец показа')
                            ->native(false)
                            ->seconds(false)
                            ->placeholder('Бессрочно')
                            ->after('starts_at'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Текст')
                    ->schema([
                        Forms\Components\TextInput::make('kicker')
                            ->label('Надзаголовок')
                            ->maxLength(80)
                            ->placeholder('Особое предложение'),
                        Forms\Components\TextInput::make('title')
                            ->label('Заголовок')
                            ->required()
                            ->maxLength(120)
                            ->placeholder('Скидка на ритуальные свечи'),
                        Forms\Components\Textarea::make('subtitle')
                            ->label('Описание')
                            ->rows(3)
                            ->maxLength(400)
                            ->placeholder('Короткий текст под заголовком')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Скидка и промокод')
                    ->schema([
                        Forms\Components\TextInput::make('discount_value')
                            ->label('Размер скидки')
                            ->maxLength(16)
                            ->placeholder('−20%')
                            ->helperText('Крупная цифра слева, например −20% или 1+1'),
                        Forms\Components\TextInput::make('discount_caption')
                            ->label('Подпись к скидке')
                            ->maxLength(40)
                            ->placeholder('на свечи'),
                        Forms\Components\TextInput::make('promo_code')
                            ->label('Промокод')
                            ->maxLength(40)
                            ->placeholder('VEDMA20')
                            ->helperText('Показывается на баннере и копируется по нажатию. Чтобы код сработал в корзине, создайте такой же в разделе «Промокоды».'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Кнопка')
                    ->schema([
                        Forms\Components\TextInput::make('button_text')
                            ->label('Текст кнопки')
                            ->maxLength(60)
                            ->placeholder('Выбрать свечи'),
                        Forms\Components\TextInput::make('button_url')
                            ->label('Ссылка')
                            ->maxLength(255)
                            ->placeholder('/catalog')
                            ->helperText('Внутренняя страница (/catalog) или полный URL'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Заголовок')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('discount_value')
                    ->label('Скидка'),
                Tables\Columns\TextColumn::make('promo_code')
                    ->label('Промокод')
                    ->placeholder('—'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активен')
                    ->boolean(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->label('С')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('сразу'),
                Tables\Columns\TextColumn::make('ends_at')
                    ->label('До')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('бессрочно'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Обновлён')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->paginationPageOptions([5, 10, 25, 50])
            ->emptyStateHeading('Нет объявлений о скидке')
            ->emptyStateDescription('Создайте баннер — на главной будет показан только активный, в пределах дат показа.')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPromoBanners::route('/'),
            'create' => Pages\CreatePromoBanner::route('/create'),
            'edit' => Pages\EditPromoBanner::route('/{record}/edit'),
        ];
    }
}
