<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary;

use ArrayAccess;
use DecodeLabs\Collections\AttributeContainer;

/**
 * @template TAttributeValue
 * @template TAttributeInput = TAttributeValue
 * @template TBuffer of Buffer = Buffer
 * @extends ArrayAccess<string,mixed>
 * @extends AttributeContainer<TAttributeValue,TAttributeInput>
 */
interface Tag extends
    Markup,
    AttributeContainer,
    ArrayAccess
{
    public ?string $tagName { get; set; }
    public ?string $id { get; set; }
    public bool $selfClosing { get; set; }
    public bool $renderEmpty { get; set; }

    public static function isSelfClosingTagName(
        string $name
    ): bool;

    public function isInline(): bool;
    public function isBlock(): bool;

    public function open(): string;
    public function close(): string;

    /**
     * @return ?TBuffer
     */
    public function renderWith(
        mixed $content = null,
        bool $pretty = false
    ): ?Buffer;
}
