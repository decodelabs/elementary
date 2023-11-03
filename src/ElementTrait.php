<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary;

use DecodeLabs\Collections\Native\SequenceTrait;

trait ElementTrait
{
    /**
     * @use SequenceTrait<mixed>
     */
    use SequenceTrait;

    // public const MUTABLE = true;

    /**
     * Init with name, content and attributes
     */
    public function __construct(
        string $name,
        mixed $content,
        array $attributes = null
    ) {
        parent::__construct($name, $attributes);

        if (
            !is_iterable($content) ||
            $content instanceof Markup
        ) {
            $content = $content === null ? [] : [$content];
        }

        $this->merge($content);
    }


    /**
     * Normalize body content to individual items
     *
     * @return $this
     */
    public function normalize(): static
    {
        $items = [];

        foreach ($this->items as $item) {
            foreach ($this->normalizeChild($item, true) as $child) {
                $items[] = $child;
            }
        }

        $this->items = $items;
        return $this;
    }



    /**
     * Render to more readable string (for dump)
     */
    public function render(
        bool $pretty = false
    ): ?Buffer {
        if (null === ($output = $this->renderWith(
            $this->renderContent($pretty),
            $pretty
        ))) {
            return null;
        }

        return $this->newBuffer((string)$output);
    }

    /**
     * Render inner content
     */
    public function renderContent(
        bool $pretty = false
    ): ?Buffer {
        $output = '';

        foreach ($this->items as $value) {
            if (empty($value) && $value != '0') {
                continue;
            }

            $output .= $this->renderChild($value, $pretty);
        }

        if (empty($output) && $output != '0') {
            return null;
        }

        return $this->newBuffer($output);
    }

    /**
     * Replace all content with new body
     */
    public function setBody(
        mixed $body
    ): static {
        $this->clear();
        $this->append($body);
        return $this;
    }


    /**
     * Export for dump inspection
     *
     * @return iterable<string, mixed>
     */
    public function glitchDump(): iterable
    {
        $renderEmpty = $this->renderEmpty;
        $this->renderEmpty = true;
        $def = (string)$this->render(true);
        $this->renderEmpty = $renderEmpty;

        if (!$renderEmpty) {
            $def = '<?' . substr($def, 1);
        }

        yield 'className' => $this->name;
        yield 'definition' => $def;

        yield 'properties' => [
            '*renderEmpty' => $this->renderEmpty,
            '*attributes' => $this->attributes,
        ];

        yield 'values' => $this->items;

        yield 'sections' => [
            'properties' => false,
            'values' => false
        ];
    }
}
