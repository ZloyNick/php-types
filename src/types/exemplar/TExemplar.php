<?php

namespace ZloyNick\StrictPhp\types\exemplar;

use ZloyNick\StrictPhp\types\exemplar\array\TArray;
use ZloyNick\StrictPhp\types\exemplar\char\TCharAbstract;
use ZloyNick\StrictPhp\types\exemplar\number\TNumber;
use ZloyNick\StrictPhp\types\TInterface;

abstract class TExemplar implements TInterface
{

    public function __construct(
        protected int|string|array|float|bool $value
    )
    {
        $this->validateValue();
    }

    abstract protected function validateValue():void;

    public function getValue(): int|string|array|float
    {
        return $this->value;
    }

    public function isChars():bool{
        return $this instanceof TCharAbstract;
    }

    public function isArray():bool{
        return $this instanceof TArray;
    }

    public function isNumber():bool{
        return $this instanceof TNumber;
    }

}