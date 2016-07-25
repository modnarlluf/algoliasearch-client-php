<?php

namespace AlgoliaSearch\Exception;

use AlgoliaSearch\AlgoliaException;

class AlgoliaRecordsTooBigException extends AlgoliaException
{
    /**
     * @var array|null
     */
    private $records;

    /**
     * @return array|null
     */
    public function getRecords()
    {
        return $this->records;
    }

    /**
     * @param array|null $records
     *
     * @return AlgoliaRecordsTooBigException
     */
    public function setRecords($records)
    {
        $this->records = $records;

        return $this;
    }

    /**
     * @param $record
     *
     * @return AlgoliaRecordsTooBigException
     */
    public function addRecord($record)
    {
        $this->records[] = $record;

        return $this;
    }
}
