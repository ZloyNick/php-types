<?php

declare(strict_types=1);

namespace ZloyNick\StrictPhp\types\exemplar\number;

use Stringable;
use ZloyNick\StrictPhp\types\exception\TException;
use ZloyNick\StrictPhp\types\exemplar\TExemplar;
use function is_float;
use function round;

/**
 * An abstract class for implementing numeric objects.
 */
abstract class TNumber extends TExemplar implements Stringable
{

    /**
     * Allowed modes for rounding numbers.
     */
    public const ALLOWED_ROUND_MODES = [
        PHP_ROUND_HALF_UP,
        PHP_ROUND_HALF_DOWN,
        PHP_ROUND_HALF_EVEN,
        PHP_ROUND_HALF_ODD,
    ];

    /**
     * @param float|int $value
     * @throws \ZloyNick\StrictPhp\types\exception\TException
     */
    public function __construct(float|int $value)
    {
        parent::__construct($value);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->value;
    }

    /**
     * Sums up all the values from $entries and returns an object, depending on the result obtained.
     *
     * @return TFloat|TNumber The amount.
     *
     * @throws \ZloyNick\StrictPhp\types\exception\TException
     */
    public function sum(TNumber|int|float ...$numbers): TFloat|TNumber
    {
        $value = $this->value;

        foreach ($numbers as $number) {
            $value += t_val($number);
        }

        return is_float($value) ? new TFloat($value) : new TInteger($value);
    }

    /**
     * Diffs up all the values from $entries and returns an object, depending on the result obtained.
     *
     * @return TFloat|TNumber The diff.
     *
     * @throws \ZloyNick\StrictPhp\types\exception\TException
     */
    public function diff(TNumber|int|float ...$numbers): TFloat|TNumber
    {
        $value = $this->value;

        foreach ($numbers as $number) {
            $value += t_val($number);
        }

        return is_float($value) ? new TFloat($value) : new TInteger($value);
    }

    /**
     * Multiplication a number by many others numbers
     *
     * @param TInteger|TFloat|int|float ...$numbers
     * @return TFloat|TNumber
     * @throws TException
     */
    public function multiply(TInteger|TFloat|int|float ...$numbers): TFloat|TNumber
    {
        $multiplicationResult = $this->value;

        foreach ($numbers as $number) {
            $multiplicationResult *= t_val($number);
        }

        return is_float($multiplicationResult) ? new TFloat($multiplicationResult) : new TInteger($multiplicationResult);
    }

    /**
     * Dividing a number by many others numbers
     *
     * @param TInteger|TFloat|int|float ...$numbers
     * @return TFloat|TNumber
     * @throws TException
     */
    public function divide(TInteger|TFloat|int|float ...$numbers): TFloat|TNumber
    {
        $multiplicationResult = $this->multiply(...$numbers)->getValue();

        if ($multiplicationResult === 0) {
            throw new TException('Division by zero.');
        }

        $divisionResult = $this->value / $multiplicationResult;

        return is_float($divisionResult) ? new TFloat($divisionResult) : new TInteger($divisionResult);
    }

    /**
     * Selects an integer value from a number.
     *
     * @return TInteger
     * @throws TException
     */
    function truncate(): TInteger
    {
        return new TInteger((int)$this->value);
    }

    /**
     * Returns the rounded value of val to specified precision (number of digits after the decimal point).
     *
     * @param TInteger|int $prevision
     * @param TInteger|int $mode
     * @return TFloat
     * @throws TException
     */
    public function round(TInteger|int $prevision, TInteger|int $mode = PHP_ROUND_HALF_UP): TFloat
    {
        if (!in_array($prevision, self::ALLOWED_ROUND_MODES)) {
            throw new TException(
                'Invalid round mode given: ' . t_val($mode) . '. Allowed: ' . implode(', ', self::ALLOWED_ROUND_MODES)
            );
        }

        return new TFloat(round($this->value, $prevision, t_val($mode)));
    }

}