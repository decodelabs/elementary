<?php

/**
 * @package Elementary
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary;

use DecodeLabs\Coercion;
use DecodeLabs\Collections\AttributeContainerTrait;
use DecodeLabs\Elementary\Attribute\ClassList\Container as ClassListContainer;
use DecodeLabs\Exceptional;
use DecodeLabs\Nuance\Entity\NativeObject as NuanceEntity;

/**
 * @template TAttributeValue
 * @template TAttributeInput = TAttributeValue
 * @template TBuffer of Buffer = Buffer
 * @phpstan-require-implements Tag
 */
trait TagTrait
{
    /**
     * @use AttributeContainerTrait<TAttributeValue,TAttributeInput>
     */
    use AttributeContainerTrait;

    /**
     * @use ChildRendererTrait<TBuffer>
     */
    use ChildRendererTrait;

    // protected const InlineTags = [];
    // protected const BooleanAttributes = [];

    public ?string $tagName {
        get => $this->tagName;
        set(?string $name) {
            if ($name === null) {
                $this->tagName = null;
                $this->selfClosing = false;
                return;
            }

            $origName = $name;

            if (false !== strpos($name, '[')) {
                $name = preg_replace_callback('/\[([^\]]*)\]/', function ($res) {
                    $parts = explode('=', $res[1], 2);

                    if (empty($key = array_shift($parts))) {
                        throw Exceptional::UnexpectedValue(
                            message: 'Invalid tag attribute definition',
                            data: $res
                        );
                    }

                    $value = (string)array_shift($parts);
                    $first = substr($value, 0, 1);
                    $last = substr($value, -1);

                    if (
                        strlen($value) > 1 &&
                        (
                            (
                                $first == '"' &&
                                $last == '"'
                            ) ||
                            (
                                $first == "'" &&
                                $last == "'"
                            )
                        )
                    ) {
                        $value = substr($value, 1, -1);
                    }

                    if ($value === '') {
                        $value = true;
                    }

                    $this->setAttribute($key, $value);
                    return '';
                }, $name) ?? $name;
            }

            if (false !== strpos($name, '#')) {
                $name = preg_replace_callback('/\#([^ .\[\]]+)/', function ($res) {
                    $this->setAttribute('id', $res[1]);
                    return '';
                }, $name) ?? $name;
            }

            $parts = explode('.', $name);

            if (empty($name = array_shift($parts))) {
                throw Exceptional::UnexpectedValue(
                    message: 'Unable to parse tag class definition',
                    data: $origName
                );
            }

            $this->tagName = $name;

            if ($this->tagName === null) {
                return;
            }

            if (false !== strpos($this->tagName, '?')) {
                $this->tagName = str_replace('?', '', $this->tagName);
                $this->renderEmpty = false;
            }

            if (substr($this->tagName, 0, 1) === '/') {
                $this->selfClosing = true;
                $this->tagName = substr($this->tagName, 1);
            } else {
                $this->selfClosing = $this->isSelfClosingTagName($this->tagName);
            }

            if (!empty($parts)) {
                if ($this instanceof ClassListContainer) {
                    $this->addClasses(...$parts);
                } else {
                    $this->setAttribute('class', implode(' ', $parts));
                }
            }
        }
    }

    public ?string $id {
        get {
            return Coercion::tryString($this->getAttribute('id'));
        }
        set(?string $id) {
            if ($id === null) {
                $this->removeAttribute('id');
                return;
            }

            if (preg_match('/\s/', $id)) {
                throw Exceptional::InvalidArgument(
                    message: 'Invalid tag id: ' . $id
                );
            }

            $this->setAttribute('id', $id);
        }
    }

    public bool $selfClosing = false;
    public bool $renderEmpty = true;


    /**
     * @param array<string,TAttributeInput>|null $attributes
     */
    public function __construct(
        ?string $tagName,
        ?array $attributes = null
    ) {
        $this->tagName = $tagName;

        if ($attributes !== null) {
            foreach ($attributes as $key => $value) {
                if ($key === 'class') {
                    $this->addClasses($value);
                } elseif ($key === 'style') {
                    $this->addStyles($value);
                } else {
                    $this->setAttribute((string)$key, $value);
                }
            }
        }
    }

    public static function isSelfClosingTagName(
        string $name
    ): bool {
        return false;
    }


    public function isInline(): bool
    {
        if ($this->tagName === null) {
            return false;
        }

        return in_array(strtolower($this->tagName), self::InlineTags);
    }

    public function isBlock(): bool
    {
        return !$this->isInline();
    }


    /**
     * @return ?TBuffer
     */
    public function renderWith(
        mixed $content = null,
        bool $pretty = false
    ): ?Buffer {
        if (!$this->selfClosing) {
            $content = $this->renderChild($content, $pretty);

            if (
                !$this->renderEmpty &&
                $content === ''
            ) {
                return null;
            }
        } else {
            $content = null;
        }

        if ($this->tagName === null) {
            $output = $content;
        } else {
            $isBlock = $this->isBlock();

            if (
                $pretty &&
                $content !== null &&
                $isBlock &&
                false !== strpos($content, '<')
            ) {
                $content = "\n  " . str_replace(">\n", ">\n  ", rtrim($content, "\n")) . "\n";
            }

            $output = $this->open() . $content . $this->close();

            if (
                $pretty &&
                $isBlock
            ) {
                $output .= "\n";
            }
        }

        return $this->newBuffer($output);
    }


    /**
     * @return TBuffer
     */
    abstract protected function newBuffer(
        ?string $content
    ): Buffer;



    public function open(): string
    {
        $attributes = [];

        foreach ($this->attributes as $key => $value) {
            if (null !== ($attr = $this->prepareAttribute($key, $value))) {
                $attributes[] = $attr;
            }
        }

        if ($attributes = implode(' ', $attributes)) {
            $attributes = ' ' . $attributes;
        }

        $output = '<' . $this->tagName . $attributes;

        if ($this->selfClosing) {
            $output .= ' /';
        }

        $output .= '>';
        return $output;
    }

    protected function prepareAttribute(
        string $key,
        mixed $value
    ): ?string {
        // Null
        if ($value === null) {
            if (substr($key, 0, 1) == ':') {
                return $key . '="null"';
            }

            return $key;
        }

        // Boolean
        if (is_bool($value)) {
            if (
                substr($key, 0, 1) == ':' ||
                substr($key, 0, 5) == 'data-' ||
                in_array($key, static::BooleanAttributes)
            ) {
                return $key . '="' . ($value ? 'true' : 'false') . '"';
            }

            if ($value) {
                return $key;
            }

            return null;
        }

        // Renderable
        if (
            is_array($value) ||
            is_callable($value)
        ) {
            return $key . '="' . (string)$this->renderChild($value) . '"';
        }

        // Markup
        if (
            $value instanceof Buffer &&
            $value->escaped
        ) {
            return $key . '="' . (string)$value . '"';
        }

        // String
        return $key . '="' . $this->esc(Coercion::asString($value)) . '"';
    }

    public function close(): string
    {
        if ($this->selfClosing) {
            return '';
        }

        return '</' . $this->tagName . '>';
    }


    public function __toString(): string
    {
        return $this->open();
    }




    public function offsetSet(
        mixed $key,
        mixed $value
    ): void {
        $this->setAttribute(Coercion::asString($key), $value);
    }

    public function offsetGet(
        mixed $key
    ): mixed {
        return $this->getAttribute(Coercion::asString($key));
    }

    public function offsetExists(
        mixed $key
    ): bool {
        return $this->hasAttribute(Coercion::asString($key));
    }

    public function offsetUnset(
        mixed $key
    ): void {
        $this->removeAttribute(Coercion::asString($key));
    }



    public function jsonSerialize(): mixed
    {
        return (string)$this;
    }


    public function toNuanceEntity(): NuanceEntity
    {
        $entity = new NuanceEntity($this);
        $output = $this->__toString();

        if (!$this->renderEmpty) {
            $output = '<?' . substr($output, 1);
        }

        $entity->itemName = $this->tagName;
        $entity->definition = $output;

        $entity->setProperty('renderEmpty', $this->renderEmpty);
        $entity->setProperty('attributes', $this->attributes);

        return $entity;
    }
}
