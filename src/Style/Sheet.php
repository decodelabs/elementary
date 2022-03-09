<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary\Style;

use ArrayIterator;

use DecodeLabs\Collections\ArrayProvider;
use DecodeLabs\Exceptional;
use DecodeLabs\Glitch\Dumpable;

use IteratorAggregate;
use Throwable;
use Traversable;

/**
 * @implements IteratorAggregate<string, Collection>
 */
class Sheet implements IteratorAggregate, Dumpable
{
    public const MUTABLE = true;

    /**
     * @var array<string, Collection>
     */
    protected $blocks = [];

    /**
     * Init with styles
     *
     * @param mixed ...$input
     */
    public function __construct(...$input)
    {
        $this->import(...$input);
    }

    /**
     * Import style data
     *
     * @param mixed ...$input
     */
    public function import(...$input): Sheet
    {
        foreach ($input as $data) {
            if (is_string($data)) {
                $data = $this->parse($data);
            } elseif ($data instanceof ArrayProvider) {
                $data = $data->toArray();
            } elseif (is_iterable($data) && !is_array($data)) {
                $data = iterator_to_array($data);
            } elseif ($data === null) {
                continue;
            } elseif (!is_array($data)) {
                throw Exceptional::InvalidArgument('Invalid style data', null, $data);
            }

            foreach ($data as $key => $value) {
                $this->set($key, $value);
            }
        }

        return $this;
    }

    /**
     * Parse string styles
     *
     * @return array<string, Collection>
     */
    protected function parse(string $style): array
    {
        $parts = explode('{', $style);
        $output = [];

        while (!empty($parts)) {
            $selector = trim((string)array_shift($parts));
            $body = explode('}', (string)array_shift($parts), 2);
            $nextSelector = trim((string)array_pop($body));
            $body = trim((string)array_shift($body));

            if (!empty($nextSelector)) {
                array_unshift($parts, $nextSelector);
            }

            $output[$selector] = new Collection($body);
        }

        return $output;
    }

    /**
     * Direct set a value
     *
     * @param mixed $value
     */
    public function set(string $key, $value): Sheet
    {
        if (!$value instanceof Collection) {
            $value = new Collection($value);
        }

        $this->blocks[$key] = $value;
        return $this;
    }

    /**
     * Get a style list
     */
    public function get(string $key): ?Collection
    {
        return $this->blocks[$key] ?? null;
    }

    /**
     * Has style list set?
     */
    public function has(string $key): bool
    {
        return isset($this->blocks[$key]);
    }

    /**
     * Remove style list
     */
    public function remove(string $key): Sheet
    {
        unset($this->blocks[$key]);
        return $this;
    }

    /**
     * Render to string
     */
    public function render(): ?string
    {
        if (null === ($styles = $this->renderBlocks())) {
            return null;
        }

        return '<style type="text/css">' . "\n    " . $styles . "\n" . '</style>';
    }

    /**
     * Render styles blocks
     */
    public function renderBlocks(): ?string
    {
        if (empty($this->blocks)) {
            return null;
        }

        $output = [];

        foreach ($this->blocks as $selector => $styles) {
            $output[] = $selector . ' { ' . $styles . ' }';
        }

        return implode("\n" . '    ', $output);
    }

    /**
     * Convert to string
     */
    public function __toString(): string
    {
        try {
            return (string)$this->render();
        } catch (Throwable $e) {
            return '';
        }
    }

    /**
     * Get iterator
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->blocks);
    }

    /**
     * Export for dump inspection
     *
     * @return iterable<string, mixed>
     */
    public function glitchDump(): iterable
    {
        yield 'definition' => $this->render();
        yield 'section:definition' => false;
        yield 'values' => $this->blocks;
    }
}
