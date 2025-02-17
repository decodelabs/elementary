<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary\Attribute\ClassList;

use DecodeLabs\Coercion;
use DecodeLabs\Collections\ArrayUtils;
use DecodeLabs\Elementary\Attribute\ClassList;

/**
 * @phpstan-require-implements Container
 */
trait ContainerTrait
{
    /**
     * Replace class list
     *
     * @return $this
     */
    public function setClasses(
        mixed ...$classes
    ): static {
        $this->clearClasses();
        $this->addClasses(...$classes);
        return $this;
    }

    /**
     * Add class set to list
     *
     * @return $this
     */
    public function addClasses(
        mixed ...$classes
    ): static {
        $classes = ArrayUtils::collapse($classes, false, true, true);
        $classes = array_map(fn ($class) => Coercion::toString($class), $classes);

        $this->getClasses()->add(...$classes);
        return $this;
    }

    /**
     * Get class list from attribute set
     */
    public function getClasses(): ClassList
    {
        if (!isset($this->attributes['class'])) {
            $this->attributes['class'] = new ClassList();
        }

        if (!$this->attributes['class'] instanceof ClassList) {
            $this->attributes['class'] = new ClassList(
                Coercion::toString($this->attributes['class'])
            );
        }

        return $this->attributes['class'];
    }

    /**
     * Add class set to list
     *
     * @return $this
     */
    public function setClass(
        ?string ...$classes
    ): static {
        $this->getClasses()->clear()->add(...$classes);
        return $this;
    }

    /**
     * Get class list from attribute set
     *
     * @return $this
     */
    public function addClass(
        ?string ...$classes
    ): static {
        $this->getClasses()->add(...$classes);
        return $this;
    }

    /**
     * Remove set of classes from list
     *
     * @return $this
     */
    public function removeClass(
        ?string ...$classes
    ): static {
        $this->getClasses()->remove(...$classes);
        return $this;
    }

    /**
     * Does class list have any of these?
     */
    public function hasClass(
        string ...$classes
    ): bool {
        return $this->getClasses()->has(...$classes);
    }

    /**
     * Does class list have ALL of these?
     */
    public function hasClasses(
        string ...$classes
    ): bool {
        return $this->getClasses()->hasAll(...$classes);
    }

    /**
     * Reset class list
     *
     * @return $this
     */
    public function clearClasses(): static
    {
        $this->getClasses()->clear();
        return $this;
    }

    /**
     * How many classes do we have?
     */
    public function countClasses(): int
    {
        return $this->getClasses()->count();
    }
}
