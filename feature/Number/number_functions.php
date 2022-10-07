<?php

/*
 * Methods for working with objects of ZloyNick\StrictPhp\types\number\TNumber
 */

namespace ZloyNick\StrictPhp\String {

    use RuntimeException;
    use ZloyNick\StrictPhp\types\exemplar\number\TFloat;
    use ZloyNick\StrictPhp\types\exemplar\number\TInteger;
    use function is_float;

    /**
     * Diffs up all the values from $entries and returns an object, depending on the result obtained.
     *
     * @param TInteger|TFloat|int|float $target
     * @param TInteger|TFloat|int|float ...$numbers
     * @return float|int
     */
    function diff(TInteger|TFloat|int|float $target, TInteger|TFloat|int|float ...$numbers) : float|int{
        $diff = t_val($target);

        foreach ($numbers as $number) {
            $diff -= t_val($number);
        }

        return $diff;
    }

    /**
     * Diffs up all the values from $entries and returns an object, depending on the result obtained.
     *
     * @param TInteger|TFloat|int|float $target
     * @param TInteger|TFloat|int|float ...$numbers
     * @return TInteger|TFloat
     * @throws \ZloyNick\StrictPhp\types\exception\TException
     */
    function t_diff(TInteger|TFloat|int|float $target, TInteger|TFloat|int|float ...$numbers) : TInteger|TFloat{
        $diff = diff($target, ...$numbers);

        return is_float($diff) ? new TFloat($diff) : new TInteger($diff);
    }

    /**
     * Sums up all the values from $entries and returns an object, depending on the result obtained.
     *
     * @param TInteger|TFloat|int|float $target
     * @param TInteger|TFloat|int|float ...$numbers
     * @return float|int
     */
    function sum(TInteger|TFloat|int|float $target, TInteger|TFloat|int|float ...$numbers) : float|int{
        $sum = t_val($target);

        foreach ($numbers as $number) {
            $sum += t_val($number);
        }

        return $sum;
    }

    /**
     * Sums up all the values from $entries and returns an object, depending on the result obtained.
     *
     * @param TInteger|TFloat|int|float $target
     * @param TInteger|TFloat|int|float ...$numbers
     * @return TInteger|TFloat
     * @throws \ZloyNick\StrictPhp\types\exception\TException
     */
    function t_sum(TInteger|TFloat|int|float $target, TInteger|TFloat|int|float ...$numbers) : TInteger|TFloat{
        $sum = sum($target, ...$numbers);

        return is_float($sum) ? new TFloat($sum) : new TInteger($sum);
    }

    /**
     * Multiplication a number by many others numbers
     *
     * @param TInteger|TFloat|int|float $target
     * @param TInteger|TFloat|int|float ...$numbers
     * @return float|int
     */
    function multiply_by(TInteger|TFloat|int|float $target, TInteger|TFloat|int|float ...$numbers) : float|int {
        $multiplicationResult = t_val($target);

        foreach ($numbers as $number) {
            $multiplicationResult *= t_val($number);
        }

        return $multiplicationResult;
    }

    /**
     * Multiplication a number by many others numbers
     *
     * @param TInteger|TFloat|int|float $target
     * @param TInteger|TFloat|int|float ...$numbers
     * @return TFloat|TInteger
     * @throws \ZloyNick\StrictPhp\types\exception\TException
     */
    function t_multiply_by(TInteger|TFloat|int|float $target, TInteger|TFloat|int|float ...$numbers) :TFloat|TInteger {
        $multiplicationResult = multiply_by($target, ...$numbers);

        return is_float($multiplicationResult) ? new TFloat($multiplicationResult) : new TInteger($multiplicationResult);
    }

    /**
     * Dividing a number by many others numbers
     *
     * @param TInteger|TFloat|int|float $target
     * @param TInteger|TFloat|int|float ...$numbers
     * @return float|int
     */
    function divide_by(TInteger|TFloat|int|float $target, TInteger|TFloat|int|float ...$numbers) : float|int {
        $multiplicationResult = multiply_by(1, ...$numbers);

        if ($multiplicationResult === 0) {
            throw new RuntimeException('Division by zero.');
        }

        return t_val($target) / multiply_by(1, ...$numbers);
    }

    /**
     * Dividing a number by many others numbers
     *
     * @param TInteger|TFloat|int|float $target
     * @param TInteger|TFloat|int|float ...$numbers
     * @return TFloat|TInteger
     * @throws \ZloyNick\StrictPhp\types\exception\TException
     */
    function t_divide_by(TInteger|TFloat|int|float $target, TInteger|TFloat|int|float ...$numbers) :TFloat|TInteger {
        $dividingResult = divide_by($target, ...$numbers);

        return is_float($dividingResult) ? new TFloat($dividingResult) : new TInteger($dividingResult);
    }

    /**
     * Selects an integer value from a number.
     *
     * @param TInteger|TFloat|int|float $target
     * @return int
     */
    function trunc(TInteger|TFloat|int|float $target):int {
        return (int)t_val($target);
    }

    /**
     * Selects an integer value from a number.
     *
     * @param TInteger|TFloat|int|float $target
     * @return TInteger
     * @throws \ZloyNick\StrictPhp\types\exception\TException
     */
    function t_trunc(TInteger|TFloat|int|float $target) : TInteger {
        return new TInteger(trunc($target));
    }

    /**
     * Returns the rounded value of val to specified precision (number of digits after the decimal point).
     *
     * @param TInteger|TFloat|int|float $target
     * @param TInteger|int $prevision
     * @param TInteger|int $mode
     * @return float
     */
    function round(TInteger|TFloat|int|float $target, TInteger|int $prevision, TInteger|int $mode = PHP_ROUND_HALF_UP) :float {
        return \round(t_val($target), t_val($prevision), t_val($mode));
    }

    /**
     * Returns the rounded value of val to specified precision (number of digits after the decimal point).
     *
     * @param TInteger|TFloat|int|float $target
     * @param TInteger|int $prevision
     * @param TInteger|int $mode
     * @return TFloat
     * @throws \ZloyNick\StrictPhp\types\exception\TException
     */
    function t_round(TInteger|TFloat|int|float $target, TInteger|int $prevision, TInteger|int $mode = PHP_ROUND_HALF_UP) {
        return new TFloat(round($target, $prevision, $mode));
    }

}