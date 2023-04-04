<?php

namespace bhenk\msdata\abc;

use ReflectionClass;
use ReflectionException;
use function array_reverse;
use function array_slice;
use function array_values;
use function get_class;

/**
 * Basic implementation of a data object
 */
class Entity implements EntityInterface {

    /**
     * Construct a new Entity
     *
     * @param int|null $ID the ID of the newly created Entity or *null* if it has no ID
     */
    function __construct(private readonly ?int $ID = null) {}

    /**
     * @inheritdoc
     * @return int|null
     */
    public function getID(): ?int {
        return $this->ID;
    }

    /**
     * Create a new Entity
     *
     * The order of the given array should be *parent-first*, i.e.:
     * ```
     * class A extends Entity
     *
     * class B extends A
     * ```
     * In :tech:`__construct()`, :tech:`toArray()` and :tech:`fromArray()` functions,
     * properties/parameters have the order:
     * ```
     * ID, {props of A}, {props of B}
     * ```
     * @inheritdoc
     * @param array $arr array with properties
     * @return static Entity object
     * @throws ReflectionException
     */
    public static function fromArray(array $arr): static {
        $rc = new ReflectionClass(static::class);
        return $rc->newInstanceArgs(array_values($arr));
    }

    /**
     * @inheritdoc
     * @return array array with properties
     * @see Entity::fromArray()
     */
    public function toArray(): array {
        $arr = [];
        foreach ($this->getParents() as $parent) {
            foreach ($parent->getProperties() as $prop) {
                $val = $prop->getValue($this);
                if ($prop->getType()->getName() == "bool") {
                    $val = $val ? 1 : 0;
                }
                $arr[$prop->getName()] = $val;
            }
        }
        return $arr;
    }

    /**
     * @inheritdoc
     * @param int|null $ID
     * @return Entity Entity, similar to this one, with the given ID
     * @throws ReflectionException
     */
    public function clone(?int $ID = null): Entity {
        $arr = $this->toArray();
        $arr["ID"] = $ID;
        return static::fromArray($arr);
    }

    /**
     * @inheritdoc
     * @param Entity $other
     * @return bool
     */
    public function equals(Entity $other): bool {
        return get_class($this) === get_class($other) and
            array_slice($this->toArray(), 1) === array_slice($other->toArray(), 1);
    }

    /**
     * @inheritdoc
     * @param Entity $other
     * @return bool
     */
    public function isSame(Entity $other): bool {
        return $this->equals($other) and
            $this->getID() === $other->getID();
    }

    /**
     * String representation of this Entity
     * @return string representing this Entity
     */
    public function __toString(): string {
        $s = get_class($this);
        $s .= PHP_EOL;
        $s .= "\tID (int) -> " . $this->ID . PHP_EOL;
        foreach ($this->getParents() as $parent) {
            foreach ($parent->getProperties() as $prop) {
                if ($prop->getName() != "ID") {
                    $val = $prop->getValue($this);
                    if ($prop->getType()->getName() == "string")
                        $val = "'" . $val . "'";
                    $s .= "\t" . $prop->getName() . " ("
                        . $prop->getType()->getName() . ") -> " . $val . PHP_EOL;
                }
            }
        }
        return $s;
    }

    /**
     * Get the (Reflection) parents of this Entity in reverse order
     *
     * ```
     * class A extends Entity
     *
     * class B extends A
     *
     * returned array = [Entity-Reflection, A-Reflection, B-Reflection]
     * ```
     *
     * @return array array with {@link ReflectionClass} parents and this Entity
     */
    public function getParents(): array {
        $parents = [];
        $rc = new ReflectionClass($this);
        do {
            $parents[] = $rc;
            $rc = $rc->getParentClass();
        } while ($rc);
        return array_reverse($parents);
    }

}