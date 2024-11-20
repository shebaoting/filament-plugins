<?php

namespace Shebaoting\FilamentPlugins\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use TomatoPHP\ConsoleHelpers\Traits\HandleFiles;
use TomatoPHP\ConsoleHelpers\Traits\HandleStub;
use Shebaoting\FilamentPlugins\Services\Traits\GeneratePage;
use Shebaoting\FilamentPlugins\Services\Traits\MoveFiles;
use Shebaoting\TomatoSettings\Settings\ThemesSettings;
use Shebaoting\FilamentPlugins\Services\Traits\GenerateInfo;
use Shebaoting\FilamentPlugins\Services\Traits\GenerateModule;
use Shebaoting\FilamentPlugins\Services\Traits\GenerateReadMe;

class PluginGenerator
{
    use HandleStub;
    use HandleFiles;
    use GenerateInfo;
    use GenerateReadMe;
    use GenerateModule;
    use GeneratePage;
    use MoveFiles;

    public function __construct(
        private string $name,
        private string $identifier,
        private string|null $description,
        public string|null $logo = null,
        public string|null $stubPath = null,
        public string|null $title = null,
    ) {
        $this->title = $name;
        $this->identifier = Str::of($identifier)->camel()->ucfirst()->toString();
        $this->stubPath = __DIR__ . '/../../stubs/';
        $this->publish = __DIR__ . '/../../stubs/';
    }

    /**
     * @return void
     */
    public function generate(): void
    {
        $this->generateModule();
        $this->generateReadMe();
        $this->generateInfo();
        $this->moveFiles();
        $this->generatePage();
    }
}
