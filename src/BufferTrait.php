<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary;

/**
 * @phpstan-require-implements Buffer
 */
trait BufferTrait
{
    public bool $escaped = false;
    protected string $content = '';

    /**
     * Init with content
     */
    public function __construct(
        ?string $content,
        bool $escaped = false
    ) {
        $this->content = (string)$content;
        $this->escaped = $escaped;
    }

    /**
     * Render content to string
     */
    public function __toString(): string
    {
        return $this->content;
    }

    /**
     * Add content to start
     */
    public function prepend(
        ?string $content
    ): static {
        $this->content = $content . $this->content;
        return $this;
    }

    /**
     * Add content to end
     */
    public function append(
        ?string $content
    ): static {
        $this->content .= $content;
        return $this;
    }

    /**
     * Replace content
     */
    public function replace(
        ?string $content
    ): static {
        $this->content = (string)$content;
        return $this;
    }

    /**
     * Is there any content here?
     */
    public function isEmpty(): bool
    {
        return $this->content === '';
    }

    /**
     * Serialize to json
     */
    public function jsonSerialize(): mixed
    {
        return (string)$this;
    }


    /**
     * Export for dump inspection
     *
     * @return iterable<string,mixed>
     */
    public function glitchDump(): iterable
    {
        yield 'definition' => $this->content;
    }
}
