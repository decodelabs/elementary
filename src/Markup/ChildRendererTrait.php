<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary\Markup;

use DecodeLabs\Elementary\Markup;

trait ChildRendererTrait
{
    /**
     * Convert child element to string
     */
    protected function renderChild($value, bool $pretty = false): string
    {
        if (
            is_callable($value) &&
            is_object($value)
        ) {
            return $this->renderChild($value($this), $pretty);
        }

        $output = '';

        if ($value instanceof Proxy) {
            $value = $value->toMarkup();
        }

        if (
            is_iterable($value) &&
            !$value instanceof Markup
        ) {
            foreach ($value as $part) {
                $output .= $this->renderChild($part, $pretty);
            }

            return $output;
        }

        if ($value instanceof Element) {
            $output = (string)$value->render($pretty);
        } else {
            $output = (string)$value;
        }

        if (!$value instanceof Markup) {
            $output = $this->esc($output);
        }

        return (string)$output;
    }

    /**
     * Escape HTML
     */
    protected function esc(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}
