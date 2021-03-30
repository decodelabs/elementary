<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary;

use DecodeLabs\Collections\Sequence;

interface Element extends Tag, Sequence
{
    public const MUTABLE = true;

    /**
     * @param mixed $body
     * @return Element<int, mixed>
     */
    public function setBody($body): Element;

    public function render(bool $pretty = false): ?Buffer;
    public function renderContent(bool $pretty = false): ?Buffer;
}
