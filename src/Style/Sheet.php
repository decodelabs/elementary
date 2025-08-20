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
use DecodeLabs\Nuance\Dumpable;
use DecodeLabs\Nuance\Entity\NativeObject as NuanceEntity;
use IteratorAggregate;
use Throwable;
use Traversable;

/**
 * @implements IteratorAggregate<string,Collection>
 */
class Sheet implements
    IteratorAggregate,
    Dumpable
{
    /**
     * @var array<string,Collection>
     */
    protected array $blocks = [];

    public function __construct(
        mixed ...$input
    ) {
        $this->import(...$input);
    }

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
                throw Exceptional::InvalidArgument(
                    message: 'Invalid style data',
                    data: $data
                );
            }

            foreach ($data as $key => $value) {
                $this->set($key, $value);
            }
        }

        return $this;
    }

    /**
     * @return array<string,Collection>
     */
    protected function parse(
        string $style
    ): array {
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

    public function set(
        string $key,
        mixed $value
    ): static {
        if (!$value instanceof Collection) {
            $value = new Collection($value);
        }

        $this->blocks[$key] = $value;
        return $this;
    }

    public function get(
        string $key
    ): ?Collection {
        return $this->blocks[$key] ?? null;
    }

    public function has(
        string $key
    ): bool {
        return isset($this->blocks[$key]);
    }

    public function remove(
        string $key
    ): static {
        unset($this->blocks[$key]);
        return $this;
    }

    public function render(): ?string
    {
        if (null === ($styles = $this->renderBlocks())) {
            return null;
        }

        return '<style type="text/css">' . "\n    " . $styles . "\n" . '</style>';
    }

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

    public function __toString(): string
    {
        try {
            return (string)$this->render();
        } catch (Throwable $e) {
            return '';
        }
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->blocks);
    }

    public function toNuanceEntity(): NuanceEntity
    {
        $entity = new NuanceEntity($this);
        $entity->definition = $this->render();
        $entity->values = $this->blocks;
        return $entity;
    }
}
