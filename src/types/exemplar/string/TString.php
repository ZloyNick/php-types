<?php

declare(strict_types=1);

namespace ZloyNick\StrictPhp\types\exemplar\string;

use ArrayAccess;
use SeekableIterator;
use ZloyNick\StrictPhp\types\exception\TException;
use ZloyNick\StrictPhp\types\exemplar\char\TChar;
use ZloyNick\StrictPhp\types\exemplar\char\TCharAbstract;
use ZloyNick\StrictPhp\types\exemplar\number\TInteger;
use function is_string;
use function mb_strlen;
use function mb_substr;
use function str_replace;
use function strrev;

/**
 *
 */
class TString extends TCharAbstract implements ArrayAccess, SeekableIterator
{

    /** @var int Maximum allowed number of characters */
    protected int $maxLength;
    /** @var int Using for iteration */
    protected int $position = 0;

    /**
     * The constructor of the TString object must take value of type 'string'.
     *
     * @param string $value Source string.
     * @param int|null $maxLength Max length.
     * @throws TException
     */
    public function __construct(string $value, ?int $maxLength = null)
    {
        $this->value = $value;
        $this->maxLength = $maxLength ?? t_val($this->length());

        $this->validateValue();
    }

    /**
     * Replace the current value with the reverse string.
     *
     * @return static Current object.
     */
    public function reverse(): static
    {
        $this->value = strrev($this->value);

        return $this;
    }

    /**
     * Returns the index or array of indexes from which the substring you are looking for begins.
     *
     * @param TCharAbstract|string $char The substring being searched for.
     * @param bool $returnAll If true, it returns an array of indexes.
     * @param bool $registerImportant If false, the case will be taken into account.
     *
     * @return TInteger|int[]|null
     * @throws TException
     */
    public function indexOf(
        TCharAbstract|string $char,
        bool                 $returnAll = false,
        bool                 $registerImportant = false
    ): null|TInteger|array
    {
        $needle = (string)$char;

        if (!$returnAll) {
            $fn = $registerImportant ? 'mb_strpos' : 'mb_stripos';
            $index = $fn($this->value, $needle);

            return $index ? new TInteger($index) : null;
        }

        return static::searchRecursive($this->value, $char, $registerImportant);
    }

    /**
     * Recursive substring search.
     *
     * @param TCharAbstract|string $str Search at.
     * @param TCharAbstract|string $needle Needle.
     * @param bool $registerImportant If false, the case will be taken into account.
     * @param int $startIndex Which index to start the search from.
     * @return int[]
     */
    protected static function searchRecursive(
        TCharAbstract|string &$str,
        TCharAbstract|string &$needle,
        bool                 $registerImportant = false,
        int                  $startIndex = 0
    ): array
    {
        $fn = $registerImportant ? 'mb_strpos' : 'mb_stripos';
        $results = [];
        $index = $fn((string)$str, (string)$needle, $startIndex);

        if ($index === false) {
            return $results;
        } else {
            $results[] = $index;

            return [...$results, ...static::searchRecursive($str, $needle, $registerImportant, $index + 1)];
        }
    }

    /**
     * Changes the case of the string characters in the specified range.
     *
     * @param int $start The index of the beginning. From which element to start changing the case.
     * @param int|null $end The index of the end. On which element to interrupt. If null, the index
     * will be equal to the maximum index of the string.
     * @param bool $lower If True, it will convert to lowercase. Otherwise - to the upper.
     * @return static Updated current object.
     * @throws TException
     */
    private function changeCase(int $start = 0, ?int $end = null, bool $lower = true): static
    {
        if (!$this->offsetExists($start)) {
            return $this;
        }

        $fn = $lower ? 'mb_strtolower' : 'mb_strtoupper';

        if ($end !== null) {
            $end = !$this->offsetExists($end) ? t_val($this->length()) - 1 : $end;
            $value = &$this->value;

            for (; $start <= $end; $start++) {
                $this->offsetSet($start, $fn($value[$start]));
            }

            return $this;
        }

        $this->offsetSet($start, $fn($this->value[$start]));

        return $this;
    }

    /**
     * Changes the case of the string characters to the upper case in the specified range.
     *
     * @param int $start The index of the beginning. From which element to start changing the case.
     * @param int|null $end The index of the end. On which element to interrupt. If null, the index
     * will be equal to the maximum index of the string.
     * @return static
     * @throws TException
     */
    public function upperCaseChars(int $start = 0, ?int $end = null): static
    {
        return $this->changeCase($start, $end, false);
    }

    /**
     * Changes the case of the string characters to the lower case in the specified range.
     *
     * @param int $start The index of the beginning. From which element to start changing the case.
     * @param int|null $end The index of the end. On which element to interrupt. If null, the index
     * will be equal to the maximum index of the string.
     * @return static
     * @throws TException
     */
    public function lowerCaseChars(int $start = 0, ?int $end = null): static
    {
        return $this->changeCase($start, $end, true);
    }

    /**
     * @inheritDoc
     */
    protected function validateValue(): void
    {
        $maxLen = &$this->maxLength;

        if ($maxLen < 1) {
            throw new TException(
                'An incorrect maximum allowed string size was passed: the maximum size must be greater than 0.
                Installed size: ' . $maxLen
            );
        }

        if (t_val($this->length()) > $maxLen) {
            throw new TException(
                'An incorrect value is set: the size of the source string is greater
                than the maximum allowed'
            );
        }
    }

    /**
     * Replaces the required substrings with the passed ones.
     *
     * @param TChar|string $search The substring being searched for.
     * @param TChar|string $replace Replace with.
     * @return static Current object.
     * @throws TException It will be thrown out if the final size exceeds the maximum.
     */
    public function replace(TChar|string $search, TChar|string $replace): static
    {
        $value = str_replace(
            (string)$search,
            (string)$replace,
            $this->value,
        );

        if ($this->maxLength < mb_strlen($value)) {
            throw new TException('Error: The value of this instance cannot be more than ' . $this->maxLength . ' characters.');
        }

        $this->value = $value;

        return $this;
    }

    /**
     * Places the substring at the beginning. If the number of characters exceeds
     * the maximum allowed, it will insert only part of the string.
     *
     * @param TCharAbstract|string $value Substring.
     * @return static Updated current object.
     * @throws TException
     */
    public function pushBack(TCharAbstract|string $value): static
    {
        $totalLength = t_val($this->length()) + mb_strlen((string)$value);

        if ($this->maxLength < $totalLength) {
            $diff = $totalLength - $this->maxLength;
            $charLen = mb_strlen((string)$value);
            $value = mb_substr((string)$value, 0, $charLen - $diff);
        }

        $this->value = $value . $this->value;

        return $this;
    }

    /**
     * Places the substring at the end. If the number of characters exceeds
     * the maximum allowed, it will insert only part of the string.
     *
     * @param TCharAbstract|string $value Substring.
     * @return static Updated current object.
     * @throws TException
     */
    public function push(TCharAbstract|string $value): static
    {
        $totalLength = t_val($this->length()) + mb_strlen((string)$value);

        if ($this->maxLength < $totalLength) {
            $diff = $totalLength - $this->maxLength;
            $charLen = mb_strlen((string)$value);
            $value = mb_substr((string)$value, 0, $charLen - $diff);
        }

        $this->value .= $value;

        return $this;
    }

    /**
     * Returns max string size (in symbols).
     *
     * @return int Max string size.
     */
    public function limit(): int
    {
        return $this->maxLength;
    }

    /**
     * @inheritDoc
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        $maxOffset = $this->maxLength - 1;

        if ($maxOffset < $offset) {
            return false;
        }

        return (bool)mb_substr($this->value, $offset, 1);
    }

    /**
     * @inheritDoc
     * @throws TException
     */
    public function offsetGet($offset): ?TChar
    {
        $maxLen = &$this->maxLength;

        if ($maxLen - 1 < $offset) {
            throw new TException('Invalid index: ' . $offset . '. Max index of current string: ' . $maxLen - 1);
        }

        if (!$this->offsetExists($offset)) {
            return null;
        }

        return new TChar(
            mb_substr($this->value, $offset, 1)
        );
    }

    /**
     * @inheritDoc
     * @throws TException
     */
    public function offsetSet($offset, $value): void
    {
        $maxLen = &$this->maxLength;

        if ($maxLen - 1 < $offset) {
            throw new TException('Invalid index: ' . $offset . '. Max index of current string: ' . $maxLen - 1);
        }

        if (!is_string($value)) {
            if ($value instanceof TChar) {
                $value = (string)$value;
            } else {
                throw new TException('Invalid value type given for set: "string" type needs');
            }
        }

        if (($len = mb_strlen($value) + t_val($this->length())) > $this->maxLength) {
            if (!$this->offsetExists($offset)) {
                throw new TException(
                    'Invalid value type given to set:
                The string cannot be larger than the original size (' . $len . ' > ' . $this->maxLength . ')'
                );
            }
        }

        $str = &$this->value;
        $str = mb_substr($str, 0, $offset)
            . mb_substr($value, 0, 1)
            . mb_substr($str, $offset + 1, t_val($this->length()) - ($offset + 1));
    }

    /**
     * @inheritDoc
     * @param $offset
     * @return void
     * @throws TException
     */
    public function offsetUnset($offset): void
    {
        $maxLen = &$this->maxLength;

        if ($maxLen - 1 < $offset) {
            throw new TException('Invalid index: ' . $offset . '. Max index of current string: ' . $maxLen - 1);
        }

        $str = &$this->value;
        $str = mb_substr($str, 0, $offset) . mb_substr($str, $offset + 1, t_val($this->length()) - ($offset + 1));
    }


    public function current(): TChar
    {
        return $this->offsetGet($this->position);
    }

    public function next(): void
    {
        $this->position++;
    }

    public function key(): TInteger
    {
        return new TInteger($this->position);
    }

    public function valid(): bool
    {
        return $this->offsetExists($this->position);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function seek(int $offset): void
    {
        $this->position = $offset;
    }
}