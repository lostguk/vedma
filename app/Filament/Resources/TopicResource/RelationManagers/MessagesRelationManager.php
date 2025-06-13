<?php

namespace App\Filament\Resources\TopicResource\RelationManagers;

use App\Models\Message;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;

class MessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';

    protected static ?string $title = 'Сообщения';

    protected static ?string $modelLabel = 'Сообщение';

    protected static ?string $pluralModelLabel = 'Сообщения';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Информация о сообщении')
                    ->schema([
                        Forms\Components\Textarea::make('content')
                            ->label('Содержание')
                            ->required()
                            ->rows(5),
                        Forms\Components\Select::make('user_id')
                            ->label('Пользователь')
                            ->relationship('user', 'email')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),
                Forms\Components\Section::make('Вложения')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('attachments')
                            ->label('Файлы')
                            ->collection(Message::ATTACHMENTS_COLLECTION)
                            ->multiple()
                            ->disk('public')
                            ->directory('message_attachments')
                            ->visibility('public')
                            ->maxSize(5120) // 5MB
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf'])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('content')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('content')
                    ->label('Содержание')
                    ->limit(100)
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Пользователь')
                    ->searchable()
                    ->sortable(),
                SpatieMediaLibraryImageColumn::make('attachments')
                    ->label('Вложения')
                    ->collection(Message::ATTACHMENTS_COLLECTION)
                    ->circular()
                    ->stacked()
                    ->limit(3),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Обновлено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Пользователь')
                    ->relationship('user', 'email')
                    ->searchable()
                    ->preload(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
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
}
