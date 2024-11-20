<?php

namespace Shebaoting\FilamentPlugins\Services\Traits;

use Illuminate\Support\Facades\Artisan;

trait GenerateModule
{
    public function generateModule()
    {
        Artisan::call('module:make ' . $this->identifier);
        sleep(3);
    }
}
