<?php

namespace node;

use bhenk\logger\unit\ConsoleLoggerTrait;
use bhenk\logger\unit\LogAttribute;
use bhenk\msdata\node\NodeDao;
use bhenk\msdata\node\NodeDo;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

#[LogAttribute(false)]
class NodeDaoTest extends TestCase {
    use ConsoleLoggerTrait;

    public function testSelectChildren() {
        $dao = new NodeDao();
        $dao->createTable(true);

        $parent = new NodeDo(null);
        $parent->setName("parent");
        $parent = $dao->insert($parent);

        $batch = [
            new NodeDo(null, $parent->getID(), "child 1"),
            new NodeDo(null, $parent->getID(), "child 2"),
            new NodeDo(null, $parent->getID(), "child 3"),
        ];
        $dao->insertBatch($batch);

        $select = $dao->selectChildren($parent->getID());
        assertEquals(3, count($select));
    }

    public function testSelectGenerationNumbers() {
        $dao = new NodeDao();
        $dao->createTable(true);

        $parent = new NodeDo(null);
        $parent->setName("parent");
        $parent = $dao->insert($parent);

        $batch = [
            new NodeDo(null, $parent->getID(), "child 1"),
            new NodeDo(null, $parent->getID(), "child 2"),
            new NodeDo(null, $parent->getID(), "child 3"),
        ];
        $dao->insertBatch($batch);

        $expected = [1 => 0, 2 => 1, 3 => 1, 4 => 1];
        $selected = $dao->selectGenerationNumbers();
        assertEquals($expected, $selected);
    }

}
