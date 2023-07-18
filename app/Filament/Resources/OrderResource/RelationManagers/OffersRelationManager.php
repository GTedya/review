<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\ManagerOffer;
use App\Models\User;
use Exception;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Builder;

class OffersRelationManager extends RelationManager
{
    protected static string $relationship = 'offers';
    protected static ?string $pluralModelLabel = 'Коммерческие предложения';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('user_id')
                ->label('Менеджер')
                ->preload()
                ->searchable()
                ->options(function () {
                    return User::query()->whereHas('roles', function (Builder $query) {
                        $query->whereIn('name', ['dealer_manager', 'leasing_manager']);
                    })->pluck('name', 'id');
                }),

            SpatieMediaLibraryFileUpload::make('file')
                ->label('Файл')
                ->collection('offer_file')
                ->directory('form-tmp')
                ->enableDownload()
                ->enableOpen()
                ->acceptedFileTypes(['application/pdf'])
                ->required(),
        ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('manager.name')->label('Пользователь'),
                IconColumn::make('Ссылка')
                    ->url(function (ManagerOffer $record) {
                        return $record->getFirstMediaUrl('offer_file');
                    })
                    ->openUrlInNewTab()
                    ->options(function (ManagerOffer $record) {
                        $url = $record->getFirstMediaUrl('offer_file');
                        if (blank($url)) {
                            return ['heroicon-o-ban'];
                        }
                        return ['heroicon-o-external-link'];
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }
}
