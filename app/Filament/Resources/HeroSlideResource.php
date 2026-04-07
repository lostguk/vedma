<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\HeroSlideResource\Pages;
use App\Models\HeroSlide;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HeroSlideResource extends Resource
{
    protected static ?string $model = HeroSlide::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Контент';

    protected static ?string $navigationLabel = 'Слайдер на главной';

    protected static ?string $modelLabel = 'Слайд';

    protected static ?string $pluralModelLabel = 'Слайды';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Содержимое слайда')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Заголовок')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ритуальные свечи'),
                        Forms\Components\TextInput::make('accent')
                            ->label('Акцент (выделенная часть)')
                            ->maxLength(255)
                            ->placeholder('ручной работы')
                            ->helperText('Текст после заголовка, выделяется другим стилем'),
                        Forms\Components\Textarea::make('subtitle')
                            ->label('Подзаголовок')
                            ->rows(2)
                            ->maxLength(500)
                            ->placeholder('Каждая свеча создаётся с намерением...'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Кнопка')
                    ->schema([
                        Forms\Components\TextInput::make('button_text')
                            ->label('Текст кнопки')
                            ->maxLength(255)
                            ->placeholder('Смотреть свечи'),
                        Forms\Components\TextInput::make('button_url')
                            ->label('Ссылка кнопки')
                            ->maxLength(255)
                            ->placeholder('/catalog'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Изображение')
                    ->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Фоновое изображение слайда')
                            ->image()
                            ->directory('hero-slides')
                            ->imageEditor()
                            ->previewable()
                            ->helperText('Рекомендуемый размер: 1920×900px'),
                    ]),

                Forms\Components\Section::make('Настройки')
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Порядок сортировки')
                            ->numeric()
                            ->default(0),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Активен')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('№')
                    ->sortable()
                    ->width(50),
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Картинка')
                    ->disk('public')
                    ->width(80)
                    ->height(45),
                Tables\Columns\TextColumn::make('title')
                    ->label('Заголовок')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('accent')
                    ->label('Акцент')
                    ->limit(30),
                Tables\Columns\TextColumn::make('button_text')
                    ->label('Кнопка')
                    ->limit(20),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активен')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Обновлён')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
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
            'index' => Pages\ListHeroSlides::route('/'),
            'create' => Pages\CreateHeroSlide::route('/create'),
            'edit' => Pages\EditHeroSlide::route('/{record}/edit'),
        ];
    }
}
