<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary\Attribute;

use Countable;
use DecodeLabs\Nuance\Dumpable;
use DecodeLabs\Nuance\Entity\NativeObject as NuanceEntity;

class ClassList implements
    Countable,
    Dumpable
{
    /**
     * @var array<string,bool>
     */
    protected array $classes = [];

    public function __construct(
        string ...$classes
    ) {
        $this->add(...$classes);
    }

    /**
     * @return $this
     */
    public function add(
        ?string ...$classes
    ): static {
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

    public function has(
        string ...$classes
    ): bool {
        foreach ($classes as $class) {
            if (isset($this->classes[$class])) {
                return true;
            }
        }

        return false;
    }

    public function hasAll(
        string ...$classes
    ): bool {
        foreach ($classes as $class) {
            if (!isset($this->classes[$class])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return $this
     */
    public function remove(
        ?string ...$classes
    ): static {
        foreach ($classes as $class) {
            if ($class === null) {
                continue;
            }

            unset($this->classes[$class]);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function clear(): static
    {
        $this->classes = [];
        return $this;
    }

    public function count(): int
    {
        return count($this->classes);
    }

    /**
     * @return array<string>
     */
    public function toArray(): array
    {
        return array_keys($this->classes);
    }

    public function __toString(): string
    {
        return implode(' ', array_keys($this->classes));
    }


    public function toNuanceEntity(): NuanceEntity
    {
        $entity = new NuanceEntity($this);
        $entity->text = $this->__toString();
        return $entity;
    }
}
