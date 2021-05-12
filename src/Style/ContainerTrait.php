<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary\Style;

use Stringable;

trait ContainerTrait
{
    /**
     * Replace style list
     *
     * @param mixed ...$styles
     * @return $this
     */
    public function setStyles(...$styles): Container
    {
        $collection = $this->getStyles();
        $collection->clear();
        $collection->import(...$styles);
        return $this;
    }

    /**
     * Merge style list
     *
     * @param mixed ...$styles
     * @return $this
     */
    public function addStyles(...$styles): Container
    {
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

        return $this->attributes['style'];
    }

    /**
     * Set a single style value
     *
     * @param string|Stringable|int|float|null $value
     * @return $this
     */
    public function setStyle(string $key, $value): Container
    {
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
    public function getStyle(string $key): ?string
    {
        return $this->getStyles()->get($key);
    }

    /**
     * Remove set of styles
     *
     * @return $this
     */
    public function removeStyle(string ...$keys): Container
    {
        $this->getStyles()->remove(...$keys);
        return $this;
    }

    /**
     * List has any of these styles?
     */
    public function hasStyle(string ...$keys): bool
    {
        return $this->getStyles()->has(...$keys);
    }

    /**
     * List has ALL of these styles?
     */
    public function hasStyles(string ...$keys): bool
    {
        return $this->getStyles()->hasAll(...$keys);
    }

    /**
     * Reset all styles
     *
     * @return $this
     */
    public function clearStyles(): Container
    {
        $this->getStyles()->clear();
        return $this;
    }
}
