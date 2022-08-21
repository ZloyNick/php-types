<?php

namespace ZloyNick\StrictPhp\types\exemplar\boolean;

use ZloyNick\StrictPhp\types\exception\TException;
use ZloyNick\StrictPhp\types\exemplar\number\TByte;

class TBoolean extends TByte
{

    public function validateValue(): void
    {
        if (!is_bool($this->value)) {
            throw new TException('Invalid boolean value given');
        }
    }

    public function not(): self
    {
        $this->value = !$this->value;

        return $this;
    }

}