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
     * @return $this
     */
    public function setClasses(
        mixed ...$classes
    ): static;

    /**
     * @return $this
     */
    public function addClasses(
        mixed ...$classes
    ): static;

    public function getClasses(): ClassList;

    /**
     * @return $this
     */
    public function setClass(
        ?string ...$classes
    ): static;

    /**
     * @return $this
     */
    public function addClass(
        ?string ...$classes
    ): static;

    /**
     * @return $this
     */
    public function removeClass(
        ?string ...$classes
    ): static;

    public function hasClass(
        string ...$classes
    ): bool;

    public function hasClasses(
        string ...$classes
    ): bool;

    /**
     * @return $this
     */
    public function clearClasses(): static;

    public function countClasses(): int;
}
