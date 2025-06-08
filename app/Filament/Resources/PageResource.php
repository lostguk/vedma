<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Страницы';

    protected static ?string $pluralLabel = 'Страницы';

    protected static ?string $label = 'Страница';

    protected static ?string $navigationGroup = 'Контент';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Заголовок')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->label('Описание')
                    ->maxLength(255),
                Forms\Components\Checkbox::make('is_visible_in_header')
                    ->label('Показывать в шапке'),
                Forms\Components\Checkbox::make('is_visible_in_footer')
                    ->label('Показывать в футере'),
                Forms\Components\RichEditor::make('text')
                    ->label('Текст')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('title')->label('Заголовок')->searchable(),
                Tables\Columns\TextColumn::make('description')->label('Описание')->limit(50),
                Tables\Columns\TextColumn::make('text')->label('Текст')->limit(50),
                Tables\Columns\IconColumn::make('is_visible_in_header')
                    ->label('Показывать в шапке')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_visible_in_footer')
                    ->label('Показывать в футере')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('Создано')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
