<?php

namespace user;

use bhenk\logger\unit\ConsoleLoggerTrait;
use bhenk\logger\unit\LogAttribute;
use bhenk\msdata\user\PersonDao;
use bhenk\msdata\user\PersonDo;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertTrue;

#[LogAttribute(false)]
class PersonDaoTest extends TestCase {
    use ConsoleLoggerTrait;

    #[LogAttribute(false)]
    public function testInsert() {
        $pdao = new PersonDao();
        $pdao->createTable();

        $pdo = new PersonDo(null, "Karel", "van der",
            "Laan", "kvdl@test.com", 101);
        /** @var PersonDo $p */
        $p = $pdao->insert($pdo);
        assertNotNull($p->getID());
        assertEquals(101, $p->getKnows());
        assertEquals("Karel", $p->getFirstName());
        assertEquals("van der", $p->getPrefixes());
        assertEquals("Laan", $p->getLastName());
        assertEquals("kvdl@test.com", $p->getEmail());
        assertFalse($pdo->isSame($p));
        assertTrue($pdo->equals($p));
    }

    #[LogAttribute(true)]
    public function testUpdate(): void {
        $pdo = new PersonDo(null, "Karel", "van der",
            "Laan", "kvdl@test.com", 101);
        $dao = new PersonDao();
        /** @var PersonDo $per2 */
        $per2 = $dao->insert($pdo);

        $per2->setEmail("kvdl@update.com");
        $per2->setKnows(102);
        $result = $dao->update($per2);

        assertEquals(1, $result);
    }

}
