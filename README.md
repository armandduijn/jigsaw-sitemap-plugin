Jigsaw Sitemap Plugin
----
[![Build Status](https://travis-ci.org/nawarian/jigsaw-sitemap-plugin.svg?branch=master)](https://travis-ci.org/nawarian/jigsaw-sitemap-plugin)
[![codecov](https://codecov.io/gh/nawarian/jigsaw-sitemap-plugin/branch/master/graph/badge.svg)](https://codecov.io/gh/nawarian/jigsaw-sitemap-plugin)

Jigsaw Sitemap Plugin is an easy and straight forward plugin
to be used on Jigsaw's `afterBuild` event.

It fetches all generated pages and generates a sitemap file
containing such entries based on configurations.

### Usage

On your Jigsaw project, require this package:
```bash
$ composer require nawarian/jigsaw-sitemap-plugin
``` 

On your `bootstrap.php` file, register the Listener:

```php
use Nawarian\JigsawSitemapPlugin\Listener\SitemapListener;

/** @var $container \Illuminate\Container\Container */
/** @var $events \TightenCo\Jigsaw\Events\EventBus */

$events->afterBuild([SitemapListener::class]);
```

After running the build you should see a `sitemap.xml` file
inside build's output folder.

### Configuration

To prevent certain pages from being included in the sitemap, add
the following setting to your project's `config.php` file.

```php
<?php

return [
    ...
    'sitemap' => [
        'blacklist' => [
            '*/submit',
            '404.html'
        ]
    ]
    ...
```

You may use asterisks to indicate wildcards.
