<?php

/*
 * Methods for working with objects of ZloyNick\StrictPhp\types\boolean\TBoolean
 */

namespace ZloyNick\StrictPhp\Boolean {

    use ZloyNick\StrictPhp\types\exemplar\boolean\TBoolean;
    use ZloyNick\StrictPhp\types\exemplar\number\TNumber;
    use function is_object;

    /**
     * Converts a Boolean value to the opposite.
     *
     * @param TNumber|TBoolean|int|bool $boolean
     * @return bool
     */
    function not(TNumber|TBoolean|int|bool $boolean): bool
    {
        return !(is_object($boolean) ? t_val($boolean) : $boolean);
    }

    /**
     * Converts a Boolean value to the opposite, but returns bool as TBoolean object.
     *
     * @param TNumber|TBoolean|int|bool $boolean
     * @return TBoolean
     * @throws \ZloyNick\StrictPhp\types\exception\TException
     */
    function t_not(TNumber|TBoolean|int|bool $boolean): TBoolean
    {
        return new TBoolean(not($boolean));
    }

    /**
     * Checks the equality of Boolean types.
     *
     * @param TNumber|TBoolean|int|bool ...$booleans
     * @return bool Returns 'false' if the types are not equivalent.
     */
    function bool_cmp(TNumber|TBoolean|int|bool ...$booleans): bool
    {
        if (empty($booleans)) {
            return false;
        }

        $firstBoolValue = $booleans[0];

        unset($booleans[0]);

        foreach ($booleans as $bool) {
            if (!($firstBoolValue && (is_object($bool) ? t_val($bool) : $bool))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks the equality of Boolean types, but returns bool as TBoolean object.
     *
     * @param TNumber|TBoolean|int|bool ...$booleans
     * @return TBoolean Returns 'TBoolean(false)' if the types are not equivalent.
     * @throws \ZloyNick\StrictPhp\types\exception\TException
     */
    function t_bool_cmp(TNumber|TBoolean|int|bool ...$booleans): TBoolean
    {
        return new TBoolean(bool_cmp(...$booleans));
    }

    /**
     * Checks whether the passed boolean types have true. Analog of 'or'.
     *
     * @param TNumber|TBoolean|int|bool ...$booleans
     * @return bool
     */
    function bool_or(TNumber|TBoolean|int|bool ...$booleans): bool
    {
        if (empty($booleans)) {
            return false;
        }

        foreach ($booleans as $bool) {
            if (is_object($bool) ? t_val($bool) : $bool) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks whether the passed boolean types have true. Analog of 'or',
     * but returns bool as TBoolean object.
     *
     * @param TNumber|TBoolean|int|bool ...$booleans
     * @return TBoolean
     * @throws \ZloyNick\StrictPhp\types\exception\TException
     */
    function t_bool_or(TNumber|TBoolean|int|bool ...$booleans): TBoolean
    {
        return new TBoolean(bool_or(...$booleans));
    }

}