<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary;

use DecodeLabs\Collections\Sequence;
use Traversable;

/**
 * @extends Traversable<int, mixed>
 */
interface Element extends Tag, Sequence, Traversable
{
    /**
     * @param mixed $body
     */
    public function setBody($body): Element;

    public function render(bool $pretty = false): ?Buffer;
    public function renderContent(bool $pretty = false): ?Buffer;
}
