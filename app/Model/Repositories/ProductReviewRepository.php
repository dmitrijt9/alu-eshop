<?php

namespace App\Model\Repositories;

/**
 * Class ProductReviewRepository - repozitář pro recenze
 * @package App\Model\Repositories
 */
class ProductReviewRepository extends BaseRepository{

    /**
     * @param null|array $whereArr
     * @param null|int $offset
     * @param null|int $limit
     * @return array
     */
    public function findAllBy($whereArr = null, $offset = null, $limit = null) {
        $query = $this->connection->select('*')->from($this->getTable());
        if (isset($whereArr['order'])) {
            $query->orderBy($whereArr['order']);
            unset($whereArr['order']);
        }
        if ($whereArr != null && count($whereArr) > 0) {
            $query = $query->where($whereArr);
        }

        return $this->createEntities($query->fetchAll($offset, $limit));
    }
}