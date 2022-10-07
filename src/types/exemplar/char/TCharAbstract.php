<?php

declare(strict_types=1);

namespace ZloyNick\StrictPhp\types\exemplar\char;

use Stringable;
use ZloyNick\StrictPhp\types\exemplar\boolean\TBoolean;
use ZloyNick\StrictPhp\types\exemplar\number\TInteger;
use ZloyNick\StrictPhp\types\exemplar\TExemplar;
use function is_numeric;
use function mb_strlen;
use function mb_strtolower;
use function mb_strtoupper;
use function strlen;

/**
 * A base class for working with strings.
 *
 * This object also provides methods for working with both single-byte and multibyte characters.
 */
abstract class TCharAbstract extends TExemplar implements Stringable
{

    /**
     * The constructor of the TCharAbstract object must take value of type 'string'.
     *
     * @param string $value Source string.
     * @throws \ZloyNick\StrictPhp\types\exception\TException
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    /**
     * @inheritDoc
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * The method returns the number of characters in the source string.
     *
     * @return TInteger The number of characters in the source string.
     * @throws \ZloyNick\StrictPhp\types\exception\TException
     */
    public function length(): TInteger
    {
        return new TInteger(mb_strlen($this->value));
    }

    /**
     * Returns the number of bytes that the original string occupies.
     *
     * @return TInteger Bytes.
     * @throws \ZloyNick\StrictPhp\types\exception\TException
     */
    public function size(): TInteger
    {
        return new TInteger(strlen($this->value));
    }

    /**
     * Returns a copy of the object with the original string in lowercase.
     *
     * @return static Current class.
     */
    public function toLowerCase(): static
    {
        $this->value = mb_strtolower($this->value);

        return $this;
    }

    /**
     * Returns a copy of the object with the original string in uppercase.
     *
     * @return static Current class.
     */
    public function toUpperCase(): static
    {
        $this->value = mb_strtoupper($this->value);

        return $this;
    }

    /**
     * Checks whether the original value is a numeric string.
     *
     * @return TBoolean
     * @throws \ZloyNick\StrictPhp\types\exception\TException
     * @see is_numeric()
     */
    public function isNumeric(): TBoolean
    {
        return new TBoolean(\is_numeric($this->value));
    }

    /**
     * Returns true if the strings are the same.
     *
     * @param string|TCharAbstract $target Compare with
     * @return TBoolean Are strings same?
     * @throws \ZloyNick\StrictPhp\types\exception\TException
     */
    public function cmp(string|TCharAbstract $target): TBoolean
    {
        return new TBoolean((string)$target === $this->value);
    }
}