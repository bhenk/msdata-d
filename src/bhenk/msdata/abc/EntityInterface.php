<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace bhenk\msdata\abc;

use Stringable;

/**
 * Definition of a basic data object
 */
interface EntityInterface extends Stringable {

    /**
     * Create a new Entity from an array of properties
     *
     * The given array should have the same order as the one gotten from {@link EntityInterface::toArray()}.
     *
     * @param array $arr property array
     * @return Entity newly created Entity with the given properties
     */
    public static function fromArray(array $arr): Entity;

    /**
     * Get the ID of this Entity or *null* if it has no ID
     *
     * @return int|null ID of this Entity or *null*
     */
    public function getID(): ?int;

    /**
     * Express the properties of this Entity in an array
     *
     * The returned array should be in such order that it can be fet to the static method
     * {@link EntityInterface::fromArray()}.
     * @return array array with properties of this Entity
     */
    public function toArray(): array;

    /**
     * Create an Entity that equals this Entity
     *
     * The newly created Entity gets the given ID or no ID if {@link $ID} is *null*.
     * @param int|null $ID
     * @return Entity
     */
    public function clone(?int $ID = null): Entity;

    /**
     * Test equals function
     *
     * The given Entity equals this Entity if all properties, except :tech:`ID`, are equal.
     * @param Entity $other Entity to test
     * @return bool *true* if all properties are equal, *false* otherwise
     */
    public function equals(Entity $other): bool;

    /**
     * Test is same function
     *
     * The given Entity is similar to this Entity if all properties, including :tech:`ID`, are equal.
     * @param Entity $other Entity to test
     * @return bool *true* if all properties, including :tech:`ID`, are equal, *false* otherwise
     */
    public function isSame(Entity $other): bool;

}