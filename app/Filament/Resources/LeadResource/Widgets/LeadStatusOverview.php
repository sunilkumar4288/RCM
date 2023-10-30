<?php

namespace App\Filament\Resources\LeadResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\Lead;
class LeadStatusOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
             Stat::make('Open', Lead::query()->where('status', 'Open')->count()),
            Stat::make('Processing', Lead::query()->where('status', 'Processing')->count()),
            Stat::make('Converted', Lead::query()->where('status', 'Converted')->count()),
            Stat::make('Closed', Lead::query()->where('status', 'Closed')->count()),
        
        ];
    
    }
}
