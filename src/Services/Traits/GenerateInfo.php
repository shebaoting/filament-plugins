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
        if(Module::find($this->name)){
            $modulePath = module_path($this->name) .'/module.json';
            $module = json_decode(File::get($modulePath));
            $module->title = [];
            $module->alias = $this->alias;
            $module->title['zh_CN'] = $this->title;
            $module->title['en'] = $this->title;
            $module->description = [];
            $module->description['zh_CN'] = $this->description;
            $module->description['en'] = $this->description;
            // $module->color = $this->color;
            // $module->icon = $this->icon;
            $module->placeholder = "placeholder.webp";
            $module->logo = "logo.jpg";
            $module->type = "plugin";
            $module->version = "v1.0";

            File::put($modulePath, json_encode($module, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));

            Module::find($this->name)->disable();
        }
    }
}
