<?php

namespace Shebaoting\FilamentPlugins\Services\Concerns;

trait GenerateRoutes
{
    private function generateRoutes()
    {
        if ($this->routes) {
            $this->generateStubs(
                $this->stubPath . "route.stub",
                $this->moduleName ? base_path('plugins/' . $this->moduleName) . "/routes/web.php" : base_path("routes/web.php"),
                [
                    "name" => $this->moduleName ? "\\plugins\\" . $this->moduleName . "\\Http\\Controllers\\{$this->modelName}Controller" : "App\\Http\\Controllers\\Admin\\{$this->modelName}Controller",
                    "table" => str_replace('_', '-', $this->tableName)
                ],
                [
                    $this->moduleName ? base_path('plugins/' . $this->moduleName) . "/routes" : base_path("routes")
                ],
                true
            );
        }

        if ($this->apiRoutes) {
            $this->generateStubs(
                $this->stubPath . "api-route.stub",
                $this->moduleName ? base_path('plugins/' . $this->moduleName) . "/routes/api.php" : base_path("routes/api.php"),
                [
                    "name" => $this->moduleName ? "\\plugins\\" . $this->moduleName . "\\Http\\Controllers\\{$this->modelName}Controller" : "App\\Http\\Controllers\\Admin\\{$this->modelName}Controller",
                    "table" => str_replace('_', '-', $this->tableName)
                ],
                [
                    $this->moduleName ? base_path('plugins/' . $this->moduleName) . "/routes" : base_path("routes")
                ],
                true
            );
        }
    }
}
