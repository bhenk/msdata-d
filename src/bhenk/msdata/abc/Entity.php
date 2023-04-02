<?php

namespace bhenk\msdata\abc;

use ReflectionClass;
use function array_reverse;
use function array_slice;
use function array_values;
use function get_class;

class Entity implements EntityInterface {

    function __construct(private readonly ?int $ID) {}

    public function getID(): ?int {
        return $this->ID;
    }

    public static function fromArray(array $arr): static {
        $rc = new ReflectionClass(static::class);
        return $rc->newInstanceArgs(array_values($arr));
    }

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

    public function clone(?int $ID): Entity {
        $arr = $this->toArray();
        $arr["ID"] = $ID;
        return static::fromArray($arr);
    }

    public function equals(Entity $other): bool {
        return get_class($this) === get_class($other) and
            array_slice($this->toArray(), 1) === array_slice($other->toArray(), 1);
    }

    public function isSame(Entity $other): bool {
        return $this->equals($other) and
            $this->getID() === $other->getID();
    }

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
     * @return array
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