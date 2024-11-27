<?php

namespace Shebaoting\FilamentPlugins\Services\Concerns;

use Illuminate\Support\Facades\Artisan;

trait GenerateModel
{
    public function generateModel()
    {
        //Check if model exists or not

        $namespace = "";
        $exists = false;
        $modelName = $this->modelName;
        $filePath = "";

        if ($this->identifier) {
            $modulePath = base_path('plugins/' . $this->identifier);
            $modelPath = $modulePath . '/app/Models/' . $this->modelName . '.php';

            if (file_exists($modelPath)) {
                $exists = true;
            }

            $namespace = "Plugins\\{$this->identifier}\\Models";
            $filePath = $modelPath;
        } else if (file_exists(app_path("Models/{$this->modelName}.php"))) {
            $exists = true;
            $namespace = "App\\Models";
            $filePath = app_path("Models/{$this->modelName}.php");
        } else {
            $namespace = "App\\Models";
            $filePath = app_path("Models/{$this->modelName}.php");
        }

        if (!$exists) {
            $this->generateStubs(
                $this->stubPath . "model.stub",
                $filePath,
                [
                    "name" => $this->modelName,
                    "namespace" => $namespace,
                    "table" => $this->tableName,
                    "fillable" => $this->getFillable(),
                    "docblock" => $this->getDocBlock(),
                    "hidden" => $this->getHidden() ? 'protected $hidden = [' . "\n" . $this->getHidden()  . "\n" . '    ];' : "",
                    "casts" => $this->getCasts() ? 'protected $casts = [' . "\n" . $this->getCasts() . "\n" . '    ];' : "",
                    "methods" => $this->getMethods(),
                ],
                [
                    $this->identifier ? base_path('plugins/' . $this->identifier) . '/app/Models/' : app_path("Models")
                ]
            );
        }
    }

    private function getDocBlock()
    {
        $block = [];
        foreach ($this->cols as $key => $column) {
            if ($column['type'] === 'relation') {
                if (str($column['relation']['model'])->contains('Account') && class_exists(\App\Models\Account::class)) {
                    $block[] = '* @property \App\Models\Account $' . $column['name'];
                } else if (str($column['relation']['model'])->contains('User') && class_exists(\App\Models\User::class)) {
                    $block[] = '* @property \App\Models\User $' . $column['name'];
                } else if (str($column['relation']['model'])->contains('Team') && class_exists(\App\Models\Team::class)) {
                    $block[] = '* @property \App\Models\Team $' . $column['name'];
                } else {
                    $block[] = '* @property ' . str($column['relation']['model'])->remove('::class') . ' $' . $column['name'];
                }
            } else if ($column['type'] === 'int') {
                $block[] = '* @property int $' . $column['name'];
            } else {
                $block[] = '* @property string $' . $column['name'];
            }
        }
        return implode("\n", $block);
    }

    private function getMethods()
    {
        $methods = [];
        foreach ($this->cols as $key => $column) {
            if ($column['type'] === 'relation') {
                $methodName = str($column['name'])->remove('_id')->camel();
                $method = 'public function ' . $methodName . '()' . "\n";
                $method .= '    {' . "\n";

                if (str($column['relation']['model'])->contains('Account') && class_exists(\App\Models\Account::class)) {
                    $method .= '        return $this->belongsTo(\App\Models\Account::class);' . "\n";
                } elseif (str($column['relation']['model'])->contains('User') && class_exists(\App\Models\User::class)) {
                    $method .= '        return $this->belongsTo(\App\Models\User::class);' . "\n";
                } elseif (str($column['relation']['model'])->contains('Team') && class_exists(\App\Models\Team::class)) {
                    // 如果 Team 模型位于 App\Models
                    $method .= '        return $this->belongsTo(\App\Models\Team::class);' . "\n";
                } else {
                    // 默认情况，使用 App\Models 下的模型
                    $modelNamespace = '\\App\\Models\\' . $column['relation']['model'];
                    $method .= '        return $this->belongsTo(' . $modelNamespace . '::class);' . "\n";
                }

                $method .= '    }';

                $methods[] = $method;
            }
        }

        if (count($methods)) {
            return implode("\n", $methods);
        } else {
            return false;
        }
    }

    private function getHidden()
    {
        $hidden = [];
        foreach ($this->cols as $key => $column) {
            if (str($column['name'])->contains(['password', 'token'])) {
                $hidden[] = '        \'' . $column['name'] . "'";
            }
        }

        if (count($hidden)) {
            return implode(",\n", $hidden);
        } else {
            return false;
        }
    }

    private function getFillable()
    {
        $fillable = [];
        foreach ($this->cols as $key => $column) {
            $fillable[] = ($key !== 0 ? '        ' : "") . '\'' . $column['name'] . "'";
        }
        return implode(",\n", $fillable);
    }

    private function getCasts()
    {
        $casts = [];
        foreach ($this->cols as $key => $column) {
            if ($column['type'] == 'boolean') {
                $casts[] = '        \'' . $column['name'] . '\' => \'boolean\'';
            } elseif ($column['type'] == 'json') {
                $casts[] = '        \'' . $column['name'] . '\' => \'json\'';
            }
        }

        if (count($casts)) {
            return implode(",\n", $casts);
        }

        return false;
    }
}
