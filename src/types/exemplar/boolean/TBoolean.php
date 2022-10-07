<?php

declare(strict_types=1);

namespace ZloyNick\StrictPhp\types\exemplar\boolean;

use ZloyNick\StrictPhp\types\exemplar\number\TByte;
use ZloyNick\StrictPhp\types\exemplar\number\TNumber;

/**
 * Boolean implementation object.
 */
class TBoolean extends TByte
{

    /**
     * @param TByte|TNumber|bool|int|float $value
     * @throws \ZloyNick\StrictPhp\types\exception\TException
     */
    public function __construct(TByte|TNumber|bool|int|float $value)
    {
        parent::__construct((int)(bool)t_val($value));
    }

    /**
     * Returns a boolean object with the opposite value.
     *
     * @return static
     */
    public function not(): static
    {
        $this->value = (int)!$this->value;

        return $this;
    }

    public function getValue(): bool
    {
        return $this->value === 1;
    }

    /**
     * Checks whether the value is false.
     *
     * @return bool
     */
    public function isNegative(): bool
    {
        return !$this->getValue();
    }

    /**
     * Checks whether the value is true.
     *
     * @return bool
     */
    public function isPositive(): bool
    {
        return !$this->isNegative();
    }

}