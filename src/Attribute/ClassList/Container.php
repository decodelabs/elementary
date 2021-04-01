<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary\Attribute\ClassList;

use DecodeLabs\Collections\AttributeContainer;
use DecodeLabs\Elementary\Attribute\ClassList;

interface Container extends AttributeContainer
{
    /**
     * @param mixed ...$classes
     * @return $this
     */
    public function setClasses(...$classes): Container;

    /**
     * @param mixed ...$classes
     * @return $this
     */
    public function addClasses(...$classes): Container;

    public function getClasses(): ClassList;

    /**
     * @return $this
     */
    public function setClass(?string ...$classes): Container;

    /**
     * @return $this
     */
    public function addClass(?string ...$classes): Container;

    /**
     * @return $this
     */
    public function removeClass(?string ...$classes): Container;

    public function hasClass(string ...$classes): bool;
    public function hasClasses(string ...$classes): bool;

    /**
     * @return $this
     */
    public function clearClasses(): Container;

    public function countClasses(): int;
}
