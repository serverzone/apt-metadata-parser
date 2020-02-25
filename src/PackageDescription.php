<?php declare(strict_types=1);

namespace ServerZone\AptMetadataParser;

/**
 * Package description.
 */
final class PackageDescription
{

    /** @var string Package name */
    private $name;

    /** @var array<string> Indicies */
    private $indicies = [];

    /**
     * Class constructor.
     *
     * @param string $name Package name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Return value of index.
     *
     * @param string $name Index name
     * @return string|null
     */
    public function getIndex(string $name): ?string
    {
        return $this->indicies[$name] ?? null;
    }

    /**
     * Set index value.
     *
     * @param string $name Index name
     * @param string $value Index value
     * @return void
     */
    public function setIndex(string $name, string $value): void
    {
        $this->indicies[$name] = $value;
    }

    /**
     * Return download url of file.
     *
     * @param string $prefix Url prefix (e.g. http://ftp.debian.org/debian)
     * @return string
     */
    public function getFileUrl(string $prefix): string
    {
        return sprintf('%s/%s', $prefix, $this->getIndex('Filename'));
    }

    /**
     * Return dependencies.
     *
     * @return array<Dependency>
     */
    public function getDependencies(): array
    {
        $dependencies = [];
        $dependsIndex = $this->getIndex('Depends');
        if ($dependsIndex !== null) {
            foreach (explode(',', $dependsIndex) as $dependency) {
                $dependencies[] = new Dependency($dependency);
            }
        }

        return $dependencies;
    }
}
