<?php

namespace App\Filament\Resources\QueryResource\Pages;

use App\Filament\Resources\QueryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateQuery extends CreateRecord
{
    protected static string $resource = QueryResource::class;
    protected function getRedirectUrl(): string
{
    return static::getResource()::getUrl('index');
}
}
