<?php

declare(strict_types=1);

namespace ZloyNick\StrictPhp\types\exemplar\number;

use ZloyNick\StrictPhp\types\exception\TException;

class TFloat extends TNumber
{

    protected function validateValue(): void
    {
        if (!is_int($this->value)) {
            throw new TException('Value of ' . __CLASS__ . ' must be a float');
        }
    }
}