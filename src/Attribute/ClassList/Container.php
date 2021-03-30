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
    public function setClasses(...$classes): Container;
    public function addClasses(...$classes): Container;
    public function getClasses(): ClassList;
    public function setClass(?string ...$classes): Container;
    public function addClass(?string ...$classes): Container;
    public function removeClass(?string ...$classes): Container;
    public function hasClass(string ...$classes): bool;
    public function hasClasses(string ...$classes): bool;
    public function clearClasses(): Container;
    public function countClasses(): int;
}
