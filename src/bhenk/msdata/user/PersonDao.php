<?php

namespace bhenk\msdata\user;

use bhenk\msdata\abc\AbstractDao;

class PersonDao extends AbstractDao {

    public function getDataObjectName(): string {
        return PersonDo::class;
    }

    public function getTableName(): string {
        return "tbl_persons";
    }
}