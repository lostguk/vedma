<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TopicResource\Pages;
use App\Filament\Resources\TopicResource\RelationManagers;
use App\Models\Topic;
use App\Repositories\MessageRepository;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TopicResource extends Resource
{
    protected static ?string $model = Topic::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Темы обращений';

    protected static ?string $modelLabel = 'Тема обращения';

    protected static ?string $pluralModelLabel = 'Темы обращений';

    public static function getNavigationBadge(): ?string
    {
        $user = Filament::auth()->user();

        if (! $user || ! $user->is_admin) {
            return null;
        }

        $count = app(MessageRepository::class)->countUnreadForAdmin();

        return $count > 0 ? (string) $count : null;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Filament::auth()->user();

        if (! $user || ! $user->is_admin) {
            return $query;
        }

        return $query->withCount(['messages as unread_messages_count' => function (Builder $query) {
            $query->whereHas('user', function (Builder $userQuery) {
                $userQuery->where('is_admin', false);
            })->where(function (Builder $query) {
                $query->whereNull('topics.admin_last_read_at')
                    ->orWhereColumn('messages.created_at', '>', 'topics.admin_last_read_at');
            });
        }]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Информация о теме')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Название')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->label('Статус')
                            ->options([
                                'new' => 'Новый',
                                'resolved' => 'Решен',
                                'requires_response' => 'Требует ответа',
                            ])
                            ->required()
                            ->default('new'),
                        Forms\Components\Select::make('user_id')
                            ->label('Пользователь')
                            ->relationship('user', 'email')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->label('Статус')
                    ->options([
                        'new' => 'Новый',
                        'resolved' => 'Решен',
                        'requires_response' => 'Требует ответа',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('unread_messages_count')
                    ->label('Новые сообщения')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Пользователь')
                    ->searchable()
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'new' => 'Новый',
                        'resolved' => 'Решен',
                        'requires_response' => 'Требует ответа',
                    ]),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Пользователь')
                    ->relationship('user', 'email')
                    ->searchable()
                    ->preload(),
            ])
            ->poll('10s')
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
            RelationManagers\MessagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTopics::route('/'),
            'create' => Pages\CreateTopic::route('/create'),
            'edit' => Pages\EditTopic::route('/{record}/edit'),
        ];
    }
}
