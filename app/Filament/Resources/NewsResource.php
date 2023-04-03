<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Models\News;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $modelLabel = 'Новость';
    protected static ?string $pluralModelLabel = 'Новости';

    public static function form(Form $form): Form
    {
        return $form->columns(3)->schema([
            Grid::make()->columnSpan(2)->schema([

                Card::make()->schema([
                    Grid::make()->schema([
                        TextInput::make('title')
                            ->label('Название')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(static::getNameChangedCallback()),

                        TextInput::make('slug')
                            ->label('Ссылка')
                            ->required(),
                    ]),


                    Fieldset::make('Мета поля')->columns(1)->schema([
                        TextInput::make('meta.title')->label('title'),
                        TextInput::make('meta.description')->label('description'),
                    ]),

                    TinyEditor::make('content')->label('Контент'),

                    Fieldset::make('Изображение')->columns(1)->schema([
                        SpatieMediaLibraryFileUpload::make('image')
                            ->image()
                            ->enableOpen()
                            ->disableLabel()
                            ->responsiveImages()
                            ->label('Изображение')
                            ->directory('form-tmp')
                            ->collection('image')
                            ->panelLayout('integrated'),
                    ]),

                    Repeater::make('files')->label('Файлы')
                        ->createItemButtonLabel('Добавить')
                        ->relationship('files')
                        ->schema([
                            TextInput::make('text')->label('Текст файла')->required(),

                            SpatieMediaLibraryFileUpload::make('file')
                                ->required()
                                ->enableOpen()
                                ->label('Файл')
                                ->directory('form-tmp')
                        ]),
                ]),
            ]),

            Grid::make()->columnSpan(1)->schema([
                Card::make()->schema([
                    DateTimePicker::make('created_at')
                        ->label('Дата создания')
                        ->default('now')
                        ->displayFormat('Y-m-d H:i:s'),
                ]),

                Section::make('Интервал')->columns(1)->schema([
                    DateTimePicker::make('start_date')
                        ->label('Дата начала')
                        ->displayFormat('Y-m-d H:i:s'),

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
                TextColumn::make('title')->label('Название')->sortable()->searchable(),
                TextColumn::make('created_at')->label('Дата создания')->sortable(),
                TextColumn::make('start_date')->label('Дата начала')->sortable(),
                TextColumn::make('end_date')->label('Дата конца')->sortable(),
                SpatieMediaLibraryImageColumn::make('media')->label('Изображение')->collection('image'),
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
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
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
