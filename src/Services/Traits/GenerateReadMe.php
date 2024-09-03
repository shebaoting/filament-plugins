<?php

namespace Shebaoting\FilamentPlugins\Services\Traits;

use Illuminate\Support\Str;

trait GenerateReadMe
{
    /**
     * @return void
     */
    private function generateReadMe(): void
    {
        //Generate Readme.md file
        $this->generateStubs(
            $this->stubPath . 'readme.stub',
            base_path("plugins") . '/' . $this->identifier . '/README.md',
            [
                "name" => $this->name,
                "title" => $this->title,
                "description" => $this->description,
            ],
            [
                base_path("plugins"),
                base_path("plugins") . "/" . $this->identifier,
            ]
        );
    }
}
