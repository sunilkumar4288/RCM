<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
{
    return [
        'all' => Tab::make()->icon('heroicon-m-user-group'),
        'enabled' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Enabled')),
        'disabled' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Disabled')),
        'suspend' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Suspend'))
    ];
}
}
