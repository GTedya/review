<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Geo;
use App\Models\Order;
use App\Models\OrderLeasingVehicle;
use App\Models\User;
use App\Models\VehicleType;
use App\Utilities\Helpers;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
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

                        Section::make('Лизинг')->relationship('leasing')
                            ->schema([
                                TextInput::make('advance')->label('Аванс')->numeric()->required(function ($get) {
                                    return filled($get('current_lessors'))
                                        || filled($get('months'))
                                        || filled($get('user_comment'))
                                        || filled($get('leasing_vehicles'));
                                }),
                                TextInput::make('current_lessors')->label('Текущие лизингодатели')->nullable(),
                                TextInput::make('months')->label('Срок лизинга')->numeric()->nullable(),
                                TinyEditor::make('user_comment')->label('Комментарий пользователя')->nullable(),

                                Repeater::make('leasing_vehicles')
                                    ->visibleOn('edit')
                                    ->label('Транспортные средства')
                                    ->createItemButtonLabel('Добавить')
                                    ->relationship('vehicles')
                                    ->defaultItems(0)
                                    ->schema([
                                        Select::make('type')
                                            ->required()
                                            ->label('Выберите тип ТС')
                                            ->relationship(
                                                'type',
                                                'name',
                                                function (Builder $query, ?OrderLeasingVehicle $record) {
                                                    $query->withTrashed()
                                                        ->where('deleted_at', null)
                                                        ->orWhere('id', $record?->vehicle_type_id);
                                                }
                                            )
                                            ->getOptionLabelFromRecordUsing(function (VehicleType $record) {
                                                return $record->trashed(
                                                ) ? "{$record->name} (Тип ТС удален)" : $record->name;
                                            })
                                            ->afterStateHydrated(function (?OrderLeasingVehicle $record, $set) {
                                                $set('type', $record?->order_leasing_id);
                                            }),
                                        TextInput::make('vehicle_brand')->label('Марка ТС')->nullable(),
                                        TextInput::make('vehicle_model')->label('Модель ТС')->nullable(),
                                        TextInput::make('vehicle_count')->label('Количество')->numeric()->nullable(),
                                        TextInput::make('vehicle_state')->label('Состояние ТС')->nullable(),
                                    ])
                            ])
                            ->collapsed(),

                        Section::make('Дилер')->relationship('dealer')
                            ->schema([
                                Repeater::make('dealer_vehicles')
                                    ->visibleOn('edit')
                                    ->label('Транспортные средства')
                                    ->createItemButtonLabel('Добавить')
                                    ->relationship('vehicles')
                                    ->defaultItems(0)
                                    ->schema([
                                        Select::make('type')->label('Выберите тип ТС')
                                            ->relationship('type', 'name')
                                            ->required(),
                                        TextInput::make('vehicle_brand')->label('Марка ТС')->nullable(),
                                        TextInput::make('vehicle_model')->label('Модель ТС')->nullable(),
                                        TextInput::make('vehicle_count')->label('Количество')->numeric()->nullable(),
                                    ])
                            ])
                            ->collapsed(),
                    ]),
                ]),

                Grid::make()->columnSpan(1)->schema([
                    Card::make()->schema([
                        Select::make('user_id')
                            ->label('Пользователь')
                            ->relationship('user', 'name', function ($query){
                                $query->whereHas('roles', fn($query) => $query->where('name', 'client'));
                            }),

                        Hidden::make('guest')
                        ->saveRelationshipsUsing(function ($get, $component){
                            if (!filled($get('user_id'))){
                               return User::create(['name'=>'guest', 'password'=>'','email'=>$component['email']]);
                            }
                        }),

                        Select::make('geo_id')
                            ->label('Область')
                            ->relationship('geo', 'name', function (Builder $query, ?Order $record) {
                                $query->withTrashed()->where('deleted_at', null)->orWhere('id', $record?->geo_id);
                            })
                            ->getOptionLabelFromRecordUsing(function (Geo $record) {
                                return $record->trashed() ? "{$record->name} (область удалена)" : $record->name;
                            }),

                        DateTimePicker::make('created_at')
                            ->label('Дата создания')
                            ->default('now')
                            ->displayFormat('Y-m-d H:i:s'),
                    ]),

                    Card::make()->columns(1)->schema([
                        Select::make('status')
                            ->label('Статус')
                            ->relationship('status', 'name')
                            ->default(1)
                            ->required(),

                        DateTimePicker::make('end_date')
                            ->label('Дата окончания')
                            ->displayFormat('Y-m-d H:i:s'),

                        Select::make('user_ban')
                            ->label('Заблокировать доступ')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->relationship('banUsers', 'name', function (Builder $query) {
                                $query->where('id', '!=', Auth::id())->whereHas('roles', function ($q) {
                                    $q->where('name', 'dealer_manager')->orWhere('name', 'leasing_manager');
                                });
                            }),
                    ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fio')->label('ФИО')->sortable()->searchable(),
                TextColumn::make('end_date')->label('Дата окончания')->sortable(),
                TextColumn::make('geo.name')->label('Область')
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->whereHas('geo', fn(Builder $q) => $q->withTrashed()->where('name', 'like', "%$search%")
                        );
                    })
                    ->getStateUsing(fn(Order $record) => $record->geo()->withTrashed()->first()?->name ?? ''),
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
