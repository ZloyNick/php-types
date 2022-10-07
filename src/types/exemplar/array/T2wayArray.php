<?php

namespace ZloyNick\StrictPhp\types\exemplar\array;

use ZloyNick\StrictPhp\types\exception\TException;
use ZloyNick\StrictPhp\types\exemplar\char\TCharAbstract;
use ZloyNick\StrictPhp\types\exemplar\number\TInteger;
use ZloyNick\StrictPhp\types\exemplar\string\TString;
use ZloyNick\StrictPhp\types\exemplar\TExemplar;


/** @property array<TExemplar|string|int, TExemplar|string|int|array|float> $value */
class T2wayArray extends TArray
{

    public function __construct(float|array|int|string $valueOrSize, private readonly ?string $keyClass = null, private readonly ?string $valueClass = null)
    {
        parent::__construct($valueOrSize);
    }

    public function validateValue(): void
    {
        parent::validateValue();

        $keyClass = $this->keyClass;
        $valueClass = $this->valueClass;

        if ($keyClass !== null || $valueClass !== null) {

            if (
                $keyClass !== null && (
                    ($ce = class_exists($keyClass)) && !is_subclass_of($keyClass, TExemplar::class)
                    || !$ce && !in_array($keyClass, self::T_ARRAY_ALLOWED_TYPES)
                )
            ) {
                throw new TException('Property ' . __CLASS__ . '::$keyClass must be instance of ' . TExemplar::class);
            }

            if (
                $valueClass !== null && (
                    !class_exists($valueClass)
                    && !in_array($valueClass, self::T_ARRAY_ALLOWED_TYPES)
                )
            ) {
                throw new TException('Property ' . __CLASS__ . '::$valueClass must be instance of ' . TExemplar::class);
            }

            foreach ($this->value as $index => $value) {
                if (count($value) < 2) {
                    throw new TException('Invalid object given at index ' . $index . '. Every entry must looks like [' . $keyClass . ', ' . $valueClass . ']');
                }

                [$key, $value] = $value;

                if ($keyClass !== null) {
                    if (
                        is_object($key) && $keyClass !== get_class($key)
                        || gettype($key) !== $keyClass
                    ) {
                        throw new TException('Invalid key given at index ' . $index);
                    }
                }

                if ($valueClass !== null) {
                    if (
                        is_object($value) && $valueClass !== get_class($value)
                        || gettype($value) !== $valueClass
                    ) {
                        throw new TException('Invalid value given at index ' . $index);
                    }
                }
            }
        }
    }

    /**
     * @param TCharAbstract|TInteger|string|int $index
     * @return int|null
     */
    private function deepFind(TCharAbstract|TInteger|string|int $index): ?int
    {
        $searchIndex = $index instanceof TExemplar ? $index->getValue() : $index;

        foreach ($this->value as $nativeIndex => $value) {
            [$key] = $value;

            if ($searchIndex === (is_object($key) ? $key->getValue() : $key)) {
                return $nativeIndex;
            }
        }

        return null;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->deepFind($offset) !== null;
    }

    /**
     * @param TCharAbstract|TString|TInteger|int|string $offset
     * @return TCharAbstract|TString|TArray|T2wayArray|TInteger|int|float|string|array
     */
    public function offsetGet(mixed $offset): mixed
    {
        $nativeOffset = $this->deepFind($offset);

        if ($nativeOffset !== null) {
            return $this->value[$nativeOffset][1];
        }

        return null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $keyClass = $this->keyClass;
        $valueClass = $this->valueClass;

        if ($keyClass !== null) {
            if (
                (($isObj = is_object($offset)) && (get_class($offset)) !== $keyClass)
                || !$isObj && gettype($offset) !== $keyClass
            ) {
                if (
                    !$isObj && (
                        is_subclass_of($keyClass, TCharAbstract::class) && is_string($offset)
                        || is_subclass_of($keyClass, TInteger::class) && is_int($offset)
                    )
                ) {
                    $offset = new $keyClass($offset);
                } else {
                    throw new TException('Invalid key type given.');
                }
            }
        }

        if ($valueClass !== null) {
            if (
                (($isObj = is_object($value)) && get_class($value) !== $valueClass)
                || !$isObj && gettype($value) !== $valueClass
            ) {
                throw new TException('Invalid value type given.');
            }
        }

        if ($this->size < $this->count() + 1 && $this->deepFind($offset) === null) {
            throw new TException('It is impossible to allocate memory for a new object: the limit has been reached');
        }

        $this->value[] = [$offset, $value];
    }

    public function offsetUnset(mixed $offset): void
    {
        $index = $this->deepFind($offset);

        if ($index !== null) {
            unset($this->value[$index]);
        }
    }

    public function current(): mixed
    {
        return $this->value[$this->position][1];
    }

    public function key(): mixed
    {
        return $this->value[$this->position][0];
    }

    public function keys(): TArray
    {
        $keys = new TArray($this->count());

        foreach ($this->value as [$tKey]) {
            $keys[] = $tKey;
        }

        return $keys;
    }

}