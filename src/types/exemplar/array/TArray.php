<?php

declare(strict_types=1);

namespace ZloyNick\StrictPhp\types\exemplar\array;

use ArrayAccess;
use Closure;
use Countable;
use SeekableIterator;
use ZloyNick\StrictPhp\types\exception\TException;
use ZloyNick\StrictPhp\types\exemplar\char\TCharAbstract;
use ZloyNick\StrictPhp\types\exemplar\number\TInteger;
use ZloyNick\StrictPhp\types\exemplar\number\TNumber;
use ZloyNick\StrictPhp\types\exemplar\TExemplar;

class TArray extends TExemplar implements ArrayAccess, Countable, SeekableIterator
{

    protected int $size;
    protected int $position = 0;

    public const T_ARRAY_ALLOWED_TYPE_STRING = 'string';
    public const T_ARRAY_ALLOWED_TYPE_ARRAY = 'array';
    public const T_ARRAY_ALLOWED_TYPE_INTEGER = 'integer';
    public const T_ARRAY_ALLOWED_TYPE_FLOAT = 'double';
    public const T_ARRAY_ALLOWED_TYPE_BOOL = 'boolean';

    protected const T_ARRAY_ALLOWED_TYPES = [
        self::T_ARRAY_ALLOWED_TYPE_ARRAY,
        self::T_ARRAY_ALLOWED_TYPE_FLOAT,
        self::T_ARRAY_ALLOWED_TYPE_INTEGER,
        self::T_ARRAY_ALLOWED_TYPE_STRING,
        self::T_ARRAY_ALLOWED_TYPE_BOOL,
    ];

    public function __construct(float|int|array|string|bool $value, private readonly ?string $class = null)
    {
        parent::__construct($value);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->value[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        if ($this->size - 1 < $offset) {
            throw new TException('Invalid index: ' . $offset . '. Max index of current array: ' . $this->size - 1);
        }

        return $this->value[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $class = $this->class;

        if ($offset !== null) {
            if ($this->size < $this->count() + 1) {
                throw new TException('Invalid index: ' . $offset . '. Max index of current array: ' . $this->size - 1);
            }
        }

        if ($class !== null) {
            if (
                ($ce = class_exists($class)) && (!is_object($value) || get_class($value) !== $class)
                || !$ce && gettype($value) !== $class
            ) {
                throw new TException('Attempt to add an object of another class to an array of ' . $class);
            }
        }

        if ($offset !== null) {
            $this->value[$offset] = $value;
        } else {
            $this->value[] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        if ($this->size - 1 < $offset) {
            throw new TException('Invalid index: ' . $offset . '. Max index of current array: ' . $this->size - 1);
        }

        unset($this->value[$offset]);
    }

    protected function validateValue(): void
    {
        if (is_int($this->value)) {
            if ($this->value < 0) {
                throw new TException('Size of array type must be >= 0');
            }

            $this->size = $this->value;
            $this->value = [];

            return;
        }

        if (!is_array($this->value)) {
            throw new TException('Value of ' . __CLASS__ . ' must be an array');
        }

        $this->size = $this->count();
        $class = $this->class;

        if ($class !== null) {
            if (
                ($isObjectAllowed = class_exists($class))
                || !in_array($class, self::T_ARRAY_ALLOWED_TYPES)
            ) {
                throw new TException('Property ' . get_class($this) . '::$class must be instance of ' . TExemplar::class);
            }

            foreach ($this->value as $index => $value) {
                if (
                    $isObjectAllowed && (!is_object($value) || get_class($value) !== $class)
                    || gettype($value) === $class
                ) {
                    throw new TException('Invalid object type given at index ' . $index);
                }
            }
        }
    }

    public function size(): int
    {
        return $this->size;
    }

    public function __toArray(): array
    {
        return $this->value;
    }

    public function reverse(bool $preserveKeys = false): self
    {
        $this->value = array_reverse($this->value, $preserveKeys);

        return $this;
    }

    public function keys(): TArray
    {
        $keys = array_keys($this->value);
        $tKeys = [];

        foreach ($keys as $key) {
            $tKeys[] = new TInteger($key);
        }

        return new TArray($tKeys);
    }

    public function count(): int
    {
        return count($this->value);
    }

    public function first(): TExemplar|string|float|int|array|null
    {
        return $this->offsetExists(0) ? $this->offsetGet(0) : null;
    }

    public function last(): TExemplar|string|float|int|array|null
    {
        return $this->offsetExists($this->count() - 1) ? $this->offsetGet($this->count() - 1) : null;
    }

    public function current(): mixed
    {
        return $this->value[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->value[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function seek(int $offset): void
    {
        $this->position = $offset;
    }

    public function filter(Closure $fn): static
    {
        foreach ($this as $key => $value) {
            $result = $fn($key, $value);

            if (!is_bool($result)) {
                throw new TException(__CLASS__ . '::filter(): function must return bool value');
            }

            if (!$result) {
                $this->offsetUnset($key);
            }
        }

        return $this;
    }

    public function find(TNumber|TCharAbstract|string|int|float|array $value): ?TInteger
    {
        if ($value instanceof TExemplar) {
            $value = $value->getValue();
        }

        if ($this->class !== null && !in_array($this->class, self::T_ARRAY_ALLOWED_TYPES)) {
            foreach ($this as $key => $tValue) {
                if ($tValue->getValue() === $value) {
                    return new TInteger($key);
                }
            }

            return null;
        }

        $index = array_search($value, $this->value);

        if ($index !== false) {
            return new TInteger($index);
        }

        return null;
    }
}