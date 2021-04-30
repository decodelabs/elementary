<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary;

interface Buffer extends Markup
{
    public function __construct(?string $content);

    /**
     * @return $this
     */
    public function prepend(?string $content): Buffer;

    /**
     * @return $this
     */
    public function append(?string $content): Buffer;

    /**
     * @return $this
     */
    public function replace(?string $content): Buffer;

    public function isEmpty(): bool;
}
