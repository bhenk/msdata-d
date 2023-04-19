<?php

namespace node;

use bhenk\logger\log\Log;
use bhenk\logger\unit\ConsoleLoggerTrait;
use bhenk\logger\unit\LogAttribute;
use bhenk\msdata\abc\Entity;
use bhenk\msdata\node\NodeDao;
use bhenk\msdata\node\NodeDo;
use Exception;
use PHPUnit\Framework\TestCase;
use function array_values;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertStringStartsWith;
use function PHPUnit\Framework\assertTrue;

#[LogAttribute(false)]
class AbstractDaoTest extends TestCase {
    use ConsoleLoggerTrait;

    #[LogAttribute(false)]
    public function testCreateTableStatement() {
        $dao = new NodeDao();
        $sql = $dao->getCreateTableStatement();
        Log::debug("Auto create table statement:", [$sql]);
        assertStringStartsWith(/** @lang text */ "CREATE TABLE IF NOT EXISTS `tbl_node`", $sql);
    }

    #[LogAttribute(false)]
    public function testCreateTableDrop(): void {
        $dao = new NodeDao();
        $result = $dao->createTable(true);
        assertEquals(2, $result);

        assertTrue($dao->dropTable());
        assertTrue($dao->dropTable());
        $dao->createTable();
    }

    #[LogAttribute(false)]
    public function testCreateTable(): void {
        $dao = new NodeDao();
        $result = $dao->createTable(false);
        assertEquals(1, $result);
    }

    #[LogAttribute(false)]
    public function testInsert(): void {
        $node = new NodeDo(null, null, "what sunday", "by alias", "zondag", false);
        $dao = new NodeDao();
        /** @var NodeDo $newNode */
        $newNode = $dao->insert($node);
        assertInstanceOf(NodeDo::class, $newNode);
        assertTrue($node->equals($newNode));
        assertFalse($node->isSame($newNode));
    }

    #[LogAttribute(false)]
    public function testInsertBatch() {
        $batch = [
            new NodeDo(null, null, "name 1", "alias 1", "nature 1", false),
            new NodeDo(null, 1, "name 2", "alias 2", "nature 2", true),
            new NodeDo(null, 2, "name 3", "alias 3", "nature 3", false),
        ];
        $dao = new NodeDao();
        $batch2 = $dao->insertBatch($batch);
        assertEquals(3, count($batch2));
        $t = 0;
        /** @var NodeDo $node */
        foreach ($batch2 as $node) {
            assertFalse($node->isSame($batch[$t]));
            assertTrue($node->equals($batch[$t++]));
        }
    }

    #[LogAttribute(false)]
    public function testInsertBatchWithError() {
        $batch = [
            new NodeDo(null, null, "name 1", "alias 1", "nature 1", false),
            new NodeDao(),
            new NodeDo(null, 2, "name 3", "alias 3", "nature 3", false),
        ];
        $dao = new NodeDao();
        $thrown = false;
        try {
            $dao->insertBatch($batch);
        } catch (Exception $e) {
            Log::debug("test exception: ", [$e]);
            $thrown = true;
        }
        assertTrue($thrown);
    }

    #[LogAttribute(false)]
    public function testInsertBatchWithError2() {
        $batch = [
            new NodeDo(null, null, "name 1", "alias 1", "nature 1", false),
            new Entity(12),
            new NodeDo(null, 2, "name 3", "alias 3", "nature 3", false),
        ];
        $dao = new NodeDao();
        $thrown = false;
        try {
            $dao->insertBatch($batch);
        } catch (Exception $e) {
            Log::debug("test exception: ", [$e]);
            $thrown = true;
        }
        assertTrue($thrown);
    }

    #[LogAttribute(false)]
    public function testUpdate(): void {
        $node = new NodeDo(null, 12, "node name", "node alias", "node nature");
        $dao = new NodeDao();
        /** @var NodeDo $node2 */
        $node2 = $dao->insert($node);

        $node2->setParentId(42);
        $node2->setNature(null);
        $result = $dao->update($node2);

        assertEquals(1, $result);
    }

    #[LogAttribute(false)]
    public function testUpdateBatch() {
        $batch = [
            new NodeDo(null, null, "name 1", "alias 1", "nature 1", false),
            new NodeDo(null, 1, "name 2", "alias 2", "nature 2", true),
            new NodeDo(null, 2, "name 3", "alias 3", "nature 3", false),
        ];
        $dao = new NodeDao();
        $batch2 = $dao->insertBatch($batch);

        $b_vals = array_values($batch2);
        $b_vals[0]->setName("altered name 1");
        $b_vals[1]->setAlias("altered alias 2");
        $b_vals[2]->setPublic(true);

        $result = $dao->updateBatch($batch2);
        assertEquals(3, $result);
    }

    #[LogAttribute(false)]
    public function testDeleteBatch() {
        $batch = [
            new NodeDo(null, null, "name 1", "alias 1", "nature 1", false),
            new NodeDo(null, 1, "name 2", "alias 2", "nature 2", true),
            new NodeDo(null, 2, "name 3", "alias 3", "nature 3", false),
        ];
        $dao = new NodeDao();
        $batch2 = $dao->insertBatch($batch);

        $b_vals = array_values($batch2);
        $delete_batch = [$b_vals[1]->getID(), $b_vals[2]->getID()];
        $num_rows = $dao->deleteBatch($delete_batch);
        assertEquals(2, $num_rows);
    }

    #[LogAttribute(false)]
    public function testDeleteNothing() {
        $dao = new NodeDao();
        $node = $dao->insert(new NodeDo(null));
        $ID = $node->getID();
        $rows = $dao->delete($ID);
        assertEquals(1, $rows);

        $rows = $dao->delete($ID);
        assertEquals(0, $rows);
    }

    #[LogAttribute(false)]
    public function testDeleteWhere() {
        $batch = [
            new NodeDo(null, null, "xyz", "alias 1", "nature 1", false),
            new NodeDo(null, 1, "name 2", "alias 2", "nature 2", true),
            new NodeDo(null, 2, "xyz", "alias 3", "nature 3", false),
        ];
        $dao = new NodeDao();
        $dao->insertBatch($batch);

        $where_clause = "name='xyz'";
        $row_count = $dao->deleteWhere($where_clause);
        assertTrue($row_count >= 2);
    }

    #[LogAttribute(false)]
    public function testSelectBatch() {
        $dao = new NodeDao();
        $node = $dao->insert(new NodeDo(null));
        $ID = $node->getID();

        $selected = $dao->selectBatch([$ID, 1, 999999999, 2]);
        assertTrue(count($selected) >= 1);
        $found = false;
        /** @var $n NodeDo */
        foreach ($selected as $n) {
            if ($n->getID() == $ID) {
                $found = true;
            }
        }
        assertTrue($found);
    }

    #[LogAttribute(false)]
    public function testSelect() {
        $dao = new NodeDao();
        $node = $dao->insert(new NodeDo(null));
        $ID = $node->getID();

        $node2 = $dao->select($ID);
        assertNotNull($node2);
        assertEquals($ID, $node2->getID());

        $node3 = $dao->select(999999999);
        assertNull($node3);
    }

    #[LogAttribute(false)]
    public function testSelectWhere() {
        $batch = [
            new NodeDo(null, null, "xyz", "alias 1", "nature 1", false),
            new NodeDo(null, 1, "name 2", "alias 2", "nature 2", true),
            new NodeDo(null, 2, "xyz", "alias 3", "nature 3", false),
        ];
        $dao = new NodeDao();
        $dao->insertBatch($batch);

        $where_clause = "name='xyz'";
        $selected = $dao->selectWhere($where_clause);
        assertTrue(count($selected) >= 2);
        assertInstanceOf(NodeDo::class, array_values($selected)[0]);
    }

    #[LogAttribute(false)]
    public function testInsertWithID() {
        $dao = new NodeDao();
        $dao->createTable(true);
        $batch = [
            new NodeDo(null, null, "xyz", "alias 1", "nature 1", false),
            new NodeDo(null, 1, "name 2", "alias 2", "nature 2", true),
            new NodeDo(null, 2, "xyz", "alias 3", "nature 3", false),
        ];

        $dao->insertBatch($batch);

        $node = new NodeDo(42, 2, "the answer", "to all questions");
        $inserted = $dao->insert($node, true);
        assertEquals(42, $inserted->getID());

        $node = new NodeDo(null, 2, "another", "node");
        $inserted = $dao->insert($node);
        assertEquals(43, $inserted->getID());
    }

}
