<?php

namespace Shebaoting\FilamentPlugins;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Nwidart\Modules\Facades\Module;
use Shebaoting\FilamentPlugins\Pages\Plugins;
use Shebaoting\FilamentPlugins\Resources\TableResource;

class FilamentPluginsPlugin implements Plugin
{

    private array $modules = [];
    private bool $useUI = true;
    private bool $autoDiscoverModules = true;
    private bool $discoverCurrentPanelOnly = false;

    public function getId(): string
    {
        return 'filament-plugins';
    }

    public function register(Panel $panel): void
    {
        $plugins = \Shebaoting\FilamentPlugins\Models\Plugin::all();
        $useClusters = config('filament-plugins.clusters.enabled', false);

        if (!count($this->modules) && $this->autoDiscoverModules) {
            $this->modules = Module::all();
        }

        foreach ($plugins as $plugin) {
            if ($plugin->type === 'plugin' && in_array($plugin->module_name, $this->modules)) {
                $module = Module::find($plugin->module_name);

                if ($module->isEnabled()) {
                    // 读取module.json
                    $moduleConfig = json_decode(File::get($module->getPath() . '/module.json'), true);
                    $panelId = $panel->getId();

                    // 检查当前面板的配置
                    $panelConfig = $moduleConfig['panels'][$panelId] ?? null;

                    if ($panelConfig) {
                        // 只注册当前面板配置的资源
                        if (!empty($panelConfig['resources'])) {
                            $resourceBasePath = $module->appPath('Filament/Resources');
                            $resourceBaseNamespace = $module->appNamespace('Filament\\Resources');
                            // 移除第三个参数（过滤函数）
                            $panel->discoverResources($resourceBasePath, $resourceBaseNamespace);
                        }

                        // 只注册当前面板配置的页面
                        if (!empty($panelConfig['pages'])) {
                            $pageBasePath = $module->appPath('Filament/Pages');
                            $pageBaseNamespace = $module->appNamespace('Filament\\Pages');
                            // 移除第三个参数（过滤函数）
                            $panel->discoverPages($pageBasePath, $pageBaseNamespace);
                        }
                    }

                    // 注册其他组件
                    $panel
                        ->discoverWidgets(
                            $module->appPath('Filament/Widgets'),
                            $module->appNamespace('\\Filament\\Widgets')
                        );

                    $panel
                        ->discoverLivewireComponents(
                            $module->appPath('Livewire'),
                            $module->appNamespace('\\Livewire')
                        );
                }
            }
        }

        if ($this->useUI) {
            $panel
                ->pages([
                    Plugins::class
                ]);
        }
    }

    public function autoDiscoverModules(bool $autoDiscoverModules = true)
    {
        $this->autoDiscoverModules = $autoDiscoverModules;
        return $this;
    }

    public function modules(array $modules)
    {
        $this->modules = $modules;
        return $this;
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return new static();
    }

    public function useUI(bool $useUI): static
    {
        $this->useUI = $useUI;
        return $this;
    }

    public function discoverCurrentPanelOnly(bool $discoverCurrentPanelOnly = true): static
    {
        $this->discoverCurrentPanelOnly = $discoverCurrentPanelOnly;
        return $this;
    }
}
