<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary;

use DecodeLabs\Collections\SequenceInterface;

/**
 * @template TAttributeValue
 * @template TAttributeInput = TAttributeValue
 * @template TBuffer of Buffer = Buffer
 * @extends SequenceInterface<mixed>
 * @extends Tag<TAttributeValue,TAttributeInput>
 * @extends Renderable<TBuffer>
 */
interface Element extends
    Tag,
    Renderable,
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

    /**
     * @return ?TBuffer
     */
    public function renderContent(
        bool $pretty = false
    ): ?Buffer;
}
