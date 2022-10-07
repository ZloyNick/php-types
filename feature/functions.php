<?php

declare(strict_types=1);

/*
 * Functions for working with objects of ZloyNick\StrictPhp\types\exemplar\TExemplar
 */

use ZloyNick\StrictPhp\types\exemplar\char\TCharAbstract;
use ZloyNick\StrictPhp\types\exemplar\TExemplar;

// String functions
require_once __DIR__ . '/String/string_functions.php';
// Boolean functions
require_once __DIR__ . '/Boolean/boolean_functions.php';
// Number functions
require_once __DIR__ . '/Number/number_functions.php';

/**
 * Returns value of given TExemplar object.
 *
 * @param TExemplar|int|float|string|bool|array $exemplar Any TExemplar object.
 *
 * @return int|float|string|bool|array
 */
function t_val(TExemplar|int|float|string|bool|array $exemplar): int|float|string|bool|array
{
    return is_object($exemplar) ? $exemplar->getValue() : $exemplar;
}

function t_print(TCharAbstract|string ...$strings):void{
    echo implode('', $strings);
}

function t_printLn(TCharAbstract|string ...$strings):void{
    echo implode(PHP_EOL, $strings);
}