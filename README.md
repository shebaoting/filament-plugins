![Screenshot](https://raw.githubusercontent.com/shebaoting/filament-plugins/master/arts/3x1io-tomato-plugins.jpg)

# Filament plugins

[![Latest Stable Version](https://poser.pugx.org/shebaoting/filament-plugins/version.svg)](https://packagist.org/packages/shebaoting/filament-plugins)
[![PHP Version Require](http://poser.pugx.org/shebaoting/filament-plugins/require/php)](https://packagist.org/packages/shebaoting/filament-plugins)
[![License](https://poser.pugx.org/shebaoting/filament-plugins/license.svg)](https://packagist.org/packages/shebaoting/filament-plugins)
[![Downloads](https://poser.pugx.org/shebaoting/filament-plugins/d/total.svg)](https://packagist.org/packages/shebaoting/filament-plugins)

Manage your modules as a plugin system with plugin generator

## Screenshots

![Plugins](https://raw.githubusercontent.com/Shebaoting/filament-plugins/master/arts/plugins.png)
![Tables](https://raw.githubusercontent.com/Shebaoting/filament-plugins/master/arts/tables.png)
![Generate](https://raw.githubusercontent.com/Shebaoting/filament-plugins/master/arts/generate.png)
![Create Col](https://raw.githubusercontent.com/Shebaoting/filament-plugins/master/arts/create-col.png)
![Table cols](https://raw.githubusercontent.com/Shebaoting/filament-plugins/master/arts/table-cols.png)

## Installation

```bash
composer require shebaoting/filament-plugins
```
after install your package please run this command

```bash
php artisan filament-plugins:install
```

By default the module classes are not loaded automatically. You can autoload your modules by adding merge-plugin to the extra section:

```json
"extra": {
    "laravel": {
        "dont-discover": []
    },
    "merge-plugin": {
        "include": [
            "plugins/*/composer.json"
        ]
    }
},
```

now you need to run this command to autoload your modules

```bash
composer dump-autoload
```

finally reigster the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(\Shebaoting\FilamentPlugins\FilamentPluginsPlugin::make())
```

## Usage

you can create a new plugin using just a command

```bash
php artisan filament-plugins:generate
```

or you can use the GUI to create a new plugin, after create a plugin you need to make sure that it's loaded on composer by run this command

```bash
composer dump-autoload
```

after create the plugin you can create a new table inside it and than run the migration generator to convert it to a migration file then you can use the GUI to generate resources, pages, widget or model, or you can easy use this commands

```bash
php artisan filament-plugins:model
php artisan filament-plugins:resource
php artisan filament-plugins:page
php artisan filament-plugins:widget
```

it will generate the files for you and you can use it directly, please note that you need to generate the model first than use other commands

## List your package on the plugins list

you can list your package on the plugins list by adding this a json file in your package root folder with name `filament-plugin.json` with content like this:

```json
{
    "name": "FilamentAccounts",
    "alias": "filament-accounts",
    "description": {
        "ar": "full accounts manager with API\/Notifications\/Contacts to manage your contacts and accounts",
        "en": "full accounts manager with API\/Notifications\/Contacts to manage your contacts and accounts",
        "gr": "full accounts manager with API\/Notifications\/Contacts to manage your contacts and accounts",
        "sp": "full accounts manager with API\/Notifications\/Contacts to manage your contacts and accounts"
    },
    "keywords": [],
    "priority": 0,
    "providers": [
        "Shebaoting\\FilamentAccounts\\FilamentAccountsServiceProvider"
    ],
    "files": [],
    "title": {
        "ar": "Filament Accounts",
        "en": "Filament Accounts",
        "gr": "Filament Accounts",
        "sp": "Filament Accounts"
    },
    "color": "#007dff",
    "icon": "heroicon-c-user-circle",
    "placeholder": "placeholder.webp",
    "type": "lib",
    "version": "v1.0",
    "github" : "https://github.com/Shebaoting/filament-accounts",
    "docs" : "https://github.com/Shebaoting/filament-accounts"
}
```

make sure you allow packages scan on the `filament-plugins.php` config file

```php
'scan' => true
```

## Use Selected Module in your panel

you can use the selected module in your panel by using this code in your PanelProvider

```php
->plugin(\Shebaoting\FilamentPlugins\FilamentPluginsPlugin::make()->modules([
    'CRM'
]))
```

so you will see only the selected modules in your panel

## Get Only Current Panel Plugins

on any plugin you can create a Page/Resource/Widget for selected panel, so if you need to showup only the current panel Page/Resources/Widgets you can use this code in your PanelProvider

```php
->plugin(\Shebaoting\FilamentPlugins\FilamentPluginsPlugin::make()->discoverCurrentPanelOnly())
```

## Stop Auto Discover Module Resources

you can stop auto-load module resources by using this code in your PanelProvider

```php
->plugin(\Shebaoting\FilamentPlugins\FilamentPluginsPlugin::make()->autoDiscoverModules(false))
```

## Stop Using Plugins UI

you can stop using the plugins UI by using this code in your PanelProvider

```php
->plugin(\Shebaoting\FilamentPlugins\FilamentPluginsPlugin::make()->useUI(false))
```

## Publish Assets

you can publish config file by use this command

```bash
php artisan vendor:publish --tag="filament-plugins-config"
```

you can publish views file by use this command

```bash
php artisan vendor:publish --tag="filament-plugins-views"
```

you can publish languages file by use this command

```bash
php artisan vendor:publish --tag="filament-plugins-lang"
```

you can publish migrations file by use this command

```bash
php artisan vendor:publish --tag="filament-plugins-migrations"
```

## Other Filament Packages

- [Filament Users](https://www.github.com/Shebaoting/filament-users)
- [Filament Translations](https://www.github.com/Shebaoting/filament-translations)
- [Filament Settings Hub](https://www.github.com/Shebaoting/filament-settings-hub)
- [Filament Alerts Sender](https://www.github.com/Shebaoting/filament-alerts)
- [Filament Accounts Builder](https://www.github.com/Shebaoting/filament-accounts)
- [Filament Wallet Manager](https://www.github.com/Shebaoting/filament-wallet)
- [Filament Artisan Runner](https://www.github.com/Shebaoting/filament-artisan)
- [Filament File Browser](https://www.github.com/Shebaoting/filament-browser)
- [Filament Developer Gate](https://www.github.com/Shebaoting/filament-developer-gate)
- [Filament Locations Seeder](https://www.github.com/Shebaoting/filament-locations)
- [Filament Menus Generator](https://www.github.com/Shebaoting/filament-menus)
- [Filament Splade Integration](https://www.github.com/Shebaoting/filament-splade)
- [Filament Types Manager](https://www.github.com/Shebaoting/filament-types)
- [Filament Icons Picker](https://www.github.com/Shebaoting/filament-icons)
- [Filament Helpers Classes](https://www.github.com/Shebaoting/filament-helpers)


## Support

you can join our discord server to get support [Shebaoting](https://discord.gg/Xqmt35Uh)

## Docs

you can check docs of this package on [Docs](https://docs.Shebaoting.com/filament/filament-plugins)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

Please see [SECURITY](SECURITY.md) for more information about security.

## Credits

- [Fady Mondy](https://wa.me/+201207860084)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
