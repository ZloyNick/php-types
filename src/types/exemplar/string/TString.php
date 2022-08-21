<?php

declare(strict_types=1);

namespace ZloyNick\StrictPhp\types\exemplar\string;

use ArrayAccess;
use ZloyNick\StrictPhp\types\exception\TException;
use ZloyNick\StrictPhp\types\exemplar\char\TChar;
use ZloyNick\StrictPhp\types\exemplar\char\TCharAbstract;

class TString extends TCharAbstract implements ArrayAccess
{

    /** @var int Max symbols of string */
    protected int $maxLength;

    public function __construct(float|int|array|string|bool $value, ?int $maxLength = null)
    {
        parent::__construct($value);

        $this->maxLength = $maxLength !== null ? $maxLength : $this->length();
    }

    public function reverse(): static
    {
        $this->value = strrev($this->value);

        return $this;
    }

    public function indexOf(
        TCharAbstract|string $char,
        bool                 $returnAll = false,
        bool                 $registerImportant = false
    ): null|int|array
    {
        $needle = (string)$char;
        $fn = $registerImportant ? 'strpos' : 'stripos';

        if (!$returnAll) {
            return $fn($this->value, $needle) ?: null;
        }

        return $this->searchRecursive($this->value, $char, $registerImportant);
    }

    protected function searchRecursive(
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

            return [...$results, ...$this->searchRecursive($str, $needle, $registerImportant, $index + 1)];
        }
    }

    public function upperCaseChar(int $start = 0, ?int $end = null): static
    {
        try{
            if (!$this->offsetExists($start)) {
                //TODO: Exception
                return $this;
            }
        }catch (TException) {
            return $this;
        }

        if ($end !== null) {
            try{
                if (!$this->offsetExists($end)) {
                    //TODO: Exception
                    return $this;
                }
            }catch (TException) {
                $end = $this->length() - 1;
            }

            $value = &$this->value;

            for (; $start <= $end; $start++) {
                $this->offsetSet($start, mb_strtoupper($value[$start]));
            }

            return $this;
        }

        $this->offsetSet($start, mb_strtoupper($this->value[$start]));

        return $this;
    }

    public function lowerCaseChar(int $start = 0, ?int $end = null): static
    {
        try{
            if (!$this->offsetExists($start)) {
                //TODO: Exception
                return $this;
            }
        }catch (TException) {
            return $this;
        }

        if ($end !== null) {
            try{
                if (!$this->offsetExists($end)) {
                    //TODO: Exception
                    return $this;
                }
            }catch (TException) {
                $end = $this->length() - 1;
            }

            $value = &$this->value;

            for (; $start <= $end; $start++) {
                $this->offsetSet($start, mb_strtolower($value[$start]));
            }

            return $this;
        }

        $this->offsetSet($start, mb_strtolower($this->value[$start]));

        return $this;
    }

    protected function validateValue(): void
    {
        parent::validateValue();
    }

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

    public function pushBack(TChar|string $char): static
    {
        $totalLength = $this->length() + mb_strlen((string)$char);

        if ($this->maxLength < $totalLength) {
            $diff = $totalLength - $this->maxLength;
            $charLen = mb_strlen((string)$char);
            $char = mb_substr((string)$char, 0, $charLen - $diff);
        }

        $this->value = $char . $this->value;

        return $this;
    }

    public function push(TChar|string $char): static
    {
        $totalLength = $this->length() + mb_strlen((string)$char);

        if ($this->maxLength < $totalLength) {
            $diff = $totalLength - $this->maxLength;
            $charLen = mb_strlen((string)$char);
            $char = mb_substr((string)$char, 0, $charLen - $diff);
        }

        $this->value .= $char;

        return $this;
    }

    public function maxCharsCount(): int
    {
        return $this->maxLength;
    }

    public function offsetExists($offset): bool
    {
        $maxLen = &$this->maxLength;

        if ($maxLen - 1 < $offset) {
            throw new TException('Invalid index: ' . $offset . '. Max index of current string: ' . $maxLen - 1);
        }

        return (bool)mb_substr($this->value, $offset, 1);
    }

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

    public function offsetSet($offset, $value)
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

        if (($len = mb_strlen($value) + $this->length()) > $this->maxLength) {
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
            . mb_substr($str, $offset + 1, $this->length() - ($offset + 1));
    }

    public function offsetUnset($offset)
    {
        $maxLen = &$this->maxLength;

        if ($maxLen - 1 < $offset) {
            throw new TException('Invalid index: ' . $offset . '. Max index of current string: ' . $maxLen - 1);
        }

        $str = &$this->value;
        $str = mb_substr($str, 0, $offset) . mb_substr($str, $offset + 1, $this->length() - ($offset + 1));
    }


}