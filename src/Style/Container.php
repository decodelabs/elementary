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
    public Collection $style { get; }

    /**
     * @return $this
     */
    public function setStyles(
        mixed ...$styles
    ): static;

    /**
     * @return $this
     */
    public function addStyles(
        mixed ...$styles
    ): static;

    public function getStyles(): Collection;

    /**
     * @return $this
     */
    public function setStyle(
        string $key,
        string|Stringable|int|float|null $value
    ): static;

    public function getStyle(
        string $key
    ): ?string;

    /**
     * @return $this
     */
    public function removeStyle(
        string ...$keys
    ): static;

    public function hasStyle(
        string ...$keys
    ): bool;

    public function hasStyles(
        string ...$keys
    ): bool;

    /**
     * @return $this
     */
    public function clearStyles(): static;
}
