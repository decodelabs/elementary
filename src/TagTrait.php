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
use DecodeLabs\Elementary\Markup\ChildRendererTrait;
use DecodeLabs\Exceptional;

/**
 * @implements \ArrayAccess<string, mixed>
 */
trait TagTrait
{
    use AttributeContainerTrait;
    use ChildRendererTrait;

    // public const BOOLEAN_ATTRIBUTES = [];
    // public const INLINE_TAGS = [];

    protected string $name;
    protected bool $closable = true;
    protected bool $renderEmpty = true;


    /**
     * Init with name and attributes
     *
     * @param string $name
     * @param array<string, mixed>|null $attributes
     */
    public function __construct(
        string $name,
        array $attributes = null
    ) {
        $this->setName($name);

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


    /**
     * Parse css style selector into tag name, classes, etc
     */
    public function setName(
        string $name
    ): static {
        $origName = $name;

        if (false !== strpos($name, '[')) {
            $name = preg_replace_callback('/\[([^\]]*)\]/', function ($res) {
                $parts = explode('=', $res[1], 2);

                if (empty($key = array_shift($parts))) {
                    throw Exceptional::UnexpectedValue(
                        'Invalid tag attribute definition',
                        null,
                        $res
                    );
                }

                $value = (string)array_shift($parts);
                $first = substr($value, 0, 1);
                $last = substr($value, -1);

                if (
                    strlen($value) > 1 &&
                    (
                        ($first == '"' && $last == '"') ||
                        ($first == "'" && $last == "'")
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
                $this->setId($res[1]);
                return '';
            }, $name) ?? $name;
        }

        $parts = explode('.', $name);

        if (empty($name = array_shift($parts))) {
            throw Exceptional::UnexpectedValue(
                'Unable to parse tag class definition',
                null,
                $origName
            );
        }

        $this->name = $name;

        if (false !== strpos($this->name, '?')) {
            $this->name = str_replace('?', '', $this->name);
            $this->renderEmpty = false;
        }

        if (substr($this->name, 0, 1) === '/') {
            $this->closable = false;
            $this->name = substr($this->name, 1);
        } else {
            $this->closable = $this->isClosableTagName($this->name);
        }

        if (!empty($parts)) {
            if ($this instanceof ClassListContainer) {
                $this->addClasses(...$parts);
            } else {
                $this->setAttribute('class', implode(' ', $parts));
            }
        }

        return $this;
    }

    /**
     * Get tag name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Is tag name a closable <tag /> type?
     */
    public static function isClosableTagName(
        string $name
    ): bool {
        return false;
    }


    /**
     * Direct set id attribute
     */
    public function setId(
        ?string $id
    ): static {
        if ($id === null) {
            $this->removeAttribute('id');
            return $this;
        }

        if (preg_match('/\s/', $id)) {
            throw Exceptional::InvalidArgument('Invalid tag id: ' . $id);
        }

        $this->setAttribute('id', $id);
        return $this;
    }

    /**
     * Get id attribute value
     */
    public function getId(): ?string
    {
        return Coercion::toStringOrNull($this->getAttribute('id'));
    }



    /**
     * Is this element inline?
     */
    public function isInline(): bool
    {
        return in_array(strtolower($this->name), self::INLINE_TAGS);
    }

    /**
     * Is this a block element?
     */
    public function isBlock(): bool
    {
        return !$this->isInline();
    }


    /**
     * Render tag with inner content
     */
    public function renderWith(
        mixed $content = null,
        bool $pretty = false
    ): ?Markup {
        if ($this->closable) {
            if (
                !$this->renderEmpty &&
                $content === null
            ) {
                return null;
            }

            $content = $this->renderChild($content);
        } else {
            $content = null;
        }

        $isBlock = $this->isBlock();

        if (
            $pretty &&
            $content !== null &&
            $isBlock &&
            false !== strpos($content, '<')
        ) {
            $content = "\n  " . str_replace("\n", "\n  ", rtrim($content, "\n")) . "\n";
        }

        $output = $this->open() . $content . $this->close();

        if ($pretty && $isBlock) {
            $output .= "\n";
        }

        return $this->newBuffer($output);
    }


    /**
     * Create new local buffer
     */
    abstract protected function newBuffer(
        ?string $content
    ): Buffer;



    /**
     * Set whether to render tag if no content
     */
    public function setRenderEmpty(
        bool $render
    ): static {
        $this->renderEmpty = $render;
        return $this;
    }

    /**
     * Render tag if no content?
     */
    public function willRenderEmpty(): bool
    {
        return $this->renderEmpty;
    }


    /**
     * Create opening tag string
     */
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

        $output = '<' . $this->name . $attributes;

        if (!$this->closable) {
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
                in_array($key, static::BOOLEAN_ATTRIBUTES)
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
        if ($value instanceof Markup) {
            return $key . '="' . (string)$value . '"';
        }

        // String
        return $key . '="' . $this->esc(Coercion::toString($value)) . '"';
    }

    /**
     * Render closing </tag>
     */
    public function close(): string
    {
        if (!$this->closable) {
            return '';
        }

        return '</' . $this->name . '>';
    }

    /**
     * Manually override whether tag has closing tag, or is single inline tag
     */
    public function setClosable(
        bool $closable
    ): static {
        $this->closable = $closable;
        return $this;
    }

    /**
     * Is this tag closable?
     */
    public function isClosable(): bool
    {
        return $this->closable;
    }



    /**
     * Render to string
     */
    public function __toString(): string
    {
        return $this->open();
    }




    /**
     * Shortcut to set attribute
     */
    public function offsetSet(
        mixed $key,
        mixed $value
    ): void {
        $this->setAttribute(Coercion::toString($key), $value);
    }

    /**
     * Shortcut to get attribute
     */
    public function offsetGet(
        mixed $key
    ): mixed {
        return $this->getAttribute(Coercion::toString($key));
    }

    /**
     * Shortcut to test for attribute
     */
    public function offsetExists(
        mixed $key
    ): bool {
        return $this->hasAttribute(Coercion::toString($key));
    }

    /**
     * Shortcut to remove attribute
     */
    public function offsetUnset(
        mixed $key
    ): void {
        $this->removeAttribute(Coercion::toString($key));
    }




    /**
     * Export for dump inspection
     *
     * @return iterable<string, mixed>
     */
    public function glitchDump(): iterable
    {
        $output = $this->__toString();

        if (!$this->renderEmpty) {
            $output = '<?' . substr($output, 1);
        }

        yield 'className' => $this->name;
        yield 'definition' => $output;

        yield 'properties' => [
            '*renderEmpty' => $this->renderEmpty,
            '*attributes' => $this->attributes,
        ];

        yield 'section:properties' => false;
    }
}
