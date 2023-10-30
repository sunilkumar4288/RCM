<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AreaResource\Pages;
use App\Filament\Resources\AreaResource\RelationManagers;
use App\Models\Area;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\BadgeColumn;

class AreaResource extends Resource
{
    protected static ?string $model = Area::class;

    // protected static ?string $navigationGroup = 'Query Management';

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?int $navigationSort = 4;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('description')
                ->maxLength(255),
                Select::make('type')
                ->options([
                    'tech' => 'Technical',
                    'non-tech' => 'Non Technical',
                    'other' => 'Other',
                ]),

                Select::make('status')
                ->options([
                    'active' => 'Active',
                    'inactive' => 'In Active',
                    
                ])->default('active'),  
            ]);
            
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('name')->searchable()
                ->forceSearchCaseInsensitive(),
                Tables\Columns\TextColumn::make('Description')->searchable()
                ->forceSearchCaseInsensitive(),
                
                
                BadgeColumn::make('status')
                ->colors([
                    'primary',
                    'secondary' => 'draft',
                    'warning' => 'reviewing',
                    'success' => 'published',
                    'danger' => 'rejected',
                ]),

                Tables\Columns\TextColumn::make('status')->badge()
                ->color(fn (string $state): string => match ($state) {
                    'active' => 'success',
                    'inactive' => 'danger',
                    default => 'primary', // You can provide a default color if needed
                })
            ])
            ->filters([
                //

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAreas::route('/'),
        ];
    }    
}
