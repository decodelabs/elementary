<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary\Style;

use Stringable;

interface Container
{
    /**
     * @param mixed ...$styles
     * @return $this
     */
    public function setStyles(...$styles): Container;

    /**
     * @param mixed ...$styles
     * @return $this
     */
    public function addStyles(...$styles): Container;

    public function getStyles(): Collection;

    /**
     * @param string|Stringable|int|float|null $value
     * @return $this
     */
    public function setStyle(string $key, $value): Container;

    public function getStyle(string $key): ?string;

    /**
     * @return $this
     */
    public function removeStyle(string ...$keys): Container;

    public function hasStyle(string ...$keys): bool;
    public function hasStyles(string ...$keys): bool;

    /**
     * @return $this
     */
    public function clearStyles(): Container;
}
