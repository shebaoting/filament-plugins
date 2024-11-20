<?php

namespace Shebaoting\FilamentPlugins\Services\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;

trait GenerateInfo
{
    /**
     * @return void
     */
    private function generateInfo(): void
    {
        if (Module::find($this->identifier)) {
            $modulePath = module_path($this->identifier) . '/module.json';
            $module = json_decode(File::get($modulePath));
            $module->title = [];
            $module->title['zh_CN'] = $this->title;
            $module->title['en'] = $this->title;
            $module->identifier = $this->identifier; // 添加插件标识
            $module->description = [];
            $module->description['zh_CN'] = $this->description;
            $module->description['en'] = $this->description;
            $module->placeholder = "placeholder.webp";
            $module->type = "plugin";
            $module->version = "v1.0";
            $module->logo = "logo.svg"; // 添加 logo 字段
            File::put($modulePath, json_encode($module, JSON_PRETTY_PRINT));
        }
    }
}
