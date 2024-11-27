<?php

namespace Shebaoting\FilamentPlugins\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;
use TomatoPHP\ConsoleHelpers\Traits\RunCommand;
use Shebaoting\FilamentPlugins\Services\CRUDGenerator;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\search;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class FilamentPluginsModel extends Command
{
    use RunCommand;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filament-plugins:model {table?} {module?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate a new model';

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $connection = config('database.default'); // 获取当前数据库连接类型
        $database = config("database.connections.$connection.database");

        $tables = match ($connection) {
            'mysql' => collect(\DB::select('SHOW TABLES'))->map(function ($item) use ($database) {
                return $item->{'Tables_in_' . $database};
            })->toArray(),

            'sqlite' => collect(\DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'"))
                ->pluck('name')
                ->toArray(),

            'pgsql' => collect(\DB::select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'"))
                ->pluck('tablename')
                ->toArray(),

            'sqlsrv' => collect(\DB::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE'"))
                ->pluck('TABLE_NAME')
                ->toArray(),

            default => throw new \Exception("Unsupported database type: $connection")
        };

        $tableName = $this->argument('table') && $this->argument('table') != "0" ? $this->argument('table') : search(
            label: 'Please input your table name you want to create CRUD?',
            options: fn(string $value) => strlen($value) > 0
                ? collect($tables)->filter(function ($item, $key) use ($value) {
                    return Str::contains($item, $value) ? (string)$item : null;
                })->toArray()
                : [],
            placeholder: "ex: users",
            scroll: 10
        );

        if (is_numeric($tableName)) {
            $tableName = $tables[$tableName];
        } else {
            $tableName = $tableName;
        }

        // 检查用户是否需要使用 HMVC
        $isModule = ($this->argument('module') && $this->argument('module') != "0") ?: confirm('Do you want to use HMVC module?');
        $moduleName = false;
        $identifier = null; // 新增的变量，用于存储插件的 identifier
        if ($isModule) {
            if (class_exists(\Nwidart\Modules\Facades\Module::class)) {
                $modules = \Nwidart\Modules\Facades\Module::toCollection()->map(function ($item) {
                    return $item->getName();
                });

                if ($modules->isEmpty()) {
                    $this->error('No modules found. Please create a module first.');
                    exit();
                }

                // 使用 select 方法让用户选择模块
                $moduleName = ($this->argument('module') && $this->argument('module') != "0") ? $this->argument('module') : select(
                    label: 'Please select your module name?',
                    options: $modules->toArray(),
                    default: $modules->first(),
                    scroll: 10
                );

                $module = \Nwidart\Modules\Facades\Module::find($moduleName);
                if (!$module) {
                    $createIt = confirm('Module not found! Do you want to create it?');
                    if ($createIt) {
                        $this->artisanCommand(["module:make", $moduleName]);
                        \Laravel\Prompts\info('We have generated it. Please re-run the command again.');
                        exit();
                    } else {
                        \Laravel\Prompts\error('Module not found and not created. Exiting.');
                        exit();
                    }
                }

                // 获取插件的 identifier
                $modulePath = $module->getPath();
                $moduleConfigPath = $modulePath . '/module.json'; // 假设插件信息存储在 module.json 中
                if (file_exists($moduleConfigPath)) {
                    $moduleConfig = json_decode(file_get_contents($moduleConfigPath), true);
                    $identifier = $moduleConfig['identifier'] ?? $moduleName;
                } else {
                    // 如果 module.json 不存在，使用默认的 moduleName
                    $identifier = $moduleName;
                }
            } else {
                $installItem = confirm('Sorry, nwidart/laravel-modules is not installed. Do you want to install it?');
                if ($installItem) {
                    $this->requireComposerPackages(["nwidart/laravel-modules"]);
                    \Laravel\Prompts\info('Add this line to composer.json psr-4 autoload:');
                    \Laravel\Prompts\info('"Modules\\\\" : "Modules/"');
                    \Laravel\Prompts\info('Now run:');
                    \Laravel\Prompts\info('composer dump-autoload');
                    \Laravel\Prompts\info('Installation successful. Please run the command again.');
                    exit();
                } else {
                    \Laravel\Prompts\error('nwidart/laravel-modules is required to use HMVC modules. Exiting.');
                    exit();
                }
            }
        }

        $generator = new CRUDGenerator(
            tableName: $tableName,
            moduleName: $moduleName, // 仍然传递 moduleName
            identifier: $identifier, // 新增的参数，传递 identifier
            migration: false,
            models: true
        );

        $generator->generate();

        $this->info('Model generated successfully.');
    }
}
