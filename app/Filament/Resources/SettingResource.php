<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $modelLabel = 'Настройки';
    protected static ?string $pluralModelLabel = 'Настройки';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Сайт')->collapsible()->schema([
                    Fieldset::make('Стандартное изображение')->columns(1)->schema([
                        SpatieMediaLibraryFileUpload::make('og_image')
                            ->image()
                            ->required()
                            ->enableOpen()
                            ->disableLabel()
                            ->directory('form-tmp')
                            ->collection('og_image')
                            ->panelLayout('integrated')
                            ->label('Стандартное изображение'),
                    ]),
                ]),

                Section::make('Связь')->collapsible()->schema([
                    Grid::make()->schema([
                        TextInput::make('phone')
                            ->label('Телефон')
                            ->required(),
                        TextInput::make('email')->label('Email'),
                    ]),
                ]),

                Section::make('Соц. сети')->collapsible()->schema([
                    Grid::make()->schema([
                        TextInput::make('telegram')->label('Telegram'),
                        TextInput::make('vk')->label('ВКонтакте'),
                        TextInput::make('app_store')->label('App Store'),
                        TextInput::make('google_play')->label('Google Play'),
                    ]),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\EditSetting::route('/'),
        ];
    }
}
