<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\User;
class UserStatusOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make('Enabled', User::query()->where('status', 'Enabled')->count()),
            Stat::make('Disabled', User::query()->where('status', 'Disabled')->count()),
            Stat::make('Suspend', User::query()->where('status', 'Suspend')->count()),
        ];
    }
}
