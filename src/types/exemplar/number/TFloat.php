<?php

declare(strict_types=1);

namespace ZloyNick\StrictPhp\types\exemplar\number;

/**
 * TFloat implementation object.
 */
class TFloat extends TNumber
{

    public function __construct(float $value)
    {
        parent::__construct($value);
    }

    protected function validateValue(): void
    {
    }
}