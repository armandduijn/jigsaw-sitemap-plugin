<?php

namespace Nawarian\JigsawSitemapPlugin\SitemapGenerator;

use Nawarian\JigsawSitemapPlugin\Config\BaseUrl;
use Nawarian\JigsawSitemapPlugin\Config\DestinationPath;
use Nawarian\JigsawSitemapPlugin\SitemapGenerator\LastModified\ImmutableCurrentTimeGenerator;
use Nawarian\JigsawSitemapPlugin\SitemapGenerator\LastModified\LastModifiedStrategy;
use samdark\sitemap\Sitemap;
use TightenCo\Jigsaw\Jigsaw;

class DefaultSitemapGenerator implements GeneratorInterface
{
    public function generate(Jigsaw $app): void
    {
        $baseUrl = BaseUrl::createFromString($app->getConfig('baseUrl'));
        $lastModifiedGeneratorClass = $app->getConfig('sitemap.lastModifiedStrategy')
            ?? ImmutableCurrentTimeGenerator::class;
        /** @var LastModifiedStrategy $lastModifiedGenerator */
        $lastModifiedGenerator = $app->app->make($lastModifiedGeneratorClass);
        $destinationPath = DestinationPath::createFromString(
            $app->getDestinationPath(),
            'sitemap.xml'
        );
        $sitemap = new Sitemap((string) $destinationPath);

        $blacklist = collect([])
            ->concat(['/assets/*', '*/favicon.ico', '*/404',])
            ->concat($app->getConfig('sitemap.blacklist') ?? []);

        collect($app->getOutputPaths())
            ->sortBy(function (string $path) {
                return $path;
            }, SORT_DESC, true)
            ->reject(function ($path) use ($blacklist) {
                return str_is($blacklist->values()->toArray(), $path);
            })
            ->each(function ($path) use ($baseUrl, $lastModifiedGenerator, $sitemap) {
                $url = rtrim((string) $baseUrl, '/') . $path;
                $lastModified = $lastModifiedGenerator->getLastModifiedTime($path);
                $sitemap->addItem($url, $lastModified, Sitemap::MONTHLY);
            });

        $sitemap->write();
    }
}
