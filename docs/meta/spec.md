# Elementary — Package Specification

> **Cluster:** `frontend`
> **Language:** `php`
> **Milestone:** `m3`
> **Repo:** `https://github.com/decodelabs/elementary`
> **Role:** Markup base library

This document describes the purpose, contracts, and design of **Elementary** within the Decode Labs ecosystem.

It is aimed at:

- Developers **using** Elementary in their own applications or libraries.
- Contributors **maintaining or extending** Elementary.
- Tools and AI assistants that need to reason about its behaviour.

---

## 1. Overview

### 1.1 Purpose

Elementary provides a shared base markup handling library for PHP. It's designed as middleware to provide tools for building tag-based manipulation systems. It provides core interfaces and traits for markup generation, tag manipulation, element handling, attribute management, class list management, style management, buffer handling, child rendering, and normalization. It's intended to be used as the foundation for other markup libraries like Tagged (HTML generator) and Exemplar (XML manipulator).

### 1.2 Non-Goals

Elementary does **not**:

- Provide HTML-specific functionality — see Tagged package
- Provide XML-specific functionality — see Exemplar package
- Handle DOM parsing — it's a generation library
- Provide validation — it's a structural library
- Handle XSS protection — escaping is provided but not enforced
- Provide template engines — it's a markup builder
- Handle CSS parsing — style parsing is basic
- Provide JavaScript integration — it's markup-only

---

## 2. Role in the Ecosystem

### 2.1 Cluster & Positioning

- **Cluster:** `frontend` (see Chorus taxonomy)
- Elementary is a frontend package that provides the base markup handling library for the Decode Labs ecosystem. It sits in the frontend cluster alongside other markup and presentation libraries. It depends on Coercion, Collections, Exceptional, and Nuance. It's used by Tagged (HTML generator) and Exemplar (XML manipulator) as their foundation. It provides the core abstractions for tag-based markup generation.

### 2.2 Typical Usage Contexts

Typical places Elementary appears:

- Base library for HTML generators
- Base library for XML manipulators
- Tag-based markup systems
- Attribute management systems
- Class list management
- Style management
- Markup rendering
- Child element normalization

Elementary is intended to be used as the foundation for building markup generation libraries, not for direct end-user interaction.

---

## 3. Public Surface

> This section focuses on the conceptual API, not every symbol.

### 3.1 Key Types

The primary public types are:

- `DecodeLabs\Elementary\Markup`
  Base interface for all markup objects. Extends `Stringable` and `JsonSerializable`.

- `DecodeLabs\Elementary\MarkupProvider`
  Interface for objects that can provide markup. Defines `toMarkup()` method.

- `DecodeLabs\Elementary\Renderable`
  Interface for objects that can be rendered. Defines `render(bool $pretty)` method returning `?Buffer`.

- `DecodeLabs\Elementary\Tag`
  Interface for tag objects. Extends `Markup`, `AttributeContainer`, and `ArrayAccess`. Provides tag name, attributes, self-closing behavior, and rendering.

- `DecodeLabs\Elementary\TagTrait`
  Trait providing tag implementation. Handles tag name parsing (with CSS selector syntax), attribute management, rendering, and ArrayAccess.

- `DecodeLabs\Elementary\Element`
  Interface for element objects with content. Extends `Tag`, `Renderable`, and `SequenceInterface`. Provides body management, normalization, and content rendering.

- `DecodeLabs\Elementary\ElementTrait`
  Trait providing element implementation. Handles content storage, normalization, rendering, and Nuance integration.

- `DecodeLabs\Elementary\Buffer`
  Interface for buffer objects. Extends `Markup`. Provides content storage, escaping, and manipulation.

- `DecodeLabs\Elementary\BufferTrait`
  Trait providing buffer implementation. Handles content storage, prepend, append, replace, and emptiness checking.

- `DecodeLabs\Elementary\ChildRendererTrait`
  Trait for rendering child elements. Handles rendering of closures, iterables, Markup objects, and escaping.

- `DecodeLabs\Elementary\Attribute\ClassList`
  Class list management. Provides add, remove, has, hasAll, clear, and count operations.

- `DecodeLabs\Elementary\Attribute\ClassList\Container`
  Interface for objects that contain class lists. Defines class list access and manipulation methods.

- `DecodeLabs\Elementary\Attribute\ClassList\ContainerTrait`
  Trait providing class list container implementation.

- `DecodeLabs\Elementary\Style\Collection`
  Style collection. Implements `DictionaryInterface` for style property management. Parses CSS-style strings.

- `DecodeLabs\Elementary\Style\Container`
  Interface for objects that contain style collections. Defines style access and manipulation methods.

- `DecodeLabs\Elementary\Style\ContainerTrait`
  Trait providing style container implementation.

- `DecodeLabs\Elementary\Style\Sheet`
  Style sheet for managing multiple style blocks. Parses CSS-style strings with selectors and blocks.

### 3.2 Main Entry Points

The main usage pattern is through implementing interfaces:

```php
use DecodeLabs\Elementary\Tag;
use DecodeLabs\Elementary\TagTrait;

class MyTag implements Tag
{
    use TagTrait;
    
    protected function newBuffer(?string $content): Buffer
    {
        return new MyBuffer($content);
    }
}
```

For elements:

```php
use DecodeLabs\Elementary\Element;
use DecodeLabs\Elementary\ElementTrait;

class MyElement implements Element
{
    use TagTrait;
    use ElementTrait;
    
    protected function newBuffer(?string $content): Buffer
    {
        return new MyBuffer($content);
    }
}
```

---

## 4. Dependencies

### 4.1 Decode Labs

- `decodelabs/coercion` (required)
  Used for type coercion when converting values to strings and parsing attributes.

- `decodelabs/collections` (required)
  Used for `SequenceInterface` on `Element`, `AttributeContainer` for attributes, `DictionaryInterface` for styles, and `ArrayProvider` for style parsing.

- `decodelabs/exceptional` (required)
  Used for exception handling when parsing invalid tag names, attributes, or styles.

- `decodelabs/nuance` (required)
  Used for debugging support via `Dumpable` interface and `NuanceEntity` for entity representation.

### 4.2 External

None — Elementary has no external dependencies beyond Decode Labs packages.

### 4.3 Optional Integrations

None — all dependencies are required.

---

## 5. Behaviour & Contracts

### 5.1 Invariants

- Markup objects implement `Stringable` and `JsonSerializable`
- Tag names can be parsed with CSS selector syntax (`tag.class#id[attr=value]`)
- Tag names support self-closing syntax (`/tag`)
- Tag names support empty rendering control (`?tag`)
- Attributes are managed via `AttributeContainer`
- Class lists are managed via `ClassList` objects
- Style collections are managed via `Collection` objects
- Buffers track escaping state
- Child rendering handles closures, iterables, and Markup objects
- Child rendering escapes non-Markup content
- Elements normalize children on demand
- Elements can be empty or self-closing
- Pretty printing adds indentation for block elements
- Style sheets parse CSS-style strings
- Style collections parse CSS property strings

### 5.2 Input & Output Contracts

**Markup Operations:**
- `__toString(): string` — Converts to string representation
- `jsonSerialize(): mixed` — Converts to JSON representation

**MarkupProvider Operations:**
- `toMarkup(): Markup` — Converts provider to markup

**Renderable Operations:**
- `render(bool $pretty = false): ?Buffer` — Renders to buffer

**Tag Operations:**
- `__construct(?string $tagName, ?array $attributes = null)` — Creates tag with name and attributes
- `?string $tagName { get; set; }` — Tag name (supports CSS selector syntax)
- `?string $id { get; set; }` — Element ID
- `bool $selfClosing { get; set; }` — Whether tag is self-closing
- `bool $renderEmpty { get; set; }` — Whether to render empty tags
- `isSelfClosingTagName(string $name): bool` — Checks if tag name is self-closing (static)
- `isInline(): bool` — Checks if tag is inline
- `isBlock(): bool` — Checks if tag is block
- `open(): string` — Gets opening tag string
- `close(): string` — Gets closing tag string
- `renderWith(mixed $content = null, bool $pretty = false): ?Buffer` — Renders tag with content
- `offsetSet(mixed $key, mixed $value): void` — Sets attribute via ArrayAccess
- `offsetGet(mixed $key): mixed` — Gets attribute via ArrayAccess
- `offsetExists(mixed $key): bool` — Checks attribute via ArrayAccess
- `offsetUnset(mixed $key): void` — Removes attribute via ArrayAccess

**Element Operations:**
- `__construct(?string $name, mixed $content, ?array $attributes = null)` — Creates element with name, content, and attributes
- `setBody(mixed $body): static` — Sets element body
- `normalize(): static` — Normalizes element children
- `renderContent(bool $pretty = false): ?Buffer` — Renders element content

**Buffer Operations:**
- `__construct(?string $content, bool $escaped = false)` — Creates buffer with content and escaping
- `bool $escaped { get; set; }` — Whether content is escaped
- `prepend(?string $content): static` — Prepends content
- `append(?string $content): static` — Appends content
- `replace(?string $content): static` — Replaces content
- `isEmpty(): bool` — Checks if buffer is empty

**ClassList Operations:**
- `__construct(string ...$classes)` — Creates class list with classes
- `add(?string ...$classes): static` — Adds classes
- `has(string ...$classes): bool` — Checks if has any class
- `hasAll(string ...$classes): bool` — Checks if has all classes
- `remove(?string ...$classes): static` — Removes classes
- `clear(): static` — Clears all classes
- `count(): int` — Gets class count
- `toArray(): array` — Gets classes as array
- `__toString(): string` — Gets classes as string

**ClassList\Container Operations:**
- `ClassList $classList { get; }` — Gets class list
- `setClasses(mixed ...$classes): static` — Sets classes
- `addClasses(mixed ...$classes): static` — Adds classes
- `getClasses(): ClassList` — Gets class list
- `setClass(?string ...$classes): static` — Sets class (single)
- `addClass(?string ...$classes): static` — Adds class (single)
- `removeClass(?string ...$classes): static` — Removes class
- `hasClass(string ...$classes): bool` — Checks if has class
- `hasClasses(string ...$classes): bool` — Checks if has all classes
- `clearClasses(): static` — Clears classes
- `countClasses(): int` — Gets class count

**Style\Collection Operations:**
- `__construct(mixed ...$input)` — Creates collection with styles
- `import(mixed ...$input): static` — Imports styles
- `export(string ...$keys): array` — Exports specific styles
- `__toString(): string` — Gets styles as CSS string

**Style\Container Operations:**
- `Collection $style { get; }` — Gets style collection
- `setStyles(mixed ...$styles): static` — Sets styles
- `addStyles(mixed ...$styles): static` — Adds styles
- `getStyles(): Collection` — Gets style collection
- `setStyle(string $key, string|Stringable|int|float|null $value): static` — Sets style property
- `getStyle(string $key): ?string` — Gets style property
- `removeStyle(string ...$keys): static` — Removes style properties
- `hasStyle(string ...$keys): bool` — Checks if has style
- `hasStyles(string ...$keys): bool` — Checks if has all styles
- `clearStyles(): static` — Clears styles

**Style\Sheet Operations:**
- `__construct(mixed ...$input)` — Creates sheet with styles
- `import(mixed ...$input): static` — Imports styles
- `set(string $key, mixed $value): static` — Sets style block
- `get(string $key): ?Collection` — Gets style block
- `has(string $key): bool` — Checks if has block
- `remove(string $key): static` — Removes block
- `render(): ?string` — Renders as `<style>` tag
- `renderBlocks(): ?string` — Renders style blocks only

### 5.3 Tag Name Parsing

Tag names support CSS selector syntax:
- `tag` — Simple tag name
- `tag.class1.class2` — Tag with classes
- `tag#id` — Tag with ID
- `tag[attr=value]` — Tag with attribute
- `tag.class#id[attr=value]` — Combined syntax
- `/tag` — Self-closing tag
- `?tag` — Tag that doesn't render when empty

### 5.4 Attribute Management

Attributes are managed via `AttributeContainer`:
- Supports string, boolean, array, and callable values
- Boolean attributes render as attribute name only (if true)
- Null attributes render as attribute name only
- Special handling for `class` and `style` attributes
- Attributes starting with `:` are always stringified
- Data attributes (`data-*`) are always stringified

### 5.5 Class List Management

Class lists are managed via `ClassList`:
- Automatically deduplicates classes
- Supports space-separated strings
- Provides has/hasAll checks
- Can be accessed via `ClassList\Container` interface

### 5.6 Style Management

Styles are managed via `Collection`:
- Parses CSS property strings (`key: value;`)
- Supports dictionary operations
- Can be accessed via `Container` interface
- Style sheets support multiple blocks with selectors

### 5.7 Child Rendering

Child rendering handles:
- Closures — calls with parent element
- Iterables — iterates and renders each item
- Markup objects — renders directly
- MarkupProvider objects — converts to markup first
- Generators — includes return value
- Strings — escapes and renders
- Other values — coerces to string and escapes

### 5.8 Normalization

Element normalization:
- Flattens nested structures
- Resolves closures
- Converts MarkupProvider to Markup
- Handles iterables and generators
- Creates buffer objects for normalized children

### 5.9 Pretty Printing

Pretty printing:
- Adds indentation for block elements
- Preserves inline element formatting
- Adds newlines between elements
- Indents content within block elements

### 5.10 Escaping

Escaping:
- Uses `htmlspecialchars()` with `ENT_QUOTES` and `UTF-8`
- Applied to non-Markup content
- Buffers track escaping state
- Escaped buffers are not re-escaped

---

## 6. Error Handling

- Invalid tag name syntax throws `Exceptional::UnexpectedValue`
- Invalid attribute definitions throw `Exceptional::UnexpectedValue`
- Invalid ID (with spaces) throws `Exceptional::InvalidArgument`
- Invalid style data throws `Exceptional::InvalidArgument`
- Style parsing failures may return empty collections
- Child rendering failures may return empty strings
- Normalization failures may skip invalid children

---

## 7. Configuration & Extensibility

- Tag implementations can override `isSelfClosingTagName()` for custom self-closing tags
- Tag implementations can define `InlineTags` constant for inline tag detection
- Tag implementations can define `BooleanAttributes` constant for boolean attribute handling
- Buffer implementations can customize escaping behavior
- Child rendering can be customized via `ChildRendererTrait` methods
- Attribute preparation can be customized via `prepareAttribute()` method
- Style parsing can be customized via `parse()` methods
- Class list behavior can be customized via `ClassList` subclass

---

## 8. Interactions with Other Packages

### 8.1 Coercion

Elementary uses Coercion for:
- Type conversion when converting values to strings
- Attribute value parsing
- Style value parsing

### 8.2 Collections

Elementary uses Collections for:
- `SequenceInterface` on `Element` for content management
- `AttributeContainer` for attribute management
- `DictionaryInterface` for style management
- `ArrayProvider` for style parsing

### 8.3 Exceptional

Elementary uses Exceptional for:
- All exception handling
- Error reporting for invalid syntax

### 8.4 Nuance

Elementary uses Nuance for:
- Debugging support via `Dumpable` interface
- Entity representation via `NuanceEntity`
- Development tools integration

### 8.5 Tagged

Tagged uses Elementary as its foundation:
- Implements `Tag` and `Element` interfaces
- Uses traits for tag and element functionality
- Extends with HTML-specific features

### 8.6 Exemplar

Exemplar uses Elementary as its foundation:
- Implements `Tag` and `Element` interfaces
- Uses traits for tag and element functionality
- Extends with XML-specific features

---

## 9. Usage Examples

### 9.1 Basic Tag Implementation

```php
use DecodeLabs\Elementary\Tag;
use DecodeLabs\Elementary\TagTrait;
use DecodeLabs\Elementary\Buffer;

class MyTag implements Tag
{
    use TagTrait;
    
    protected function newBuffer(?string $content): Buffer
    {
        return new MyBuffer($content);
    }
}

$tag = new MyTag('div.class#id[data-value=test]');
echo $tag->open(); // <div class="class" id="id" data-value="test">
```

### 9.2 Element Implementation

```php
use DecodeLabs\Elementary\Element;
use DecodeLabs\Elementary\TagTrait;
use DecodeLabs\Elementary\ElementTrait;
use DecodeLabs\Elementary\Buffer;

class MyElement implements Element
{
    use TagTrait;
    use ElementTrait;
    
    protected function newBuffer(?string $content): Buffer
    {
        return new MyBuffer($content);
    }
}

$element = new MyElement('div', 'Hello World');
echo $element->render(); // <div>Hello World</div>
```

### 9.3 Class List Management

```php
use DecodeLabs\Elementary\Attribute\ClassList;

$classes = new ClassList('foo', 'bar');
$classes->add('baz');
$classes->has('foo'); // true
$classes->hasAll('foo', 'bar'); // true
$classes->remove('bar');
echo $classes; // "foo baz"
```

### 9.4 Style Management

```php
use DecodeLabs\Elementary\Style\Collection;

$styles = new Collection('color: red; background: blue;');
$styles->set('font-size', '16px');
echo $styles; // "color: red; background: blue; font-size: 16px;"
```

### 9.5 Style Sheet

```php
use DecodeLabs\Elementary\Style\Sheet;

$sheet = new Sheet([
    '.foo' => 'color: red;',
    '.bar' => 'background: blue;'
]);
echo $sheet->render();
// <style type="text/css">
//     .foo { color: red; }
//     .bar { background: blue; }
// </style>
```

### 9.6 Child Rendering

```php
$element->setBody([
    'Hello',
    function($parent) {
        return 'World';
    },
    $otherElement
]);
$element->normalize();
echo $element->renderContent();
```

### 9.7 Pretty Printing

```php
$element->render(true); // Pretty printed with indentation
```

### 9.8 Buffer Operations

```php
use DecodeLabs\Elementary\Buffer;

$buffer = new MyBuffer('Hello', false);
$buffer->append(' World');
$buffer->prepend('Say: ');
echo $buffer; // "Say: Hello World"
```

### 9.9 MarkupProvider

```php
use DecodeLabs\Elementary\MarkupProvider;

class MyProvider implements MarkupProvider
{
    public function toMarkup(): Markup
    {
        return new MyElement('div', 'Content');
    }
}

$provider = new MyProvider();
$element->setBody($provider);
```

### 9.10 ArrayAccess

```php
$tag['data-value'] = 'test';
$value = $tag['data-value'];
isset($tag['data-value']); // true
unset($tag['data-value']);
```

---

## 10. Implementation Notes (for Contributors)

### 10.1 Tag Name Parsing

Tag name parsing uses regex:
- `[attr=value]` — Attribute extraction
- `#id` — ID extraction
- `.class` — Class extraction
- `/tag` — Self-closing detection
- `?tag` — Empty rendering control

### 10.2 Attribute Preparation

Attribute preparation:
- Null values render as attribute name only
- Boolean values render as attribute name only (if true)
- Special handling for `:` prefix and `data-` prefix
- Arrays and callables are rendered as strings
- Escaped buffers are used directly

### 10.3 Child Rendering

Child rendering:
- Closures are called with parent element
- Iterables are iterated recursively
- MarkupProvider is converted to Markup
- Generators include return value
- Non-Markup content is escaped

### 10.4 Normalization

Normalization:
- Flattens nested structures
- Resolves closures
- Converts providers to markup
- Creates buffers for normalized children

### 10.5 Pretty Printing

Pretty printing:
- Detects block vs inline elements
- Adds indentation for block elements
- Preserves inline formatting
- Adds newlines between elements

### 10.6 Style Parsing

Style parsing:
- Collection parses `key: value;` format
- Sheet parses `selector { properties }` format
- Handles multiple selectors and blocks
- Supports array and string input

### 10.7 Class List

Class list:
- Uses associative array for deduplication
- Splits space-separated strings
- Provides efficient has/hasAll checks
- Converts to string for output

### 10.8 Buffer Escaping

Buffer escaping:
- Tracks escaping state
- Prevents double-escaping
- Used for attribute values
- Used for content rendering

---

## 11. Testing & Quality

- **Code Quality Score:** 4.5/5
- **README Quality Score:** 3/5
- **Documentation Score:** 0/5 (this spec)
- **Test Coverage Score:** 0/5

See `composer.json` for supported PHP versions.

---

## 12. Roadmap & Future Ideas

- Add more comprehensive style parsing
- Improve CSS selector parsing
- Add validation support
- Consider adding DOM parsing
- Consider adding XSS protection utilities
- Improve documentation and usage examples
- Add test coverage
- Consider adding template engine integration
- Consider adding JavaScript integration
- Add more attribute type support
- Improve pretty printing
- Add more normalization options

---

## 13. References

- [Coercion Package](https://github.com/decodelabs/coercion) — Type conversion
- [Collections Package](https://github.com/decodelabs/collections) — Data structures
- [Exceptional Package](https://github.com/decodelabs/exceptional) — Exception handling
- [Nuance Package](https://github.com/decodelabs/nuance) — Debugging
- [Tagged Package](https://github.com/decodelabs/tagged) — HTML generator (uses Elementary)
- [Exemplar Package](https://github.com/decodelabs/exemplar) — XML manipulator (uses Elementary)
- [Chorus Package Index](../../../chorus/config/packages.json) — Ecosystem metadata

