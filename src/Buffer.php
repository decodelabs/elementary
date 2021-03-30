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
    public function prepend(?string $content): Buffer;
    public function append(?string $content): Buffer;
    public function replace(?string $content): Buffer;
    public function isEmpty(): bool;
}
