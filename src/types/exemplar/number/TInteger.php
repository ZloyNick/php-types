<?php

namespace ZloyNick\StrictPhp\types\exemplar\number;

use ZloyNick\StrictPhp\types\exception\TException;
use ZloyNick\StrictPhp\types\exemplar\TExemplar;

class TInteger extends TExemplar
{

    protected function validateValue(): void
    {
        if (!is_int($this->value)) {
            throw new TException('Value of ' . __CLASS__ . ' must be an integer');
        }
    }
}