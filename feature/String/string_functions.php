<?php

declare(strict_types=1);

/*
 * Methods for working with objects of ZloyNick\StrictPhp\types\string\TCharAbstract
 */

namespace ZloyNick\StrictPhp\String {

    use ZloyNick\StrictPhp\types\exception\TException;
    use ZloyNick\StrictPhp\types\exemplar\boolean\TBoolean;
    use ZloyNick\StrictPhp\types\exemplar\char\TChar;
    use ZloyNick\StrictPhp\types\exemplar\char\TCharAbstract;
    use ZloyNick\StrictPhp\types\exemplar\number\TInteger;
    use ZloyNick\StrictPhp\types\exemplar\string\TString;

    /**
     * Analogous to the str_replace function and returns result as object.
     *
     * @param string|TCharAbstract $replace
     * @param string|TCharAbstract $with
     * @param string|TString $str
     * @param TInteger|int|null $count
     * @return TString
     * @throws TException
     * @see str_replace()
     */
    function t_str_replace(
        string|TCharAbstract $replace,
        string|TCharAbstract $with,
        string|TString       $str,
        TInteger|int|null    $count = null,
    ): TCharAbstract
    {
        $outputString = \str_replace(
            (string)$replace,
            (string)$with,
            (string)$str,
            $count,
        );

        return \mb_strlen($outputString) === 1 ? new TChar($outputString) : new TString($outputString);
    }

    /**
     * Analogous to the php str_replace function, but using \ZloyNick\StrictPhp\types\exemplar\TExemplar objects.
     *
     * @param string|TCharAbstract $replace
     * @param string|TCharAbstract $with
     * @param string|TString $str
     * @param TInteger|int|null $count
     * @return string
     * @see \str_replace()
     */
    function str_replace(
        string|TCharAbstract $replace,
        string|TCharAbstract $with,
        string|TString       $str,
        TInteger|int|null    $count = null,
    ): string
    {
        $count = t_val($count);

        return \str_replace(
            (string)$replace,
            (string)$with,
            (string)$str,
            $count,
        );
    }

    /**
     * Analogous to the php strpos function, but using \ZloyNick\StrictPhp\types\exemplar\TExemplar objects
     * and returns result as object.
     *
     * @param TCharAbstract|string $needle
     * @param TString|string $haystack
     * @param TInteger|int $in_offset
     * @return TInteger|TBoolean
     * @throws TException
     * @see strpos()
     */
    function t_strpos(TCharAbstract|string $needle, TString|string $haystack, TInteger|int $in_offset = 0): TInteger|TBoolean
    {
        $result = strpos(
            $needle,
            $haystack,
            t_val($in_offset),
        );

        return $result === false ? new TBoolean(false) : new TInteger($result);
    }

    /**
     * Analogous to the php strpos function, but using \ZloyNick\StrictPhp\types\exemplar\TExemplar objects.
     *
     * @param TCharAbstract|string $needle
     * @param TString|string $haystack
     * @param TInteger|int $in_offset
     * @return int|false
     */
    function strpos(TCharAbstract|string $needle, TString|string $haystack, TInteger|int $in_offset = 0): int|false
    {
        return \strpos((string)$needle, (string)$haystack, t_val($in_offset));
    }

    /**
     * Analogous to the php stripos function, but using \ZloyNick\StrictPhp\types\exemplar\TExemplar objects.
     * and returns result as object.
     *
     * @param TCharAbstract|string $needle
     * @param TString|string $haystack
     * @param TInteger|int $in_offset
     * @return TInteger|TBoolean
     * @throws TException
     */
    function t_stripos(TCharAbstract|string $needle, TString|string $haystack, TInteger|int $in_offset = 0): TInteger|TBoolean
    {
        $result = stripos(
            $needle,
            $haystack,
            t_val($in_offset),
        );

        return $result === false ? new TBoolean(false) : new TInteger($result);
    }

    /**
     * Analogous to the php stripos function, but using \ZloyNick\StrictPhp\types\exemplar\TExemplar objects.
     *
     * @param TCharAbstract|string $needle
     * @param TString|string $haystack
     * @param TInteger|int $in_offset
     * @return int|null
     */
    function stripos(TCharAbstract|string $needle, TString|string $haystack, TInteger|int $in_offset = 0): int|null
    {
        return \stripos((string)$needle, (string)$haystack, t_val($in_offset));
    }

    /**
     * Analogous to the php strcmp function, but using \ZloyNick\StrictPhp\types\exemplar\TExemplar objects
     * and returns result as object.
     *
     * @param string|TCharAbstract $str
     * @param string|TCharAbstract $compareWith
     * @return TBoolean
     * @throws TException
     */
    function t_strcpm(string|TCharAbstract $str, string|TCharAbstract $compareWith): TBoolean
    {
        return new TBoolean(strcpm($str, $compareWith));
    }

    /**
     * Analogous to the php strcmp function, but using \ZloyNick\StrictPhp\types\exemplar\TExemplar objects.
     *
     * @param string|TCharAbstract $str
     * @param string|TCharAbstract $compareWith
     * @return bool
     */
    function strcpm(string|TCharAbstract $str, string|TCharAbstract $compareWith): bool
    {
        return (string)$str === (string)$compareWith;
    }

    /**
     * Analogous to the php strtolower function, but using \ZloyNick\StrictPhp\types\exemplar\TExemplar objects
     * and returns result as object.
     *
     * @param TCharAbstract|string $str
     * @return TCharAbstract
     * @throws TException
     */
    function t_strtolower(TCharAbstract|string $str): TCharAbstract
    {
        $result = strtolower($str);

        return \strlen($result) === 1 ? new TChar($result) : new TString($result);
    }

    /**
     * Analogous to the php strtolower function, but using \ZloyNick\StrictPhp\types\exemplar\TExemplar objects.
     *
     * @param TCharAbstract|string $str
     * @return string
     */
    function strtolower(TCharAbstract|string $str): string
    {
        return \strtolower((string)$str);
    }

    /**
     * Analogous to the php mb_strtolower function, but using \ZloyNick\StrictPhp\types\exemplar\TExemplar objects
     * and returns result as object.
     *
     * @param TCharAbstract|string $str
     * @return TCharAbstract
     * @throws TException
     */
    function t_mb_strtolower(TCharAbstract|string $str): TCharAbstract
    {
        $result = mb_strtolower($str);

        return \mb_strlen($result) === 1 ? new TChar($result) : new TString($result);
    }

    /**
     * Analogous to the php mb_strtolower function, but using \ZloyNick\StrictPhp\types\exemplar\TExemplar objects.
     *
     * @param TCharAbstract|string $str
     * @return string
     */
    function mb_strtolower(TCharAbstract|string $str): string
    {
        return \mb_strtolower((string)$str);
    }

    /**
     * Analogous to the php strtoupper function, but using \ZloyNick\StrictPhp\types\exemplar\TExemplar objects
     * and returns result as object.
     *
     * @param TCharAbstract|string $str
     * @return TCharAbstract
     * @throws TException
     */
    function t_strtoupper(TCharAbstract|string $str): TCharAbstract
    {
        $result = strtoupper($str);

        return \strlen($result) === 1 ? new TChar($result) : new TString($result);
    }

    /**
     * Analogous to the php strtoupper function, but using \ZloyNick\StrictPhp\types\exemplar\TExemplar objects
     *
     * @param TCharAbstract|string $str
     * @return string
     */
    function strtoupper(TCharAbstract|string $str): string
    {
        return \strtoupper((string)$str);
    }

    /**
     * Analogous to the php mb_strtoupper function, but using \ZloyNick\StrictPhp\types\exemplar\TExemplar objects
     * and returns result as object.
     *
     * @param TCharAbstract|string $str
     * @return TCharAbstract
     * @throws TException
     */
    function t_mb_strtoupper(TCharAbstract|string $str): TCharAbstract
    {
        $result = mb_strtoupper($str);

        return \mb_strlen($result) === 1 ? new TChar($result) : new TString($result);
    }

    /**
     * Analogous to the php mb_strtoupper function, but using \ZloyNick\StrictPhp\types\exemplar\TExemplar objects
     *
     * @param TCharAbstract|string $str
     * @return string
     */
    function mb_strtoupper(TCharAbstract|string $str): string
    {
        return \mb_strtoupper((string)$str);
    }

    /**
     * Analogous to the php is_numeric function, but using \ZloyNick\StrictPhp\types\exemplar\TExemplar objects
     * and returns result as object.
     *
     * @param TCharAbstract|string $str
     * @return TBoolean
     *
     * @throws TException
     */
    function t_is_numeric(TCharAbstract|string $str): TBoolean
    {
        return new TBoolean(is_numeric($str));
    }

    /**
     * Analogous to the php is_numeric function, but using \ZloyNick\StrictPhp\types\exemplar\TExemplar objects.
     *
     * @param TCharAbstract|string $str
     * @return bool
     */
    function is_numeric(TCharAbstract|string $str): bool
    {
        return \is_numeric($str);
    }

    /**
     * Analogous to the php mb_strlen function, but using \ZloyNick\StrictPhp\types\exemplar\TExemplar objects.
     *
     * @param TCharAbstract|string $str
     * @return int
     */
    function mb_strlen(TCharAbstract|string $str): int
    {
        return \mb_strlen((string)$str);
    }

    /**
     * Analogous to the php mb_strlen function, but using \ZloyNick\StrictPhp\types\exemplar\TExemplar objects
     * and returns result as object
     *
     * @param TCharAbstract|string $str
     * @return TInteger
     * @throws TException
     */
    function t_mb_strlen(TCharAbstract|string $str): TInteger
    {
        return new TInteger(\mb_strlen((string)$str));
    }

    /**
     * Analogous to the php strlen function, but using \ZloyNick\StrictPhp\types\exemplar\TExemplar objects
     * and returns result as object
     *
     * @param TCharAbstract|string $str
     * @return TInteger
     * @throws TException
     */
    function t_strlen(TCharAbstract|string $str): TInteger
    {
        return new TInteger(strlen($str));
    }

    /**
     * Analogous to the php strlen function, but using \ZloyNick\StrictPhp\types\exemplar\TExemplar objects.
     *
     * @param TCharAbstract|string $str
     * @return int
     */
    function strlen(TCharAbstract|string $str): int
    {
        return \strlen((string)$str);
    }

    /**
     * @param TCharAbstract|string $str
     * @return string
     */
    function strrev(TCharAbstract|string $str): string
    {
        return \strrev((string)$str);
    }

    /**
     * @param TCharAbstract|string $str
     * @return TCharAbstract
     * @throws TException
     */
    function t_strrev(TCharAbstract|string $str): TCharAbstract
    {
        $reversedString = strrev((string)$str);

        return \strlen($reversedString) === 1 ? new TChar($reversedString) : new TString($reversedString);
    }

}