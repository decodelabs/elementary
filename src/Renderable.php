<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary;

/**
 * @template TBuffer of Buffer
 */
interface Renderable
{
    /**
     * @return ?TBuffer
     */
    public function render(
        bool $pretty = false
    ): ?Buffer;
}
