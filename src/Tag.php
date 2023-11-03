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
 * @extends \ArrayAccess<string, mixed>
 */
interface Tag extends
    Markup,
    AttributeContainer,
    ArrayAccess
{
    /**
     * @return $this
     */
    public function setName(
        string $name
    ): static;

    public function getName(): string;

    public static function isClosableTagName(
        string $name
    ): bool;

    /**
     * @return $this
     */
    public function setId(
        ?string $id
    ): static;

    public function getId(): ?string;

    public function isInline(): bool;
    public function isBlock(): bool;

    public function open(): string;
    public function close(): string;

    /**
     * @return $this
     */
    public function setClosable(
        bool $closable
    ): static;

    public function isClosable(): bool;

    public function renderWith(
        mixed $content = null,
        bool $pretty = false
    ): ?Markup;

    /**
     * @return $this
     */
    public function setRenderEmpty(
        bool $render
    ): static;

    public function willRenderEmpty(): bool;
}
