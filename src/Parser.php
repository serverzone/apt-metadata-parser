<?php declare(strict_types=1);

namespace ServerZone\AptMetadataParser;

/**
 * Apt metadata parser.
 *
 * Example of usage:
 *    $content = file_get_contents('compress.zlib://Packages.gz');
 *    $parser = new Parser($content);
 *    $description = $parser->getPackage('claws-mail');
 */
final class Parser
{
    /** @var array<array<PackageDescription>> Packages */
    private $packages = [];

    /**
     * Class costructor.
     *
     * @param string $content Packages content
     */
    public function __construct(string $content)
    {
        $lines = explode(PHP_EOL, $content);
        $this->packages = $this->parse($lines);
    }

    /**
     * Return number of items.
     *
     * @return integer
     */
    public function count(): int
    {
        return count($this->packages);
    }

    /**
     * Return package descriptions.
     *
     * @param string $name Package name
     * @return array<PackageDescription>|null
     */
    public function getPackage(string $name): ?array
    {
        return $this->packages[$name] ?? null;
    }

    /**
     * Parse packages content to array.
     *
     * @param array<string> $lines Packages content lines
     * @return array<array<PackageDescription>>
     */
    protected function parse(array $lines): array
    {
        $description = [];
        $tag = null;
        $value = '';
        $package = null;
        $package = null;
        foreach ($lines as $line) {
            if (strlen($line) == 0) {
                if ($package !== null && $tag !== null) {
                    $package->setIndex($tag, $value);
                }
                $package = null;
            } else if (substr($line, 0, 1) == " ") {
                $value .= PHP_EOL;
                if ($line != " .") {
                    $value .= substr($line, 1);
                }
            } else {
                if ($package !== null && $tag !== null) {
                    $package->setIndex($tag, $value);
                }
                $pair = explode(': ', $line);
                if (count($pair) == 2) {
                    $tag = $pair[0];
                    $value = $pair[1];

                    if ($tag == "Package") {
                        $package = new PackageDescription($value);
                        if (isset($description[$value]) === false) {
                            $description[$value] = [];
                        }
                        $description[$value][] = $package;
                    }
                }
            }
        }

        return $description;
    }
}
