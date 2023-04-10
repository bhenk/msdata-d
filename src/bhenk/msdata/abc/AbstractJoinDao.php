<?php

namespace bhenk\msdata\abc;

use bhenk\logger\log\Log;
use Closure;
use Exception;
use function is_null;

/**
 * Abstract Dao for a join table
 *
 * The corresponding Data Object is envisaged to extend {@link Join}.
 *
 * .. image:: /img/join.svg
 *    :alt: symmetric join relation
 *
 * The relationship is symmetric.
 *
 * A Join with the field *$deleted* set to true will be deleted on any call to
 * {@link AbstractJoinDao::updateLeftJoin()} or {@link AbstractJoinDao::updateRightJoin()}.
 */
abstract class AbstractJoinDao extends AbstractDao {

    /**
     * Select on left hand foreign key
     *
     * @param int $fk_left left hand foreign key
     * @return Join[] with right hand IDs as key
     * @throws Exception
     */
    public function selectLeft(int $fk_left): array {
        $where = "FK_LEFT=" . $fk_left;
        $callback = function (Join $join): int {
            return $join->getFkRight();
        };
        return parent::selectWhere($where, $callback);
    }

    /**
     * Select on right hand foreign key
     *
     * @param int $fk_right right hand foreign key
     * @return Join[] with left hand IDs as key
     * @throws Exception
     */
    public function selectRight(int $fk_right): array {
        $where = "FK_RIGHT=" . $fk_right;
        $callback = function (Join $join): int {
            return $join->getFkLeft();
        };
        return parent::selectWhere($where, $callback);
    }

    /**
     * Update Joins with a common FK_LEFT
     *
     * This method deletes deleted Joins; updates existing Joins and inserts new Joins.
     *
     * Side effect: the common {@link $fk_left} will be set on all Joins.
     *
     * @param int $fk_left common left hand foreign key
     * @param Join[] $joins Joins to update
     * @return Join[] Updated Joins, array key is FK_RIGHT
     * @throws Exception
     */
    public function updateLeftJoin(int $fk_left, array $joins): array {
        $common = function (Join $join) use ($fk_left): void {
            $join->setFkLeft($fk_left);
        };
        $key = function (Join $join): int {
            return $join->getFkRight();
        };
        return $this->updateJoins($joins, $common, $key);
    }

    /**
     * Update Joins
     *
     * This method deletes deleted Joins; updates existing Joins and inserts new Joins.
     *
     * @param Join[] $joins Joins to update
     * @param Closure $common sets common key
     * @param Closure $key returns array key for returned array
     * @return Join[] updated Joins
     * @throws Exception
     */
    private function updateJoins(array $joins, Closure $common, Closure $key): array {
        $ingests = [];
        $deletes = [];
        $updates = [];
        foreach ($joins as $join) {
            $common($join);
            if (is_null($join->getID()) and !$join->isDeleted()) {
                $ingests[] = $join;
            } elseif ($join->isDeleted()) {
                $deletes[] = $join->getID();
            } else {
                $updates[$key($join)] = $join;
            }
        }
        if (!empty($deletes)) $this->deleteBatch($deletes);
        if (!empty($updates)) $this->updateBatch($updates);
        $ingested = empty($ingests) ? [] : $this->insertBatch($ingests, $key);
        Log::debug("deletes: " . count($deletes)
            . ", updates: " . count($updates) . ", ingested: " . count($ingested));
        foreach ($ingested as $FK => $join) {
            $updates[$FK] = $join;
        }
        return $updates;
    }

    /**
     * Update Joins with a common FK_RIGHT
     *
     * This method deletes deleted Joins; updates existing Joins and inserts new Joins.
     *
     * Side effect: the common {@link $fk_right} will be set on all Joins.
     *
     * @param int $fk_right common right hand foreign key
     * @param Join[] $joins Joins to update
     * @return Join[] Updated Joins, array key is FK_LEFT
     * @throws Exception
     */
    public function updateRightJoin(int $fk_right, array $joins): array {
        $common = function (Join $join) use ($fk_right): void {
            $join->setFkRight($fk_right);
        };
        $key = function (Join $join): int {
            return $join->getFkLeft();
        };
        return $this->updateJoins($joins, $common, $key);
    }

}