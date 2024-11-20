<?php

namespace Shebaoting\FilamentPlugins\Pages;

use Composer\Autoload\ClassLoader;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Nwidart\Modules\Facades\Module;
use Shebaoting\FilamentPlugins\Models\Plugin;
use Shebaoting\FilamentPlugins\Services\PluginGenerator;

class Plugins extends Page implements HasTable
{
    use InteractsWithTable;

    protected $listeners = ['pluginRefresh' => '$refresh'];

    public static ?string $navigationIcon = 'heroicon-o-squares-plus';
    public static string $view = 'filament-plugins::pages.plugins';

    public function getTitle(): string
    {
        return trans('filament-plugins::messages.plugins.title');
    }

    public static function getNavigationLabel(): string
    {
        return trans('filament-plugins::messages.plugins.title');
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('filament-plugins::messages.group');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Plugin::query())
            ->content(function () {
                return view('filament-plugins::pages.table');
            })
            ->columns([
                TextColumn::make('name')
                    ->label(trans('filament-plugins::messages.plugins.form.name'))
                    ->searchable(),
            ]);
    }

    public function disableAction(): Action
    {
        return Action::make('disable')
            ->iconButton()
            ->icon('heroicon-s-x-circle')
            ->color('danger')
            ->tooltip(trans('filament-plugins::messages.plugins.actions.disable'))
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                $module = Module::find($arguments['item']['module_name']);
                $module?->disable();

                Notification::make()
                    ->title(trans('filament-plugins::messages.plugins.notificationss.disabled.title'))
                    ->body(trans('filament-plugins::messages.plugins.notificationss.disabled.body'))
                    ->success()
                    ->send();

                $this->js('window.location.reload()');
            });
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->iconButton()
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->tooltip(trans('filament-plugins::messages.plugins.actions.delete'))
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                $module = Module::find($arguments['item']['module_name']);
                $module?->delete();

                Notification::make()
                    ->title(trans('filament-plugins::messages.plugins.notificationss.deleted.title'))
                    ->body(trans('filament-plugins::messages.plugins.notificationss.deleted.body'))
                    ->success()
                    ->send();

                $this->js('window.location.reload()');
            });
    }


    public function activeAction(): Action
    {
        return Action::make('active')
            ->iconButton()
            ->icon('heroicon-s-check-circle')
            ->tooltip(trans('filament-plugins::messages.plugins.actions.active'))
            ->color('success')
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                if (!class_exists(json_decode($arguments['item']['providers'])[0])) {
                    Notification::make()
                        ->title(trans('filament-plugins::messages.plugins.notificationss.autoload.title'))
                        ->body(trans('filament-plugins::messages.plugins.notificationss.autoload.body'))
                        ->danger()
                        ->send();
                    return;
                }
                $module = Module::find($arguments['item']['module_name']);
                $module?->enable();

                Notification::make()
                    ->title(trans('filament-plugins::messages.plugins.notificationss.enabled.title'))
                    ->body(trans('filament-plugins::messages.plugins.notificationss.enabled.body'))
                    ->success()
                    ->send();

                $this->js('window.location.reload()');
            });
    }

    public function getHeaderActions(): array
    {
        if ((bool)config('filament-plugins.allow_create')) {
            return [
                Action::make('create')
                    ->label(trans('filament-plugins::messages.plugins.create'))
                    ->icon('heroicon-o-plus')
                    ->form([
                        TextInput::make('name')
                            ->label(trans('filament-plugins::messages.plugins.form.name'))
                            ->placeholder(trans('filament-plugins::messages.plugins.form.name-placeholder'))
                            ->required(),
                        TextInput::make('identifier')
                            ->label(trans('filament-plugins::messages.plugins.form.identifier'))
                            ->placeholder(trans('filament-plugins::messages.plugins.form.identifier-placeholder'))
                            ->required()
                            ->rules(['regex:/^[a-zA-Z_]+$/']),
                        Textarea::make('description')
                            ->label(trans('filament-plugins::messages.plugins.form.description'))
                            ->placeholder(trans('filament-plugins::messages.plugins.form.description-placeholder'))
                            ->required(),
                        FileUpload::make('logo')
                            ->label(trans('filament-plugins::messages.plugins.form.logo'))
                            ->directory('plugins/logos')
                            ->visibility('public')
                            ->required()
                    ])
                    ->action(fn(array $data) => $this->createPlugin($data)),
                Action::make('import')
                    ->label(trans('filament-plugins::messages.plugins.import'))
                    ->icon('heroicon-o-arrow-up-on-square')
                    ->form([
                        FileUpload::make('file')
                            ->label(trans('filament-plugins::messages.plugins.form.file'))
                            ->acceptedFileTypes(['application/zip'])
                            ->required()
                            ->storeFiles(false)
                    ])
                    ->action(fn(array $data) => $this->importPlugin($data)),
            ];
        }

        return [];
    }

    public function importPlugin(array $data)
    {
        $zip = new \ZipArchive();
        $res = $zip->open($data['file']->getRealPath());

        if ($res === true) {
            $zip->extractTo(base_path('Modules'));
            if (File::exists(base_path('Modules/__MACOSX'))) {
                File::deleteDirectory(base_path('Modules/__MACOSX'));
            }

            $zip->close();

            Notification::make()
                ->title(trans('filament-plugins::messages.plugins.notificationss.import.title'))
                ->body(trans('filament-plugins::messages.plugins.notificationss.import.body'))
                ->success()
                ->send();

            $this->js('window.location.reload()');
        }
    }

    public function createPlugin(array $data)
    {
        // 验证插件标识
        if (!preg_match('/^[a-zA-Z_]+$/', $data['identifier'])) {
            Notification::make()
                ->title(trans('filament-plugins::messages.plugins.notifications.invalid_identifier.title'))
                ->body(trans('filament-plugins::messages.plugins.notifications.invalid_identifier.body'))
                ->danger()
                ->send();
            return;
        }

        $pluginIdentifier = Str::of($data['identifier'])->camel()->ucfirst()->toString();
        $checkIfPluginExists = Module::find($pluginIdentifier);
        if ($checkIfPluginExists) {
            Notification::make()
                ->title(trans('filament-plugins::messages.plugins.notifications.exists.title'))
                ->body(trans('filament-plugins::messages.plugins.notifications.exists.body'))
                ->danger()
                ->send();
            return;
        }

        // Generate the plugin using PluginGenerator
        $generator = new PluginGenerator(
            $data['name'],
            $data['identifier'],
            $data['description'],
            $data['logo']
        );
        $generator->generate();

        // Move the logo file to the plugin directory
        if (isset($data['logo'])) {
            // Define paths
            $storagePath = storage_path('app/public');
            $pluginPath = base_path('plugins/' . $pluginIdentifier);

            // Ensure the plugin directory exists
            if (!File::exists($pluginPath)) {
                File::makeDirectory($pluginPath, 0755, true);
            }

            // Get the original logo path
            $originalLogoPath = $storagePath . '/' . $data['logo'];

            // Move and rename the logo file
            $newLogoPath = $pluginPath . '/logo.svg';

            if (File::exists($originalLogoPath)) {
                File::move($originalLogoPath, $newLogoPath);

                Notification::make()
                    ->title(trans('filament-plugins::messages.plugins.notifications.imported.title'))
                    ->body(trans('filament-plugins::messages.plugins.notifications.imported.body'))
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title(trans('filament-plugins::messages.plugins.notifications.logo_failed.title'))
                    ->body(trans('filament-plugins::messages.plugins.notifications.logo_failed.body'))
                    ->danger()
                    ->send();
            }
        }

        $this->js('window.location.reload()');
    }
}
