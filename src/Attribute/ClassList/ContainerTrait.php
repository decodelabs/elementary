<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary\Attribute\ClassList;

use DecodeLabs\Collections\ArrayUtils;
use DecodeLabs\Elementary\Attribute\ClassList;

trait ContainerTrait
{
    /**
     * Replace class list
     *
     * @param mixed ...$classes
     * @return $this
     */
    public function setClasses(...$classes): Container
    {
        /** @var array<string> $classes */
        $classes = ArrayUtils::collapse($classes, false, true, true);
        $this->getClasses()->clear()->add(...$classes);
        return $this;
    }

    /**
     * Add class set to list
     *
     * @param mixed ...$classes
     * @return $this
     */
    public function addClasses(...$classes): Container
    {
        /** @var array<string> $classes */
        $classes = ArrayUtils::collapse($classes, false, true, true);
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

        return $this->attributes['class'];
    }

    /**
     * Add class set to list
     *
     * @return $this
     */
    public function setClass(?string ...$classes): Container
    {
        $this->getClasses()->clear()->add(...$classes);
        return $this;
    }

    /**
     * Get class list from attribute set
     *
     * @return $this
     */
    public function addClass(?string ...$classes): Container
    {
        $this->getClasses()->add(...$classes);
        return $this;
    }

    /**
     * Remove set of classes from list
     *
     * @return $this
     */
    public function removeClass(?string ...$classes): Container
    {
        $this->getClasses()->remove(...$classes);
        return $this;
    }

    /**
     * Does class list have any of these?
     */
    public function hasClass(string ...$classes): bool
    {
        return $this->getClasses()->has(...$classes);
    }

    /**
     * Does class list have ALL of these?
     */
    public function hasClasses(string ...$classes): bool
    {
        return $this->getClasses()->hasAll(...$classes);
    }

    /**
     * Reset class list
     *
     * @return $this
     */
    public function clearClasses(): Container
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
