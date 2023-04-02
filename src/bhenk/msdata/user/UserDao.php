<?php

namespace bhenk\msdata\user;

use bhenk\msdata\abc\AbstractDao;

class UserDao extends AbstractDao {

    public function getDataObjectName(): string {
        return UserDo::class;
    }

    public function getTableName(): string {
        return "tbl_users";
    }
}