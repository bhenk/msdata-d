<?php

namespace bhenk\msdata\zzz;

use bhenk\msdata\abc\AbstractJoinDao;
use bhenk\msdata\abc\Join;

class JoinDao extends AbstractJoinDao {

    /**
     * @inheritDoc
     */
    public function getDataObjectName(): string {
        return Join::class;
    }

    /**
     * @inheritDoc
     */
    public function getTableName(): string {
        return "tbl_join_test";
    }
}