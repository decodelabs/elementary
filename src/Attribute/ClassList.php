<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary\Attribute;

use Countable;

use DecodeLabs\Glitch\Dumpable;

class ClassList implements
    Countable,
    Dumpable
{
    /**
     * @var array<string, bool>
     */
    protected array $classes = [];

    /**
     * Init with list
     */
    public function __construct(string ...$classes)
    {
        $this->add(...$classes);
    }

    /**
     * Add class list
     *
     * @return $this
     */
    public function add(?string ...$classes): static
    {
        foreach ($classes as $value) {
            if ($value === null) {
                continue;
            }

            foreach (explode(' ', $value) as $class) {
                if (!strlen($class)) {
                    continue;
                }

                $this->classes[$class] = true;
            }
        }

        return $this;
    }

    /**
     * Has class(es) in list
     */
    public function has(string ...$classes): bool
    {
        foreach ($classes as $class) {
            if (isset($this->classes[$class])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Has all classes in list
     */
    public function hasAll(string ...$classes): bool
    {
        foreach ($classes as $class) {
            if (!isset($this->classes[$class])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Remove all classes in list
     *
     * @return $this
     */
    public function remove(?string ...$classes): static
    {
        foreach ($classes as $class) {
            if ($class === null) {
                continue;
            }

            unset($this->classes[$class]);
        }

        return $this;
    }

    /**
     * Clear class list
     *
     * @return $this
     */
    public function clear(): static
    {
        $this->classes = [];
        return $this;
    }

    /**
     * How many classes in list?
     */
    public function count(): int
    {
        return count($this->classes);
    }

    /**
     * Export to array
     *
     * @return array<string>
     */
    public function toArray(): array
    {
        return array_keys($this->classes);
    }

    /**
     * Render to string
     */
    public function __toString(): string
    {
        return implode(' ', array_keys($this->classes));
    }

    /**
     * Export for dump inspection
     *
     * @return iterable<string, mixed>
     */
    public function glitchDump(): iterable
    {
        yield 'text' => $this->__toString();
    }
}
