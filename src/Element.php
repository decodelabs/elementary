<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary;

use DecodeLabs\Collections\SequenceInterface;

/**
 * @extends SequenceInterface<mixed>
 */
interface Element extends
    Tag,
    SequenceInterface
{
    /**
     * @return $this
     */
    public function setBody(
        mixed $body
    ): static;

    /**
     * @return $this
     */
    public function normalize(): static;

    public function render(
        bool $pretty = false
    ): ?Buffer;

    public function renderContent(
        bool $pretty = false
    ): ?Buffer;
}
