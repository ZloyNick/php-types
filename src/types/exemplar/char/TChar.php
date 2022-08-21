<?php

declare(strict_types=1);

namespace ZloyNick\StrictPhp\types\exemplar\char;

use ZloyNick\StrictPhp\types\exception\TException;

class TChar extends TCharAbstract
{

    protected function validateValue(): void
    {
        parent::validateValue();

        if (($len = $this->length()) <> 1) {
            throw new TException('Char type of '.self::class.' must include only 1 char. '.$len.' given');
        }
    }
}