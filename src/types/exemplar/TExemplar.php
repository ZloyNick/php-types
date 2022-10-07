<?php

declare(strict_types=1);

namespace ZloyNick\StrictPhp\types\exemplar;

use ZloyNick\StrictPhp\types\exemplar\array\{T2wayArray, TArray};
use ZloyNick\StrictPhp\types\exception\TException;
use ZloyNick\StrictPhp\types\exemplar\boolean\TBoolean;
use ZloyNick\StrictPhp\types\exemplar\char\{TChar, TCharAbstract};
use ZloyNick\StrictPhp\types\exemplar\number\{TByte, TFloat, TInteger, TNumber};
use ZloyNick\StrictPhp\types\exemplar\string\TString;
use ZloyNick\StrictPhp\types\TInterface;

/**
 * Base class for implementing types.
 */
abstract class TExemplar implements TInterface
{

    /**
     * The TExemplar constructor.
     *
     * @param int|string|array|float|bool $value
     * Can accept any type of data.
     *
     * @throws TException
     */
    public function __construct(
        protected int|string|array|float|bool $value
    )
    {
        $this->validateValue();
    }

    /**
     * This method checks the correctness of the raw value.
     * If the value does not match the expected value,
     * the method should throw an exception.
     *
     * @return void
     *
     * @throws TException
     */
    abstract protected function validateValue(): void;

    /**
     * The method returns a value as a type/object stored in the current instance.
     *
     * @return int|string|array|float|bool The raw value.
     */
    public function getValue(): int|string|array|float|bool
    {
        return $this->value;
    }

    /**
     * Checks whether the instance is TCharAbstract.
     *
     * @return bool
     * @see TChar
     * @see TString
     *
     * @see TCharAbstract
     */
    public function isChars(): bool
    {
        return $this instanceof TCharAbstract;
    }

    /**
     * Checks whether the instance is TArray.
     *
     * @return bool
     * @see T2wayArray
     *
     * @see TArray
     */
    public function isArray(): bool
    {
        return $this instanceof TArray;
    }

    /**
     * Checks whether the instance is TNumber.
     *
     * @return bool
     * @see TNumber
     */
    public function isNumber(): bool
    {
        return $this instanceof TNumber;
    }

    /**
     * Checks whether the instance is TInteger.
     *
     * @return bool
     * @see TInteger
     */
    public function isInteger(): bool
    {
        return $this instanceof TInteger;
    }

    /**
     * Checks whether the instance is TFloat.
     *
     * @return bool
     * @see TFloat
     */
    public function isFloat(): bool
    {
        return $this instanceof TFloat;
    }

    /**
     * Checks whether the instance is TByte.
     *
     * @return bool
     * @see TByte
     */
    public function isByte(): bool
    {
        return $this instanceof TByte;
    }

    /**
     * Checks whether the instance is TBoolean.
     *
     * @return bool
     * @see TBoolean
     */
    public function isBoolean(): bool
    {
        return $this instanceof TBoolean;
    }

}