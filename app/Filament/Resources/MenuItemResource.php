<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Models\MenuGroup;
use App\Models\MenuItem;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-menu';

    protected static ?string $modelLabel = 'Элемент меню';
    protected static ?string $pluralModelLabel = 'Элементы меню';

    public static function form(Form $form): Form
    {
        return $form->columns(3)->schema([
            Card::make()->columnSpan(2)->schema([
                TextInput::make('name')
                    ->label('Название')
                    ->required(),

                TextInput::make('link')
                    ->label('Ссылка')
                    ->required(),

                Select::make('group_id')
                    ->label('Группа')
                    ->required()
                    ->relationship('group', 'name')
                    ->getOptionLabelFromRecordUsing(function (MenuGroup $record) {
                        return $record->is_bottom ? "{$record->name} (нижнее меню)" : $record->name;
                    }),
            ]),

            Card::make()->columnSpan(1)->schema([
                TextInput::make('sort_index')
                    ->label('Индекс сортировки')
                    ->default(500)
                    ->required(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_index')->label('Индекс сортировки')->sortable(),
                TextColumn::make('name')->label('Название')->sortable()->searchable(),
                TextColumn::make('link')->label('Ссылка')->sortable(),
                TextColumn::make('group.name')->label('Группа')->sortable()
                    ->getStateUsing(function (MenuItem $record) {
                        $group = $record->group;
                        return $group->is_bottom ? "{$group->name} (нижнее меню)" : $group->name;
                    }),
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
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
