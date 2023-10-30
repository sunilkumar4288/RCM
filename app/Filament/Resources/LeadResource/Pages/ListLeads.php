<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Resources\LeadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         LeadResource\Widgets\LeadStatusOverview::class,
    //     ];
    // }
    public function getTabs(): array
    {
        return [
            'all' => Tab::make()->icon('heroicon-m-arrow-trending-up'),
            'open' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Open')),
            'processing' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Processing')),
            'converted' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Converted')),
            'closed' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Closed')),

        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ImportAction::make()->handleBlankRows(true)
            ->fields([
                ImportField::make('name')
                    ->label('Name')
                    ->helperText('Name of the person'),
                ImportField::make('phone')
                    ->label('Phone No.')
                    ->helperText('Person\'s Phone No.'),
                ImportField::make('email')
                    ->label('Email')
                    ->helperText('Person\'s Email'),
                ImportField::make('city')
                    ->label('City')
                    ->helperText('Person\'s City'),    
                ImportField::make('university')
                    ->label('university')
                    ->helperText('Person\'s Univ.'),
                    ImportField::make('source')
                    ->label('Source')
                    ->helperText('Source'),      
                ImportField::make('notes')
                    ->label('Notes')
                    ->helperText('Person\'s Remark.'),                    
                    
            ],columns:2),
            
        ];
    }
  
}
