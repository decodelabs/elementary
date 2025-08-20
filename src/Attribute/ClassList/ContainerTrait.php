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
    public ClassList $classList {
        get => $this->getClasses();
    }

    /**
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
     * @return $this
     */
    public function addClasses(
        mixed ...$classes
    ): static {
        $classes = ArrayUtils::collapse($classes, false, true, true);
        $classes = array_map(fn ($class) => Coercion::asString($class), $classes);

        $this->getClasses()->add(...$classes);
        return $this;
    }

    public function getClasses(): ClassList
    {
        if (!isset($this->attributes['class'])) {
            $this->attributes['class'] = new ClassList();
        }

        if (!$this->attributes['class'] instanceof ClassList) {
            $this->attributes['class'] = new ClassList(
                Coercion::asString($this->attributes['class'])
            );
        }

        return $this->attributes['class'];
    }

    /**
     * @return $this
     */
    public function setClass(
        ?string ...$classes
    ): static {
        $this->getClasses()->clear()->add(...$classes);
        return $this;
    }

    /**
     * @return $this
     */
    public function addClass(
        ?string ...$classes
    ): static {
        $this->getClasses()->add(...$classes);
        return $this;
    }

    /**
     * @return $this
     */
    public function removeClass(
        ?string ...$classes
    ): static {
        $this->getClasses()->remove(...$classes);
        return $this;
    }

    public function hasClass(
        string ...$classes
    ): bool {
        return $this->getClasses()->has(...$classes);
    }

    public function hasClasses(
        string ...$classes
    ): bool {
        return $this->getClasses()->hasAll(...$classes);
    }

    /**
     * @return $this
     */
    public function clearClasses(): static
    {
        $this->getClasses()->clear();
        return $this;
    }

    public function countClasses(): int
    {
        return $this->getClasses()->count();
    }
}
