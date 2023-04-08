<?php

namespace bhenk\msdata\abc;

/**
 * A basic Entity for a join function
 *
 * Join can be used when expressing a many-to-many relation
 */
class Join extends Entity {

    /**
     * Constructs a new Join
     *
     * @param int|null $ID ID of this Join
     * @param int|null $FK_LEFT the left hand foreign key
     * @param int|null $FK_RIGHT the right hand foreign key
     */
    function __construct(
        ?int         $ID = null,
        private ?int $FK_LEFT = null,
        private ?int $FK_RIGHT = null
    ) {
        parent::__construct($ID);
    }

    /**
     * Get the left hand foreign key
     *
     * @return int|null
     */
    public function getFkLeft(): ?int {
        return $this->FK_LEFT;
    }

    /**
     * Set the left hand foreign key
     *
     * @param int|null $FK_LEFT
     */
    public function setFkLeft(?int $FK_LEFT): void {
        $this->FK_LEFT = $FK_LEFT;
    }

    /**
     * Get the right hand foreign key
     *
     * @return int|null
     */
    public function getFkRight(): ?int {
        return $this->FK_RIGHT;
    }

    /**
     * Set the right hand foreign key
     *
     * @param int|null $FK_RIGHT
     */
    public function setFkRight(?int $FK_RIGHT): void {
        $this->FK_RIGHT = $FK_RIGHT;
    }

}