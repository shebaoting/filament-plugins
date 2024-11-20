<?php

namespace Shebaoting\FilamentPlugins\Resources\TableResource\Pages;

use Shebaoting\FilamentPlugins\Resources\TableResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTable extends EditRecord
{
    protected static string $resource = TableResource::class;

    public function getTitle(): string
    {
        return trans('filament-plugins::messages.tables.edit');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
