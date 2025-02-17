<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary\Tests;

use DecodeLabs\Elementary\Element;
use DecodeLabs\Elementary\ElementTrait;

/**
 * @implements Element<string>
 */
class AnalyzeElementTrait extends AnalyzeTagTrait implements Element
{
    use ElementTrait;
}
