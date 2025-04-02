<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary\Style;

use Stringable;

/**
 * @phpstan-require-implements Container
 */
trait ContainerTrait
{
    public Collection $style {
        get => $this->getStyles();
    }

    /**
     * Replace style list
     *
     * @return $this
     */
    public function setStyles(
        mixed ...$styles
    ): static {
        $this->clearStyles();
        $this->addStyles(...$styles);
        return $this;
    }

    /**
     * Merge style list
     *
     * @return $this
     */
    public function addStyles(
        mixed ...$styles
    ): static {
        $this->getStyles()->import(...$styles);
        return $this;
    }

    /**
     * Get style object
     */
    public function getStyles(): Collection
    {
        if (!isset($this->attributes['style'])) {
            $this->attributes['style'] = new Collection();
        }

        if (!$this->attributes['style'] instanceof Collection) {
            $this->attributes['style'] = new Collection(
                $this->attributes['style']
            );
        }

        return $this->attributes['style'];
    }

    /**
     * Set a single style value
     *
     * @return $this
     */
    public function setStyle(
        string $key,
        string|Stringable|int|float|null $value
    ): static {
        $styles = $this->getStyles();

        if ($value === null) {
            $styles->remove($key);
        } else {
            $styles->set($key, (string)$value);
        }

        return $this;
    }

    /**
     * Get a single style value
     */
    public function getStyle(
        string $key
    ): ?string {
        return $this->getStyles()->get($key);
    }

    /**
     * Remove set of styles
     *
     * @return $this
     */
    public function removeStyle(
        string ...$keys
    ): static {
        $this->getStyles()->remove(...$keys);
        return $this;
    }

    /**
     * List has any of these styles?
     */
    public function hasStyle(
        string ...$keys
    ): bool {
        return $this->getStyles()->has(...$keys);
    }

    /**
     * List has ALL of these styles?
     */
    public function hasStyles(
        string ...$keys
    ): bool {
        return $this->getStyles()->hasAll(...$keys);
    }

    /**
     * Reset all styles
     *
     * @return $this
     */
    public function clearStyles(): static
    {
        $this->getStyles()->clear();
        return $this;
    }
}
