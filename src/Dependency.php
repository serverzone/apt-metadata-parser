<?php declare(strict_types=1);

namespace ServerZone\AptMetadataParser;

/**
 * Package dependency.
 */
final class Dependency
{

    /** @var string Package name */
    private $name;

    /** @var string|null Alternative package name */
    private $alternative_name = null;

    /**
     * Class constructor.
     *
     * @param string $value Dependency string representation
     */
    public function __construct(string $value)
    {
        if (strpos($value, '|') > 0) {
            $exp = explode('|', $value);
            $this->name = trim($exp[0]);
            $this->alternative_name = trim($exp[1]);
        } elseif (strpos($value, '(') > 0) {
            $this->name = trim(substr($value, 0, strpos($value, '(') - 1));
        } else {
            $this->name = trim($value);
        }
    }

    /**
     * Return package name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Return alternative package name.
     *
     * @return string|null
     */
    public function getAlternativeName(): ?string
    {
        return $this->alternative_name;
    }
}
