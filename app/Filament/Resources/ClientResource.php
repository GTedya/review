<?php

namespace App\Filament\Resources;

use App\Filament\BaseResource\UserResource;
use App\Filament\Resources\ClientResource\Pages;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Form;

class ClientResource extends UserResource
{
    protected static ?string $slug = 'clients';

    protected static ?string $modelLabel = 'Клиент';
    protected static ?string $pluralModelLabel = 'Клиенты';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $role = 'client';
    protected static ?int $role_id = 4;

    public static function form(Form $form): Form
    {
        return $form->columns(3)->schema([
            ...static::baseFields(),

            Section::make('Файлы')->collapsed()->schema([
                Repeater::make('files')
                    ->disableLabel()
                    ->disableItemMovement()
                    ->relationship('files')
                    ->createItemButtonLabel('Добавить')
                    ->schema([
                        Select::make('type_id')
                            ->label('Тип файлов')
                            ->relationship('type', 'name', fn($query) => $query->orderBy('id'))
                            ->required(),

                        SpatieMediaLibraryFileUpload::make('files')
                            ->label('Файлы')
                            ->collection('default')
                            ->directory('form-tmp')
                            ->enableDownload()
                            ->enableOpen()
                            ->multiple()
                            ->required(),
                    ])
                    ->rules([
                        function () {
                            return function (string $attribute, $value, callable $fail) {
                                $types = [];
                                foreach ($value as $item) {
                                    $id = $item['type_id'];
                                    if ($types[$id] ?? false) {
                                        return $fail(
                                            'Один или более тип файлов повторяется. Перепроверьте указанные значения.'
                                        );
                                    }
                                    $types[$id] = true;
                                }
                                return true;
                            };
                        }
                    ]),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
