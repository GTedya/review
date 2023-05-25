<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuGroupResource\Pages;
use App\Models\MenuGroup;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class MenuGroupResource extends Resource
{
    protected static ?string $model = MenuGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $modelLabel = 'Группа меню';
    protected static ?string $pluralModelLabel = 'Группы меню';

    public static function form(Form $form): Form
    {
        return $form->columns(3)->schema([
            Card::make()->columnSpan(2)->schema([
                TextInput::make('name')
                    ->label('Название группы')
                    ->required(),
            ]),

            Card::make()->columnSpan(1)->schema([
                TextInput::make('sort_index')
                    ->label('Индекс сортировки')
                    ->default(500)
                    ->required(),

                Checkbox::make('is_bottom')
                    ->label('Нижнее меню')
                    ->default(false)
                    ->reactive(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('sort_index')->label('Индекс сортировки')->sortable(),
            TextColumn::make('name')->label('Название группы')->limit(20)->sortable()->searchable(),
            TextColumn::make('Позиция')->sortable()->getStateUsing(function (MenuGroup $record) {
                return $record->is_bottom ? 'Нижнее меню' : 'Верхнее меню';
            }),
        ])->filters([
            //
        ])->actions([
            Tables\Actions\EditAction::make(),
        ])->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ])->defaultSort('sort_index', 'desc');
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
            'index' => Pages\ListMenuGroups::route('/'),
            'create' => Pages\CreateMenuGroup::route('/create'),
            'edit' => Pages\EditMenuGroup::route('/{record}/edit'),
        ];
    }
}

