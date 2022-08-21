<?php

declare(strict_types=1);

namespace ZloyNick\StrictPhp\types\exemplar\number;

use ZloyNick\StrictPhp\types\exception\TException;

class TByte extends TNumber
{

    protected function validateValue(): void
    {
        $value = &$this->value;

        if($value <= 0 || $value > 1) {
            throw new TException('Invalid byte value given: '.$value.'. Allowed values: 0, 1');
        }
    }
}