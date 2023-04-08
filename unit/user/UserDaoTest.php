<?php

namespace user;

use bhenk\logger\unit\ConsoleLoggerTrait;
use bhenk\logger\unit\LogAttribute;
use bhenk\msdata\user\UserDao;
use bhenk\msdata\user\UserDo;
use PHPUnit\Framework\TestCase;
use function array_values;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertGreaterThanOrEqual;
use function PHPUnit\Framework\assertStringStartsWith;

#[LogAttribute(false)]
class UserDaoTest extends TestCase {
    use ConsoleLoggerTrait;

    #[LogAttribute(true)]
    public function testCreateTableStatement() {
        $dao = new UserDao();
        $sql = $dao->getCreateTableStatement();
        assertStringStartsWith("CREATE", $sql);
    }

    public function testDropCreateTable() {
        $dao = new UserDao();
        $r = $dao->createTable(true);
        assertGreaterThanOrEqual(1, $r);
    }

    public function testInsertBatch() {
        $batch = [
            new UserDo(null, "Bob", null, "Dylan", "bob@email.com"),
            new UserDo(null, "Elvis", null, "Presley", "elvis@email.com"),
        ];
        $dao = new UserDao();
        $batch2 = $dao->insertBatch($batch);
        $b_vals = array_values($batch2);
        assertEquals("Bob", $b_vals[0]->getFirstName());
        assertEquals("Presley", $b_vals[1]->getLastName());
    }

}
