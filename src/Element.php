<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary;

use DecodeLabs\Collections\Sequence;

/**
 * @extends Sequence<mixed>
 */
interface Element extends
    Tag,
    Sequence
{
    public const MUTABLE = true;

    /**
     * @return $this
     */
    public function setBody(mixed $body): static;

    /**
     * @return $this
     */
    public function normalize(): static;

    public function render(bool $pretty = false): ?Buffer;
    public function renderContent(bool $pretty = false): ?Buffer;
}
