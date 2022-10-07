<?php

declare(strict_types=1);

namespace ZloyNick\StrictPhp\types\exemplar\number;

use ZloyNick\StrictPhp\types\exception\TException;

/**
 * Integer implementation object.
 */
class TInteger extends TNumber
{

    /**
     * @param int $value
     * @throws TException
     */
    public function __construct(int $value)
    {
        parent::__construct($value);
    }

    /**
     * @return void
     */
    protected function validateValue(): void
    {
    }

    public function truncate(): TInteger
    {
        return $this;
    }

    public function round(int|TInteger $prevision, int|TInteger $mode = PHP_ROUND_HALF_UP): TFloat
    {
        return new TFloat($this->value);
    }

}