<?php

namespace App\Filament\Resources\ScholarResource\Pages;

use App\Filament\Resources\ScholarResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateScholar extends CreateRecord
{
    protected static string $resource = ScholarResource::class;
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
