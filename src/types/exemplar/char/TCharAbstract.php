<?php

declare(strict_types=1);

namespace ZloyNick\StrictPhp\types\exemplar\char;

use Stringable;
use ZloyNick\StrictPhp\types\exception\TException;
use ZloyNick\StrictPhp\types\exemplar\TExemplar;

abstract class TCharAbstract extends TExemplar implements Stringable
{

    public function __construct(float|int|array|string $value)
    {
        parent::__construct($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function length(): int
    {
        return mb_strlen($this->value);
    }

    public function size(): int
    {
        return strlen($this->value);
    }

    protected function validateValue(): void
    {
        if(!is_string($this->value)) {
            throw new TException('Value of ' . get_class($this) . ' must be a string');
        }
    }

    public function toLowerCase(): static
    {
        $this->value = mb_strtolower($this->value);

        return $this;
    }

    public function toUpperCase(): static
    {
        $this->value = mb_strtoupper($this->value);

        return $this;
    }

    public function isNumeric():bool{
        return is_numeric($this->value);
    }

    public function find(TExemplar $key){

    }
}