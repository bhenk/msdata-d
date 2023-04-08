<?php

namespace bhenk\msdata\abc;

use bhenk\logger\log\Log;
use bhenk\msdata\connector\MysqlConnector;
use Exception;
use ReflectionClass;
use ReflectionException;
use Throwable;
use function array_reverse;
use function array_slice;
use function array_values;
use function count;
use function is_null;
use function mysqli_report;
use function rtrim;
use function str_repeat;

/**
 * Data Access Object with basic functionality
 *
 * In most cases subclasses only need to implement {@link AbstractDao::getDataObjectName() getDataObjectName} and
 * {@link AbstractDao::getTableName() getTableName}. If
 * {@link AbstractDao::getCreateTableStatement() getCreateTableStatement} is not sufficient
 * override that method as well.
 *
 * This class expects {@link EntityInterface Entities} that subclass other Entity implementations to have
 * parent-first in their :tech:`__construct()` and {@link EntityInterface::toArray() toArray()} functions, i.e.:
 * ```
 * class A extends Entity
 *
 * class B extends A
 * ```
 * In their :tech:`__construct()` and :tech:`toArray()` functions, properties/parameters have the order:
 * ```
 * ID, {props of A}, {props of B}
 * ```
 */
abstract class AbstractDao {

    /**
     * Get the fully qualified classname of the {@link Entity} this class provides access to
     *
     * @return string fully qualified classname
     */
    public abstract function getDataObjectName(): string;

    /**
     * Get the name of the table that will store the {@link Entity Entities} this class provides access to
     *
     * @return string name of table reserved for DO
     */
    public abstract function getTableName(): string;

    /**
     * Produces a minimal *CreateTableStatement*.
     *
     * ```
     * CREATE TABLE IF NOT EXISTS `%table_name%`
     * (
     *      `ID`                INT NOT NULL AUTO_INCREMENT,
     *      `%int_prop%`        INT,
     *      `%string_prop%`     VARCHAR(255),
     *      `%bool_prop%`       BOOLEAN,
     *      `%float_prop%`      FLOAT,
     *      PRIMARY KEY (`ID`)
     * );
     * ```
     * In the above :tech:`%xyz%` is placeholder for table name or property name. Notice that string type
     * parameters have a limited length of 255 characters.
     *
     * Subclasses may override. The table MUST have the same name as the one returned by the method
     * {@link AbstractDao::getTableName() getTableName}.
     *
     * @return string the :tech:`CREATE TABLE` sql
     * @throws ReflectionException
     */
    public function getCreateTableStatement(): string {
        $sql = /** @lang text */
            "CREATE TABLE IF NOT EXISTS `" . $this->getTableName() . "`\n(\n"
            . "\t`ID` \tINT NOT NULL AUTO_INCREMENT";
        foreach ($this->getDoParents() as $parent) {
            foreach ($parent->getProperties() as $prop) {
                $name = $prop->getName();
                if ($name != "ID") {
                    $datatype = DataTypes::fromName($prop->getType()->getName());
                    $sql .= ",\n\t`" . $prop->getName() . "`\t" . $datatype;
                }
            }
        }
        $sql .= ",\n\tPRIMARY KEY (`ID`)\n);";
        return $sql;
    }

    /**
     * Create a table in the database
     *
     * The statement used is the one from {@link AbstractDao::getCreateTableStatement() getCreateTableStatement}.
     *
     * @param bool $drop Drop (if exists) table with same name before create
     * @return int count of executed statements
     * @throws ReflectionException
     * @throws Exception code 200
     */
    public function createTable(bool $drop = false): int {
        $query = $drop ?
            /** @lang text */
            "DROP TABLE IF EXISTS `"
            . $this->getTableName()
            . "`;" . PHP_EOL
            : "";
        $query .= $this->getCreateTableStatement();
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $conn = MysqlConnector::get()->getConnector();
            $result = $conn->multi_query($query);
            $result += $conn->next_result();
            Log::info("Executed statements: " . $result, [$query]);
            return $result;
        } catch (Throwable $e) {
            throw new Exception("Could not create table " . $this->getTableName(), 200, $e);
        }
    }

    /**
     * Insert the given Entity
     *
     * The :tech:`ID` of the {@link Entity} (if any) will be ignored. Returns an Entity equal to the
     * given Entity with the new :tech:`ID`.
     *
     * @param Entity $entity Entity to insert
     * @return Entity new Entity, equal to given one, with new :tech:`ID`
     * @throws Exception code 201
     */
    public function insert(Entity $entity): Entity {
        $inserted = $this->insertBatch([$entity]);
        return array_values($inserted)[0];
    }

    /**
     * Update the given Entity
     *
     * @param Entity $entity persisted Entity to update
     * @return int rows affected: 1 for success, 0 for failure
     * @throws Exception code 202
     */
    public function update(Entity $entity): int {
        return $this->updateBatch([$entity]);
    }

    /**
     * Delete the row with the given ID
     *
     * @param int $ID the :tech:`ID` to delete
     * @return int rows affected: 1 for success, 0 if :tech:`ID` was not present
     * @throws Exception code 203
     */
    public function delete(int $ID): int {
        return $this->deleteBatch([$ID]);
    }

    /**
     * Fetch the Entity with the given ID
     *
     * @param int $ID the :tech:`ID` to fetch
     * @return Entity|null Entity with given :tech:`ID` or *null* if not present
     * @throws Exception code 204
     */
    public function select(int $ID): ?Entity {
        $selected = $this->selectBatch([$ID]);
        return (count($selected) == 1) ? array_values($selected)[0] : null;
    }

    /**
     * Insert the Entities from the given array
     *
     * The :tech:`ID` of the {@link Entity Entities} (if any) will be ignored. Returns an array of
     * Entities equal to the
     * given Entities with new :tech:`ID`\ s and ID as array key.
     *
     * @param Entity[] $entity_array array of Entities to insert
     * @return Entity[] array of Entities with new :tech:`ID`\ s
     * @throws Exception code 201
     */
    public function insertBatch(array $entity_array): array {
        $sql = $this->getPrepareInsertStatement();
        $new_entities = [];
        $stmt = null;
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $row_count = 0;
            $conn = MysqlConnector::get()->getConnector();
            $stmt = $conn->prepare($sql);
            foreach ($entity_array as $entity) {
                $arr = $entity->toArray();
                $result = $stmt->execute(array_slice(array_values($arr), 1));
                if ($result) {
                    $ID = $stmt->insert_id;
                    $new_entities[$ID] = $entity->clone($ID);
                    Log::debug("Inserted " . $entity::class . ", ID = " . $ID);
                    $row_count += $stmt->affected_rows;
                } else {
                    $msg = "Could not insert " . $this->getDataObjectName();
                    Log::error($msg, [$entity]);
                    throw new Exception($msg);
                }
            }
            Log::debug("INSERT row count: " . $row_count);
            return $new_entities;
        } catch (Throwable $e) {
            throw new Exception("Could not insert Entity", 201, $e);
        } finally {
            if (!is_null($stmt)) $stmt->close();
        }
    }

    /**
     * Update the Entities in the given array
     *
     * @param Entity[] $entity_array array of persisted Entities to update
     * @return int rows affected
     * @throws Exception code 202
     */
    public function updateBatch(array $entity_array): int {
        $sql = $this->getPrepareUpdateStatement();
        // UPDATE tbl_name SET field1=?, field2=?, (...), WHERE ID=?
        $stmt = null;
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $row_count = 0;
            $conn = MysqlConnector::get()->getConnector();
            $stmt = $conn->prepare($sql);
            foreach ($entity_array as $entity) {
                $arr = $entity->toArray();
                $update = array_slice(array_values($arr), 1);
                $update[] = $entity->getID();
                $result = $stmt->execute($update);
                if (!$result) {
                    $msg = "Could not update " . $this->getDataObjectName();
                    Log::error($msg, [$entity]);
                    throw new Exception($msg);
                }
                $row_count += $stmt->affected_rows;
            }
            Log::debug("UPDATE row count: " . $row_count);
            return $row_count;
        } catch (Throwable $e) {
            throw new Exception("Could not update Entity", 202, $e);
        } finally {
            if (!is_null($stmt)) $stmt->close();
        }
    }

    /**
     * Delete rows with the given IDs
     *
     * @param int[] $ids array with IDs of persisted entities
     * @return int affected rows
     * @throws Exception code 203
     */
    public function deleteBatch(array $ids): int {
        $sql = $this->getPrepareDeleteStatement(count($ids));
        Log::debug($sql);
        $stmt = null;
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $conn = MysqlConnector::get()->getConnector();
            $stmt = $conn->prepare($sql);
            $stmt->execute($ids);
            $row_count = $stmt->affected_rows;
            Log::debug("DELETE row count: " . $row_count);
            return $row_count;
        } catch (Throwable $e) {
            throw new Exception("Could not delete Entity", 203, $e);
        } finally {
            if (!is_null($stmt)) $stmt->close();
        }
    }

    /**
     * Delete Entity rows with a *where-clause*
     *
     * ```
     * DELETE FROM %table_name% WHERE %expression%
     * ```
     *
     * @param string $where_clause expression
     * @return int rows affected
     * @throws Exception code 203
     */
    public function deleteWhere(string $where_clause): int {
        $sql = /** @lang text */
            "DELETE FROM `" . $this->getTableName() . "` WHERE " . $where_clause;
        Log::debug($sql);
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $conn = MysqlConnector::get()->getConnector();
            $conn->query($sql);
            $row_count = $conn->affected_rows;
            Log::debug("DELETE row count: " . $row_count);
            return $row_count;
        } catch (Throwable $e) {
            throw new Exception("Could not delete Entity", 203, $e);
        }
    }

    /**
     * Select Entities with the given IDs
     *
     * The returned Entity[] array has Entity IDs as keys.
     *
     * @param int[] $ids array of IDs of persisted Entities
     * @return Entity[] array of Entities or empty array if none found
     * @throws Exception code 204
     */
    public function selectBatch(array $ids): array {
        $sql = $this->getPrepareSelectStatement(count($ids));
        Log::debug($sql);
        $stmt = null;
        /** @var $do Entity */
        $do = $this->getDataObjectName();
        $selected = [];
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $row_count = 0;
            $conn = MysqlConnector::get()->getConnector();
            $stmt = $conn->prepare($sql);
            $stmt->execute($ids);
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $entity = $do::fromArray($row);
                $selected[$entity->getID()] = $entity;
                $row_count++;
            }
            Log::debug("SELECT row count: " . $row_count);
            return $selected;
        } catch (Throwable $e) {
            throw new Exception("Could not select Entity", 204, $e);
        } finally {
            if (!is_null($stmt)) $stmt->close();
        }
    }

    /**
     * Select Entities with a *where-clause*
     *
     * ```
     * SELECT FROM %table_name% WHERE %expression%
     * ```
     * The returned Entity[] array has Entity IDs as keys.
     *
     * @param string $where_clause expression
     * @return Entity[] array of Entities or empty array if none found
     * @throws Exception code 204
     */
    public function selectWhere(string $where_clause): array {
        $sql = /** @lang text */
            "SELECT * FROM `" . $this->getTableName() . "` WHERE " . $where_clause;
        Log::debug($sql);
        /** @var $do Entity */
        $do = $this->getDataObjectName();
        $selected = [];
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $row_count = 0;
            $conn = MysqlConnector::get()->getConnector();
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                $entity = $do::fromArray($row);
                $selected[$entity->getID()] = $entity;
                $row_count++;
            }
            Log::debug("SELECT row count: " . $row_count);
            return $selected;
        } catch (Throwable $e) {
            throw new Exception("Could not select Entity", 204, $e);
        }
    }

    private function getPrepareInsertStatement(): string {
        // INSERT INTO tbl_node (parent_id, name, alias, nature) VALUES (?, ?, ?, ?)
        $s1 = /** @lang text */
            "INSERT INTO " . $this->getTableName() . " (";
        $s2 = ") VALUES (";
        foreach ($this->getDoParents() as $parent) {
            foreach ($parent->getProperties() as $prop) {
                $name = $prop->getName();
                if ($name != "ID") {
                    $s1 .= $name . ", ";
                    $s2 .= "?, ";
                }
            }
        }
        $statement = rtrim($s1, ", ") . rtrim($s2, ", ") . ")";
        Log::debug("prepared insert statement: ", [$statement]);
        return $statement;
    }

    private function getPrepareUpdateStatement(): string {
        // UPDATE tbl_name SET parent_id=?, name=?, alias=?, nature=?, public=? WHERE ID=?
        $s1 = /** @lang text */
            "UPDATE "
            . $this->getTableName()
            . " SET ";
        foreach ($this->getDoParents() as $parent) {
            foreach ($parent->getProperties() as $prop) {
                $name = $prop->getName();
                if ($name != "ID") {
                    $s1 .= $prop->getName() . "=?, ";
                }
            }
        }
        $statement = rtrim($s1, ", ") . " WHERE ID=?";
        Log::debug("prepared update statement: ", [$statement]);
        return $statement;
    }

    private function getPrepareDeleteStatement(int $count): string {
        // DELETE FROM `tbl_name` WHERE `ID`=?[ OR `ID`=?]...
        $sql = /** @lang text */
            "DELETE FROM `" . $this->getTableName() . "` WHERE `ID`=?";
        $sql .= str_repeat(" OR `ID`=?", $count - 1);
        return $sql;
    }

    private function getPrepareSelectStatement(int $count): string {
        // SELECT * FROM `table_name` WHERE `ID`=?[ OR `ID`=?]...
        $sql = /** @lang text */
            "SELECT * FROM `" . $this->getTableName() . "` WHERE `ID`=?";
        $sql .= str_repeat(" OR `ID`=?", $count - 1);
        return $sql;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getDoParents(): array {
        $parents = [];
        $rc = new ReflectionClass($this->getDataObjectName());
        do {
            $parents[] = $rc;
            $rc = $rc->getParentClass();
        } while ($rc);
        return array_reverse($parents);
    }

}