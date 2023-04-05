<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Utilities\Helpers;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $modelLabel = 'Заказ';
    protected static ?string $pluralModelLabel = 'Заказы';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Grid::make()->columnSpan(2)->schema([
                    Card::make()->schema([
                        TextInput::make('fio')
                            ->label('Имя')
                            ->required(),


                        TextInput::make('inn')
                            ->label('ИНН'),

                        TextInput::make('org_name')
                            ->label('Название организации'),

                        TextInput::make('phone')
                            ->label('Номер телефона')
                            ->required()
                            ->minLength(10)
                            ->dehydrateStateUsing(function ($state) {
                                return Helpers::getCleanPhone($state);
                            }),

                        TextInput::make('email')
                            ->label('Email')
                            ->required(),

                        TinyEditor::make('admin_comment')->label('Коментарий администратора'),
                    ]),
                ]),

                Grid::make()->columnSpan(1)->schema([
                    Section::make('Данные пользователя')->columns(1)->schema([
                        TextInput::make('user_id')
                            ->label('Пользователь')
                            ->numeric()
                            ->required(),

                        Select::make('geo_id')
                            ->label('Область')
                            ->relationship('geo', 'name'),
                    ]),

                    Section::make('Дополнительная информация')->columns(1)->schema([
                        Select::make('status')
                            ->label('Статус')
                            ->relationship('status', 'name')
                            ->default(1)
                            ->required(),

                        DateTimePicker::make('end_date')
                            ->label('Дата окончания')
                            ->displayFormat('Y-m-d H:i:s'),
                    ]),
                ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('ФИО')->sortable()->searchable(),
                TextColumn::make('end_date')->label('Дата окончания')->sortable(),
                TextColumn::make('geo.name')->label('Область')
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->whereHas('geo', fn(Builder $q) => $q->withTrashed()->where('name', 'like', "%$search%")
                        );
                    })
                    ->getStateUsing(fn(Order $record) => $record->geo()->withTrashed()->first()->name),
                TextColumn::make('created_at')->label('Дата создания заказа')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
