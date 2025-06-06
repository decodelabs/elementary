<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary;

interface Buffer extends Markup
{
    public bool $escaped { get; set; }

    public function __construct(
        ?string $content,
        bool $escaped = false
    );

    /**
     * @return $this
     */
    public function prepend(
        ?string $content
    ): static;

    /**
     * @return $this
     */
    public function append(
        ?string $content
    ): static;

    /**
     * @return $this
     */
    public function replace(
        ?string $content
    ): static;

    public function isEmpty(): bool;
}
