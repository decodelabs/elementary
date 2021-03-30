<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary\Tests;

use DecodeLabs\Elementary\Attribute\ClassList\Container as ClassListContainer;
use DecodeLabs\Elementary\Attribute\ClassList\ContainerTrait as ClassListContainerTrait;
use DecodeLabs\Elementary\Style\Container as StyleContainer;
use DecodeLabs\Elementary\Style\ContainerTrait as StyleContainerTrait;
use DecodeLabs\Elementary\Tag;
use DecodeLabs\Elementary\TagTrait;

class AnalyzeTagTrait implements
    Tag,
    ClassListContainer,
    StyleContainer
{
    use TagTrait;
    use ClassListContainerTrait;
    use StyleContainerTrait;

    const INLINE_TAGS = [];
    const BOOLEAN_ATTRIBUTES = [];
};
