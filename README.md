# Kirby Google My Business plugin

![GitHub release (with filter)](https://img.shields.io/github/v/release/Pixel-Open/kirby-googlemybusiness?style=for-the-badge)

[![Dependency](https://img.shields.io/badge/kirby-3.6-cca000.svg?style=for-the-badge)](https://getkirby.com/)

A plugin for [Kirby CMS](http://getkirby.com) to handle Google My Business data

## Commercial Usage

This plugin is free

## Installation

### Download

[Download the files](https://github.com/Pixel-Open/kirby-googlemybusiness/releases) and place them inside `site/plugins/kirby-googlemybusiness`.

### Composer

```
composer require pixelopen/kirby-googlemybusiness
```

### Git Submodule

You can add the plugin as a Git submodule.

    $ cd your/project/root
    $ git submodule add https://github.com/Pixel-Open/kirby-googlemybusiness.git site/plugins/kirby-googlemybusiness
    $ git submodule update --init --recursive
    $ git commit -am "Add Kirby Google My Business plugin"

Run these commands to update the plugin:

    $ cd your/project/root
    $ git submodule foreach git checkout master
    $ git submodule foreach git pull
    $ git commit -am "Update submodules"
    $ git submodule update --init --recursive

### Initialization

Run your website.
Add the tabs `tabs/googlemybusiness` to your `site.yml` blueprint.
You should see a new tab named Google My Business in the homepage of the panel.
From this page you can change data manually.

The plugin adds two snippets : `reviews` and `business_info`.
They respectivly create a reviews container and a button that will open a window with Google My Business data.
You can integrate them wherever you need them.

Both plugins are provided with a basic CSS style, it is up to you to modify them according to your website aspect.
You can overwrite propery by creating/modifiying a CSS file and adding `body` before class/id name

The plugin functionnalities work well with Kirby 3 and 4, but some dispositions issues may occur in the panel under Kirby 3.

## Options

To use the customs commands and update data automatically, you need to config the option `apiKey` and `placeId` in your config file.

To get the place ID, you can use this website : [Place ID Finder](https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder)

To get your API key, you must go to this page: [API Key](https://console.cloud.google.com/projectselector2/google/maps-apis/credentials)

```php
return [
    'pixelopen.googlemybusiness' => [
        'apiKey' => 'YOUR_API_KEY',
        'placeId' => 'YOUR_PLACE_ID'
    ]
];
```

Then you can use theses commands from the root directory of your project:
* ```gmb:sync```: Synchronize the data from Google My Business to your website
* ```gmb:reviews```: Get the reviews from customers and global rating from Google My Business
* ```gmb:init```: Create an empty structure for entering manually opening hours
* ```gmb:sort```: Sort both structure opening hours and reviews list, respectively by weekday and most recent
