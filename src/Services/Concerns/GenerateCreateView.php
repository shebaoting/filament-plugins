<?php

namespace Shebaoting\FilamentPlugins\Services\Concerns;

use Illuminate\Support\Str;

trait GenerateCreateView
{
    private function generateCreateView(): void
    {
        $folders = [];
        if ($this->moduleName) {
            $folders[] = module_path($this->moduleName) . "/resources/views/" . Str::replace('_', '-', $this->tableName);
        } else {
            $folders[] = resource_path("views/admin");
            $folders[] = resource_path("views/admin/" . Str::replace('_', '-', $this->tableName));
        }

        $this->generateStubs(
            $this->stubPath . "create.stub",
            $this->moduleName ? module_path($this->moduleName) . "/resources/views/" . str_replace('_', '-', $this->tableName) . "/create.blade.php" : resource_path("views/admin/" . Str::replace('_', '-', $this->tableName) . "/create.blade.php"),
            [
                "title" => $this->modelName,
                "table" => str_replace('_', '-', $this->tableName),
                "cols" => $this->generateForm()
            ],
            $folders
        );
    }
}
