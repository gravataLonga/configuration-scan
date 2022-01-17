<?php

namespace Gravatalonga\ConfigurationScan;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Scan
{
    private const CONFIGURATION_NAME = 'config';

    private string $root;

    public function __construct(string $root)
    {
        $this->root = $root;
    }

    public function containers(): array
    {
        $directories = $this->createFinderForDirectories();

        $domains = [];
        foreach ($directories as $domainFinder) {
            if (!$this->hasConfig($domainFinder)) {
                continue;
            }
            $domains[$domainFinder->getBasename()] = $this->configContent($domainFinder);
        }
        return $domains;
    }

    public function configurations(string $domain): array
    {
        $configurationFinder = $this->createFinderForDomainConfigurations($domain);

        $configurations = [];
        foreach ($configurationFinder as $configuration) {
            $configurations[$configuration->getFilenameWithoutExtension()] = json_decode($configuration->getContents());
        }
        return $configurations;
    }

    private function hasConfig(SplFileInfo $finder): bool
    {
        return file_exists($finder->getPathname() . '/'. self::CONFIGURATION_NAME . '.json');
    }

    /**
     * @throws ConfigurationDomainNotValid
     */
    private function configContent(SplFileInfo $finder): \stdClass
    {
        $config = json_decode(file_get_contents($finder->getPathname() . '/'. self::CONFIGURATION_NAME . '.json'));
        if (empty($config)) {
            throw new ConfigurationDomainNotValid($finder->getPathname());
        }
        return $config;
    }

    private function createFinderForDirectories(): Finder
    {
        return (new Finder())
            ->directories()
            ->depth('== 0')
            ->in($this->root);
    }

    private function createFinderForDomainConfigurations(string $domain): Finder
    {
        return (new Finder())
            ->files()
            ->depth('== 0')
            ->name('*.json')
            ->notName('config.json')
            ->in($this->root . $domain);
    }
}