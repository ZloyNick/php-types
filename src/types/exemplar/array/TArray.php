<?php

declare(strict_types=1);

namespace ZloyNick\StrictPhp\types\exemplar\array;

use ArrayAccess;
use Closure;
use Countable;
use SeekableIterator;
use ZloyNick\StrictPhp\types\exception\TException;
use ZloyNick\StrictPhp\types\exemplar\boolean\TBoolean;
use ZloyNick\StrictPhp\types\exemplar\char\TCharAbstract;
use ZloyNick\StrictPhp\types\exemplar\number\TInteger;
use ZloyNick\StrictPhp\types\exemplar\number\TNumber;
use ZloyNick\StrictPhp\types\exemplar\TExemplar;
use function ZloyNick\StrictPhp\Boolean\bool_cmp;
use function ZloyNick\StrictPhp\Boolean\bool_or;

/**
 * Array implementation object.
 */
class TArray extends TExemplar implements ArrayAccess, Countable, SeekableIterator
{

    /** @var int Maximum number of elements */
    protected int $size;
    /** @var int Using for iteration */
    protected int $position = 0;

    // Allowed php types to check values if it has been fixed.
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

    /**
     * @param TArray|TNumber|int|array $value
     * @param string|null $class
     * @throws TException
     */
    public function __construct(TArray|TNumber|int|array $value, private readonly ?string $class = null)
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

    /**
     * @inheritDoc
     * @return void
     * @throws TException
     */
    protected function validateValue(): void
    {
        $value = &$this->value;
        $value = t_val($this->value);

        if (is_int($value)) {
            if ($value < 0) {
                throw new TException('Size of array type must be >= 0');
            }

            [$this->size, $value] = [$value, []];

            return;
        }

        if (!is_array($value)) {
            throw new TException('Value of ' . __CLASS__ . ' must be an array');
        }

        $this->size = $this->count();
        $class = $this->class;

        if ($class !== null) {
            if (
                !($isObjectAllowed = class_exists($class))
                && !in_array($class, self::T_ARRAY_ALLOWED_TYPES)
            ) {
                throw new TException('Property ' . get_class($this) . '::$class must be instance of ' . TExemplar::class);
            }

            foreach ($value as $index => $item) {
                if (
                    $isObjectAllowed && (!is_object($item) || get_class($item) !== $class)
                    || gettype($item) !== $class
                ) {
                    throw new TException('Invalid object type given at index ' . $index);
                }
            }
        }
    }

    /**
     * Returns maximum number of elements.
     *
     * @return TInteger
     * @throws TException
     */
    public function size(): TInteger
    {
        return new TInteger($this->size);
    }

    /**
     * Return an array with elements in reverse order.
     *
     * @param TBoolean|bool $preserveKeys
     * @return static
     */
    public function reverse(TBoolean|bool $preserveKeys = false): static
    {
        $this->value = array_reverse($this->value, t_val($preserveKeys));

        return $this;
    }

    /**
     * Return all the keys or a subset of the keys of an array.
     *
     * @return TArray<TInteger>
     * @throws TException
     */
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

    /**
     * Returns the first element of the array
     *
     * @return object|string|float|int|array|null
     * @throws TException
     */
    public function first(): string|float|int|array|null|object
    {
        return $this->offsetExists(0) ? $this->offsetGet(0) : null;
    }

    /**
     * Returns the last element of the array.
     *
     * @return object|string|float|int|array|null
     * @throws TException
     */
    public function last(): object|string|float|int|array|null
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

    /**
     * Iterates over each value in the array passing them to the callback function. If the callback function returns true,
     * the current value from array is returned into the result array. Array keys are preserved.
     *
     * @param Closure|null $fn
     * @param TInteger|int $mode
     * @return static
     * @throws TException
     * @see array_filter()
     */
    public function filter(?Closure $fn = null, TInteger|int $mode = 0): static
    {
        return new TArray(array_filter($this->value, $fn, $mode));
    }

    /**
     * Searches the array for a given value and returns the first corresponding key if successful.
     *
     * @param TNumber|TCharAbstract|string|int|float|array $value
     * @return TInteger|null
     * @throws TException
     */
    public function find(TNumber|TCharAbstract|string|int|float|array $value): ?TInteger
    {
        $value = t_val($value);

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