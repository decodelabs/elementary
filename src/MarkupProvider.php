<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary;

use DecodeLabs\Elementary\Markup;

interface MarkupProvider
{
    public function toMarkup(): Markup;
}
