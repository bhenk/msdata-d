<?php

namespace zzz;

use bhenk\logger\unit\ConsoleLoggerTrait;
use bhenk\logger\unit\LogAttribute;
use bhenk\msdata\abc\Join;
use bhenk\msdata\zzz\JoinDao;
use Exception;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use function array_keys;
use function array_values;
use function count;
use function PHPUnit\Framework\assertEquals;

#[LogAttribute(false)]
class JoinDaoTest extends TestCase {
    use ConsoleLoggerTrait;

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    #[LogAttribute(false)]
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

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function testUpdateLeftJoin() {
        $dao = new JoinDao();
        $dao->createTable(true);

        $join3 = new Join(null, 100, 202);
        $join4 = new Join(null, 100, 203);
        /** @var Join[] $ingested */
        $ingested = array_values($dao->insertBatch([$join3, $join4]));

        $join3 = $ingested[0];
        $join4 = $ingested[1];
        $join3->setDeleted(true);
        $join4->setFkRight(300);
        $join1 = new Join(null, null, 200);
        $join2 = new Join(null, null, 201);
        $updated = $dao->updateLeftJoin(100, [$join1, $join2, $join3, $join4]);
        assertEquals(3, count($updated));
        assertEquals(100, $updated[200]->getFkLeft());
        assertEquals(100, $updated[201]->getFkLeft());
        assertEquals(100, $updated[300]->getFkLeft());
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function testUpdateRightJoin() {
        $dao = new JoinDao();
        $dao->createTable(true);

        $join3 = new Join(null, 101, 200);
        $join4 = new Join(null, 102, 200);
        $join5 = new Join(null, 10, 9);
        /** @var Join[] $ingested */
        $ingested = array_values($dao->insertBatch([$join3, $join4, $join5]));

        $join3 = $ingested[0];
        $join4 = $ingested[1];
        $join3->setDeleted(true);
        $join4->setFkLeft(42);
        $join1 = new Join(null, 103, null);
        $join2 = new Join(null, 104, null);
        $updated = $dao->updateRightJoin(200, [$join1, $join2, $join3, $join4]);
        assertEquals(3, count($updated));
        assertEquals(200, $updated[103]->getFkRight());
        assertEquals(200, $updated[104]->getFkRight());
        assertEquals(200, $updated[42]->getFkRight());
    }

}
