<?php

namespace bhenk\msdata\abc;

use Exception;

/**
 * Abstract Dao for a join table
 *
 * The corresponding Data Object is envisaged to extend {@link Join}.
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

}