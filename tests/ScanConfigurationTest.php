<?php

namespace Tests;

use Gravatalonga\ConfigurationScan\ConfigurationDomainNotValid;
use Gravatalonga\ConfigurationScan\Scan;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Gravatalonga\ConfigurationScan\Scan
 */
class ScanConfigurationTest extends TestCase
{
    /**
     * @test
     */
    public function scan_parent_domains ()
    {
        $scan = new Scan(__DIR__ . '/stubs/happy_path/');

        $this->assertNotEmpty($scan->containers());
        $this->assertArrayHasKey('jonathan.pt', $scan->containers());
        $this->assertIsObject($scan->containers()['jonathan.pt']);
    }

    /**
     * @test
     */
    public function scan_empty_directories ()
    {
        $scan = new Scan(__DIR__ . '/stubs/empty_directory/');

        $this->assertEmpty($scan->containers());
    }

    /**
     * @test
     */
    public function scan_directory_with_empty_config ()
    {
        $scan = new Scan(__DIR__ . '/stubs/no_config/');

        $this->assertEmpty($scan->containers());
    }

    /**
     * @test
     */
    public function config_not_valid ()
    {
        $this->expectException(ConfigurationDomainNotValid::class);
        $this->expectExceptionMessage('example.com has configuration mal formed');

        $scan = new Scan(__DIR__ . '/stubs/not_valid_config/');
        $scan->containers();
    }

    /**
     * @test
     */
    public function scan_multiple_domains ()
    {
        $scan = new Scan(__DIR__ . '/stubs/multiple_domain/');
        $domains = $scan->containers();

        $this->assertNotEmpty($domains);
        $this->assertCount(2, $domains);
    }

    /**
     * @test
     */
    public function scan_checkers_by_domain ()
    {
        $scan = new Scan(__DIR__ . '/stubs/happy_path/');

        $checks = $scan->configurations('jonathan.pt');

        $this->assertNotEmpty($checks);
        $this->assertCount(1, $checks);
        $this->assertArrayHasKey('ping', $checks);
    }
}