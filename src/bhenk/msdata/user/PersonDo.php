<?php

namespace bhenk\msdata\user;

class PersonDo extends UserDo {

    public function __construct(
        ?int         $ID = null,
        ?string      $first_name = null,
        ?string      $prefixes = null,
        ?string      $last_name = null,
        ?string      $email = null,
        private ?int $knows = null
    ) {
        parent::__construct($ID, $first_name, $prefixes, $last_name, $email);
    }

    /**
     * @return int|null
     */
    public function getKnows(): ?int {
        return $this->knows;
    }

    /**
     * @param int|null $knows
     */
    public function setKnows(?int $knows): void {
        $this->knows = $knows;
    }

}