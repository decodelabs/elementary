<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary\Style;

interface Container
{
    /**
     * @param mixed ...$styles
     */
    public function setStyles(...$styles): Container;

    /**
     * @param mixed ...$styles
     */
    public function addStyles(...$styles): Container;

    public function getStyles(): Collection;
    public function setStyle(string $key, ?string $value): Container;
    public function getStyle(string $key): ?string;
    public function removeStyle(string ...$keys): Container;
    public function hasStyle(string ...$keys): bool;
    public function hasStyles(string ...$keys): bool;
    public function clearStyles(): Container;
}
