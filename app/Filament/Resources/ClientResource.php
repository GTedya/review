<?php

namespace App\Filament\Resources;

use App\Filament\BaseResource\UserResource;
use App\Filament\Resources\ClientResource\Pages;
use App\Models\Company;
use App\Models\Geo;
use App\Models\UserFileType;
use App\Rules\InnSize;
use App\Services\DadataService;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Illuminate\Database\Eloquent\Builder;

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
        $dadata = new DadataService();

        return $form->columns(3)->schema([
            ...static::baseFields(),
            Section::make('О компании')->collapsed()->relationship('company')->schema([
                TextInput::make('inn')
                    ->label('ИНН')
                    ->required()
                    ->rule(new InnSize())
                    ->debounce('600ms')
                    ->rule(function (?string $state, callable $fail) use ($dadata){
                        $data = $dadata->dadataCompanyInfo($state);
                            if (blank($data)){
                                $fail('ИНН не найден');
                            };
                    })
                    ->afterStateUpdated(function (callable $set, ?string $state, TextInput $component) use ($dadata) {
                        $data = $dadata->dadataCompanyInfo($state);
                        if (blank($data)) {
                            $set('org_name', '');
                            $set('org_type', null);
                            $set('geo_id', null);
                            return true;
                        }
                        $set('org_name', $data['org_name']);
                        $set('org_type', $data['org_type']);
                        $set('geo_id', $data['geo_id']);
                    }),

                TextInput::make('org_name')
                    ->label('Название организации'),

                Radio::make('org_type')
                    ->label('Тип организации')
                    ->options(UserFileType::ORG_TYPES)
                    ->required()
                    ->reactive(),

                Select::make('geo_id')
                    ->label('Область')
                    ->relationship('geo', 'name', function (Builder $query, ?Company $record) {
                        $query->doesntHave('children')->withTrashed()->where('deleted_at', null)->orWhere(
                            'id',
                            $record?->geo_id
                        );
                    })
                    ->getOptionLabelFromRecordUsing(function (Geo $record, $get) {
                        return $record->trashed() ? "{$record->name} (область удалена)" : $record->name;
                    }),
            ]),

            Section::make('Файлы')->collapsed()->schema([
                Repeater::make('files')
                    ->disableLabel()
                    ->disableItemMovement()
                    ->relationship('files')
                    ->createItemButtonLabel('Добавить')
                    ->schema([
                        Select::make('type_id')
                            ->label('Тип файлов')
                            ->reactive()
                            ->relationship('type', 'name', function (Builder $query, callable $get) {
                                $query->whereJsonContains('org_type', $get('data.company.org_type', true))->get();
                            })
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
