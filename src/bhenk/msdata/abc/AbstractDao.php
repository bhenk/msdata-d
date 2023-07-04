<?php

namespace bhenk\msdata\abc;

use bhenk\logger\log\Log;
use bhenk\msdata\connector\MysqlConnector;
use Closure;
use Exception;
use ReflectionClass;
use ReflectionException;
use Throwable;
use function array_reverse;
use function array_slice;
use function array_values;
use function count;
use function implode;
use function is_bool;
use function is_null;
use function mysqli_report;
use function rtrim;
use function str_repeat;
use function str_replace;
use function substr;

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
     * Drop table if it exists
     *
     * Tries to drop the table with the name returned by {@link AbstractDao::getTableName()}.
     *
     * @return bool *true* on success, even if table does not exist, *false* on failure
     * @throws Exception
     */
    public function dropTable(): bool {
        $query = /** @lang text */
            "DROP TABLE IF EXISTS `"
            . $this->getTableName()
            . "`;";
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $conn = MysqlConnector::get()->getConnector();
            return $conn->query($query);
        } catch (Throwable $e) {
            throw new Exception("Could not drop table " . $this->getTableName(), 200, $e);
        }
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

    /**
     * Get the fully qualified classname of the {@link Entity} this class provides access to
     *
     * @return string fully qualified classname
     */
    public abstract function getDataObjectName(): string;

    /**
     * Insert the given Entity
     *
     * With {@link $insertID} set to *false* (this is the default), the :tech:`ID` of the {@link Entity} (if any)
     * will be ignored. Returns an Entity equal to the
     * given Entity with the new :tech:`ID`.
     *
     * In order to be able to reconstruct a table, the :tech:`ID` of the Entity can be inserted as well. Set
     * {@link $insertID} to *true* to achieve this.
     *
     * @param Entity $entity Entity to insert
     * @param bool $insertID should the *primary key* ID also be inserted
     * @return Entity new Entity, equal to given one, with new :tech:`ID`
     * @throws Exception code 201
     */
    public function insert(Entity $entity, bool $insertID = false): Entity {
        $inserted = $this->insertBatch([$entity], null, $insertID);
        return array_values($inserted)[0];
    }

    /**
     * Insert the Entities from the given array
     *
     * The :tech:`ID` of the {@link Entity Entities} (if any) will be ignored. Returns an array of
     * Entities equal to the
     * given Entities with new :tech:`ID`\ s and ID as array key. This default behaviour can be altered by
     * providing a closure that receives each inserted entity and decides what key will be returned:
     * ```
     * $func = function(Entity $entity): int {
     *     return  $entity->getID();
     * };
     * ```
     *
     * In order to be able to reconstruct a table, the ID of the Entities can be inserted as well. Set
     * {@link $insertID} to *true* to achieve this.
     *
     * @param Entity[] $entity_array array of Entities to insert
     * @param Closure|null $func function to assign key in the returned array
     * @param bool $insertID should the *primary key* ID also be inserted
     * @return Entity[] array of Entities with new :tech:`ID`\ s
     * @throws Exception code 201
     */
    public function insertBatch(array $entity_array, Closure $func = null, bool $insertID = false): array {
        if (empty($entity_array)) return [];
        if (is_null($func)) {
            $func = function (Entity $entity): int {
                return $entity->getID();
            };
        }
        if (MysqlConnector::get()->useParameterizedQueries()) {
            return $this->insertBatchP($entity_array, $func, $insertID);
        } else {
            return $this->insertBatchNp($entity_array, $func, $insertID);
        }
    }

    /**
     * Insert Batch with parameterized query
     * @param array $entity_array
     * @param Closure $func
     * @param bool $insertID
     * @return Entity[]
     * @throws ReflectionException
     */
    private function insertBatchP(array $entity_array, Closure $func, bool $insertID = false): array {
        $sql = $this->getPrepareInsertStatement($insertID);
        $new_entities = [];
        $stmt = null;
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $row_count = 0;
            $conn = MysqlConnector::get()->getConnector();
            $stmt = $conn->prepare($sql);
            foreach ($entity_array as $entity) {
                $arr = $entity->toArray();
                $offset = $insertID ? 0 : 1;
                $result = $stmt->execute(array_slice(array_values($arr), $offset));
                if ($result) {
                    $ID = $stmt->insert_id;
                    $new_entity = $entity->clone($ID);
                    $new_entities[$func($new_entity)] = $new_entity;
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
     * @param bool $insertID should *primary key* ID also be inserted
     * @return string the prepare insert statement
     * @throws ReflectionException
     */
    private function getPrepareInsertStatement(bool $insertID = false): string {
        // INSERT INTO tbl_node ([ID], parent_id, name, alias, nature) VALUES (?, ?, ?, ?)
        $s1 = /** @lang text */
            "INSERT INTO " . $this->getTableName() . " (";
        $s2 = ") VALUES (";
        $ID_passed = false;
        foreach ($this->getDoParents() as $parent) {
            foreach ($parent->getProperties() as $prop) {
                $name = $prop->getName();
                if ($name != "ID" or ($insertID and !$ID_passed)) {
                    $s1 .= $name . ", ";
                    $s2 .= "?, ";
                }
                if ($name == "ID") $ID_passed = true;
            }
        }
        $statement = rtrim($s1, ", ") . rtrim($s2, ", ") . ")";
        Log::debug("prepared insert statement: ", [$statement]);
        return $statement;
    }

    /**
     * Insert batch with statements, not parameterized
     * @param array $entity_array
     * @param Closure $func
     * @param bool $insertID
     * @return Entity[]
     * @throws Exception
     */
    private function insertBatchNp(array $entity_array, Closure $func, bool $insertID = false): array {
        $s1 = /** @lang text */
            "INSERT INTO " . $this->getTableName() . " (";
        $s3 = ") VALUES (";
        $ID_passed = false;
        $name_array = [];
        foreach ($this->getDoParents() as $parent) {
            foreach ($parent->getProperties() as $prop) {
                $name = $prop->getName();
                if ($name != "ID" or ($insertID and !$ID_passed)) {
                    $name_array[] = $name;
                }
                if ($name == "ID") $ID_passed = true;
            }
        }
        $sql = $s1 . implode(", ", $name_array) . $s3;
        $new_entities = [];
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $conn = MysqlConnector::get()->getConnector();
            $row_count = 0;
            foreach ($entity_array as $entity) {
                $arr = array_map(function ($x) use ($conn) {
                    if (is_null($x)) {
                        return "NULL";
                    } else {
                        return $conn->real_escape_string($x);
                    }
                }, $entity->toArray());
                $offset = $insertID ? 0 : 1;
                $values = "'" . implode("', '", array_slice(array_values($arr), $offset)) . "');";
                $values = str_replace("'NULL'", "NULL", $values);
                $conn->query($sql . $values);
                $ID = $conn->insert_id;
                $new_entity = $entity->clone($ID);
                $new_entities[$func($new_entity)] = $new_entity;
                Log::debug("Inserted " . $entity::class . ", ID = " . $ID);
                $row_count += $conn->affected_rows;
            }
            Log::debug("INSERT row count: " . $row_count);
        } catch (Throwable $e) {
            throw new Exception("Could not insert Entity", 201, $e);
        }
        return $new_entities;
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
     * Update the Entities in the given array
     *
     * @param Entity[] $entity_array array of persisted Entities to update
     * @return int rows affected
     * @throws Exception code 202
     */
    public function updateBatch(array $entity_array): int {
        if (empty($entity_array)) return 0;
        if (MysqlConnector::get()->useParameterizedQueries()) {
            return $this->updateBatchP($entity_array);
        } else {
            return $this->updateBatchNp($entity_array);
        }
    }

    /**
     * Update batch with parameterized execution
     * @param Entity[] $entity_array
     * @return int
     * @throws Exception
     */
    private function updateBatchP(array $entity_array): int {
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
     * @return string
     * @throws ReflectionException
     */
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

    /**
     * Update batch with statements, not parameterized
     * @param Entity[] $entity_array
     * @return int
     * @throws Exception
     */
    private function updateBatchNp(array $entity_array): int {
        $sql = "UPDATE " . $this->getTableName() . " SET ";
        $row_count = 0;
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $conn = MysqlConnector::get()->getConnector();
            foreach ($entity_array as $entity) {
                $updates = "";
                foreach (array_slice($entity->toArray(), 1) as $name => $value) {
                    if (is_null($value)) {
                        $updates .= "`$name`=NULL, ";
                    } else {
                        $esc_value = $conn->real_escape_string($value);
                        $updates .= "`$name`='$esc_value', ";
                    }
                }
                $updates = substr($updates, 0, -2) . " WHERE ID=" . $entity->getID() . ";";
                Log::debug($sql . $updates);
                $conn->query($sql . $updates);
                $row_count += $conn->affected_rows;
            }
        } catch (Throwable $e) {
            throw new Exception("Could not update Entity", 202, $e);
        }
        return $row_count;
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
     * Delete rows with the given IDs
     *
     * @param int[] $ids array with IDs of persisted entities
     * @return int affected rows
     * @throws Exception code 203
     */
    public function deleteBatch(array $ids): int {
        if (empty($ids)) return 0;
        if (MysqlConnector::get()->useParameterizedQueries()) {
            return $this->deleteBatchP($ids);
        } else {
            return $this->deleteBatchNp($ids);
        }
    }

    /**
     * Delete batch with parameterized execution
     * @param int[] $ids
     * @return int
     * @throws Exception
     */
    private function deleteBatchP(array $ids): int {
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

    private function getPrepareDeleteStatement(int $count): string {
        // DELETE FROM `tbl_name` WHERE `ID`=?[ OR `ID`=?]...
        $sql = /** @lang text */
            "DELETE FROM `" . $this->getTableName() . "` WHERE `ID`=?";
        $sql .= str_repeat(" OR `ID`=?", $count - 1);
        return $sql;
    }

    /**
     * Delete batch with statement, not parameterized
     * @param int[] $ids
     * @return int
     * @throws Exception
     */
    private function deleteBatchNp(array $ids): int {
        $clause = implode(" OR ID=", $ids);
        $sql = /** @lang text */
            "DELETE FROM " . $this->getTableName() . " WHERE ID=" . $clause . ";";
        Log::debug($sql);
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $conn = MysqlConnector::get()->getConnector();
            $conn->query($sql);
            $rows = $conn->affected_rows;
        } catch (Throwable $e) {
            throw new Exception("Could not delete Entity", 203, $e);
        }
        return $rows;
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
     * Select Entities with the given IDs
     *
     * The returned Entity[] array has Entity IDs as keys.
     *
     * @param int[] $ids array of IDs of persisted Entities
     * @return Entity[] array of Entities or empty array if none found
     * @throws Exception code 204
     */
    public function selectBatch(array $ids): array {
        if (empty($ids)) return [];
        if (MysqlConnector::get()->useParameterizedQueries()) {
            return $this->selectBatchP($ids);
        } else {
            return $this->selectBatchNp($ids);
        }
    }

    /**
     * @param int[] $ids
     * @return Entity[]
     * @throws Exception
     */
    private function selectBatchP(array $ids): array {
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

    private function getPrepareSelectStatement(int $count): string {
        // SELECT * FROM `table_name` WHERE `ID`=?[ OR `ID`=?]...
        $sql = /** @lang text */
            "SELECT * FROM `" . $this->getTableName() . "` WHERE `ID`=?";
        $sql .= str_repeat(" OR `ID`=?", $count - 1);
        return $sql;
    }

    /**
     * @param int[] $ids
     * @return Entity[]
     * @throws Exception
     */
    private function selectBatchNp(array $ids): array {
        $clause = implode(" OR ID=", $ids);
        $sql = /** @lang text */
            "SELECT * FROM " . $this->getTableName()
            . " WHERE ID=" . $clause . ";";
        return $this->selectSql($sql);
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
     * Select Entities with a *where-clause*
     *
     * ```
     * SELECT FROM %table_name% WHERE %expression% LIMIT %offset%, %limit%;
     * ```
     * The optional {@link $func} receives selected Entities and can decide what key
     * the Entity will have in the returned Entity[] array.
     * Default: the returned Entity[] array has Entity IDs as keys.
     * ```
     * $func = function(Entity $entity): int {
     *     return  $entity->getID();
     * };
     * ```
     *
     * @param string $where_clause expression
     * @param int $offset offset of the first row to return
     * @param int $limit the maximum number of rows to return
     * @param Closure|null $func if given decides which keys the returned array will have
     * @return Entity[] array of Entities or empty array if none found
     * @throws Exception code 204
     */
    public function selectWhere(string  $where_clause,
                                int     $offset = 0,
                                int     $limit = PHP_INT_MAX,
                                Closure $func = null): array {
        $sql = /** @lang text */
            "SELECT * FROM `" . $this->getTableName() . "` WHERE " . $where_clause
            . " LIMIT " . $offset . "," . $limit . ";";
        return $this->selectSql($sql, $func);
    }

    /**
     * Select Entities with a sql statement
     *
     * The optional {@link $func} receives selected Entities and can decide what key
     * the Entity will have in the returned Entity[] array.
     * Default: the returned Entity[] array has Entity IDs as keys.
     * ```
     * $func = function(Entity $entity): int {
     *     return  $entity->getID();
     * };
     * ```
     * If the {@link $sql} selects not all fields from the designated table or selects from tables other than
     * the designated, the result is unpredictable.
     *
     * @param string $sql sql selecting all fields from designated table
     * @param Closure|null $func if given decides which keys the returned array will have
     * @return Entity[] array of Entities or empty array if none found
     * @throws Exception
     */
    public function selectSql(string  $sql,
                              Closure $func = null): array {
        if (is_null($func)) {
            $func = function (Entity $entity): int {
                return $entity->getID();
            };
        }
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
                $selected[$func($entity)] = $entity;
                $row_count++;
            }
            Log::debug("SELECT row count: " . $row_count);
            return $selected;
        } catch (Throwable $e) {
            throw new Exception("Could not select Entity", 204, $e);
        }
    }

    /**
     * Execute the given query
     *
     * @param string $sql
     * @return array|bool result rows in array; bool if result is boolean
     * @throws Exception
     */
    public function execute(string $sql): array|bool {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $results = [];
        Log::debug($sql);
        try {
            $conn = MysqlConnector::get()->getConnector();
            $result = $conn->query($sql);
            if (is_bool($result)) return $result;
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
            return $results;
        } catch (Throwable $e) {
            throw new Exception("Could not execute query", 205, $e);
        }
    }

}