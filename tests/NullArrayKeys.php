<?php

/**
 * Elementary
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Elementary\Tests\NullArrayKeys;

require dirname(__DIR__) . '/vendor/autoload.php';

error_reporting(E_ALL);
set_error_handler(static function (int $severity, string $message, string $file, int $line): never {
    throw new \ErrorException($message, 0, $severity, $file, $line);
});

function expectSame(mixed $expected, mixed $actual): void
{
    if ($expected !== $actual) {
        throw new \RuntimeException('Unexpected null-key regression result: ' . var_export($actual, true));
    }
}

try {
    /** @return \Generator<string|null,string> */
    function properties(): \Generator
    {
        yield null => 'first';
        yield '' => 'last';
        yield 'color' => 'red';
    }

    $styles = new \DecodeLabs\Elementary\Style\Collection(properties());
    expectSame(['' => 'last', 'color' => 'red'], $styles->toArray());
    $sheet = new \DecodeLabs\Elementary\Style\Sheet((static function (): \Generator {
        yield null => ['color' => 'red'];
        yield '' => ['color' => 'blue'];
        yield '.normal' => ['color' => 'green'];
    })());
    expectSame('blue', $sheet->get('')?->get('color'));
    expectSame('green', $sheet->get('.normal')?->get('color'));
} finally {
    restore_error_handler();
}

echo "Null array-key regression checks passed.\n";
