<?php

namespace zzz;

use bhenk\msdata\abc\Join;
use bhenk\msdata\zzz\JoinDao;
use PHPUnit\Framework\TestCase;
use function array_keys;
use function PHPUnit\Framework\assertEquals;

class JoinDaoTest extends TestCase {

    public function testSelectLeftAndRight() {
        $dao = new JoinDao();
        $dao->createTable(true);
        $join1 = new Join(null, 100, 200);
        $join2 = new Join(null, 100, 201);
        $join3 = new Join(null, 100, 242);
        $join4 = new Join(null, 101, 242);
        $dao->insertBatch([$join1, $join2, $join3, $join4]);

        $joins = $dao->selectLeft(100);
        assertEquals([200, 201, 242], array_keys($joins));

        $joins = $dao->selectRight(242);
        assertEquals([100, 101], array_keys($joins));
    }

}
