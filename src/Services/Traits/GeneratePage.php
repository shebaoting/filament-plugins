<?php

namespace Shebaoting\FilamentPlugins\Services\Traits;

use Nwidart\Modules\Facades\Module;

trait GeneratePage
{
    public function generatePage(): void
    {
        $module = Module::find($this->identifier);
        $this->generateStubs(
            $this->stubPath . 'page.stub',
            base_path("plugins") . '/' . $this->identifier . '/app/Filament/Pages/' . $this->identifier . 'Page.php',
            [
                "namespace" => "Plugins\\" . $this->identifier . "\\Filament\\Pages",
                "view" => $module->getLowerName() . "::index",
                "title" => $this->title,
                "identifier" => $this->identifier,
                "name" => $this->identifier . 'Page',
                "icon" => 'heroicon-o-bell-snooze',
            ],
            [
                base_path("plugins") . "/" . $this->identifier . "/app/Filament",
                base_path("plugins") . "/" . $this->identifier . "/app/Filament/Pages",
            ]
        );
    }
}
