<?php

namespace Shebaoting\FilamentPlugins\Console;

use Illuminate\Console\Command;
use Nwidart\Modules\Facades\Module;
use TomatoPHP\ConsoleHelpers\Traits\RunCommand;
use Shebaoting\FilamentPlugins\Services\PluginGenerator;
use function Laravel\Prompts\error;
use function Laravel\Prompts\text;

class FilamentPluginsGenerate extends Command
{
    use RunCommand;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filament-plugins:generate {identifier?} {name?} {description?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成一个新的插件';

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
        $identifier = $this->argument('identifier') ?? text(label: '插件的标识是什么？', required: true);
        while (Module::find($identifier)) {
            error('抱歉，该插件标识已存在。');
            $identifier = $this->argument('identifier') ?? text(label: '插件的标识是什么？', required: true);
        }

        $name = $this->argument('name') ?? text(label: '插件的名称是什么？', required: true);
        $description = $this->argument('description') ?? text(label: '插件的描述是什么？', required: true);
        $pluginGenerator = new PluginGenerator(
            name: $name,
            identifier: $identifier,
            description: $description,
        );
        $pluginGenerator->generate();

        $this->info('插件生成成功。');
    }
}
