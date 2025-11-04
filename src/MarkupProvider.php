<?php

/**
 * Elementary
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary;

interface MarkupProvider
{
    public function toMarkup(): Markup;
}
