<?php

namespace Shebaoting\FilamentPlugins\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;
use Sushi\Sushi;
use Shebaoting\FilamentPlugins\Facades\FilamentPlugins;

class Plugin extends Model
{
    use Sushi;

    protected $schema = [
        'module_name' => 'string',
        'title' => 'json',
        'description' => 'json',
        'placeholder' => 'string',
        'identifier' => 'string',
        'version' => 'string',
        'docs' => 'string',
        'github' => 'string',
        'active' => 'boolean',
        'providers' => 'json',
        'type' => 'string',
    ];

    public function getRows()
    {
        $getPlugins = [];
        if (File::exists(base_path('plugins'))) {
            $getPlugins = collect(File::directories(base_path('plugins')));
            $getPlugins = $getPlugins->filter(function ($item) {
                $json = json_decode(File::get($item . "/module.json"));
                if (isset($json->type) && $json->type === 'plugin') {
                    return true;
                } else {
                    return false;
                }
            })->transform(callback: static function ($item) {
                $info = json_decode(File::get($item . "/module.json"));
                return [
                    "module_name" => $info->name,
                    "name" => json_encode($info->title),
                    "description" => json_encode($info->description),
                    "placeholder" => $info->placeholder,
                    "version" => $info->version,
                    "type" => $info->type,
                    "identifier" => $info->identifier,
                    "github" => isset($info->github) ? $info->github : null,
                    "docs" => isset($info->docs) ? $info->docs : null,
                    "active" => Module::find($info->name) ? Module::find($info->name)->isEnabled() : false,
                    "providers" => json_encode($info->providers)
                ];
            });
        }

        $providersPlugins = [];
        if (config('filament-plugins.scan')) {
            $getVendorPathes = File::directories(base_path('vendor'));
            foreach ($getVendorPathes as $item) {
                $checkInsideDir = File::directories($item);
                foreach ($checkInsideDir as $dir) {
                    $getDirFiles = File::files($dir);
                    foreach ($getDirFiles as $file) {
                        if (str($file->getFilename())->contains('filament-plugin.json')) {
                            $info = json_decode($file->getContents());
                            $providersPlugins[] = [
                                "module_name" => $info->name,
                                "name" => json_encode($info->title),
                                "description" => json_encode($info->description),
                                "type" => $info->type,
                                "placeholder" => $info->placeholder,
                                "version" => $info->version,
                                "identifier" => $info->identifier,
                                "github" => isset($info->github) ? $info->github : null,
                                "docs" => isset($info->docs) ? $info->docs : null,
                                "active" => Module::find($info->name) ? Module::find($info->name)->isEnabled() : false,
                                "providers" => json_encode($info->providers)
                            ];
                        }
                    }
                }
            }
        }

        if (is_array($getPlugins)) {
            $values = array_values($getPlugins);
        } else {
            $values = array_values($getPlugins->toArray());
        }


        return array_merge($values, array_values($providersPlugins));
    }
}
