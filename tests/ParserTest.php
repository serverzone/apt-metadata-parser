<?php declare(strict_types=1);

namespace ServerZone\AptMetadataParser\Tests;

use PHPUnit\Framework\TestCase;
use ServerZone\AptMetadataParser\Parser;

/**
 * Package parser test.
 */
final class ParserTest extends TestCase
{
    /**
     * Parse packages from .gz file test.
     *
     * @return void
     */
    public function testParseFromFile(): void
    {
        $content = file_get_contents('compress.zlib://' . __DIR__ . '/Packages.gz');
        $parser = new Parser($content);

        // number of total packages
        $this->assertSame(11, $parser->count());

        /** @var array<PackageDescription> $package descriptions of dkms package */
        $package = $parser->getPackage('dkms');
        $this->assertCount(1, $package);
        $this->assertSame('dkms', $package[0]->getIndex('Package'));
        $this->assertSame('optional', $package[0]->getIndex('Priority'));
        $this->assertSame('kernel', $package[0]->getIndex('Section'));
        $this->assertSame('278', $package[0]->getIndex('Installed-Size'));
        $this->assertSame('Dynamic Kernel Modules Support Team <dkms@tracker.debian.org>', $package[0]->getIndex('Maintainer'));
        $this->assertSame('all', $package[0]->getIndex('Architecture'));
        $this->assertSame('2.6.1-4~bpo9+1', $package[0]->getIndex('Version'));
        $this->assertSame('kmod | kldutils, gcc, dpkg-dev, make | build-essential, coreutils (>= 7.4), patch', $package[0]->getIndex('Depends'));
        $this->assertSame('fakeroot, sudo, linux-headers-686-pae | linux-headers-amd64 | linux-headers-generic | linux-headers, lsb-release', $package[0]->getIndex('Recommends'));
        $this->assertSame('python3-apport, menu', $package[0]->getIndex('Suggests'));
        $this->assertSame('pool/main/d/dkms/dkms_2.6.1-4~bpo9+1_all.deb', $package[0]->getIndex('Filename'));
        $this->assertSame('74488', $package[0]->getIndex('Size'));
        $this->assertSame('4abd7756f1175d05d3e969b07fa36d92', $package[0]->getIndex('MD5sum'));
        $this->assertSame('04d36857d938d3353df573973cff57e5272b5d5f', $package[0]->getIndex('SHA1'));
        $this->assertSame('049dc24388e0e96aba4a5b0ac1aea8369f152795fe583c2b0671995bc6bd9d35', $package[0]->getIndex('SHA256'));
        $this->assertSame('47310dc335444a945b4998339cb9a5bd0ece3764b257dea9ec967bdd762760c4c3eddf77dacc310996a7c51285a19e884adf2d281455e6a40e94f34db271a233', $package[0]->getIndex('SHA512'));
        $this->assertSame('Dynamic Kernel Module Support Framework
DKMS is a framework designed to allow individual kernel modules to be upgraded
without changing the whole kernel. It is also very easy to rebuild modules as
you upgrade kernels.', $package[0]->getIndex('Description'));
        $this->assertSame('foreign', $package[0]->getIndex('Multi-Arch'));
        $this->assertSame('https://github.com/dell-oss/dkms', $package[0]->getIndex('Homepage'));

        $this->assertSame('kernel', $package[0]->getIndex('Section'));
        $this->assertSame('http://ftp.debian.org/debian/pool/main/d/dkms/dkms_2.6.1-4~bpo9+1_all.deb', $package[0]->getFileUrl('http://ftp.debian.org/debian'));

        // descriptions of geoip-database package
        $this->assertCount(2, $parser->getPackage('geoip-database'));

        // descriptions of foo package
        $this->assertNull($parser->getPackage('foo'));

        // check dependencies
        $dependencies = $package[0]->getDependencies();
        $this->assertCount(6, $dependencies);
        $this->assertSame('kmod', $dependencies[0]->getName());
        $this->assertSame('kldutils', $dependencies[0]->getAlternativeName());
        $this->assertSame('gcc', $dependencies[1]->getName());
        $this->assertNull($dependencies[1]->getAlternativeName());
        $this->assertSame('coreutils', $dependencies[4]->getName());
    }

    /**
     * Parse packages from url test.
     *
     * @return void
     */
    public function testParseFromUrl(): void
    {
        $content = file_get_contents('compress.zlib://http://ftp.debian.org/debian/dists/stretch-backports/main/binary-amd64/Packages.gz');
        $parser = new Parser($content);

        // number of total packages
        $this->assertGreaterThan(4000, $parser->count());

        // descriptions of dkms package
        $package = $parser->getPackage('dkms');
        $this->assertCount(1, $package);
        $this->assertSame('kernel', $package[0]->getIndex('Section'));

        // descriptions of foo package
        $this->assertNull($parser->getPackage('foo'));
    }
}
