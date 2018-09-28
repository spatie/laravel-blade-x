<?php

namespace Spatie\BladeX;

use Illuminate\Support\Facades\File;
use Spatie\BladeX\Exceptions\CouldNotRegisterComponent;
use Symfony\Component\DomCrawler\Crawler;

class BladeX
{
    /** @var array */
    public $registeredComponents = [];

    public function component(string $componentName, string $classOrView)
    {
        $component = $this->getComponent($classOrView);

        if (! $component) {
            throw CouldNotRegisterComponent::componentNotFound($componentName, $classOrView);
        }

        $this->registeredComponents[$componentName] = $component;
    }

    public function components(string $directory)
    {
        if (! File::isDirectory($directory)) {
            throw CouldNotRegisterComponent::componentDirectoryNotFound($directory);
        }

        dd(File::allFiles($directory));
    }

    protected function getComponent(string $classOrView): ?object
    {
        if (class_exists($classOrView)) {
            return app($classOrView);
        }

        if (view()->exists($classOrView)) {
            return new BladeViewComponent($classOrView);
        }

        return null;
    }

    public function compile(string $view): string
    {
        $crawler = new Crawler($view);

        foreach($this->registeredComponents as $componentName => $classOrView) {
            $crawler
                ->filter($componentName)
                ->each(function (Crawler $subCrawler) use ($classOrView) {
                    $node = $subCrawler->getNode(0);

                    $node->parentNode->replaceChild(
                        $node->ownerDocument->createTextNode("@include({$classOrView})"), // TEMP: @include everything
                        $node
                    );
                });
        }

        return $crawler->html();
    }
}
