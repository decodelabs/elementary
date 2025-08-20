<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary;

use DecodeLabs\Nuance\Entity\NativeObject as NuanceEntity;

/**
 * @phpstan-require-implements Buffer
 */
trait BufferTrait
{
    public bool $escaped = false;
    protected string $content = '';

    public function __construct(
        ?string $content,
        bool $escaped = false
    ) {
        $this->content = (string)$content;
        $this->escaped = $escaped;
    }

    public function __toString(): string
    {
        return $this->content;
    }

    public function prepend(
        ?string $content
    ): static {
        $this->content = $content . $this->content;
        return $this;
    }

    public function append(
        ?string $content
    ): static {
        $this->content .= $content;
        return $this;
    }

    public function replace(
        ?string $content
    ): static {
        $this->content = (string)$content;
        return $this;
    }

    public function isEmpty(): bool
    {
        return $this->content === '';
    }

    public function jsonSerialize(): mixed
    {
        return (string)$this;
    }


    public function toNuanceEntity(): NuanceEntity
    {
        $entity = new NuanceEntity($this);
        $entity->definition = $this->content;
        return $entity;
    }
}
