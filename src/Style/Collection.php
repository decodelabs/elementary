<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary\Style;

use DecodeLabs\Coercion;
use DecodeLabs\Collections\ArrayProvider;
use DecodeLabs\Collections\DictionaryInterface;
use DecodeLabs\Collections\DictionaryTrait;
use DecodeLabs\Exceptional;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<string,string>
 * @implements DictionaryInterface<string>
 */
class Collection implements
    IteratorAggregate,
    DictionaryInterface
{
    /**
     * @use DictionaryTrait<string>
     */
    use DictionaryTrait;

    protected const bool Mutable = true;

    public function __construct(
        mixed ...$input
    ) {
        $this->import(...$input);
    }

    /**
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
            } elseif (
                is_iterable($data) &&
                !is_array($data)
            ) {
                $data = iterator_to_array($data);
            } elseif ($data === null) {
                continue;
            } elseif (!is_array($data)) {
                throw Exceptional::InvalidArgument(
                    message: 'Invalid style data',
                    data: $data
                );
            }

            /** @var array<string,string> $data */
            $data = array_map(fn ($value) => Coercion::asString($value), $data);
            $this->merge($data);
        }

        return $this;
    }

    /**
     * @return array<string,string>
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
     * @return array<string,string>
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

    public function __toString(): string
    {
        $output = [];

        foreach ($this->items as $key => $value) {
            $output[] = $key . ': ' . $value . ';';
        }

        return implode(' ', $output);
    }
}
