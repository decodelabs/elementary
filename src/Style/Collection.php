<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary\Style;

use DecodeLabs\Collections\ArrayProvider;
use DecodeLabs\Collections\HashMap;
use DecodeLabs\Collections\Native\HashMapTrait;
use DecodeLabs\Exceptional;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<string, string>
 * @implements HashMap<string>
 */
class Collection implements
    IteratorAggregate,
    HashMap
{
    /**
     * @use HashMapTrait<string>
     */
    use HashMapTrait;

    protected const Mutable = true;

    /**
     * Init with styles
     */
    public function __construct(
        mixed ...$input
    ) {
        $this->import(...$input);
    }

    /**
     * Import style data
     *
     * @return $this
     */
    public function import(
        mixed ...$input
    ): static {
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

            $this->merge($data);
        }

        return $this;
    }

    /**
     * Parse string styles
     *
     * @return array<string, string>
     */
    protected function parse(
        string $style
    ): array {
        $parts = explode(';', $style);
        $output = [];

        foreach ($parts as $part) {
            $part = trim($part);

            if (empty($part)) {
                continue;
            }

            $exp = explode(':', $part);

            if (count($exp) == 2) {
                $output[trim((string)array_shift($exp))] = trim((string)array_shift($exp));
            }
        }

        return $output;
    }

    /**
     * Export list of styles that have been set
     *
     * @return array<string, string>
     */
    public function export(
        string ...$keys
    ): array {
        $output = [];

        foreach ($keys as $key) {
            $value = $this->get($key);

            if ($value !== null) {
                $output[$key] = $value;
            }
        }

        return $output;
    }

    /**
     * Render to string
     */
    public function __toString(): string
    {
        $output = [];

        foreach ($this->items as $key => $value) {
            $output[] = $key . ': ' . $value . ';';
        }

        return implode(' ', $output);
    }
}
