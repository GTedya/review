<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $modelLabel = 'Страница';
    protected static ?string $pluralModelLabel = 'Страницы';

    public static function form(Form $form): Form
    {
        return $form->columns(3)->schema([
            Grid::make()->columnSpan(2)->schema([
                Card::make()->schema([
                    Grid::make()->schema([
                        TextInput::make('title')
                            ->label('Название')
                            ->reactive()
                            ->afterStateUpdated(static::getNameChangedCallback())
                            ->required(),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->disabled(function (?Page $record) {
                                return $record?->template == 'main';
                            })
                            ->dehydrated(function (?Page $record) {
                                return $record?->template != 'main';
                            })
                            ->unique(ignoreRecord: true)
                            ->required(),


                        Fieldset::make('Мета поля')->columns(1)->schema([
                            TextInput::make('meta.title')->label('title'),
                            TextInput::make('meta.description')->label('description'),
                        ]),
                    ]),
                ]),

            ]),
            Grid::make()->columnSpan(1)->schema([
                Card::make()->schema([
                    Select::make('template')
                        ->disabledOn('edit')
                        ->label('Шаблон')
                        ->default('default')
                        ->options(function (string $context) {
                            return ($context == 'create') ? Page::CAN_CREATE : Page::NAMES;
                        }),
                    DateTimePicker::make('created_at')
                        ->label('Дата создания')
                        ->default('now')
                        ->displayFormat('Y-m-d H:i:s')
                    ,
                ]),
            ])
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('template')->label('Шаблон')->getStateUsing(function (Page $record) {
                    return Page::NAMES[$record->template];
                })->sortable()->searchable(),
                TextColumn::make('slug')->label('Ссылка')->sortable()->searchable(),
                TextColumn::make('title')->label('Название')->sortable()->searchable(),
                TextColumn::make('created_at')->label('Дата создания')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
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

    private static function getNameChangedCallback(): callable
    {
        return function ($state, callable $set, string $context) {
            if ($context === 'create') {
                $set('slug', Str::slug($state));
            }
        };
    }
}
