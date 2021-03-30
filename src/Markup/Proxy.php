<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary\Markup;

use DecodeLabs\Elementary\Markup;

interface Proxy
{
    public function toMarkup(): Markup;
}
