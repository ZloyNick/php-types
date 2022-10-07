<?php

declare(strict_types=1);

namespace ZloyNick\StrictPhp\types\exemplar\char;

use ZloyNick\StrictPhp\types\exception\TException;

/**
 * The symbol object.
 */
class TChar extends TCharAbstract
{

    protected function validateValue(): void
    {
        if (($len = t_val($this->length())) > 1) {
            throw new TException('Char type of '.self::class.' must include only 1 char. '.$len.' given');
        }

        if ($len === 0) {
            throw new TException('Char type of '.self::class.' must include 1 char. 0 given');
        }
    }
}